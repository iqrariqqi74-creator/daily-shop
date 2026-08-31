<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\CouponInterface;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponRepository implements CouponInterface
{

    public function getOptions($request)
    {
        $query = Coupon::query();

        $query->orderBy('title', 'asc');
        $query->where('status', 'active');

        $coupons = $query->get();

        $coupon_options = [];
        if (!empty($coupons)) {
            foreach ($coupons as $coupon) {
                $coupon_options[] = [
                    'id' => $coupon->id,
                    'name' => $coupon->name,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'coupons' => $coupon_options,
        ]);
    }

    public function getcoupons($request)
    {
        $query = Coupon::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('coupons.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('coupons.name', 'like', '%' . $search . '%')
                ->orWhere('coupons.value', 'like', '%' . $search . '%')
                ->orWhere('coupons.code', 'like', '%' . $search . '%')
                ->orWhere('coupons.type', 'like', '%' . $search . '%')
                ->orWhere('coupons.minimum_order_amount', 'like', '%' . $search . '%')
                ->orWhere('coupons.is_one_time', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('coupons.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('coupons.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('coupons.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('coupons.created_at', $date);
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
                $sort_column = 'coupons.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('coupons.title', 'asc');
        }

        // Pagination setup
        $records_per_page = (int) $request->get('records_per_page', 10);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $records_per_page;

        $total_records = $query->count();
        $total_pages = $total_records > 0 ? ceil($total_records / $records_per_page) : 0;

        // Apply pagination
        $query->offset($offset)->limit($records_per_page);

        // Select necessary fields
        $query->select([
            'coupons.*'
        ]);

        $coupons = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'coupons' => $coupons,
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
            $coupon = Coupon::create($data);

            return response()->json([
                'coupon' => $coupon,
                'status' => 'success',
                'message' => 'coupon created successfully'
            ], 201);
        } catch (\Exception $e) {

        // dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update($request, $id)
    {
        try {

            $coupon = Coupon::findOrFail($id);

            $user = $request->user();
            $user_id = $user->id;

            $coupon->title = $request->title;
            $coupon->code = $request->code;
            $coupon->value = $request->value;
            $coupon->status = $request->status;
            $coupon->type = $request->type;
            $coupon->is_one_time = $request->is_one_time;
            $coupon->minimum_order_amount = $request->minimum_order_amount;
            $coupon->updated_by = $user_id;

            $coupon->save();

            return response()->json([
                'status' => 'success',
                'message' => 'coupon updated successfully.',
                'coupon' => $coupon->fresh()
            ]);

        } catch (\Exception $e) {
                  dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $coupon = Coupon::findOrFail($id);

        if (!$coupon) {
            throw new \Exception('coupon not found.');
        }

        $coupon->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'coupon deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {

      try {
            $coupon = Coupon::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$coupon) {
            throw new \Exception('coupon not found.');
        }

        return response()->json([
            'status' => 'success',
            'coupon' => $coupon,
        ]);
        } catch(\Exception $e){
                dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}