<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InsertSaldohistoricoitensRolesDados extends Migration
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
        Permission::create(['name' => 'saldohistoricoitens_inserir']);
        Permission::create(['name' => 'saldohistoricoitens_editar']);
        Permission::create(['name' => 'saldohistoricoitens_deletar']);
        Permission::create(['name' => 'saldohistoricoitens_carregaritens']);


        $role = Role::where('name','Administrador')->first();
        $role->givePermissionTo('saldohistoricoitens_inserir');
        $role->givePermissionTo('saldohistoricoitens_editar');
        $role->givePermissionTo('saldohistoricoitens_deletar');
        $role->givePermissionTo('saldohistoricoitens_carregaritens');

        $role = Role::where('name','Administrador Órgão')->first();
        $role->givePermissionTo('saldohistoricoitens_inserir');
        $role->givePermissionTo('saldohistoricoitens_editar');
        $role->givePermissionTo('saldohistoricoitens_deletar');
        $role->givePermissionTo('saldohistoricoitens_carregaritens');

        $role = Role::where('name','Administrador Unidade')->first();
        $role->givePermissionTo('saldohistoricoitens_inserir');
        $role->givePermissionTo('saldohistoricoitens_editar');
        $role->givePermissionTo('saldohistoricoitens_deletar');
        $role->givePermissionTo('saldohistoricoitens_carregaritens');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('saldohistoricoitens_inserir');
        $role->revokePermissionTo('saldohistoricoitens_editar');
        $role->revokePermissionTo('saldohistoricoitens_deletar');
        $role->revokePermissionTo('saldohistoricoitens_carregaritens');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('saldohistoricoitens_inserir');
        $role->revokePermissionTo('saldohistoricoitens_editar');
        $role->revokePermissionTo('saldohistoricoitens_deletar');
        $role->revokePermissionTo('saldohistoricoitens_carregaritens');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('saldohistoricoitens_inserir');
        $role->revokePermissionTo('saldohistoricoitens_editar');
        $role->revokePermissionTo('saldohistoricoitens_deletar');
        $role->revokePermissionTo('saldohistoricoitens_carregaritens');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('saldohistoricoitens_inserir');
        $role->revokePermissionTo('saldohistoricoitens_editar');
        $role->revokePermissionTo('saldohistoricoitens_deletar');
        $role->revokePermissionTo('saldohistoricoitens_carregaritens');

        Permission::where(['name' => 'saldohistoricoitens_inserir'])->delete();
        Permission::where(['name' => 'saldohistoricoitens_editar'])->delete();
        Permission::where(['name' => 'saldohistoricoitens_deletar'])->forceDelete();
        Permission::where(['name' => 'saldohistoricoitens_carregaritens'])->delete();


    }
}
