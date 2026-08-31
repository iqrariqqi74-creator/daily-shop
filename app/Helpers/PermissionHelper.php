<?php

namespace App\Helpers;

use App\Models\Permission;
use App\Models\RolePermission;

class PermissionHelper
{
    public static function checkPermission($auth_user, $permission)
    {
        if ($auth_user->is_supper_admin) {
            return true;
        }

        $roles = $auth_user->roles->toArray();

        $role_ids = array_column($roles, 'id');

        $permission = Permission::where('permission', $permission)->first();
        if (!empty($permission)) {
            $role_permission = RolePermission::whereIn('role_id', $role_ids)
                ->where('permission_id', $permission->id)
                ->first();
            if (!empty($role_permission)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
