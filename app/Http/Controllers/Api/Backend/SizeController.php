<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\SizeInterface;

use Illuminate\Http\Request;
use App\Models\Size;
use App\Http\Requests\Backend\sizes\CreateSizeRequest;
use App\Http\Requests\Backend\sizes\DeleteSizeRequest;
use App\Http\Requests\Backend\sizes\ShowSizeRequest;
use App\Http\Requests\Backend\sizes\UpdateSizeRequest;
use App\Http\Requests\Backend\sizes\EditSizeRequest;
use Illuminate\Support\Facades\DB;

class SizeController extends Controller
{
    private $size_repository;

    public function __construct(SizeInterface $size_interface)
    {
        $this->size_repository = $size_interface;
    }

    public function index(ShowSizeRequest $request)
    {
        try {
            return $this->size_repository->getSizes($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateSizeRequest $request)
    {
        DB::beginTransaction();

        try {
            $size = $this->size_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $size,
                'message' => 'Size created successfully.',
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

    public function update(UpdateSizeRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $size = $this->size_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $size,
                'message' => 'Size updated successfully.',
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

    public function destroy(DeleteSizeRequest $request, $id)
    {
        try {
            return $this->size_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditSizeRequest $request, $id)
    {
        try {
            return $this->size_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $size = Size::findOrFail($id);

           $size->status = $request->status;
           $size->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
