<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "Maksim",
            "email" => "maksim@gmail.com",
            "password" => bcrypt("password"),
        ]);

        User::factory()->create([
            "name" => "Ivan",
            "email" => "ivan@gmail.com",
            "password" => bcrypt("password"),
        ]);
    }
}
