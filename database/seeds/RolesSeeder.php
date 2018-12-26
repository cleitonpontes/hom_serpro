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

    }
}
