<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InsertPermissionImr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'contrato_servico_inserir']);
        Permission::create(['name' => 'contrato_servico_editar']);
        Permission::create(['name' => 'contrato_servico_deletar']);

        Permission::create(['name' => 'contrato_servico_indicador_inserir']);
        Permission::create(['name' => 'contrato_servico_indicador_editar']);
        Permission::create(['name' => 'contrato_servico_indicador_deletar']);

        Permission::create(['name' => 'glosa_inserir']);
        Permission::create(['name' => 'glosa_editar']);
        Permission::create(['name' => 'glosa_deletar']);

        $role = Role::where(['name' => 'Administrador'])->first();
        $role->givePermissionTo('contrato_servico_inserir');
        $role->givePermissionTo('contrato_servico_editar');
        $role->givePermissionTo('contrato_servico_deletar');

        $role->givePermissionTo('contrato_servico_indicador_inserir');
        $role->givePermissionTo('contrato_servico_indicador_editar');
        $role->givePermissionTo('contrato_servico_indicador_deletar');

        $role->givePermissionTo('glosa_inserir');
        $role->givePermissionTo('glosa_editar');
        $role->givePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->givePermissionTo('contrato_servico_inserir');
        $role->givePermissionTo('contrato_servico_editar');
        $role->givePermissionTo('contrato_servico_deletar');

        $role->givePermissionTo('contrato_servico_indicador_inserir');
        $role->givePermissionTo('contrato_servico_indicador_editar');
        $role->givePermissionTo('contrato_servico_indicador_deletar');

        $role->givePermissionTo('glosa_inserir');
        $role->givePermissionTo('glosa_editar');
        $role->givePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->givePermissionTo('contrato_servico_inserir');
        $role->givePermissionTo('contrato_servico_editar');
        $role->givePermissionTo('contrato_servico_deletar');

        $role->givePermissionTo('contrato_servico_indicador_inserir');
        $role->givePermissionTo('contrato_servico_indicador_editar');
        $role->givePermissionTo('contrato_servico_indicador_deletar');

        $role->givePermissionTo('glosa_inserir');
        $role->givePermissionTo('glosa_editar');
        $role->givePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        if(!$role){
            $role = Role::create(['name' => 'Setor Contratos']);
        }
        $role->givePermissionTo('contrato_servico_inserir');
        $role->givePermissionTo('contrato_servico_editar');
        $role->givePermissionTo('contrato_servico_deletar');

        $role->givePermissionTo('contrato_servico_indicador_inserir');
        $role->givePermissionTo('contrato_servico_indicador_editar');
        $role->givePermissionTo('contrato_servico_indicador_deletar');

        $role->givePermissionTo('glosa_inserir');
        $role->givePermissionTo('glosa_editar');
        $role->givePermissionTo('glosa_deletar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where(['name' => 'Administrador'])->first();
        $role->revokePermissionTo('contrato_servico_inserir');
        $role->revokePermissionTo('contrato_servico_editar');
        $role->revokePermissionTo('contrato_servico_deletar');

        $role->revokePermissionTo('contrato_servico_indicador_inserir');
        $role->revokePermissionTo('contrato_servico_indicador_editar');
        $role->revokePermissionTo('contrato_servico_indicador_deletar');

        $role->revokePermissionTo('glosa_inserir');
        $role->revokePermissionTo('glosa_editar');
        $role->revokePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Administrador Órgão'])->first();
        $role->revokePermissionTo('contrato_servico_inserir');
        $role->revokePermissionTo('contrato_servico_editar');
        $role->revokePermissionTo('contrato_servico_deletar');

        $role->revokePermissionTo('contrato_servico_indicador_inserir');
        $role->revokePermissionTo('contrato_servico_indicador_editar');
        $role->revokePermissionTo('contrato_servico_indicador_deletar');

        $role->revokePermissionTo('glosa_inserir');
        $role->revokePermissionTo('glosa_editar');
        $role->revokePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Administrador Unidade'])->first();
        $role->revokePermissionTo('contrato_servico_inserir');
        $role->revokePermissionTo('contrato_servico_editar');
        $role->revokePermissionTo('contrato_servico_deletar');

        $role->revokePermissionTo('contrato_servico_indicador_inserir');
        $role->revokePermissionTo('contrato_servico_indicador_editar');
        $role->revokePermissionTo('contrato_servico_indicador_deletar');

        $role->revokePermissionTo('glosa_inserir');
        $role->revokePermissionTo('glosa_editar');
        $role->revokePermissionTo('glosa_deletar');

        $role = Role::where(['name' => 'Setor Contratos'])->first();
        $role->revokePermissionTo('contrato_servico_inserir');
        $role->revokePermissionTo('contrato_servico_editar');
        $role->revokePermissionTo('contrato_servico_deletar');

        $role->revokePermissionTo('contrato_servico_indicador_inserir');
        $role->revokePermissionTo('contrato_servico_indicador_editar');
        $role->revokePermissionTo('contrato_servico_indicador_deletar');

        $role->revokePermissionTo('glosa_inserir');
        $role->revokePermissionTo('glosa_editar');
        $role->revokePermissionTo('glosa_deletar');

        Permission::where(['name' => 'contrato_servico_inserir'])->forceDelete();
        Permission::where(['name' => 'contrato_servico_editar'])->forceDelete();
        Permission::where(['name' => 'contrato_servico_deletar'])->forceDelete();

        Permission::where(['name' => 'contrato_servico_indicador_inserir'])->forceDelete();
        Permission::where(['name' => 'contrato_servico_indicador_editar'])->forceDelete();
        Permission::where(['name' => 'contrato_servico_indicador_deletar'])->forceDelete();

        Permission::where(['name' => 'glosa_inserir'])->forceDelete();
        Permission::where(['name' => 'glosa_editar'])->forceDelete();
        Permission::where(['name' => 'glosa_deletar'])->forceDelete();
    }
}
