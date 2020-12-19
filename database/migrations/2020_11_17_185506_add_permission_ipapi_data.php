<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionIpapiData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'ipapi_inserir']);
        Permission::create(['name' => 'ipapi_editar']);
        Permission::create(['name' => 'ipapi_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('ipapi_inserir');
        $role->givePermissionTo('ipapi_editar');
        $role->givePermissionTo('ipapi_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('ipapi_inserir');
        $role->revokePermissionTo('ipapi_editar');
        $role->revokePermissionTo('ipapi_deletar');

        Permission::where(['name' => 'ipapi_inserir'])->forceDelete();
        Permission::where(['name' => 'ipapi_editar'])->forceDelete();
        Permission::where(['name' => 'ipapi_deletar'])->forceDelete();
    }
}
