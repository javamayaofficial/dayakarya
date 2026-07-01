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

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:creator,reader,listener,affiliate,sponsor,csr'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => $data['password'],
            'status'   => 'active',
        ]);
        $user->assignRole($data['role']);

        // Kirim salam verifikasi via WhatsApp (Fonnte)
        $this->notifier->whatsapp($user,
            "Selamat datang di Dayakarya, {$user->name}! Akunmu sebagai " . ucfirst($data['role']) .
            " sudah aktif. Yuk mulai berkarya dan berpenghasilan.");

        $token = $user->createToken('dayakarya')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'user'    => $user->only(['id', 'name', 'email', 'phone']),
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
