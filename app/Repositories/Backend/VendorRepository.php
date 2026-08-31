<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\VendorInterface;
use App\Models\Vendor;
use Carbon\Carbon;

class VendorRepository implements VendorInterface
{

    public function getOptions($request)
    {
        $query = Vendor::query();

        $query->orderBy('name', 'asc');
        $query->where('status', '1');

        $vendors = $query->get();

        $vendor_options = [];
        if (!empty($vendors)) {
            foreach ($vendors as $vendor) {
                $vendor_options[] = [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'vendors' => $vendor_options,
        ]);
    }

    public function getVendors($request)
    {
        $query = Vendor::query();

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('vendors.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('vendors.name', 'like', '%' . $search . '%')
                ->orWhere('vendors.phone_number', 'like', '%' . $search . '%')
                ->orWhere('vendors.email', 'like', '%' . $search . '%')
                ->orWhere('vendors.location', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('vendors.status', 1);
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('vendors.status', 0);
                } elseif (is_numeric($search)) {
                    $q->orWhere('vendors.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('vendors.created_at', $date);
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
                $sort_column = 'vendors.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('vendors.name', 'asc');
        }

        // Pagination setup
        $records_per_page = (int) $request->get('records_per_page', 4);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_records = $query->count();
        $total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 0;

        // Apply pagination
        $query->offset($offset)->limit($records_per_page);

        // Select necessary fields
        $query->select([
            'vendors.*'
        ]);

        // Load relationships

        // Fetch vendors
        $vendors = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'vendors' => $vendors,
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
                $image->move(public_path('vendors'), $image_name);
                // DB me path save
                $data['image'] = 'vendors/' . $image_name;
            }

            //  dd($data);
            $vendor = Vendor::create($data);

            return response()->json([
                'vendor' => $vendor,
                'status' => 'success',
                'message' => 'vendor created successfully'
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

        $vendor = Vendor::findOrFail($id);

        if (!$vendor) {
            throw new \Exception('You are trying to update an invalid vendor.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;

         if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();

                // public folder me direct save karo (easy way)
                $image->move(public_path('vendors'), $image_name);
                // DB me path save
                $data['image'] = 'vendors/' . $image_name;
            }

        $vendor->update($data);

        return response()->json([
            'vendor' => $vendor,
            'status' => 'success',
            'message' => 'vendor updated successfully'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $vendor = Vendor::findOrFail($id);

        if (!$vendor) {
            throw new \Exception('vendor not found.');
        }


        if ($vendor->orders()->count() > 0) {
            throw new \Exception('Cannot delete vendor with existing orders.');
        }
        $vendor->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'vendor deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        if (!$vendor) {
            throw new \Exception('vendor not found.');
        }

        return response()->json([
            'status' => 'success',
            'vendor' => $vendor,
        ]);
    }
}