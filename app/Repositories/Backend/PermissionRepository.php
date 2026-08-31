<?php

namespace App\Repositories\backend;

use App\Interfaces\backend\RolePermissionInterface;
use App\Models\Permission;
use App\Models\RolePermission;

class PermissionRepository implements RolePermissionInterface
{
    public function getPermissions($request)
    {
        $query = Permission::where('module', '!=', null);

        $permissions_array = array();
        $permissions = $query->get()->groupBy('module')->toArray();
        foreach ($permissions as $key => $permission) {
            $permissions_array[] = array(
                'module' => $key,
                'permissions' => $permission,
            );
        }
        $records_per_page = $request->get('records_per_page', 20);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_pages = 0;
        $total_records = $query->count();
        if ($total_records > 0) {
            $total_pages = ceil($total_records / $records_per_page);
        }

        $query->offset($offset)->limit($records_per_page);

        if (!empty($permissions_array) && count($permissions_array) > 0) {
            return response()->json([
                'status' => 'success',
                'modules' => $permissions_array,
                'total_records' => $total_records,
                'records_per_page' => $records_per_page,
                'offset' => $offset,
                'total_pages' => $total_pages,
                'page' => $page,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'modules' => $permissions_array,
            'page' => $page,
        ]);
    }

    public function create($request)
    {
        try {
            $data = $request->validate([
                'role_id' => 'required|integer|exists:roles,id',
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'integer|exists:permissions,id',
            ]);

            $user = $request->user();
            $user_id = $user->id;

            // Remove existing
            RolePermission::where('user_id', $user_id)
                ->where('role_id', $data['role_id'])
                ->delete();

            $new_permissions = [];

            foreach ($data['permission_ids'] as $permission_id) {
                $role_permission = RolePermission::create([
                    'permission_id' => $permission_id,
                    'role_id' => $data['role_id'],
                    'user_id' => $user_id,
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ]);
                $new_permissions[] = $role_permission;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Role permissions created successfully.',
                'role_permissions' => $new_permissions,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $ve->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating role permissions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}