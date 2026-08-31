<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\RoleInterface;

use App\Http\Requests\Backend\Roles\CreateRoleRequest;
use App\Http\Requests\Backend\Roles\DeleteRoleRequest;
use App\Http\Requests\Backend\Roles\ShowRoleRequest;
use App\Http\Requests\Backend\Roles\UpdateRoleRequest;
use App\Http\Requests\Backend\Roles\EditRoleRequest;


class RoleController extends Controller
{
    private $role_repository;

    public function __construct(RoleInterface $role_interface)
    {
        $this->role_repository = $role_interface;
    }

    public function index(ShowRoleRequest $request)
    {
        try {
            return $this->role_repository->getRoles($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateRoleRequest $request)
    {
        try {
            return $this->role_repository->create($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            return $this->role_repository->update($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function destroy(DeleteRoleRequest $request, $id)
    {
        try {
            return $this->role_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditRoleRequest $request, $id)
    {
        try {
            return $this->role_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}
