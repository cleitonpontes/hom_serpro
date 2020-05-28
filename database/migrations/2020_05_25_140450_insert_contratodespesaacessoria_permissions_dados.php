<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertContratodespesaacessoriaPermissionsDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'contratodespesaacessoria_inserir']);
        Permission::create(['name' => 'contratodespesaacessoria_editar']);
        Permission::create(['name' => 'contratodespesaacessoria_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('contratodespesaacessoria_inserir');
        $role->givePermissionTo('contratodespesaacessoria_editar');
        $role->givePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('contratodespesaacessoria_inserir');
        $role->givePermissionTo('contratodespesaacessoria_editar');
        $role->givePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('contratodespesaacessoria_inserir');
        $role->givePermissionTo('contratodespesaacessoria_editar');
        $role->givePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        if(!$role){
            $role = Role::create(['name' => 'Setor Contratos']);
        }
        $role->givePermissionTo('contratodespesaacessoria_inserir');
        $role->givePermissionTo('contratodespesaacessoria_editar');
        $role->givePermissionTo('contratodespesaacessoria_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('contratodespesaacessoria_inserir');
        $role->revokePermissionTo('contratodespesaacessoria_editar');
        $role->revokePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('contratodespesaacessoria_inserir');
        $role->revokePermissionTo('contratodespesaacessoria_editar');
        $role->revokePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('contratodespesaacessoria_inserir');
        $role->revokePermissionTo('contratodespesaacessoria_editar');
        $role->revokePermissionTo('contratodespesaacessoria_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('contratodespesaacessoria_inserir');
        $role->revokePermissionTo('contratodespesaacessoria_editar');
        $role->revokePermissionTo('contratodespesaacessoria_deletar');

        Permission::where(['name' => 'contratodespesaacessoria_inserir'])->forceDelete();
        Permission::where(['name' => 'contratodespesaacessoria_editar'])->forceDelete();
        Permission::where(['name' => 'contratodespesaacessoria_deletar'])->forceDelete();
    }
}
