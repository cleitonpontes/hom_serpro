<?php
/**
 * Created by PhpStorm.
 * User: heles.junior
 * Date: 28/11/2018
 * Time: 15:58
 */
namespace App\Auth;

use Illuminate\Support\Facades\Redirect;

trait Authorizes{
    public function authorizePermissions(array $permissions){
        $user = backpack_user();
        $result = false;
        foreach ($permissions as $permission){ //edit cliente, permission
            $hasPermission = $user->hasPermissionTo($permission); //3
            if($hasPermission){
                $result = true;
                break;
            }
        }
        if(!$result){
            \Prologue\Alerts\Facades\Alert::error('Sem permissão!')->flash();
            return Redirect::back();
        }
    }
}