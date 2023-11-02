<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GameSession;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'fullname' => 'Admin',
            'role' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
        ]);

        GameSession::create([
            'time' => 10,
            'mode' => 'easy',
            'status' => 'pending',
            'session_code' => '123456',
        ]);

        $this->call(TrashSeeder::class);
    }
}
