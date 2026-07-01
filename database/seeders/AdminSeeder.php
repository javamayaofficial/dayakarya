<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@dayakarya.id'],
            [
                'name'     => 'Admin Dayakarya',
                'phone'    => '628123456789',
                'password' => Hash::make('password'), // WAJIB diganti setelah login pertama
                'status'   => 'active',
            ]
        );
        $admin->assignRole('admin');
    }
}
