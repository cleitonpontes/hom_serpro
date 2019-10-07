<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateMigracaoAtualizacaoEmpenhosPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'migracao_empenhos']);
        Permission::create(['name' => 'atualizacao_saldos_empenhos']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('migracao_empenhos');
        $role->givePermissionTo('atualizacao_saldos_empenhos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('migracao_empenhos');
        $role->revokePermissionTo('atualizacao_saldos_empenhos');

        Permission::where(['name' => 'migracao_empenhos'])->forceDelete();
        Permission::where(['name' => 'atualizacao_saldos_empenhos'])->forceDelete();
    }
}
