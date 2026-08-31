<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\CustomerInterface;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\Backend\customers\CreateCustomerRequest;
use App\Http\Requests\Backend\customers\DeleteCustomerRequest;
use App\Http\Requests\Backend\customers\ShowCustomerRequest;
use App\Http\Requests\Backend\customers\UpdateCustomerRequest;
use App\Http\Requests\Backend\customers\EditCustomerRequest;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customer_repository;

    public function __construct(CustomerInterface $customer_interface)
    {
        $this->customer_repository = $customer_interface;
    }

    public function index(ShowCustomerRequest $request)
    {
        try {
            return $this->customer_repository->getCustomers($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateCustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            $customer = $this->customer_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $customer,
                'message' => 'Customer created successfully.',
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

    public function update(UpdateCustomerRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $customer = $this->customer_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $customer,
                'message' => 'Customer updated successfully.',
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

    public function destroy(DeleteCustomerRequest $request, $id)
    {
        try {
            return $this->customer_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditCustomerRequest $request, $id)
    {
        try {
            return $this->customer_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $customer = Customer::findOrFail($id);

            $customer->status = $request->status;
            $customer->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
