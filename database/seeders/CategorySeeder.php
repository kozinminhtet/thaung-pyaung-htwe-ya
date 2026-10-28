<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private const CATEGORIES = [
        ['video', 'ထွေရာလေးပါးကြည့်မယ်', 'video-category-1', 1],
        ['video', 'ရုပ်ရှင်ကြည့်မယ်', 'video-category-2', 2],
        ['video', 'ဆိုင်းနားထောင်မယ်', 'video-category-3', 3],
        ['video', 'တရားနာမယ်', 'video-category-4', 4],
        ['article', 'ကဗျာဖတ်မယ်', 'article-category-1', 1],
        ['article', 'အက်အေး/ဝတ္ထုဖတ်မယ်', 'article-category-2', 2],
        ['article', 'အခန်းဆက်ဝတ္ထု', 'article-category-3', 3],
        ['article', 'ဆောင်းပါးများ', 'article-category-4', 4],
        ['article', 'ဟာသများ', 'article-category-5', 5],
    ];

    public function run(): void
    {
        foreach (self::CATEGORIES as [$type, $name, $slug, $order]) {
            $category = Category::query()->firstOrNew(['name' => $name]);
            if (! $category->exists) {
                $category->slug = $slug;
            }
            $category->type = $type;
            $category->sort_order = $order;
            $category->save();
        }
    }
}
