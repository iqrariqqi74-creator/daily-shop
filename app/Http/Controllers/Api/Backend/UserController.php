<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\UserInterface;

use App\Http\Requests\Backend\Users\CreateUserRequest;
use App\Http\Requests\Backend\Users\DeleteUserRequest;
use App\Http\Requests\Backend\Users\ShowUserRequest;
use App\Http\Requests\Backend\Users\UpdateUserRequest;
use App\Http\Requests\Backend\Users\EditUserRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $user_repository;

    public function __construct(UserInterface $user_interface)
    {
        $this->user_repository = $user_interface;
    }

    public function index(ShowUserRequest $request)
    {
        try {
            return $this->user_repository->getUsers($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $this->user_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $user,
                'message' => 'User created successfully.',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $user = $this->user_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $user,
                'message' => 'User updated successfully.',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function destroy(DeleteUserRequest $request, $id)
    {
        try {
            return $this->user_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditUserRequest $request, $id)
    {
        try {
            return $this->user_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}
