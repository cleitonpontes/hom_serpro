<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('roles')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'name' => 'Administrador Órgão',
                'guard_name' => 'web',
                'created_at' => '2019-10-23 18:46:08',
                'updated_at' => '2019-10-23 18:46:08',
            ),
            1 =>
            array (
                'id' => 55000003,
                'name' => 'Administrador Unidade',
                'guard_name' => 'web',
                'created_at' => '2019-10-23 18:46:12',
                'updated_at' => '2019-10-23 18:46:12',
            ),
            2 =>
            array (
                'id' => 55000004,
                'name' => 'Setor Contratos',
                'guard_name' => 'web',
                'created_at' => '2019-10-23 18:46:14',
                'updated_at' => '2019-10-23 18:46:14',
            ),
            3 =>
            array (
                'id' => 55000002,
                'name' => 'Administrador',
                'guard_name' => 'web',
                'created_at' => '2019-10-23 18:46:09',
                'updated_at' => '2019-11-12 17:39:01',
            ),
            4 =>
            array (
                'id' => 55000005,
                'name' => 'Setor Financeiro',
                'guard_name' => 'web',
                'created_at' => '2019-11-13 19:36:06',
                'updated_at' => '2019-11-13 19:36:06',
            ),
            5 =>
            array (
                'id' => 55000006,
                'name' => 'Execução Financeira',
                'guard_name' => 'web',
                'created_at' => '2020-07-21 22:20:11',
                'updated_at' => '2020-07-21 22:20:11',
            ),
        ));


    }
}
