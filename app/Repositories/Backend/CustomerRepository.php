<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\CustomerInterface;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerRepository implements CustomerInterface
{

    public function getOptions($request)
    {
        $query = Customer::query();

        $query->orderBy('name', 'asc');
        $query->where('status', '1');

        $customers = $query->get();

        $customer_options = [];
        if (!empty($customers)) {
            foreach ($customers as $customer) {
                $customer_options[] = [
                    'id' => $customer->id,
                    'name' => $customer->name,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'customers' => $customer_options,
        ]);
    }

    public function getCustomers($request)
    {
        $query = Customer::query();

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('customers.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('customers.name', 'like', '%' . $search . '%')
                    ->orWhere('customers.phone_number', 'like', '%' . $search . '%')
                    ->orWhere('customers.email', 'like', '%' . $search . '%')
                    ->orWhere('customers.address', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('customers.status', 1);
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('customers.status', 0);
                } elseif (is_numeric($search)) {
                    $q->orWhere('customers.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('customers.created_at', $date);
                } catch (\Exception $e) {
                    // Invalid date format, ignore
                }
            });
        }

        // Sorting logic
        $sort_column = $request->get('sort_by', null);
        $sort_order = $request->get('order', 'asc');

        if (!empty($sort_column)) {
            // Prefix with table if needed
            if (!str_contains($sort_column, '.')) {
                $sort_column = 'customers.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('customers.name', 'asc');
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
            'customers.*'
        ]);

        $customers = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'customers' => $customers,
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

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();

                // public folder me direct save karo (easy way)
                $image->move(public_path('customers'), $image_name);
                // DB me path save
                $data['image'] = 'customers/' . $image_name;
            }

            $customer = Customer::create($data);

            return response()->json([
                'customer' => $customer,
                'status' => 'success',
                'message' => 'Customer created successfully'
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

        $customer = Customer::findOrFail($id);

        if (!$customer) {
            throw new \Exception('You are trying to update an invalid customer.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            // public folder me direct save karo (easy way)
            $image->move(public_path('customers'), $image_name);
            // DB me path save
            $data['image'] = 'customers/' . $image_name;
        }

        $customer->update($data);

        return response()->json([
            'customer' => $customer,
            'status' => 'success',
            'message' => 'Customer updated successfully'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $customer = Customer::findOrFail($id);

        if (!$customer) {
            throw new \Exception('Customer not found.');
        }

        if ($customer->orders()->count() > 0) {
            throw new \Exception('Cannot delete customer with existing orders.');
        }

        if ($customer->transactions()->count() > 0) {
            throw new \Exception('Cannot delete customer with existing transactions.');
        }

        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $user_id = $request->user()->id;
        $customer = Customer::with('package')->findOrFail($id);
        if (!$customer) {
            throw new \Exception('Customer not found.');
        }

        return response()->json([
            'status' => 'success',
            'customer' => $customer,
        ]);
    }
}
