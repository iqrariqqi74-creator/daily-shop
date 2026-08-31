<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\CouponInterface;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\Backend\coupons\CreateCouponRequest;
use App\Http\Requests\Backend\coupons\DeleteCouponRequest;
use App\Http\Requests\Backend\coupons\ShowCouponRequest;
use App\Http\Requests\Backend\coupons\UpdateCouponRequest;
use App\Http\Requests\Backend\coupons\EditCouponRequest;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    private $coupon_repository;

    public function __construct(CouponInterface $coupon_interface)
    {
        $this->coupon_repository = $coupon_interface;
    }

    public function index(ShowCouponRequest $request)
    {
        try {
            return $this->coupon_repository->getCoupons($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateCouponRequest $request)
    {
        DB::beginTransaction();

        try {
            $coupon = $this->coupon_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $coupon,
                'message' => 'Coupon created successfully.',
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

    public function update(UpdateCouponRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $coupon = $this->coupon_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $coupon,
                'message' => 'Coupon updated successfully.',
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

    public function destroy(DeleteCouponRequest $request, $id)
    {
        try {
            return $this->coupon_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditCouponRequest $request, $id)
    {
        try {
            return $this->coupon_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $coupon = Banner::findOrFail($id);

           $coupon->status = $request->status;
           $coupon->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
