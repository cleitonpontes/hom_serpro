<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InsertSubrogacaoPermissionsDados extends Migration
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
        Permission::create(['name' => 'subrogacao_inserir']);
        Permission::create(['name' => 'subrogacao_deletar']);

        $role = Role::where('name','Administrador')->first();
        $role->givePermissionTo('subrogacao_inserir');
        $role->givePermissionTo('subrogacao_deletar');

        $role = Role::where('name','Administrador Órgão')->first();
        $role->givePermissionTo('subrogacao_inserir');
        $role->givePermissionTo('subrogacao_deletar');

        $role = Role::where('name','Administrador Unidade')->first();
        $role->givePermissionTo('subrogacao_inserir');
        $role->givePermissionTo('subrogacao_deletar');

        $role = Role::where('name','Setor Contratos')->first();
        $role->givePermissionTo('subrogacao_inserir');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('subrogacao_inserir');
        $role->revokePermissionTo('subrogacao_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('subrogacao_inserir');
        $role->revokePermissionTo('subrogacao_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('subrogacao_inserir');
        $role->revokePermissionTo('subrogacao_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('subrogacao_inserir');

        Permission::where(['name' => 'subrogacao_inserir'])->delete();
        Permission::where(['name' => 'subrogacao_deletar'])->delete();
    }
}
