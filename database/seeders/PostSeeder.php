<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()->create([
            "title" => "Статья о PHP",
            "description" => "Описание статьи",
            "password" => "PHP - это язык программирования",
            "user_id" => 3
        ]);

        Post::factory()->create([
            "title" => "Статья о Laravel",
            "description" => "Описание статьи",
            "text" => "Laravel - это php-фреймворк",
            "user_id" => 2
        ]);
    }
}
