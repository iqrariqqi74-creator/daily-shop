<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\RolePermissionInterface;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permission_repository;

    public function __construct(RolePermissionInterface $permission_interface)
    {
        $this->permission_repository = $permission_interface;
    }

    public function index(Request $request)
    {
        try {
            return $this->permission_repository->getPermissions($request);
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
            return $this->permission_repository->create($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}
