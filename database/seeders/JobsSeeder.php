<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajosSeeder extends Seeder
{

    public function run(): void
    {
        //
        DB::table('trabajo')->insert([
            'name' => "Plomeria",
        ]);
        DB::table('trabajo')->insert([
            'name' => "Electricidad",
        ]);
        DB::table('trabajo')->insert([
            'name' => "Carpinteria",
        ]);
        DB::table('trabajo')->insert([
            'name' => "Pintura",
        ]);
        DB::table('trabajo')->insert([
            'name' => "Jardineria",
        ]);
        DB::table('trabajo')->insert([
            'name' => "Mecanica",
        ]);
    }
}
