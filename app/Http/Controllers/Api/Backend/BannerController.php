<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\BannerInterface;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\Backend\banners\CreateBannerRequest;
use App\Http\Requests\Backend\banners\DeleteBannerRequest;
use App\Http\Requests\Backend\banners\ShowBannerRequest;
use App\Http\Requests\Backend\banners\UpdateBannerRequest;
use App\Http\Requests\Backend\banners\EditBannerRequest;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    private $banner_repository;

    public function __construct(BannerInterface $banner_interface)
    {
        $this->banner_repository = $banner_interface;
    }

    public function index(ShowBannerRequest $request)
    {
        try {
            return $this->banner_repository->getBanners($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateBannerRequest $request)
    {
        DB::beginTransaction();

        try {
            $banner = $this->banner_repository->create($request);
            DB::commit();
            return response()->json([
                'data' => $banner,
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

    public function update(UpdateBannerRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $banner = $this->banner_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $banner,
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

    public function destroy(DeleteBannerRequest $request, $id)
    {
        try {
            return $this->banner_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditBannerRequest $request, $id)
    {
        try {
            return $this->banner_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
        {
            $banner = Banner::findOrFail($id);

           $banner->status = $request->status;
           $banner->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}
