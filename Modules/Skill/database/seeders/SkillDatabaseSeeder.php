<?php

namespace Modules\Skill\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Skill\Models\Skill;

class SkillDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'Drawing',
            'Painting',
            'Sculpting',
            'Printmaking',
            'Collage',
            'Textile Arts',
            'Ceramics',
            'Photography',
            'Graphic Design',
            'Calligraphy',
            'Jewelry Making',
            'Mosaic',
            'Woodworking',
            'Upcycling',
            'Origami',
            'Mixed Media',
            'Animation',
            'Crafting Techniques',
            'Color Theory',
            '3D Printing',
            '3d Design',
            'CNC Machining',
            'Laser Cutting',
            'Digital Fabrication',
            'Embroidery',
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill]);
        }
    }
}
