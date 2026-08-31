<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\BrandInterface;
use App\Models\Brand;
use Carbon\Carbon;

class BrandRepository implements BrandInterface
{

    public function getOptions($request)
    {
        $query = Brand::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $brands = $query->get();

        $brand_options = [];
        if (!empty($brands)) {
            foreach ($brands as $brand) {
                $brand_options[] = [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'image' => $brand->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'brands' => $brand_options,
        ]);
    }

    public function getBrands($request)
    {
        $query = Brand::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('brands.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('brands.name', 'like', '%' . $search . '%')
                ->orWhere('brands.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('brands.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('brands.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('brands.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('brands.created_at', $date);
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
                $sort_column = 'brands.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('brands.name', 'asc');
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
            'brands.*'
        ]);

        $brands = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'brands' => $brands,
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
            $data['parent_id'] = $request->parent_id ?: null;

            // dd($data);

            // Handle image upload
           if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();

                // public folder me direct save karo (easy way)
                $image->move(public_path('brands'), $image_name);
                // DB me path save
                $data['image'] = 'brands/' . $image_name;
            }

            $brand = Brand::create($data);

            return response()->json([
                'brand' => $brand,
                'status' => 'success',
                'message' => 'Brand created successfully'
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

            $brand = Brand::findOrFail($id);

            $user = $request->user();
            $user_id = $user->id;


            $brand->name = $request->name;
            $brand->status = $request->status;
            $brand->description = $request->description;
            $brand->updated_by = $user_id;

            // Image Upload
            if ($request->hasFile('image')) {

                // Delete old image
                if ($brand->image && file_exists(public_path($brand->image))) {
                    unlink(public_path($brand->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('brands'), $imageName);

                $brand->image = 'brands/' . $imageName;
            }

            $brand->save();

            // dd($brand);

            return response()->json([
                'status' => 'success',
                'message' => 'Brand updated successfully.',
                'brand' => $brand->fresh()
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $brand = Brand::findOrFail($id);

        if (!$brand) {
            throw new \Exception('Brand not found.');
        }

        $brand->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Brand deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $brand = Brand::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$brand) {
            throw new \Exception('Brand not found.');
        }

        return response()->json([
            'status' => 'success',
            'brand' => $brand,
        ]);
    }
}
