<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\VendorInterface;

use Illuminate\Http\Request;
use App\Http\Requests\Backend\vendors\CreateVendorRequest;
use App\Http\Requests\Backend\vendors\DeleteVendorRequest;
use App\Http\Requests\Backend\vendors\ShowVendorRequest;
use App\Http\Requests\Backend\vendors\UpdateVendorRequest;
use App\Http\Requests\Backend\vendors\EditVendorRequest;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    private $vendor_repository;

    public function __construct(VendorInterface $vendor_interface)
    {
        $this->vendor_repository = $vendor_interface;
    }

    public function index(ShowVendorRequest $request)
    {
        try {
            return $this->vendor_repository->getVendors($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateVendorRequest $request)
    {
        DB::beginTransaction();

        try {
           $vendor = $this->vendor_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $vendor,
                'message' => 'Vendor created successfully.',
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

    public function update(UpdateVendorRequest $request, $id)
    {
         DB::beginTransaction();
        try {
          $vendor = $this->vendor_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' =>$vendor,
                'message' => 'Vendor updated successfully.',
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

    public function destroy(DeleteVendorRequest $request, $id)
    {
        try {
            return $this->vendor_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditVendorRequest $request, $id)
    {
        try {
            return $this->vendor_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $vendor = Vendor::findOrFail($id);

            $vendor->status = $request->status;
            $vendor->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
