<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fijo', 'slug' => 'fijo'],
            ['name' => 'Personal', 'slug' => 'personal'],
            ['name' => 'Circunstancial', 'slug' => 'circunstancial'],
            ['name' => 'Imprevisto', 'slug' => 'imprevisto'],
            ['name' => 'Gusto', 'slug' => 'gusto'],
            ['name' => 'Ahorro', 'slug' => 'ahorro'],
            ['name' => 'Deuda', 'slug' => 'deuda'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}