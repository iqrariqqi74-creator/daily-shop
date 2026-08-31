<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\ProductInterface;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Backend\products\ShowProductRequest;
use App\Http\Requests\Backend\products\CreateProductRequest;
use App\Http\Requests\Backend\products\DeleteProductRequest;
use App\Http\Requests\Backend\products\UpdateProductRequest;
use App\Http\Requests\Backend\products\EditProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $product_repository;

    public function __construct(ProductInterface $product_interface)
    {
        $this->product_repository = $product_interface;
    }

    public function index( ShowProductRequest $request)
    {
        try {
            return $this->product_repository->getProducts($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = $this->product_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $product,
                'message' => 'Product created successfully.',
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

    public function update(UpdateProductRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $product = $this->product_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $product,
                'message' => 'Product updated successfully.',
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

    public function destroy(DeleteProductRequest $request, $id)
    {
        try {
            return $this->product_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditProductRequest $request, $id)
    {
        try {
            return $this->product_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $product = Product::findOrFail($id);

           $product->status = $request->status;
           $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
