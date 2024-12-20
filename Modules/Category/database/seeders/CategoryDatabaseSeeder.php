<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Models\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::firstOrCreate(
            [
                'label' => 'AC Cleaning',
                'type' => 'PRODUCTS',
            ],
            [
                'description' => 'AC Cleaning',
                'type' => 'PRODUCTS',
            ]
        );

        Category::firstOrCreate(
            [
                'label' => 'Housekeeping',
                'type' => 'PRODUCTS',
            ],
            [
                'description' => 'Housekeeping',
                'type' => 'PRODUCTS',
            ]
        );
    }
}
