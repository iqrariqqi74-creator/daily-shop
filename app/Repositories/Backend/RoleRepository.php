<?php

namespace App\Repositories\backend;

use App\Interfaces\backend\RoleInterface;

use App\Models\Role;

class RoleRepository implements RoleInterface
{
    public function getOptions($request)
    {

        $query = Role::query();;

        $query->orderBy('name', 'asc');
        $query->where('status', '1');

        $roles = $query->get();

        $role_options = [];
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $role_options[] = [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'roles' => $role_options,
        ]);
    }

    public function getRoles($request)
    {

        $query = Role::query();

         $sort_array = [
            'column' => $request->get('sort_by', null),
            'order' => $request->get('order', null),
        ];

        $sort_array = [
            'column' => $request->get('sort_by', null),
            'order' => $request->get('order', null),
        ];

        if (!empty($sort_array['column']) && !empty($sort_array['order'])) {
            $query->orderBy($sort_array['column'], $sort_array['order']);
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

        $query->orderBy('name', 'asc');

        $roles = $query->get();

        if (!empty($roles) && count($roles) > 0) {
            return response()->json([
                'status' => 'success',
                'roles' => $roles,
                'total_records' => $total_records,
                'records_per_page' => $records_per_page,
                'offset' => $offset,
                'total_pages' => $total_pages,
                'page' => $page,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'roles' => $roles,
            'page' => $page,
        ]);
    }

    public function create($request)
    {
        $user = $request->user();

        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['created_by'] = $user->id;

        $role = Role::create($data);
        return response()->json([
            'role' => $role,
            'status' => 'success',
            'message' => 'Role created successfully.'

        ], 201);
    }

    public function update($request, $id)
    {
        $user = $request->user();

        $role = Role::query()->findOrFail($id);

        if (!$role) {
            throw new \Exception('You are trying to update an invalid role.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;
        $role->update($data);

        return response()->json([
            'role' => $role,
            'status' => 'success',
            'message' => 'Role updated successfully.'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $role = Role::where('user_id', $user->id)->findOrFail($id);

        if (!$role) {
            throw new \Exception('Role not found.');
        }
        if ($role->name == 'Super Admin') {
            throw new \Exception('You cannot delete the Super Admin role.');
        }
        $role->deleted_by = $user->id;
        $role->save();

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'role deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $role = Role::query()->with('permissions')->findOrFail($id);
        if (!$role) {
            throw new \Exception('role not found.');
        }

        return response()->json([
            'status' => 'success',
            'role' => $role,
        ]);
    }
}