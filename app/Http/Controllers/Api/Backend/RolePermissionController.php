<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\RoleAsPermissionInterface;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    private $role_permission_repository;

    public function __construct(RoleAsPermissionInterface $role_permission_interface)
    {
        $this->role_permission_repository = $role_permission_interface;
    }

    public function index(Request $request)
    {
        try {
            return $this->role_permission_repository->getRolePermission($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            return $this->role_permission_repository->create($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            return $this->role_permission_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}