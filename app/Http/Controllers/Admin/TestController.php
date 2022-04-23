<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\UserChanged;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class TestController extends BaseController
{
    public function testFunction(Request $request)
    {



        // create basic roles & permissions + give current user all rights (for testing)
        $user = Auth::user();

        $role = Role::create(['name' => 'admin']);

        $permission = Permission::create(['name' => 'admin']);
        $role->givePermissionTo($permission);

        Permission::create(['name' => 'admin.users.read']);
        Permission::create(['name' => 'admin.users.create']);
        Permission::create(['name' => 'admin.users.update']);
        Permission::create(['name' => 'admin.users.delete']);

        Permission::create(['name' => 'admin.users.permissions']);
        Permission::create(['name' => 'admin.users.permissions.read']);
        Permission::create(['name' => 'admin.users.permissions.create']);
        Permission::create(['name' => 'admin.users.permissions.update']);
        Permission::create(['name' => 'admin.users.permissions.delete']);

        Permission::create(['name' => 'admin.users.roles']);
        Permission::create(['name' => 'admin.users.roles.read']);
        Permission::create(['name' => 'admin.users.roles.create']);
        Permission::create(['name' => 'admin.users.roles.update']);
        Permission::create(['name' => 'admin.users.roles.delete']);

        $user->assignRole($role);


        /*
                //legacy permissions setup

                Permission::create(['name' => 'legacy.central']);
                Permission::create(['name' => 'legacy.central_config']);
                Permission::create(['name' => 'legacy.central_api_users']);
                Permission::create(['name' => 'legacy.central_users']);
                Permission::create(['name' => 'legacy.central_users_administration']);
                Permission::create(['name' => 'legacy.central_actions']);
                Permission::create(['name' => 'legacy.central_integrations']);
                Permission::create(['name' => 'legacy.central_logs']);

                Permission::create(['name' => 'legacy.bookkeeping']);
                Permission::create(['name' => 'legacy.bookkeeping_datev_reports']);

                Permission::create(['name' => 'legacy.warehouse']);
                Permission::create(['name' => 'legacy.warehouse_supplierstockimporter']);
                Permission::create(['name' => 'legacy.warehouse_supplierstockimporter_upload']);
                Permission::create(['name' => 'legacy.warehouse_supplierstockimporter_archives']);
                Permission::create(['name' => 'legacy.warehouse_rtv']);

                Permission::create(['name' => 'legacy.tools']);
                Permission::create(['name' => 'legacy.tools_logicsale_optimizer']);

                Permission::create(['name' => 'legacy.reportings']);
                Permission::create(['name' => 'legacy.reportings_blisstribute']);

                Permission::create(['name' => 'legacy.remnantsshop']);

                Permission::create(['name' => 'legacy.shopware']);
                Permission::create(['name' => 'legacy.shopware_remnantsshop_item_upload']);

                Permission::create(['name' => 'legacy.api']);
                */

      //  $permissions = Permission::all();
      //  $roles = Role::all();

     //  $role = Role::findById(2);

      //  $permission = Permission::findById(2);


      //  $role->givePermissionTo($permission);
        // Alternative: $permission->assignRole($role);
       // Notification::send(Auth::user(), new UserChanged());
   // $name = Auth::user()->name;

      //  echo __FUNCTION__;
//var_dump($request);
        //$value = $request->session()->all();
     //   $value = $request->session()-> get('password_hash_web');
      //  dd($value);

   // return view('legacy.index');

      //  $session = session('key');
       // $value = $session()->get('key');
      //  echo $session;
       // echo $value;

    }

}
