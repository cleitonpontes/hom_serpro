<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertPermissionFeriados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'feriados_inserir']);
        Permission::create(['name' => 'feriados_editar']);
        Permission::create(['name' => 'feriados_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('feriados_inserir');
        $role->givePermissionTo('feriados_editar');
        $role->givePermissionTo('feriados_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('feriados_inserir');
        $role->revokePermissionTo('feriados_editar');
        $role->revokePermissionTo('feriados_deletar');

        Permission::where(['name' => 'feriados_inserir'])->forceDelete();
        Permission::where(['name' => 'feriados_editar'])->forceDelete();
        Permission::where(['name' => 'feriados_deletar'])->forceDelete();
    }
}
