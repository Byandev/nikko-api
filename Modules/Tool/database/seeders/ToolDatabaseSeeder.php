<?php

namespace Modules\Tool\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Tool\Models\Tool;

class ToolDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            'Pencils',
            'Charcoal',
            'Pastels',
            'Erasers',
            'Paper',
            'Brushes',
            'Acrylic Paint',
            'Oil Paint',
            'Watercolors',
            'Canvas',
            'Clay',
            'Chisels',
            'Modeling Tools',
            'Wire',
            'Armatures',
            'Printing Press',
            'Ink',
            'Brayers',
            'Carving Tools',
            'Linoleum Blocks',
            'Scissors',
            'Glue',
            'Magazines',
            'Photographs',
            'Needles',
            'Thread',
            'Yarn',
            'Fabric',
            'Loom',
            'Potter’s Wheel',
            'Kiln',
            'Glazes',
            'Sculpting Tools',
            'Camera',
            'Tripod',
            'Lenses',
            'Lighting Equipment',
            'Editing Software',
            'Computer',
            'Graphics Tablet',
            'Design Software',
            'Stylus',
            'Monitor',
            'Brush Pens',
            'Ink',
            'Nibs',
            'Calligraphy Paper',
            'Pliers',
            'Wire Cutters',
            'Beads',
            'Chains',
            'Jeweler’s Torch',
            'Tiles',
            'Glass Cutters',
            'Grout',
            'Adhesive',
            'Tweezers',
            'Saw',
            'Planer',
            'Hammer',
            'Wood Glue',
            'Sewing Machine',
            'Glue Gun',
            'Paint',
            'Sandpaper',
            'Origami Paper',
            'Bone Folder',
            'Cutting Mat',
            'Ruler',
            'Found Objects',
            'Lightbox',
            'Color Wheel',
            'Swatches',
            'Color Palettes',
            'Lighting',
            '3D Printer',
            'Filament',
            'CAD Software',
            'Nozzle',
            'Build Plate',
            '3D Scanner',
            'CNC Machine',
            'Cutting Tools',
            'Clamp',
            'Drill Bits',
            'Laser Cutter',
            'Cutting Bed',
            'Material Sheets',
            'Exhaust System',
            'Embroidery Hoop',
            'Embroidery Patterns',
        ];

        foreach ($tools as $name) {
            Tool::firstOrCreate(['name' => $name]);
        }
    }
}
