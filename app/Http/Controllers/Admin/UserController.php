<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DataTables;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserChanged;
use Illuminate\Support\Facades\Redis;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;


class UserController extends BaseController
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with('roles', 'permissions')->get();

            foreach ($users as $user) { //foreach loop necessary since spatie permissions does only fetch role based permissions by default (we want all permissions)
                $permissions = $user->getAllPermissions();
                $permission_array = array();
                foreach ($permissions as $permission) {
                    $permission_array[] = $permission['name'];
                }
                $user['permissions_name'] = $permission_array;
                $users_perm[] = $user;
            }

            return Datatables::of($users_perm)->make(true);
        }

        $permissions_sorted = $this->indexPermissions();
        $permissions_sorted = $this->addPermissionGraphics($permissions_sorted);
//dd($permissions_sorted);
        return view('admin.user.index')
            ->with('permissions', $permissions_sorted)
            ->with('roles', Role::all());
    }


    public function indexPermissions($specific_permissions = null): array // determine categories and corresponding rights
    {
        if ($specific_permissions == null){
            $specific_permissions = Permission::all();
        }

        $permissions_sorted = array();
        $permissions_sorted_toplevels = array(); // to ensure proper formatting where category does not exist (eg. user.permission.create is displayed properly even if user.permission does not exists))

        foreach ($specific_permissions as $permission) {
            $permission_partials = explode('.', $permission->name);
            $permissions_sorted_toplevels_partials = $permission_partials;
            array_pop($permissions_sorted_toplevels_partials);

            if (isset($permissions_sorted_toplevels_partials[0]) && !$permissions_sorted_toplevels_partials[0] == null) { // to avoid empty entries

                $permission_partials[$permission->name] = $permission->name;
                $permissions_sorted_toplevels_partials[implode(".", $permissions_sorted_toplevels_partials)] = implode(".", $permissions_sorted_toplevels_partials);

                $permissions_sorted = array_merge_recursive($permissions_sorted, $this->addArrayLevels($permission_partials, $permissions_sorted));
                $permissions_sorted_toplevels = array_merge_recursive($permissions_sorted_toplevels, $this->addArrayLevels($permissions_sorted_toplevels_partials, $permissions_sorted_toplevels));

            }

        }

        $permissions_sorted = array_merge_recursive($permissions_sorted, $permissions_sorted_toplevels);

        return $this->addPermissionFullname($permissions_sorted);
    }

    private function addArrayLevels(array $keys, array $target): array
    {
        if ($keys) {
            $key = array_shift($keys);
            $target[$key] = $this->addArrayLevels($keys, []);
        }
        return $target;
    }

    private function addPermissionFullname($array): array
    {
        foreach ($array as $key => $value) {
            if (!$value == []) {
                $array[$key] = array_replace($value, $this->addPermissionFullname($value));
            } else{
                $array['fullname'] = $key;
            }
        }
        return $array;
    }

    public function addPermissionGraphics($array, $checkbox = false, $specific_permissions = null): string
    {
        $output = '<ul>';
        foreach ($array as $key => $value) {
            preg_match('/\./', $key, $matches);
            if ($key == 'fullname' || isset($matches[0])){ //skip array entry "fullname"
                continue;
            }
            $i = 0;
            foreach ($value as $value_count) {
                $i++;
            }
            if ($i > 2) { //determine wether array contains arrays- > if so add expansion button
                $output .= '<li>' . $key . $this->addPermissionGraphics($value, $checkbox, $specific_permissions) . ' <span class="btn-primary btn-xs text-white text-primary text-uppercase text-bold p-0" style="border-radius: 12px; width: 120px">&nbsp&nbsp<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>&nbsp&nbsp</span></li>';
            } elseif ($i > 0 && $checkbox) { //determine wether array is last in tree & checkbox requested -> if so add checkbox with unique value
                if ($specific_permissions){ //determine wether user specific info is requested -> if so add correspondingly ckecked checkboxs
                    $j = 0;
                    foreach ($specific_permissions as $specific_permission) {
                        if (isset($value['fullname']) && $specific_permission->name == $value['fullname']) {
                            $output .= '<li>' . $key . ' <input type="checkbox" name="permissions[]" value="' . $value['fullname'] . '"checked></li>';
                            $j++;
                        }
                    }
                    if (!$j>0 && isset($value['fullname'])){
                        $output .= '<li>' . $key . ' <input type="checkbox" name="permissions[]" value="' . $value['fullname'] . '"></li>';
                    }
                }
                else $output .= '<li>' . $key . ' <input type="checkbox" name="permissions[]" value="' . $value['fullname'] . '"></li>';
            }
            elseif ($i > 0) { //determine wether array is last in tree
                $output .= '<li>' . $key . '</li>';
            }
        }
        $output .= "</ul>";
        return $output;
    }

    private function notify($notify_type, $notify_cause, $notify_subject, $notify_body) {
        request()->user()->notify(new UserChanged($notify_type, $notify_cause, $notify_subject, $notify_body));
    }

    public function showNotify(){
        $notifications = Auth::user()->notifications;
        $dt_first = Carbon::now();
        foreach ($notifications as $notification) {
            if($notification['data']['type'] == "user_created"){
                $notification['time'] = Carbon::parse($notification['updated_at'])->diffForHumans();
                $user = User::find($notification['data']['subject']);
                $creator = User::find($notification['data']['cause']);
                $notification['photo'] = $user->profile_photo_url;
                $notification['cause'] = $creator->name;
                if ($dt_first > $notification['updated_at']){
                    $dt_first = $notification['updated_at'];
                }
            }

        }
        $dt_first = $dt_first->formatLocalized('%d.%m.%Y');
        $dt_now = Carbon::now()->formatLocalized('%d.%m.%Y');
        return view('admin.user.monitorUser')
            ->with('notifications', $notifications)
            ->with('date_now', $dt_now)
            ->with('date_first', $dt_first);
    }

    public function createUser(Request $request) {
        if($request->isMethod('post')) {
            $user = new User();
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
           // $inactive = $request->inactive;
            $roles = $request->roles;
            $permissions = $request->permissions;

            //Standard Password
            if (!$password) {
                $password = 'Blume123#';
            }

            $user->password = Hash::make($password);
            $user->email = $email;
            $user->name = $name;
            $user->inactive = ($inactive) ? 1 : 0;

            $roles_notify = null;
            if ($roles) {
                foreach ($roles as $role) {
                    $user->assignRole($role);
                    if ($roles_notify == null) {
                        $roles_notify = "$role";
                    } else $roles_notify .= ", $role";
                }
            }
            $permissions_notify = null;
            if ($permissions) {
                foreach ($permissions as $permission) {
                    $user->givePermissionTo($permission);
                    if ($permissions_notify == null) {
                        $permissions_notify = "$permission";
                    } else $permissions_notify .= ", $permission";
                }
            }
            $user->save();
            $creator = Auth::user();
            $notify_cause = $creator->id;
            $notify_subject = $user->id;
            $notify_type = "user_created";
            $notify_body = "<b>Nutzername:</b> " . $user->name . "<br>";

            Redis::set('user:' . $user->id . ':permissions', json_encode($user->getAllPermissions()->toArray()));

            if (!$permissions_notify == null || !$roles_notify == null) {
                if (!$permissions_notify == null) {
                    $notify_body .= "<b>Brechtigungen:</b> " . $permissions_notify . "<br>";
                }
                if (!$roles_notify == null) {
                    $notify_body .= "<b>Rollen:</b> " . $roles_notify . "<br>";
                }
                if(!$roles_notify == null || !$permissions_notify == null){
                    $notify_body .= "<br>";
                }
            }
            else $notify_body .= "<br><br>";

            $this->notify($notify_type, $notify_cause, $notify_subject, $notify_body);

            return redirect('/admin/user/');
        }
        else return view('admin.user.createUser')
            ->with('permissions', Permission::all())
            ->with('roles', Role::all());
    }

    public function editUser(Request $request) {

        if($request->isMethod('post')) {

            if (isset($_POST['delete_button'])) {
                $this->deleteUser($request);
            }
            else $this->updateUser($request);

            return redirect('/admin/user/');

        }

        else {
            $permissions_sorted = $this->indexPermissions();
            $permissions_sorted = $this->addPermissionGraphics($permissions_sorted, true, User::find($request->id)->getAllPermissions());
            return view('admin.user.editUser')
                ->with('user', User::find($request->id))
                ->with('permissions', Permission::all())
                ->with('roles', Role::all())
                ->with('permission_users', User::find($request->id)->getAllPermissions())
                ->with('role_users', User::find($request->id)->roles->pluck('name'))
                ->with('permissions_html', $permissions_sorted);
        }

    }

    public function updateUser(Request $request) {

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $inactive = $request->inactive;
        $roles = $request->roles;
        $permissions = $request->permissions;
        $id = $request->id;


        $user = User::find($id);

        if ($name){ //if unnecessary in case form requires name
            $user->name = $name;
        }

        if ($email){ //if unnecessary in case form requires email
            $user->email = $email;
        }

        if ($password){
            $user->password = Hash::make($password);
        }

        $user->inactive = ($inactive) ? 1 : 0;

        $user->syncRoles(); //to remove all roles
        if ($roles){
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }

        $user->syncPermissions(); //to remove all permissions
        if ($permissions){
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
            }
        }

        $user->save();

        Redis::set('user:' . $user->id . ':permissions', json_encode($user->getAllPermissions()->toArray()));
    }

    public function deleteUser(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
    }

}
