<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * Autentikasi berbasis token (Sanctum) untuk web PWA & mobile client.
 */
class AuthController extends \App\Http\Controllers\Controller
{
    public function __construct(protected NotificationService $notifier) {}

    protected const PERSONA_ROLE_MAP = [
        'reader' => 'reader',
        'writer' => 'creator',
        'listener_creator' => 'listener',
    ];

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'persona'  => ['required', 'in:' . implode(',', array_keys(self::PERSONA_ROLE_MAP))],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => $data['password'],
            'status'   => 'active',
        ]);

        $role = self::PERSONA_ROLE_MAP[$data['persona']] ?? User::DEFAULT_ROLE;
        $user->assignRole($role);

        $welcomeMessage = match ($role) {
            'creator' => "Selamat datang di Dayakarya, {$user->name}! Akunmu sudah aktif. Sekarang kamu bisa mulai bikin draft, merapikan karya, dan menayangkannya saat siap.",
            'listener' => "Selamat datang di Dayakarya, {$user->name}! Akunmu sudah aktif. Kamu bisa menikmati audio, bikin konten dengar, lalu mulai membangun audiens dari tempat yang sama.",
            default => "Selamat datang di Dayakarya, {$user->name}! Akunmu sudah aktif. Sekarang kamu bisa jelajah karya, buka konten premium, dan masuk ke wallet kapan pun dibutuhkan.",
        };

        // Kirim salam verifikasi via WhatsApp (Fonnte)
        $this->notifier->whatsapp($user, $welcomeMessage);

        $token = $user->createToken('dayakarya')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user'    => $user->only(['id', 'name', 'email', 'phone']),
            'roles'   => $user->getRoleNames(),
            'redirect_to' => $user->defaultInternalPath(),
            'token'   => $token,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($user->status === 'suspended') {
            throw ValidationException::withMessages([
                'email' => ['Akun kamu sedang ditangguhkan. Hubungi admin.'],
            ]);
        }

        $token = $user->createToken('dayakarya')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user'    => $user->only(['id', 'name', 'email', 'phone']),
            'roles'   => $user->getRoleNames(),
            'redirect_to' => $user->defaultInternalPath(),
            'token'   => $token,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('wallet');
        return response()->json([
            'user'   => $user->only(['id', 'name', 'email', 'phone', 'avatar', 'bio']),
            'roles'  => $user->getRoleNames(),
            'wallet' => [
                'credit_balance' => $user->wallet->credit_balance ?? 0,
                'rupiah_balance' => $user->wallet->rupiah_balance ?? 0,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.']);
    }
}
