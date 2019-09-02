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

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('usuarioorgao_inserir');
        $role->revokePermissionTo('usuarioorgao_editar');
        $role->revokePermissionTo('usuariounidade_inserir');
        $role->revokePermissionTo('usuariounidade_editar');
        $role->revokePermissionTo('contrato_inserir');
        $role->revokePermissionTo('contrato_editar');
        $role->revokePermissionTo('contrato_deletar');
        $role->revokePermissionTo('responsavel_inserir');
        $role->revokePermissionTo('responsavel_editar');
        $role->revokePermissionTo('responsavel_deletar');
        $role->revokePermissionTo('garantia_inserir');
        $role->revokePermissionTo('garantia_editar');
        $role->revokePermissionTo('garantia_deletar');
        $role->revokePermissionTo('fornecedor_inserir');
        $role->revokePermissionTo('fornecedor_editar');
        $role->revokePermissionTo('fornecedor_deletar');
        $role->revokePermissionTo('contratoarquivo_inserir');
        $role->revokePermissionTo('contratoarquivo_editar');
        $role->revokePermissionTo('contratoarquivo_deletar');
        $role->revokePermissionTo('folha_apropriacao_acesso');
        $role->revokePermissionTo('folha_apropriacao_passo');
        $role->revokePermissionTo('folha_apropriacao_deletar');
        $role->revokePermissionTo('empenho_inserir');
        $role->revokePermissionTo('empenho_editar');
        $role->revokePermissionTo('empenho_deletar');
        $role->revokePermissionTo('empenhodetalhado_inserir');
        $role->revokePermissionTo('empenhodetalhado_editar');
        $role->revokePermissionTo('empenhodetalhado_deletar');
        $role->revokePermissionTo('situacaosiafi_inserir');
        $role->revokePermissionTo('situacaosiafi_editar');
        $role->revokePermissionTo('situacaosiafi_deletar');
        $role->revokePermissionTo('sfcertificado_inserir');
        $role->revokePermissionTo('sfcertificado_editar');
        $role->revokePermissionTo('sfcertificado_deletar');
        $role->revokePermissionTo('rhsituacao_inserir');
        $role->revokePermissionTo('rhsituacao_editar');
        $role->revokePermissionTo('rhsituacao_deletar');
        $role->revokePermissionTo('rhrubrica_inserir');
        $role->revokePermissionTo('rhrubrica_editar');
        $role->revokePermissionTo('rhrubrica_deletar');
        $role->revokePermissionTo('rhsituacao_rhrubrica_inserir');
        $role->revokePermissionTo('rhsituacao_rhrubrica_editar');
        $role->revokePermissionTo('rhsituacao_rhrubrica_deletar');
        $role->revokePermissionTo('contratoempenho_inserir');
        $role->revokePermissionTo('contratoempenho_editar');
        $role->revokePermissionTo('contratoempenho_deletar');
        $role->revokePermissionTo('contratocronograma_inserir');
        $role->revokePermissionTo('contratocronograma_editar');
        $role->revokePermissionTo('contratocronograma_deletar');
        $role->revokePermissionTo('contratoaditivo_inserir');
        $role->revokePermissionTo('contratoaditivo_editar');
        $role->revokePermissionTo('contratoaditivo_deletar');
        $role->revokePermissionTo('contratoapostilamento_inserir');
        $role->revokePermissionTo('contratoapostilamento_editar');
        $role->revokePermissionTo('contratoapostilamento_deletar');
        $role->revokePermissionTo('contratorescisao_inserir');
        $role->revokePermissionTo('contratorescisao_editar');
        $role->revokePermissionTo('contratorescisao_deletar');
        $role->revokePermissionTo('contratoitem_inserir');
        $role->revokePermissionTo('contratoitem_editar');
        $role->revokePermissionTo('contratoitem_deletar');
        $role->forceDelete();

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('usuarioorgao_inserir');
        $role->revokePermissionTo('usuarioorgao_editar');
        $role->revokePermissionTo('usuariounidade_inserir');
        $role->revokePermissionTo('usuariounidade_editar');
        $role->revokePermissionTo('contrato_inserir');
        $role->revokePermissionTo('contrato_editar');
        $role->revokePermissionTo('contrato_deletar');
        $role->revokePermissionTo('responsavel_inserir');
        $role->revokePermissionTo('responsavel_editar');
        $role->revokePermissionTo('responsavel_deletar');
        $role->revokePermissionTo('garantia_inserir');
        $role->revokePermissionTo('garantia_editar');
        $role->revokePermissionTo('garantia_deletar');
        $role->revokePermissionTo('fornecedor_inserir');
        $role->revokePermissionTo('fornecedor_editar');
        $role->revokePermissionTo('fornecedor_deletar');
        $role->revokePermissionTo('contratoarquivo_inserir');
        $role->revokePermissionTo('contratoarquivo_editar');
        $role->revokePermissionTo('contratoarquivo_deletar');
        $role->revokePermissionTo('folha_apropriacao_acesso');
        $role->revokePermissionTo('folha_apropriacao_passo');
        $role->revokePermissionTo('folha_apropriacao_deletar');
        $role->revokePermissionTo('empenho_inserir');
        $role->revokePermissionTo('empenho_editar');
        $role->revokePermissionTo('empenho_deletar');
        $role->revokePermissionTo('empenhodetalhado_inserir');
        $role->revokePermissionTo('empenhodetalhado_editar');
        $role->revokePermissionTo('empenhodetalhado_deletar');
        $role->revokePermissionTo('situacaosiafi_inserir');
        $role->revokePermissionTo('situacaosiafi_editar');
        $role->revokePermissionTo('situacaosiafi_deletar');
        $role->revokePermissionTo('sfcertificado_inserir');
        $role->revokePermissionTo('sfcertificado_editar');
        $role->revokePermissionTo('sfcertificado_deletar');
        $role->revokePermissionTo('rhsituacao_inserir');
        $role->revokePermissionTo('rhsituacao_editar');
        $role->revokePermissionTo('rhsituacao_deletar');
        $role->revokePermissionTo('rhrubrica_inserir');
        $role->revokePermissionTo('rhrubrica_editar');
        $role->revokePermissionTo('rhrubrica_deletar');
        $role->revokePermissionTo('rhsituacao_rhrubrica_inserir');
        $role->revokePermissionTo('rhsituacao_rhrubrica_editar');
        $role->revokePermissionTo('rhsituacao_rhrubrica_deletar');
        $role->revokePermissionTo('contratoempenho_inserir');
        $role->revokePermissionTo('contratoempenho_editar');
        $role->revokePermissionTo('contratoempenho_deletar');
        $role->revokePermissionTo('contratocronograma_inserir');
        $role->revokePermissionTo('contratocronograma_editar');
        $role->revokePermissionTo('contratocronograma_deletar');
        $role->revokePermissionTo('contratoaditivo_inserir');
        $role->revokePermissionTo('contratoaditivo_editar');
        $role->revokePermissionTo('contratoaditivo_deletar');
        $role->revokePermissionTo('contratoapostilamento_inserir');
        $role->revokePermissionTo('contratoapostilamento_editar');
        $role->revokePermissionTo('contratoapostilamento_deletar');
        $role->revokePermissionTo('contratorescisao_inserir');
        $role->revokePermissionTo('contratorescisao_editar');
        $role->revokePermissionTo('contratorescisao_deletar');
        $role->revokePermissionTo('contratoitem_inserir');
        $role->revokePermissionTo('contratoitem_editar');
        $role->revokePermissionTo('contratoitem_deletar');
        $role->forceDelete();

        Permission::where(['name' => 'usuarioorgao_inserir'])->forceDelete();
        Permission::where(['name' => 'usuarioorgao_editar'])->forceDelete();
        Permission::where(['name' => 'usuariounidade_inserir'])->forceDelete();
        Permission::where(['name' => 'usuariounidade_editar'])->forceDelete();

    }
}
