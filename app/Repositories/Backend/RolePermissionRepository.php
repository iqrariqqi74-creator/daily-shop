<?php

namespace App\Repositories\Backend;

use App\Interfaces\Backend\RoleAsPermissionInterface;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionRepository implements RoleAsPermissionInterface
{
    public function getRolePermission($request)
    {
        $account_id = $request->user()->account_id;
        $role_id = $request->role_id;
        try {
            if (!empty($role_id)) {
                $permission_ids = RolePermission::where('account_id', $account_id)
                    ->where('role_id', $role_id)
                    ->pluck('permission_id')->toArray();

                if (!$permission_ids) {
                    throw new \Exception('role not found.');
                }

                return response()->json([
                    'status' => 'success',
                    'permission_ids' => $permission_ids,
                    'role_id' => $role_id,
                ]);
            } else {
                $query = RolePermission::where('account_id', $account_id)
                    ->with('permission', 'role');
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function create($request)
    {
        $user = $request->user();
        $data = $request->all();
        $permission_ids = $data['permission_ids'];
        $role_id = $data['role_id'];
        $account_id = $user->account_id;

        $role_permissions = RolePermission::where('account_id', $account_id)
            ->where('role_id', $role_id)
            ->get();

        if (count($role_permissions) > 0) {
            foreach ($role_permissions as $role_permission) {
                $role_permission->delete();
            }
        }

        foreach ($permission_ids as  $permission_id) {
            $role_permission = RolePermission::Create([
                'permission_id' => $permission_id,
                'role_id' => $role_id,
                'account_id' => $account_id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            if (!$role_permission) {
                throw new \Exception('role permission not created.');
            }
        }

        return response()->json([
            'status' => 'success'
        ], 201);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $role_permission = RolePermission::where('account_id', $user->account_id)
            ->where('permission_id', $id)
            ->first();

        if (!$role_permission) {
            throw new \Exception('Permission not found.');
        }
        $role_permission->delete();

        return response()->json([
            'status' => 'success',
            'message' =>'Role deleted successfully'
        ], 200);
    }
}