<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajosSeeder extends Seeder
{
    public function run(): void
    {
        $trabajos = [
            ['name' => 'Carpintería'],
            ['name' => 'Herrería'],
            ['name' => 'Electricidad'],
            ['name' => 'Albañilería'],
            ['name' => 'Plomería'],
            ['name' => 'Cuidados'],
            ['name' => 'Pintura'],
        ];

        DB::table('trabajo')->insert($trabajos);
    }
}
