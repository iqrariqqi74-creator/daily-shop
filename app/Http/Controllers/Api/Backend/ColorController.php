<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\ColorInterface;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Http\Requests\Backend\colors\CreateColorRequest;
use App\Http\Requests\Backend\colors\DeleteColorRequest;
use App\Http\Requests\Backend\colors\ShowColorRequest;
use App\Http\Requests\Backend\colors\UpdateColorRequest;
use App\Http\Requests\Backend\colors\EditColorRequest;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    private $color_repository;

    public function __construct(ColorInterface $color_interface)
    {
        $this->color_repository = $color_interface;
    }

    public function index(ShowColorRequest $request)
    {
        try {
            return $this->color_repository->getColors($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateColorRequest $request)
    {
        DB::beginTransaction();

        try {
            $color = $this->color_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $color,
                'message' => 'Color created successfully.',
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

    public function update(UpdateColorRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $color = $this->color_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $color,
                'message' => 'Color updated successfully.',
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

    public function destroy(DeleteColorRequest $request, $id)
    {
        try {
            return $this->color_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditColorRequest $request, $id)
    {
        try {
            return $this->color_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $color = Color::findOrFail($id);

           $color->status = $request->status;
           $color->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
