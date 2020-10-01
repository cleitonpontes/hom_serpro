<?php

use App\Models\Unidade;
use Illuminate\Database\Seeder;

class SigiloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Processando, por favor aguarde');
        $codigos = [
            '110120',
            '110121',
            '110122',
            '110123',
            '110124',
            '110125',
            '110126',
            '110127',
            '110128',
            '110129',
            '110130',
            '110131',
            '110132',
            '110133',
            '110134',
            '110135',
            '110136',
            '110137',
            '110138',
            '110139',
            '110140',
            '110384',
            '110385',
            '110386',
            '110387',
            '110537',
            '110538'
        ];

        Unidade::whereIn('codigo',$codigos)->update(['sigilo'=>true]);
    }
}
