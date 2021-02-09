<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionEditarMinuta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'minuta_ajuste_editar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('minuta_ajuste_editar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('minuta_ajuste_editar');

        Permission::where(['name' => 'minuta_ajuste_editar'])->forceDelete();
    }
}
