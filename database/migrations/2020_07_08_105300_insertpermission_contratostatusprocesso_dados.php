<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertpermissionContratostatusprocessoDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'statusprocesso_inserir']);
        Permission::create(['name' => 'statusprocesso_editar']);
        Permission::create(['name' => 'statusprocesso_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('statusprocesso_inserir');
        $role->givePermissionTo('statusprocesso_editar');
        $role->givePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('statusprocesso_inserir');
        $role->givePermissionTo('statusprocesso_editar');
        $role->givePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('statusprocesso_inserir');
        $role->givePermissionTo('statusprocesso_editar');
        $role->givePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        if(!$role){
            $role = Role::create(['name' => 'Setor Contratos']);
        }
        $role->givePermissionTo('statusprocesso_inserir');
        $role->givePermissionTo('statusprocesso_editar');
        $role->givePermissionTo('statusprocesso_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('statusprocesso_inserir');
        $role->revokePermissionTo('statusprocesso_editar');
        $role->revokePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('statusprocesso_inserir');
        $role->revokePermissionTo('statusprocesso_editar');
        $role->revokePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('statusprocesso_inserir');
        $role->revokePermissionTo('statusprocesso_editar');
        $role->revokePermissionTo('statusprocesso_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('statusprocesso_inserir');
        $role->revokePermissionTo('statusprocesso_editar');
        $role->revokePermissionTo('statusprocesso_deletar');

        Permission::where(['name' => 'statusprocesso_inserir'])->forceDelete();
        Permission::where(['name' => 'statusprocesso_editar'])->forceDelete();
        Permission::where(['name' => 'statusprocesso_deletar'])->forceDelete();
    }
}
