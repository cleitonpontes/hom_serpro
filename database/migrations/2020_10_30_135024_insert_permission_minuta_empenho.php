<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertPermissionMinutaEmpenho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');


        Permission::create(['name' => 'empenho_minuta_acesso']);
        Permission::create(['name' => 'empenho_minuta_inserir']);
        Permission::create(['name' => 'empenho_minuta_editar']);
        Permission::create(['name' => 'empenho_minuta_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();

        $role->givePermissionTo('empenho_minuta_acesso');
        $role->givePermissionTo('empenho_minuta_inserir');
        $role->givePermissionTo('empenho_minuta_editar');
        $role->givePermissionTo('empenho_minuta_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('empenho_minuta_acesso');
        $role->revokePermissionTo('empenho_minuta_inserir');
        $role->revokePermissionTo('empenho_minuta_editar');
        $role->revokePermissionTo('empenho_minuta_deletar');

        Permission::where(['name' => 'empenho_minuta_acesso'])->forceDelete();
        Permission::where(['name' => 'empenho_minuta_inserir'])->forceDelete();
        Permission::where(['name' => 'empenho_minuta_editar'])->forceDelete();
        Permission::where(['name' => 'empenho_minuta_deletar'])->forceDelete();
    }
}
