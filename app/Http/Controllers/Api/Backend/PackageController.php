<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\PackageInterface;

use App\Http\Requests\Backend\Packages\CreatePackageRequest;
use App\Http\Requests\Backend\Packages\DeletePackageRequest;
use App\Http\Requests\Backend\Packages\ShowPackageRequest;
use App\Http\Requests\Backend\Packages\UpdatePackageRequest;
use App\Http\Requests\Backend\Packages\EditPackageRequest;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    private $package_repository;

    public function __construct(PackageInterface $package_interface)
    {
        $this->package_repository = $package_interface;
    }

    public function index(ShowPackageRequest $request)
    {
        try {
            return $this->package_repository->getPackages($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreatePackageRequest $request)
    {
        DB::beginTransaction();

        try {
            $package = $this->package_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $package,
                'message' => 'Package created successfully.',
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

    public function update(UpdatePackageRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $package = $this->package_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $package,
                'message' => 'Package updated successfully.',
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

    public function destroy(DeletePackageRequest $request, $id)
    {
        try {
            return $this->package_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditPackageRequest $request, $id)
    {
        try {
            return $this->package_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}