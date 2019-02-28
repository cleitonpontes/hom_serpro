<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class RolesSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        // acesso aos modulos
        Permission::create(['name' => 'usuario_inserir']);
        Permission::create(['name' => 'usuario_editar']);
        Permission::create(['name' => 'usuario_deletar']);
        Permission::create(['name' => 'grupo_inserir']);
        Permission::create(['name' => 'grupo_editar']);
        Permission::create(['name' => 'grupo_deletar']);
        Permission::create(['name' => 'permissao_inserir']);
        Permission::create(['name' => 'permissao_editar']);
        Permission::create(['name' => 'permissao_deletar']);
        Permission::create(['name' => 'contrato_inserir']);
        Permission::create(['name' => 'contrato_editar']);
        Permission::create(['name' => 'contrato_deletar']);
        Permission::create(['name' => 'responsavel_inserir']);
        Permission::create(['name' => 'responsavel_editar']);
        Permission::create(['name' => 'responsavel_deletar']);
        Permission::create(['name' => 'garantia_inserir']);
        Permission::create(['name' => 'garantia_editar']);
        Permission::create(['name' => 'garantia_deletar']);
        Permission::create(['name' => 'fornecedor_inserir']);
        Permission::create(['name' => 'fornecedor_editar']);
        Permission::create(['name' => 'fornecedor_deletar']);
        Permission::create(['name' => 'codigo_inserir']);
        Permission::create(['name' => 'codigo_editar']);
        Permission::create(['name' => 'codigo_deletar']);
        Permission::create(['name' => 'codigoitem_inserir']);
        Permission::create(['name' => 'codigoitem_editar']);
        Permission::create(['name' => 'codigoitem_deletar']);
        Permission::create(['name' => 'contratoarquivo_inserir']);
        Permission::create(['name' => 'contratoarquivo_editar']);
        Permission::create(['name' => 'contratoarquivo_deletar']);
        Permission::create(['name' => 'empenho_inserir']);
        Permission::create(['name' => 'empenho_editar']);
        Permission::create(['name' => 'empenho_deletar']);
        Permission::create(['name' => 'empenhodetalhado_inserir']);
        Permission::create(['name' => 'empenhodetalhado_editar']);
        Permission::create(['name' => 'empenhodetalhado_deletar']);
        Permission::create(['name' => 'folha_apropriacao_acesso']);
        Permission::create(['name' => 'folha_apropriacao_passo']);
        Permission::create(['name' => 'folha_apropriacao_deletar']);
        Permission::create(['name' => 'situacaosiafi_inserir']);
        Permission::create(['name' => 'situacaosiafi_editar']);
        Permission::create(['name' => 'situacaosiafi_deletar']);
        Permission::create(['name' => 'sfcertificado_inserir']);
        Permission::create(['name' => 'sfcertificado_editar']);
        Permission::create(['name' => 'sfcertificado_deletar']);
        Permission::create(['name' => 'rhsituacao_inserir']);
        Permission::create(['name' => 'rhsituacao_editar']);
        Permission::create(['name' => 'rhsituacao_deletar']);
        Permission::create(['name' => 'rhrubrica_inserir']);
        Permission::create(['name' => 'rhrubrica_editar']);
        Permission::create(['name' => 'rhrubrica_deletar']);
        Permission::create(['name' => 'rhsituacao_rhrubrica_inserir']);
        Permission::create(['name' => 'rhsituacao_rhrubrica_editar']);
        Permission::create(['name' => 'rhsituacao_rhrubrica_deletar']);
        Permission::create(['name' => 'contratoempenho_inserir']);
        Permission::create(['name' => 'contratoempenho_editar']);
        Permission::create(['name' => 'contratoempenho_deletar']);




        $role = Role::create(['name' => 'Administrador']);
        $role->givePermissionTo('usuario_inserir');
        $role->givePermissionTo('usuario_editar');
        $role->givePermissionTo('usuario_deletar');
        $role->givePermissionTo('grupo_inserir');
        $role->givePermissionTo('grupo_editar');
        $role->givePermissionTo('grupo_deletar');
        $role->givePermissionTo('permissao_inserir');
        $role->givePermissionTo('permissao_editar');
        $role->givePermissionTo('permissao_deletar');
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
        $role->givePermissionTo('codigo_inserir');
        $role->givePermissionTo('codigo_editar');
        $role->givePermissionTo('codigo_deletar');
        $role->givePermissionTo('codigoitem_inserir');
        $role->givePermissionTo('codigoitem_editar');
        $role->givePermissionTo('codigoitem_deletar');
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

    }
}
