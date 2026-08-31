<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\SizeInterface;
use App\Models\Size;
use Carbon\Carbon;

class SizeRepository implements SizeInterface
{

    public function getOptions($request)
    {
        $query = Size::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $sizes = $query->get();
        $size_options = [];
        if (!empty($sizes)) {
            foreach ($sizes as $size) {
                $size_options[] = [
                    'id' => $size->id,
                    'name' => $size->name,
                    'image' => $size->image,
                ];
            }
        }
        return response()->json([
            'status' => 'success',
            'sizes' => $size_options,
        ]);
    }

    public function getSizes($request)
    {
        $query = Size::query();
        $query->where('status', 'active');

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('sizes.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('sizes.name', 'like', '%' . $search . '%')
                ->orWhere('sizes.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('sizes.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('sizes.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('sizes.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('sizes.created_at', $date);
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
                $sort_column = 'sizes.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('sizes.name', 'asc');
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
            'sizes.*'
        ]);

        $sizes = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'sizes' => $sizes,
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

           if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('sizes'), $image_name);
                $data['image'] = 'sizes/' . $image_name;
            }

            $size = Size::create($data);
            return response()->json([
                'size' => $size,
                'status' => 'success',
                'message' => 'Size created successfully'
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

            $user = $request->user();
            $size = Size::findOrFail($id);
            $data = $request->all();
            $data['updated_by'] = $user->id;
            // Parent size
            $data['status'] = $request->status;

            // Image Upload
            if ($request->hasFile('image')) {

                // Delete old image
                if ($size->image && file_exists(public_path($size->image))) {
                    unlink(public_path($size->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('sizes'), $imageName);

                $data['image'] = 'sizes/' . $imageName;
            }

            $size->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Size updated successfully',
                'size' => $size
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

        $size = Size::findOrFail($id);

        if (!$size) {
            throw new \Exception('Size not found.');
        }

        if($size->products()->count() > 0) {
                throw new \Exception('Cannot delete size with existing products.');
            }

        $size->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Size deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $size = Size::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$size) {
            throw new \Exception('size not found.');
        }

        return response()->json([
            'status' => 'success',
            'size' => $size,
        ]);
    }
}