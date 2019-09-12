<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertpermissionOrgaosubcategoriasDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'orgaosubcategorias_inserir']);
        Permission::create(['name' => 'orgaosubcategorias_editar']);
        Permission::create(['name' => 'orgaosubcategorias_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('orgaosubcategorias_inserir');
        $role->givePermissionTo('orgaosubcategorias_editar');
        $role->givePermissionTo('orgaosubcategorias_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('orgaosubcategorias_inserir');
        $role->givePermissionTo('orgaosubcategorias_editar');
        $role->givePermissionTo('orgaosubcategorias_deletar');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('orgaosubcategorias_inserir');
        $role->revokePermissionTo('orgaosubcategorias_editar');
        $role->revokePermissionTo('orgaosubcategorias_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('orgaosubcategorias_inserir');
        $role->revokePermissionTo('orgaosubcategorias_editar');
        $role->revokePermissionTo('orgaosubcategorias_deletar');

        Permission::where(['name' => 'orgaosubcategorias_inserir'])->forceDelete();
        Permission::where(['name' => 'orgaosubcategorias_editar'])->forceDelete();
        Permission::where(['name' => 'orgaosubcategorias_deletar'])->forceDelete();
    }
}
