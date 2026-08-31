<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\TaxInterface;

use Illuminate\Http\Request;
use App\Models\Tax;
use App\Http\Requests\Backend\taxes\CreateTaxRequest;
use App\Http\Requests\Backend\taxes\DeleteTaxRequest;
use App\Http\Requests\Backend\taxes\ShowTaxRequest;
use App\Http\Requests\Backend\taxes\UpdateTaxRequest;
use App\Http\Requests\Backend\taxes\EditTaxRequest;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    private $tax_repository;

    public function __construct(TaxInterface $tax_interface)
    {
        $this->tax_repository = $tax_interface;
    }

    public function index(ShowTaxRequest $request)
    {
        try {
            return $this->tax_repository->getTaxes($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateTaxRequest $request)
    {
        DB::beginTransaction();

        try {
            $tax = $this->tax_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $tax,
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

    public function update(UpdateTaxRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $tax = $this->tax_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $tax,
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

    public function destroy(DeleteTaxRequest $request, $id)
    {
        try {
            return $this->tax_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditTaxRequest $request, $id)
    {
        try {
            return $this->tax_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $tax = Tax::findOrFail($id);

           $tax->status = $request->status;
           $tax->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
