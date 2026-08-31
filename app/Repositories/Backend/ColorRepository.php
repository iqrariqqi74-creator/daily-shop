<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\ColorInterface;
use App\Models\Color;
use Carbon\Carbon;

class ColorRepository implements ColorInterface
{

    public function getOptions($request)
    {
        $query = Color::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $colors = $query->get();

        $color_options = [];
        if (!empty($colors)) {
            foreach ($colors as $color) {
                $color_options[] = [
                    'id' => $color->id,
                    'name' => $color->name,
                    'image' => $color->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'colors' => $color_options,
        ]);
    }

    public function getColors($request)
    {
        $query = Color::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('colors.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('colors.name', 'like', '%' . $search . '%')
                ->orWhere('colors.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('colors.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('colors.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('colors.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('colors.created_at', $date);
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
                $sort_column = 'colors.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('colors.name', 'asc');
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
            'colors.*'
        ]);

        $colors = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'colors' => $colors,
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
                $image->move(public_path('colors'), $image_name);
                // DB me path save
                $data['image'] = 'colors/' . $image_name;
            }


            $color = Color::create($data);

            return response()->json([
                'color' => $color,
                'status' => 'success',
                'message' => 'Color created successfully'
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

            $color = Color::findOrFail($id);

            $data = $request->all();
            $data['updated_by'] = $user->id;


           $color->status = $data['status'] ;
            $color->name = $data['name'];
            $color->description = $data['description'];
            $color->updated_by = $data['updated_by'];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                // public folder me direct save karo (easy way)
                $image->move(public_path('colors'), $image_name);
                // DB me path save
                $color->image = 'colors/' . $image_name;
            }
            $color->save();

            // dd($color->fresh());

            return response()->json([
                'status' => 'success',
                'message' => 'Color updated successfully',
                'color' => $color->fresh()
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

        $color = Color::findOrFail($id);

        if (!$color) {
            throw new \Exception('Color not found.');
        }

        if($color->products()->count() > 0) {
                throw new \Exception('Cannot delete color with existing products.');
            }

        $color->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Color deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $color = Color::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$color) {
            throw new \Exception('Color not found.');
        }

        return response()->json([
            'status' => 'success',
            'color' => $color,
        ]);
    }
}
