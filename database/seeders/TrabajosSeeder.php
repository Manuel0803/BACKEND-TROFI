<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('Trabajos')->insert([
            'dni' => 44944446,
            'anos_xp' => 4,
            'oficio_id' => 1,
            'user_id' => 1,
            'descripcion' => 'Todo tipo de trabajos en madera'
        ]);
    }
}
