<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InsertImportacaoPermissionsDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'importacao_inserir']);
        Permission::create(['name' => 'importacao_editar']);
        Permission::create(['name' => 'importacao_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('importacao_inserir');
        $role->givePermissionTo('importacao_editar');
        $role->givePermissionTo('importacao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('importacao_inserir');
        $role->givePermissionTo('importacao_editar');
        $role->givePermissionTo('importacao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('importacao_inserir');
        $role->givePermissionTo('importacao_editar');
        $role->givePermissionTo('importacao_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('importacao_inserir');
        $role->revokePermissionTo('importacao_editar');
        $role->revokePermissionTo('importacao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('importacao_inserir');
        $role->revokePermissionTo('importacao_editar');
        $role->revokePermissionTo('importacao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('importacao_inserir');
        $role->revokePermissionTo('importacao_editar');
        $role->revokePermissionTo('importacao_deletar');

        Permission::where(['name' => 'importacao_inserir'])->forceDelete();
        Permission::where(['name' => 'importacao_editar'])->forceDelete();
        Permission::where(['name' => 'importacao_deletar'])->forceDelete();

    }
}
