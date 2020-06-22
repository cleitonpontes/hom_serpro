<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertPadraosiafiPermissionsDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'contratosfpadrao_inserir']);
        Permission::create(['name' => 'contratosfpadrao_editar']);
        Permission::create(['name' => 'contratosfpadrao_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('contratosfpadrao_inserir');
        $role->givePermissionTo('contratosfpadrao_editar');
        $role->givePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->givePermissionTo('contratosfpadrao_inserir');
        $role->givePermissionTo('contratosfpadrao_editar');
        $role->givePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('contratosfpadrao_inserir');
        $role->givePermissionTo('contratosfpadrao_editar');
        $role->givePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('contratosfpadrao_inserir');
        $role->givePermissionTo('contratosfpadrao_editar');
        $role->givePermissionTo('contratosfpadrao_deletar');

//        $role = Role::where(['name' => 'Execução Financeira'])->first();
//        $role->givePermissionTo('contratosfpadrao_inserir');
//        $role->givePermissionTo('contratosfpadrao_editar');
//        $role->givePermissionTo('contratosfpadrao_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('contratosfpadrao_inserir');
        $role->revokePermissionTo('contratosfpadrao_editar');
        $role->revokePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('contratosfpadrao_inserir');
        $role->revokePermissionTo('contratosfpadrao_editar');
        $role->revokePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('contratosfpadrao_inserir');
        $role->revokePermissionTo('contratosfpadrao_editar');
        $role->revokePermissionTo('contratosfpadrao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('contratosfpadrao_inserir');
        $role->revokePermissionTo('contratosfpadrao_editar');
        $role->revokePermissionTo('contratosfpadrao_deletar');

//        $role = Role::where(['name' => 'Execução Financeira'])->first();
//        $role->revokePermissionTo('contratosfpadrao_inserir');
//        $role->revokePermissionTo('contratosfpadrao_editar');
//        $role->revokePermissionTo('contratosfpadrao_deletar');


        Permission::where(['name' => 'contratosfpadrao_inserir'])->forceDelete();
        Permission::where(['name' => 'contratosfpadrao_editar'])->forceDelete();
        Permission::where(['name' => 'contratosfpadrao_deletar'])->forceDelete();

    }
}
