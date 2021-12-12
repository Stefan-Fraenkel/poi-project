<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
//use PPEStores\App\Views\Legacy\Controllers\Administration\UserController;
use App\Http\Controllers\Admin\UserController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionController extends BaseController
{
    public function createPermission(Request $request)
    {
        if($request->isMethod('post')) {
            Permission::findOrCreate($request->permission);
            return redirect('/admin/user/');
        }
        else return view('admin.user.createPermission')
            ->with('permissions', Permission::all());
    }

    public function deletePermission(Request $request)
    {
        if($request->isMethod('post')) {
            $permissions = $request->permissions;
            if ($permissions) {
                foreach ($permissions as $permission) {
                    $delete = Permission::findByName($permission);
                    $delete->delete();
                }
            }
            return redirect('/admin/user/');
        }
        else return view('admin.user.deletePermission')
            ->with('permissions', Permission::all());
    }

    public function createRole(Request $request)
    {
        if($request->isMethod('post')) {
            $role = Role::findOrCreate($request->role);
            $permissions = $request->permissions;

            if ($permissions) {
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
            return redirect('/admin/user/');
        }

        else return view('admin.user.createRole')
            ->with('permissions', Permission::all())
            ->with('roles', Role::all());

    }

    public function editRole(Request $request)
    {
        if($request->isMethod('post')) {
            $role = Role::findByName($request->role);
            $permissions = $request->permissions;
            $role->syncPermissions();
            if ($permissions) {
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
            return redirect('/admin/user/');
        }
        else {
            $usercontroller = new UserController();
            $permission_roles_html = $usercontroller->indexPermissions();
            $permission_roles_html = $usercontroller->addPermissionGraphics($permission_roles_html, true, Role::findByName($request->role)->getAllPermissions());

            return view('admin.user.editRole')
                ->with('permissions', Permission::all())
                ->with('role', $request->role)
                ->with('permission_roles_html', $permission_roles_html)
            ->with('permission_roles', Role::findByName($request->role)->getAllPermissions());
            }
    }

    public function deleteRole(Request $request)
    {
        if($request->isMethod('post')) {
            $roles = $request->roles;
            if ($roles) {
                foreach ($roles as $role) {
                    $delete = Role::findByName($role);
                    $delete->delete();
                }
            }
            return redirect('/admin/user/');
        }
        else return view('admin.user.deleteRole')
            ->with('roles', Role::all());
    }

}


