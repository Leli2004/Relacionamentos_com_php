<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fake = Faker::create("pt_BR");
        foreach(\range(1,3) as $index){
            DB::table('categoria_aluno')->insert(
                [   'nome'=>$fake->randomElement($array = array ('TEC_INF','ENG_CA','ESPEC_1')),
                    'nivel'=>$fake->randomElement($array = array ('Cat1','Cat2','Cat3')),
                ]
            );
        }
    }
}
