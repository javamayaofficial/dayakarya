<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fiksi', 'Non-Fiksi', 'Romansa', 'Petualangan', 'Horor',
            'Motivasi & Pengembangan Diri', 'Religi', 'Anak & Dongeng',
            'Pendidikan', 'Sejarah & Budaya', 'Podcast', 'Audiobook',
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
