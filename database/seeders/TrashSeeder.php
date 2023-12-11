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

        $trashes = [
            [
                'name' => 'Kaleng',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938101.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Kulit Pisang',
                'description' => '',
                'category' => 'organic',
                'photo' => '1698905261.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Botol Kaca',
                'description' => '',
                'category' => 'glass',
                'photo' => '1698938522.png',
                'game_mode' => 'hard'
            ],
            [
                'name' => 'Bungkus Snack',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938455.png',
                'game_mode' => 'hard'
            ],
            [
                'name' => 'Lampu',
                'description' => '',
                'category' => 'glass',
                'photo' => '1698938544.png',
                'game_mode' => 'hard'
            ],
            [
                'name' => 'Lampu',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938330.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Sisa Makanan',
                'description' => 'Didapatkan dari sisa-sisa rumah tangga. Ini adalah sampah organik yang bisa diuraikan',
                'category' => 'organic',
                'photo' => '1698937839.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Kulit Apel',
                'description' => '',
                'category' => 'organic',
                'photo' => '1698937891.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Kantong Kresek',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938438.png',
                'game_mode' => 'hard'
            ],
            [
                'name' => 'Botol Kaca',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938310.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Kertas',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938390.png',
                'game_mode' => 'easy'
            ],
            [
                'name' => 'Kertas',
                'description' => '',
                'category' => 'paper',
                'photo' => '1698938570.png',
                'game_mode' => 'hard'
            ],
            [
                'name' => 'Kaleng',
                'description' => '',
                'category' => 'inorganic',
                'photo' => '1698938115.png',
                'game_mode' => 'easy'
            ],

        ];

        foreach ($trashes as $trash) {
            Trash::create($trash);
        }

    }
}
