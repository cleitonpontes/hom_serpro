<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertpermisionConfiguracaounidadeDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'unidadeconfiguracao_inserir']);
        Permission::create(['name' => 'unidadeconfiguracao_editar']);
        Permission::create(['name' => 'unidadeconfiguracao_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('unidadeconfiguracao_inserir');
        $role->givePermissionTo('unidadeconfiguracao_editar');
        $role->givePermissionTo('unidadeconfiguracao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('unidadeconfiguracao_inserir');
        $role->givePermissionTo('unidadeconfiguracao_editar');
        $role->givePermissionTo('unidadeconfiguracao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('unidadeconfiguracao_inserir');
        $role->givePermissionTo('unidadeconfiguracao_editar');
        $role->givePermissionTo('unidadeconfiguracao_deletar');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('unidadeconfiguracao_inserir');
        $role->revokePermissionTo('unidadeconfiguracao_editar');
        $role->revokePermissionTo('unidadeconfiguracao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('unidadeconfiguracao_inserir');
        $role->revokePermissionTo('unidadeconfiguracao_editar');
        $role->revokePermissionTo('unidadeconfiguracao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('unidadeconfiguracao_inserir');
        $role->revokePermissionTo('unidadeconfiguracao_editar');
        $role->revokePermissionTo('unidadeconfiguracao_deletar');

        Permission::where(['name' => 'unidadeconfiguracao_inserir'])->forceDelete();
        Permission::where(['name' => 'unidadeconfiguracao_editar'])->forceDelete();
        Permission::where(['name' => 'unidadeconfiguracao_deletar'])->forceDelete();
    }
}
