<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertpermissionContratoprepostoDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'preposto_inserir']);
        Permission::create(['name' => 'preposto_editar']);
        Permission::create(['name' => 'preposto_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('preposto_inserir');
        $role->givePermissionTo('preposto_editar');
        $role->givePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('preposto_inserir');
        $role->givePermissionTo('preposto_editar');
        $role->givePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('preposto_inserir');
        $role->givePermissionTo('preposto_editar');
        $role->givePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        if(!$role){
            $role = Role::create(['name' => 'Setor Contratos']);
        }
        $role->givePermissionTo('preposto_inserir');
        $role->givePermissionTo('preposto_editar');
        $role->givePermissionTo('preposto_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('preposto_inserir');
        $role->revokePermissionTo('preposto_editar');
        $role->revokePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('preposto_inserir');
        $role->revokePermissionTo('preposto_editar');
        $role->revokePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('preposto_inserir');
        $role->revokePermissionTo('preposto_editar');
        $role->revokePermissionTo('preposto_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('preposto_inserir');
        $role->revokePermissionTo('preposto_editar');
        $role->revokePermissionTo('preposto_deletar');

        Permission::where(['name' => 'preposto_inserir'])->forceDelete();
        Permission::where(['name' => 'preposto_editar'])->forceDelete();
        Permission::where(['name' => 'preposto_deletar'])->forceDelete();
    }
}
