<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        $categories = ['Tech', 'Knowledge', 'Joke', 'Health',];

        foreach ($categories as $cat) {
            \App\Models\Category::create([
                'name' => $cat,
                'slug' => str()->slug($cat),
                'icon' => 'fa-tag'
            ]);
        }
        \App\Models\Post::factory(5)->create();
    }
}
