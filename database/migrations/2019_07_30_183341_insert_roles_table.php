<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InsertRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        // acesso aos modulos
        Permission::create(['name' => 'usuarioorgao_inserir']);
        Permission::create(['name' => 'usuarioorgao_editar']);
        Permission::create(['name' => 'usuariounidade_inserir']);
        Permission::create(['name' => 'usuariounidade_editar']);

        $role = Role::create(['name' => 'Administrador Órgão']);
        $role->givePermissionTo('usuarioorgao_inserir');
        $role->givePermissionTo('usuarioorgao_editar');
        $role->givePermissionTo('usuariounidade_inserir');
        $role->givePermissionTo('usuariounidade_editar');
        $role->givePermissionTo('contrato_inserir');
        $role->givePermissionTo('contrato_editar');
        $role->givePermissionTo('contrato_deletar');
        $role->givePermissionTo('responsavel_inserir');
        $role->givePermissionTo('responsavel_editar');
        $role->givePermissionTo('responsavel_deletar');
        $role->givePermissionTo('garantia_inserir');
        $role->givePermissionTo('garantia_editar');
        $role->givePermissionTo('garantia_deletar');
        $role->givePermissionTo('fornecedor_inserir');
        $role->givePermissionTo('fornecedor_editar');
        $role->givePermissionTo('fornecedor_deletar');
        $role->givePermissionTo('contratoarquivo_inserir');
        $role->givePermissionTo('contratoarquivo_editar');
        $role->givePermissionTo('contratoarquivo_deletar');
        $role->givePermissionTo('folha_apropriacao_acesso');
        $role->givePermissionTo('folha_apropriacao_passo');
        $role->givePermissionTo('folha_apropriacao_deletar');
        $role->givePermissionTo('empenho_inserir');
        $role->givePermissionTo('empenho_editar');
        $role->givePermissionTo('empenho_deletar');
        $role->givePermissionTo('empenhodetalhado_inserir');
        $role->givePermissionTo('empenhodetalhado_editar');
        $role->givePermissionTo('empenhodetalhado_deletar');
        $role->givePermissionTo('situacaosiafi_inserir');
        $role->givePermissionTo('situacaosiafi_editar');
        $role->givePermissionTo('situacaosiafi_deletar');
        $role->givePermissionTo('sfcertificado_inserir');
        $role->givePermissionTo('sfcertificado_editar');
        $role->givePermissionTo('sfcertificado_deletar');
        $role->givePermissionTo('rhsituacao_inserir');
        $role->givePermissionTo('rhsituacao_editar');
        $role->givePermissionTo('rhsituacao_deletar');
        $role->givePermissionTo('rhrubrica_inserir');
        $role->givePermissionTo('rhrubrica_editar');
        $role->givePermissionTo('rhrubrica_deletar');
        $role->givePermissionTo('rhsituacao_rhrubrica_inserir');
        $role->givePermissionTo('rhsituacao_rhrubrica_editar');
        $role->givePermissionTo('rhsituacao_rhrubrica_deletar');
        $role->givePermissionTo('contratoempenho_inserir');
        $role->givePermissionTo('contratoempenho_editar');
        $role->givePermissionTo('contratoempenho_deletar');
        $role->givePermissionTo('contratocronograma_inserir');
        $role->givePermissionTo('contratocronograma_editar');
        $role->givePermissionTo('contratocronograma_deletar');
        $role->givePermissionTo('contratoaditivo_inserir');
        $role->givePermissionTo('contratoaditivo_editar');
        $role->givePermissionTo('contratoaditivo_deletar');
        $role->givePermissionTo('contratoapostilamento_inserir');
        $role->givePermissionTo('contratoapostilamento_editar');
        $role->givePermissionTo('contratoapostilamento_deletar');
        $role->givePermissionTo('contratorescisao_inserir');
        $role->givePermissionTo('contratorescisao_editar');
        $role->givePermissionTo('contratorescisao_deletar');
        $role->givePermissionTo('contratoitem_inserir');
        $role->givePermissionTo('contratoitem_editar');
        $role->givePermissionTo('contratoitem_deletar');

        $role = Role::create(['name' => 'Administrador Unidade']);
        $role->givePermissionTo('usuarioorgao_inserir');
        $role->givePermissionTo('usuarioorgao_editar');
        $role->givePermissionTo('usuariounidade_inserir');
        $role->givePermissionTo('usuariounidade_editar');
        $role->givePermissionTo('contrato_inserir');
        $role->givePermissionTo('contrato_editar');
        $role->givePermissionTo('contrato_deletar');
        $role->givePermissionTo('responsavel_inserir');
        $role->givePermissionTo('responsavel_editar');
        $role->givePermissionTo('responsavel_deletar');
        $role->givePermissionTo('garantia_inserir');
        $role->givePermissionTo('garantia_editar');
        $role->givePermissionTo('garantia_deletar');
        $role->givePermissionTo('fornecedor_inserir');
        $role->givePermissionTo('fornecedor_editar');
        $role->givePermissionTo('fornecedor_deletar');
        $role->givePermissionTo('contratoarquivo_inserir');
        $role->givePermissionTo('contratoarquivo_editar');
        $role->givePermissionTo('contratoarquivo_deletar');
        $role->givePermissionTo('folha_apropriacao_acesso');
        $role->givePermissionTo('folha_apropriacao_passo');
        $role->givePermissionTo('folha_apropriacao_deletar');
        $role->givePermissionTo('empenho_inserir');
        $role->givePermissionTo('empenho_editar');
        $role->givePermissionTo('empenho_deletar');
        $role->givePermissionTo('empenhodetalhado_inserir');
        $role->givePermissionTo('empenhodetalhado_editar');
        $role->givePermissionTo('empenhodetalhado_deletar');
        $role->givePermissionTo('situacaosiafi_inserir');
        $role->givePermissionTo('situacaosiafi_editar');
        $role->givePermissionTo('situacaosiafi_deletar');
        $role->givePermissionTo('sfcertificado_inserir');
        $role->givePermissionTo('sfcertificado_editar');
        $role->givePermissionTo('sfcertificado_deletar');
        $role->givePermissionTo('rhsituacao_inserir');
        $role->givePermissionTo('rhsituacao_editar');
        $role->givePermissionTo('rhsituacao_deletar');
        $role->givePermissionTo('rhrubrica_inserir');
        $role->givePermissionTo('rhrubrica_editar');
        $role->givePermissionTo('rhrubrica_deletar');
        $role->givePermissionTo('rhsituacao_rhrubrica_inserir');
        $role->givePermissionTo('rhsituacao_rhrubrica_editar');
        $role->givePermissionTo('rhsituacao_rhrubrica_deletar');
        $role->givePermissionTo('contratoempenho_inserir');
        $role->givePermissionTo('contratoempenho_editar');
        $role->givePermissionTo('contratoempenho_deletar');
        $role->givePermissionTo('contratocronograma_inserir');
        $role->givePermissionTo('contratocronograma_editar');
        $role->givePermissionTo('contratocronograma_deletar');
        $role->givePermissionTo('contratoaditivo_inserir');
        $role->givePermissionTo('contratoaditivo_editar');
        $role->givePermissionTo('contratoaditivo_deletar');
        $role->givePermissionTo('contratoapostilamento_inserir');
        $role->givePermissionTo('contratoapostilamento_editar');
        $role->givePermissionTo('contratoapostilamento_deletar');
        $role->givePermissionTo('contratorescisao_inserir');
        $role->givePermissionTo('contratorescisao_editar');
        $role->givePermissionTo('contratorescisao_deletar');
        $role->givePermissionTo('contratoitem_inserir');
        $role->givePermissionTo('contratoitem_editar');
        $role->givePermissionTo('contratoitem_deletar');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
