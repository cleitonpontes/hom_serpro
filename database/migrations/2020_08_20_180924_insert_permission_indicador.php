<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertPermissionIndicador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'indicador_inserir']);
        Permission::create(['name' => 'indicador_editar']);
        Permission::create(['name' => 'indicador_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('indicador_inserir');
        $role->givePermissionTo('indicador_editar');
        $role->givePermissionTo('indicador_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('indicador_inserir');
        $role->revokePermissionTo('indicador_editar');
        $role->revokePermissionTo('indicador_deletar');

        Permission::where(['name' => 'indicador_inserir'])->forceDelete();
        Permission::where(['name' => 'indicador_editar'])->forceDelete();
        Permission::where(['name' => 'indicador_deletar'])->forceDelete();
    }
}
