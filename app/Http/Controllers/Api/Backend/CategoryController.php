<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\CategoryInterface;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Backend\categories\CreateCategoryRequest;
use App\Http\Requests\Backend\categories\DeleteCategoryRequest;
use App\Http\Requests\Backend\categories\ShowCategoryRequest;
use App\Http\Requests\Backend\categories\UpdateCategoryRequest;
use App\Http\Requests\Backend\categories\EditCategoryRequest;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $category_repository;

    public function __construct(CategoryInterface $category_interface)
    {
        $this->category_repository = $category_interface;
    }

    public function index(ShowCategoryRequest $request)
    {
        try {
            return $this->category_repository->getCategories($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateCategoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $category = $this->category_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $category,
                'message' => 'Category created successfully.',
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

    public function update(UpdateCategoryRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $category = $this->category_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $category,
                'message' => 'Category updated successfully.',
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

    public function destroy(DeleteCategoryRequest $request, $id)
    {
        try {
            return $this->category_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditCategoryRequest $request, $id)
    {
        try {
            return $this->category_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $category = Category::findOrFail($id);

           $category->status = $request->status;
           $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
