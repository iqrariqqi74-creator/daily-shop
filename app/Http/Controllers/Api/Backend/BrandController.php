<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\BrandInterface;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Http\Requests\Backend\brands\CreateBrandRequest;
use App\Http\Requests\Backend\brands\DeleteBrandRequest;
use App\Http\Requests\Backend\brands\ShowBrandRequest;
use App\Http\Requests\Backend\brands\UpdateBrandRequest;
use App\Http\Requests\Backend\brands\EditBrandRequest;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    private $brand_repository;

    public function __construct(BrandInterface $brand_interface)
    {
        $this->brand_repository = $brand_interface;
    }

    public function index(ShowBrandRequest $request)
    {
        try {
            return $this->brand_repository->getBrands($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateBrandRequest $request)
    {
        DB::beginTransaction();

        try {
            $brand = $this->brand_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $brand,
                'message' => 'Brand created successfully.',
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

    public function update(UpdateBrandRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $brand = $this->brand_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $brand,
                'message' => 'Brand updated successfully.',
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

    public function destroy(DeleteBrandRequest $request, $id)
    {
        try {
            return $this->brand_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditBrandRequest $request, $id)
    {
        try {
            return $this->brand_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $brand = Brand::findOrFail($id);

           $brand->status = $request->status;
           $brand->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
