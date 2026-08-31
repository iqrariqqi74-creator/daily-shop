<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\UserInterface;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{


     public function getOptions($request)
     {
        $query = User::query();;

        $query->orderBy('name', 'asc');
        $query->where('status', '1');

        $users = $query->get();

        $user_options = [];

     }
    public function getUsers($request)
    {
        $user_id = $request->user()->id;
        $query = User::query();

        // Apply where filter on the authenticated user
        $query->where('users.is_supper_admin', '!=',  1);

        // Join with companies
        // $query->leftJoin('companies', 'users.company_id', '=', 'companies.id');

        // Search logic
        $search = $request->get('search', null);
        $filter = $request->get('filter', null);

        if (!empty($filter)) {
          $query->where('company_id', $filter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('users.phone_number', 'like', '%' . $search . '%')
                    ->orWhere('users.status', 'like', '%' . $search . '%')
                    ->orWhere('companies.name', 'like', '%' . $search . '%');
            });
        }

        // Sorting
        $sort_array = [
            'column' => $request->get('sort_by', null),
            'order' => $request->get('order', null),
        ];

        if (!empty($sort_array['column']) && !empty($sort_array['order'])) {
            $sortColumn = $sort_array['column'];

            if (!str_contains($sortColumn, '.')) {
                $sortColumn = 'users.' . $sortColumn;
            }
            $query->orderBy($sortColumn, $sort_array['order']);
        } else {
            $query->orderBy('users.name', 'asc');
        }

        // Pagination
        $records_per_page = $request->get('records_per_page', 20);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_records = $query->count();
        $total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 0;

        // Apply pagination
        $query->offset($offset)->limit($records_per_page);

        // Eager load relationships and select fields
        $query->with(['roles']);
        $query->select('users.*');

        // Get users
        $users = $query->get();

        // Response
        return response()->json([
            'status' => 'success',
            'users' => $users,
            'total_records' => $total_records,
            'records_per_page' => $records_per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'page' => $page,
        ]);
    }


    public function create($request)
    {
       try{
         $user = $request->user();
        $user_id = $user->id;
        $data = $request->all();
        $user_id = $user->id;

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'status' => $request->input('status'),
            'user_id' => $user_id,
            'password' => Hash::make($request->input('password')),
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);

        $role_ids = $data['role_ids'];

        if (count($role_ids) > 0) {
            foreach ($role_ids as  $role_id) {
                $user_role = UserRole::Create([
                    'role_id' => $role_id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return response()->json([
            'user' => $user,
            'user_roles' => $user_role,
            'status' => 'success',
            'message' => 'User created successfully'
        ], 201);
       }catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => $e->getMessage()
           ], 500);
       }

    }

    public function update($request, $id)
    {
        $user = $request->user();
        $data = $request->all();
        $role_ids = $data['role_ids'];

        $user_roles = UserRole::where('user_id', $id)
            ->get();

        if (count($user_roles) > 0) {
            foreach ($user_roles as $user_role) {
                $user_role->delete();
            }
        }

        if (count($role_ids) > 0) {
            foreach ($role_ids as  $role_id) {
                $user_role = UserRole::Create([
                    'role_id' => $role_id,
                    'user_id' => $id,
                ]);
            }
        }

        $user = User::where('user_id', $user->id)->findOrFail($id);

        if (!$user) {
            throw new \Exception('You are trying to update an invalid user.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;

        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return response()->json([
            'user' => $user,
            'status' => 'success',
            'message' => 'User updated successfully'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $user = User::where('user_id', $user->id)->findOrFail($id);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        if ($user->roles()->count() > 0) {
            throw new \Exception('Cannot delete user with associated roles.');
        }

        $user->deleted_by = $user->id;
        $user->save();

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $user_id = $request->user()->id;
        $user = User::where('user_id', $user_id)
            ->with('roles')
            ->findOrFail($id);
        if (!$user) {
            throw new \Exception('User not found.');
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }
}
