<?php

use Illuminate\Database\Seeder;

class EmpenhosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sfnonce')->insert(['tipo' => '110062_1_']);

    }
}
