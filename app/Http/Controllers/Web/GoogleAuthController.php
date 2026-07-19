<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $request->session()->put('google_oauth_return_to', $this->resolveAuthEntryUrl($request));

        if ($response = $this->guardUnavailableGoogleAuth()) {
            return $response;
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        return redirect()->away('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $this->googleConfig('client_id'),
            'redirect_uri' => $this->googleConfig('redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
            'state' => $state,
        ]));
    }

    public function callback(Request $request)
    {
        if ($response = $this->guardUnavailableGoogleAuth()) {
            return $response;
        }

        if ($request->filled('error')) {
            return $this->redirectBackWithError('Akses Google dibatalkan atau ditolak. Silakan coba lagi.');
        }

        $expectedState = (string) $request->session()->pull('google_oauth_state', '');
        $actualState = (string) $request->query('state', '');

        if ($expectedState === '' || $actualState === '' || ! hash_equals($expectedState, $actualState)) {
            return $this->redirectBackWithError('Sesi login Google tidak valid. Silakan mulai ulang dari halaman masuk.');
        }

        $authorizationCode = trim((string) $request->query('code'));

        if ($authorizationCode === '') {
            return $this->redirectBackWithError('Kode otorisasi Google tidak ditemukan. Silakan coba lagi.');
        }

        try {
            $googleUser = $this->fetchGoogleUser($authorizationCode);
        } catch (Throwable) {
            return $this->redirectBackWithError('Login Google belum berhasil. Silakan coba lagi.');
        }

        $googleId = trim((string) ($googleUser['id'] ?? ''));
        $googleEmail = trim((string) ($googleUser['email'] ?? ''));

        if ($googleId === '' || $googleEmail === '') {
            return $this->redirectBackWithError('Profil Google belum lengkap. Silakan gunakan email dan password terlebih dahulu.');
        }

        $user = User::query()
            ->where('google_id', $googleId)
            ->orWhere('email', $googleEmail)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => trim((string) ($googleUser['name'] ?? '')) ?: 'Pengguna Google',
                'email' => $googleEmail,
                'password' => Str::password(32),
                'avatar' => $googleUser['picture'] ?? null,
                'status' => 'active',
                'auth_provider' => 'google',
                'google_id' => $googleId,
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole(User::DEFAULT_ROLE);
        } else {
            $user->forceFill([
                'auth_provider' => 'google',
                'google_id' => $googleId,
                'avatar' => $user->avatar ?: ($googleUser['picture'] ?? null),
                'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
                'status' => $user->status === 'pending' ? 'active' : $user->status,
            ])->save();

            if ($user->getRoleNames()->isEmpty()) {
                $user->assignRole(User::DEFAULT_ROLE);
            }
        }

        $user->tokens()->where('name', 'dayakarya-google')->delete();
        $token = $user->createToken('dayakarya-google')->plainTextToken;

        return response()->view('auth.google-callback', [
            'token' => $token,
            'redirectTo' => $user->defaultInternalPath(),
            'notice' => $user->phone
                ? 'Akun Google berhasil terhubung. Anda sedang dialihkan ke Dayakarya.'
                : 'Akun Google berhasil terhubung. Nomor WhatsApp bisa Anda lengkapi nanti saat mulai memakai fitur finansial.',
        ]);
    }

    private function guardUnavailableGoogleAuth(): ?RedirectResponse
    {
        $clientId = $this->googleConfig('client_id');
        $clientSecret = $this->googleConfig('client_secret');

        if ($clientId === '' || $clientSecret === '') {
            return $this->redirectBackWithError('Login Google belum selesai dikonfigurasi. Silakan gunakan email dan password terlebih dahulu.');
        }

        return null;
    }

    private function googleConfig(string $key): string
    {
        $googleConfig = config('services.google', []);
        $fallbackRedirect = rtrim((string) config('app.url'), '/') . '/auth/google/callback';

        return trim((string) match ($key) {
            'client_id' => $googleConfig['client_id'] ?? '',
            'client_secret' => $googleConfig['client_secret'] ?? '',
            'redirect' => $googleConfig['redirect'] ?? $fallbackRedirect,
            default => '',
        });
    }

    private function fetchGoogleUser(string $authorizationCode): array
    {
        $tokenResponse = Http::asForm()
            ->acceptJson()
            ->timeout(20)
            ->post('https://oauth2.googleapis.com/token', [
                'code' => $authorizationCode,
                'client_id' => $this->googleConfig('client_id'),
                'client_secret' => $this->googleConfig('client_secret'),
                'redirect_uri' => $this->googleConfig('redirect'),
                'grant_type' => 'authorization_code',
            ])
            ->throw()
            ->json();

        $accessToken = trim((string) ($tokenResponse['access_token'] ?? ''));

        if ($accessToken === '') {
            throw new \RuntimeException('Google access token not returned.');
        }

        $userResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(20)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo')
            ->throw()
            ->json();

        if (! is_array($userResponse)) {
            throw new \RuntimeException('Google user info response invalid.');
        }

        return $userResponse;
    }

    private function redirectBackWithError(string $message): RedirectResponse
    {
        $fallback = session()->pull('google_oauth_return_to', route('login'));

        return redirect()->to($fallback)
            ->with('google_auth_error', $message);
    }

    private function resolveAuthEntryUrl(Request $request): string
    {
        $previousUrl = (string) url()->previous();
        $registerUrl = route('register');
        $loginUrl = route('login');

        if ($previousUrl !== '' && str_starts_with($previousUrl, $registerUrl)) {
            return $registerUrl;
        }

        if ($previousUrl !== '' && str_starts_with($previousUrl, $loginUrl)) {
            return $loginUrl;
        }

        return $loginUrl;
    }
}
