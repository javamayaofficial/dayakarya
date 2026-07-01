<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('login')->with('google_auth_error', 'Login Google belum berhasil. Silakan coba lagi.');
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'password' => Str::password(32),
                'avatar' => $googleUser->getAvatar(),
                'status' => 'active',
                'auth_provider' => 'google',
                'google_id' => $googleUser->getId(),
                'email_verified_at' => Carbon::now(),
            ]);
            $user->assignRole('reader');
        } else {
            $user->forceFill([
                'auth_provider' => 'google',
                'google_id' => $googleUser->getId(),
                'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?: Carbon::now(),
                'status' => $user->status === 'pending' ? 'active' : $user->status,
            ])->save();

            if ($user->getRoleNames()->isEmpty()) {
                $user->assignRole('reader');
            }
        }

        $user->tokens()->where('name', 'dayakarya-google')->delete();
        $token = $user->createToken('dayakarya-google')->plainTextToken;

        return response()->view('auth.google-callback', [
            'token' => $token,
            'redirectTo' => route('home'),
            'notice' => $user->phone
                ? 'Akun Google berhasil terhubung. Anda sedang dialihkan ke Dayakarya.'
                : 'Akun Google berhasil terhubung. Nomor WhatsApp bisa Anda lengkapi nanti saat mulai memakai fitur finansial.',
        ]);
    }
}
