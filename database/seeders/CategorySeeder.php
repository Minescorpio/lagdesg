<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jeux Vidéo',
                'slug' => 'jeux-video',
                'description' => 'Tous nos jeux vidéo',
                'active' => true,
            ],
            [
                'name' => 'Consoles',
                'slug' => 'consoles',
                'description' => 'Toutes nos consoles de jeux',
                'active' => true,
            ],
            [
                'name' => 'Accessoires',
                'slug' => 'accessoires',
                'description' => 'Tous les accessoires gaming',
                'active' => true,
            ],
            [
                'name' => 'Goodies',
                'slug' => 'goodies',
                'description' => 'Tous nos produits dérivés',
                'active' => true,
            ],
            [
                'name' => 'Retrogaming',
                'slug' => 'retrogaming',
                'description' => 'Tout pour les nostalgiques',
                'active' => true,
            ],
            [
                'name' => 'High-Tech',
                'slug' => 'high-tech',
                'description' => 'Tous nos produits high-tech',
                'active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
} 