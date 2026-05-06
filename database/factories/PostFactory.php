<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        return [
            // User တစ်ယောက်မှ မရှိသေးရင် တစ်ယောက် ဆောက်ခိုင်းလိုက်မယ်
            'user_id' => User::first()?->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id,
            'content' => $this->faker->paragraphs(3, true),
            'image_url' => 'https://picsum.photos/800/600?random=' . rand(1, 1000),
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'status' => 'published',
            'views_count' => rand(10, 100),
            'likes_count' => rand(1, 50),
            'published_at' => now(),
        ];
    }
}