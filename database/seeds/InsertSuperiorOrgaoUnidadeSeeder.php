<?php

use App\Models\Unidade;
use Illuminate\Database\Seeder;
use App\Models\OrgaoSuperior;
use App\Models\Orgao;
use App\Models\Municipio;
use Illuminate\Support\Facades\DBB;

class InsertSuperiorOrgaoUnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adiciono a extensão do postgres para retirar os acentos na comparação
        // Supported Versions do Postgresql: Current (12) / 11 / 10 / 9.6 / 9.5
        DB::select( DB::raw(" CREATE EXTENSION IF NOT EXISTS unaccent ") );

        $json = File::get('database/data/lista_uasg_2020_06_05_min.json');
        $data = json_decode($json);

        foreach ($data as $item) {
            $mun = Municipio::join('estados','estados.id','=','municipios.estado_id')
            ->where(DB::raw('unaccent(municipios.nome)'),'ilike',$item->unidades_mun)
            ->where('estados.sigla','ilike',$item->unidades_uf)
            ->select('municipios.id')
            ->first();

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
                    'municipio_id' => $mun->id ?? null,
                ]
            );

        }

        $this->command->info('[Lista UASG] adicionada com sucesso ao banco!');
    }
}
