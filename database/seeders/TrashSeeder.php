<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trash;

class TrashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Trash::create([
            'name' => 'Sisa Makanan',
            'description' => 'Didapatkan dari sisa-sisa rumah tangga. Ini adalah sampah organik yang bisa diuraikan.',
            'category' => 'organic',
            'photo' => 'sisa_makanan.jpg',
            'game_mode' => 'easy'
        ]);
    }
}
