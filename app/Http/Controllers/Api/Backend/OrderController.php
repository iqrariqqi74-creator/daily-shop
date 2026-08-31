<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\OrderInterface;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Requests\Backend\orders\CreateOrderRequest;
use App\Http\Requests\Backend\orders\DeleteOrderRequest;
use App\Http\Requests\Backend\orders\ShowOrderRequest;
use App\Http\Requests\Backend\orders\UpdateOrderRequest;
use App\Http\Requests\Backend\orders\EditOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $order_repository;

    public function __construct(OrderInterface $order_interface)
    {
        $this->order_repository = $order_interface;
    }

    public function index(ShowOrderRequest $request)
    {
        try {
            return $this->order_repository->getOrders($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $customer = $this->order_repository->create($request);

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

    public function update(UpdateOrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $customer = $this->order_repository->update($request, $id);
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

    public function destroy(DeleteOrderRequest $request, $id)
    {
        try {
            return $this->order_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditOrderRequest $request, $id)
    {
        try {
            return $this->order_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function getOrderByCustomer(Request $request, $id)
    {

        // $id = 14;

        $orders = Order::where('customers_id', $id)->get();
        $orders->load('customer');

        return response()->json([
            'status' => 'success',
            'orders' => $orders,
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->order_status = $request->status;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status updated successfully'
        ]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->payment_status = $request->status;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Payment status updated successfully'
        ]);
    }

    public function orderDetails(Request $request, $id)
    {
        try {
            return $this->order_repository->getOrderDetails($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
}
