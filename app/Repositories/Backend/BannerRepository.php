<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\BannerInterface;
use App\Models\Banner;
use Carbon\Carbon;

class BannerRepository implements BannerInterface
{

    public function getOptions($request)
    {
        $query = Banner::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $banner = $query->get();

        $banner_options = [];
        if (!empty($banner)) {
            foreach ($banner as $banner) {
                $banner_options[] = [
                    'id' => $banner->id,
                    'name' => $banner->name,
                    'image' => $banner->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'banners' => $banner_options,
        ]);
    }

    public function getBanners($request)
    {
        $query = Banner::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('banners.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('banners.name', 'like', '%' . $search . '%')
                ->orWhere('banners.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('banners.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('banners.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('banners.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('banners.created_at', $date);
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
                $sort_column = 'banners.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('banners.name', 'asc');
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
            'banners.*'
        ]);

        $banner = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'banners' => $banner,
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
                $image->move(public_path('banners'), $image_name);
                // DB me path save
                $data['image'] = 'banners/' . $image_name;
            }

            $banner = Banner::create($data);

            return response()->json([
                'banner' => $banner,
                'status' => 'success',
                'message' => 'banner created successfully'
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
        try {

            $banner = Banner::findOrFail($id);
            $user = $request->user();
            $user_id = $user->id;

            $banner->name = $request->name;
            $banner->text = $request->text;
            $banner->status = $request->status;
            $banner->updated_by = $user_id;

            // dd($request->all());

            // Image Upload
            if ($request->hasFile('image')) {

                // Delete old image
                if ($banner->image && file_exists(public_path($banner->image))) {
                    unlink(public_path($banner->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('banners'), $imageName);

                $banner->image = 'banners/' . $imageName;
            }

            $banner->save();


            return response()->json([
                'status' => 'success',
                'message' => 'banner updated successfully.',
                'banner' => $banner->fresh()
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

        $banner = Banner::findOrFail($id);

        if (!$banner) {
            throw new \Exception('banner not found.');
        }

        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'banner deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $banner = Banner::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$banner) {
            throw new \Exception('banner not found.');
        }

        return response()->json([
            'status' => 'success',
            'banner' => $banner,
        ]);
    }
}
