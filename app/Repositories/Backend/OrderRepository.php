<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\OrderInterface;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

class OrderRepository implements OrderInterface
{

    public function getOptions($request)
    {
        $query = Order::query();

        $query->orderBy('id', 'asc');
        $query->where('status', '1');

        $orders = $query->get();

        $order_options = [];
        if (!empty($orders)) {
            foreach ($orders as $order) {
                $order_options[] = [
                    'id' => $order->id,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'orders' => $order_options,
        ]);
    }

    public function getOrders($request)
    {
        $query = Order::query();

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);
        $payment_status = $request->get('payment_status', null);

        $from_date = $request->get('from_date', null);
        $to_date = $request->get('to_date', null);


        if ($from_date && $to_date) {

            try {
                $from = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
                $to = Carbon::createFromFormat('Y-m-d', $to_date)->endOfDay();
                $query->whereBetween('added_on', [$from, $to]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid date format. Please use Y-m-d.',
                ], 400);
            }
        }


        if ($status !== null && $status !== '') {
            $query->where('orders.order_status', $status);
        }

        if ($payment_status !== null && $payment_status !== '') {
            $query->where('orders.payment_status', $payment_status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('orders.payment_type', 'like', '%' . $search . '%')
                    ->orWhere('orders.total_amount', 'like', '%' . $search . '%')
                    ->orWhere('orders.name', 'like', '%' . $search . '%')
                    ->orWhere('orders.email', 'like', '%' . $search . '%');

                // status search
                if (strtolower($search) === 'completed') {
                    $q->orWhere('orders.order_status', 'completed');
                } elseif (strtolower($search) === 'processing') {
                    $q->orWhere('orders.order_status', 'processing');
                } elseif (strtolower($search) === 'cancelled') {
                    $q->orWhere('orders.order_status', 'cancelled');
                } elseif (is_numeric($search)) {
                    $q->orWhere('orders.order_status', $search);
                }

                // payment method search
                if (strtolower($search) === 'COD') {
                    $q->orWhere('orders.payment_type', 'cash');
                } elseif (strtolower($search) === 'credit card') {
                    $q->orWhere('orders.payment_type', 'credit_card');
                } elseif (strtolower($search) === 'debit card') {
                    $q->orWhere('orders.payment_type', 'debit_card');
                }

                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('orders.added_on', $date);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid date format. Please use d-m-Y.',
                    ], 400);
                }
            });
        }

        // Sorting logic
        $sort_column = $request->get('sort_by', null);
        $sort_order = $request->get('order', 'asc');

        if (!empty($sort_column)) {
            // Prefix with table if needed
            if (!str_contains($sort_column, '.')) {
                $sort_column = 'orders.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('orders.id', 'asc');
        }

        // Pagination setup
        $records_per_page = (int) $request->get('records_per_page', 20);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_records = $query->count();
        $total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 0;

        // Apply pagination
        $query->offset($offset)->limit($records_per_page);

        // Select necessary fields
        $query->select([
            'orders.*',

        ]);

        // Load relationships
        $query->with('customer');
        $orders = $query
            ->reorder('orders.id', 'desc')
            ->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'orders' => $orders,
            'total_records' => $total_records,
            'records_per_page' => $records_per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'page' => $page,
        ]);
    }


    public function create($request)
    {
        try {
            $user = $request->user();
            $user_id = $user->id;

            $data = $request->all();
            $data['created_by'] = $user_id;
            $data['updated_by'] = $user_id;

            // make order number unique by adding timestamp like this "c001"
            $data['order_number'] = 'C00' . time();

            $order = Order::create($data);

            return response()->json([
                'order' => $order,
                'status' => 'success',
                'message' => 'Order created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update($request, $id)
    {
        $user = $request->user();
        $data = $request->all();

        $order = Order::findOrFail($id);

        if (!$order) {
            throw new \Exception('You are trying to update an invalid vendor.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;


        $order->update($data);

        return response()->json([
            'vendor' => $order,
            'status' => 'success',
            'message' => 'vendor updated successfully'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $order = Order::findOrFail($id);

        if (!$order) {
            throw new \Exception('vendor not found.');
        }

        $order->deleted_by = $user->id;
        $order->save();

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'vendor deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $order = Order::with(['customer', 'vendor'])->findOrFail($id);
        if (!$order) {
            throw new \Exception('order not found.');
        }

        return response()->json([
            'status' => 'success',
            'order' => $order,
        ]);
    }

    public function getOrderDetails($request, $id)
    {
        try {

            $orderDetails = OrderDetail::with([
                'order',
                'productAttribute.product',
                'productAttribute.product.tax',
                'productAttribute.size',
                'productAttribute.color',
            ])
                ->where('order_id', $id)
                ->get();

            if ($orderDetails->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                ], 404);
            }

            // dd($orderDetails);

            return response()->json([
                'status' => 'success',
                'message' => 'Order details retrieved successfully',
                'orders_details' => $orderDetails,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
