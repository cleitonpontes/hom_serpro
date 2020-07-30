<?php

use App\Models\Unidade;
use Illuminate\Database\Seeder;
use App\Models\OrgaoSuperior;
use App\Models\Orgao;

class InsertSuperiorOrgaoUnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $json = File::get('database/data/lista_uasg_2020_06_05_min.json');
        $data = json_decode($json);

        foreach ($data as $item) {
            $superior = OrgaoSuperior::firstOrCreate(
                ['codigo' => $item->orgaossuperiores_codigo],
                [
                    'nome' => $item->orgaossuperiores_nome,
                    'situacao' => true
                ]
            )->id;

            $orgao = Orgao::firstOrCreate(
                ['codigo' => $item->orgaos_codigo],
                [
                    'orgaosuperior_id' => $superior,
                    'nome' => $item->orgaos_nome,
                    'codigosiasg' => $item->orgaos_codigo,
                    'situacao' => true
                ]
            );

            Unidade::firstOrCreate(
                ['codigo' => $item->unidades_codigo],
                [
                    'orgao_id' => $orgao->id,
                    'codigo' => $item->unidades_codigo,
                    'gestao' => $orgao->codigo,
                    'codigosiasg' => $item->unidades_codigo,
                    'nome' => $item->unidades_nome,
                    'nomeresumido' => $item->unidades_nomeresumido,
                    'tipo' => 'E',
                    'situacao' => true,
                ]
            );

        }

        $this->command->info('[Lista UASG] adicionada com sucesso ao banco!');
    }
}
