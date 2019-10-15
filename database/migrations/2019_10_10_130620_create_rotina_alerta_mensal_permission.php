<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRotinaAlertaMensalPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'executa_rotina_alerta_mensal']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('executa_rotina_alerta_mensal');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('executa_rotina_alerta_mensal');

        Permission::where(['name' => 'executa_rotina_alerta_mensal'])->forceDelete();
    }
}
