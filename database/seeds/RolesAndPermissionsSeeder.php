<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // acesso aos modulos
        Permission::create(['name' => 'administracao_inicio_acesso']);
        Permission::create(['name' => 'contrato_inicio_acesso']);
        Permission::create(['name' => 'fiscalizacao_inicio_acesso']);
        Permission::create(['name' => 'capacitacao_inicio_acesso']);
        Permission::create(['name' => 'execucao_inicio_acesso']);
        Permission::create(['name' => 'execsiafi_inicio_acesso']);
        Permission::create(['name' => 'tributario_inicio_acesso']);
        Permission::create(['name' => 'folha_inicio_acesso']);
        Permission::create(['name' => 'inicio_inicio_acesso']);
        Permission::create(['name' => 'todos_mudarmodulo_acesso']);
        Permission::create(['name' => 'administracao_orgaosuperior_acesso']);
        Permission::create(['name' => 'administracao_orgao_acesso']);
        Permission::create(['name' => 'administracao_unidade_acesso']);
        Permission::create(['name' => 'administracao_usuario_acesso']);
        Permission::create(['name' => 'administracao_modulo_acesso']);
        Permission::create(['name' => 'administracao_grupo_acesso']);
        Permission::create(['name' => 'administracao_permissao_acesso']);
        Permission::create(['name' => 'administracao_codigo_acesso']);
        Permission::create(['name' => 'administracao_email_acesso']);
        Permission::create(['name' => 'administracao_cadastro_acesso']);
        Permission::create(['name' => 'administracao_comunicacao_acesso']);
        Permission::create(['name' => 'administracao_orgao_unidade_acesso']);
        Permission::create(['name' => 'administracao_acesso_acesso']);
        Permission::create(['name' => 'administracao_outros_acesso']);
        Permission::create(['name' => 'folha_cadastro_acesso']);
        Permission::create(['name' => 'folha_rubrica_acesso']);
        Permission::create(['name' => 'administracao_sfcertificado_acesso']);
        Permission::create(['name' => 'administracao_orgaosuperior_inserir']);
        Permission::create(['name' => 'administracao_orgao_inserir']);
        Permission::create(['name' => 'administracao_unidade_inserir']);
        Permission::create(['name' => 'administracao_usuario_inserir']);
        Permission::create(['name' => 'administracao_modulo_inserir']);
        Permission::create(['name' => 'administracao_grupo_inserir']);
        Permission::create(['name' => 'administracao_permissao_inserir']);
        Permission::create(['name' => 'administracao_codigo_inserir']);
        Permission::create(['name' => 'administracao_sfcertificado_inserir']);
        Permission::create(['name' => 'folha_rubrica_inserir']);
        Permission::create(['name' => 'administracao_orgaosuperior_editar']);
        Permission::create(['name' => 'administracao_orgao_editar']);
        Permission::create(['name' => 'administracao_unidade_editar']);
        Permission::create(['name' => 'administracao_usuario_editar']);
        Permission::create(['name' => 'administracao_modulo_editar']);
        Permission::create(['name' => 'administracao_grupo_editar']);
        Permission::create(['name' => 'administracao_permissao_editar']);
        Permission::create(['name' => 'administracao_sfcertificado_editar']);
        Permission::create(['name' => 'administracao_codigo_editar']);
        Permission::create(['name' => 'administracao_orgaosuperior_excluir']);
        Permission::create(['name' => 'administracao_orgao_excluir']);
        Permission::create(['name' => 'administracao_unidade_excluir']);
        Permission::create(['name' => 'administracao_usuario_excluir']);
        Permission::create(['name' => 'administracao_modulo_excluir']);
        Permission::create(['name' => 'administracao_grupo_excluir']);
        Permission::create(['name' => 'administracao_permissao_excluir']);
        Permission::create(['name' => 'administracao_sfcertificado_excluir']);
        Permission::create(['name' => 'administracao_codigo_excluir']);
        Permission::create(['name' => 'administracao_sfcertificado_mostrar']);
        Permission::create(['name' => 'administracao_unidade_exportar']);
        Permission::create(['name' => 'administracao_permissao_exportar']);
        Permission::create(['name' => 'administracao_grupo_exportar']);
        Permission::create(['name' => 'administracao_mais_grupo_permissao_acesso']);
        Permission::create(['name' => 'administracao_mais_codigoitem_acesso']);
        Permission::create(['name' => 'administracao_mais_usuario_unidade_acesso']);
        Permission::create(['name' => 'administracao_mais_usuario_grupo_acesso']);
        Permission::create(['name' => 'administracao_mais_codigoitem_inserir']);
        Permission::create(['name' => 'administracao_mais_codigoitem_editar']);
        Permission::create(['name' => 'administracao_mais_codigoitem_excluir']);



        // create roles and assign existing permissions
        $role = Role::create(['name' => 'Super Administrador']);
        $role->givePermissionTo('inicio_inicio_acesso');
        $role->givePermissionTo('administracao_inicio_acesso');
        $role->givePermissionTo('contrato_inicio_acesso');
        $role->givePermissionTo('fiscalizacao_inicio_acesso');
        $role->givePermissionTo('capacitacao_inicio_acesso');
        $role->givePermissionTo('execucao_inicio_acesso');
        $role->givePermissionTo('execsiafi_inicio_acesso');
        $role->givePermissionTo('tributario_inicio_acesso');
        $role->givePermissionTo('folha_inicio_acesso');
        $role->givePermissionTo('todos_mudarmodulo_acesso');
        $role->givePermissionTo('administracao_orgaosuperior_acesso');
        $role->givePermissionTo('administracao_orgao_acesso');
        $role->givePermissionTo('administracao_unidade_acesso');
        $role->givePermissionTo('administracao_usuario_acesso');
        $role->givePermissionTo('administracao_modulo_acesso');
        $role->givePermissionTo('administracao_grupo_acesso');
        $role->givePermissionTo('administracao_permissao_acesso');
        $role->givePermissionTo('administracao_codigo_acesso');
        $role->givePermissionTo('administracao_email_acesso');
        $role->givePermissionTo('administracao_orgao_unidade_acesso');
        $role->givePermissionTo('administracao_acesso_acesso');
        $role->givePermissionTo('administracao_cadastro_acesso');
        $role->givePermissionTo('administracao_outros_acesso');
        $role->givePermissionTo('administracao_comunicacao_acesso');
        $role->givePermissionTo('administracao_orgaosuperior_inserir');
        $role->givePermissionTo('administracao_orgao_inserir');
        $role->givePermissionTo('administracao_unidade_inserir');
        $role->givePermissionTo('administracao_usuario_inserir');
        $role->givePermissionTo('administracao_modulo_inserir');
        $role->givePermissionTo('administracao_grupo_inserir');
        $role->givePermissionTo('administracao_permissao_inserir');
        $role->givePermissionTo('administracao_codigo_inserir');
        $role->givePermissionTo('administracao_orgaosuperior_editar');
        $role->givePermissionTo('administracao_orgao_editar');
        $role->givePermissionTo('administracao_unidade_editar');
        $role->givePermissionTo('administracao_usuario_editar');
        $role->givePermissionTo('administracao_modulo_editar');
        $role->givePermissionTo('administracao_grupo_editar');
        $role->givePermissionTo('administracao_permissao_editar');
        $role->givePermissionTo('administracao_codigo_editar');
        $role->givePermissionTo('administracao_orgaosuperior_excluir');
        $role->givePermissionTo('administracao_orgao_excluir');
        $role->givePermissionTo('administracao_unidade_excluir');
        $role->givePermissionTo('administracao_usuario_excluir');
        $role->givePermissionTo('administracao_modulo_excluir');
        $role->givePermissionTo('administracao_grupo_excluir');
        $role->givePermissionTo('administracao_permissao_excluir');
        $role->givePermissionTo('administracao_codigo_excluir');
        $role->givePermissionTo('administracao_unidade_exportar');
        $role->givePermissionTo('administracao_permissao_exportar');
        $role->givePermissionTo('administracao_grupo_exportar');
        $role->givePermissionTo('administracao_mais_grupo_permissao_acesso');
        $role->givePermissionTo('administracao_mais_codigoitem_acesso');
        $role->givePermissionTo('administracao_mais_usuario_unidade_acesso');
        $role->givePermissionTo('administracao_mais_usuario_grupo_acesso');
        $role->givePermissionTo('administracao_mais_codigoitem_inserir');
        $role->givePermissionTo('administracao_mais_codigoitem_editar');
        $role->givePermissionTo('administracao_mais_codigoitem_excluir');
        $role->givePermissionTo('folha_cadastro_acesso');
        $role->givePermissionTo('folha_rubrica_acesso');
        $role->givePermissionTo('folha_rubrica_inserir');
        $role->givePermissionTo('administracao_sfcertificado_acesso');
        $role->givePermissionTo('administracao_sfcertificado_inserir');
        $role->givePermissionTo('administracao_sfcertificado_editar');
        $role->givePermissionTo('administracao_sfcertificado_excluir');
        $role->givePermissionTo('administracao_sfcertificado_mostrar');

        $role = Role::create(['name' => 'Administrador Orgao Superior']);
        $role->givePermissionTo('administracao_inicio_acesso');
        $role->givePermissionTo('inicio_inicio_acesso');

        $role = Role::create(['name' => 'Administrador Orgao']);
        $role->givePermissionTo('administracao_inicio_acesso');
        $role->givePermissionTo('inicio_inicio_acesso');
    }
}
