<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\CategoryInterface;
use App\Models\Category;
use Carbon\Carbon;

class CategoryRepository implements CategoryInterface
{

    public function getOptions($request)
    {
        $query = Category::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $categories = $query->get();

        $category_options = [];
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $category_options[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'categories' => $category_options,
        ]);
    }

    public function getCategories($request)
    {
        $query = Category::query();
        $query->with(['createdBy', 'updatedBy', 'deletedBy', 'children']);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('categories.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('categories.name', 'like', '%' . $search . '%')
                ->orWhere('categories.description', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('categories.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('categories.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('categories.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('categories.created_at', $date);
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
                $sort_column = 'categories.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('categories.name', 'asc');
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
            'categories.*'
        ]);

        $categories = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
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


           if ($request->hasFile('image')) {
                $image = $request->file('image');
                $image_name = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('categories'), $image_name);
                $data['image'] = 'categories/' . $image_name;
            }

            $category = Category::create($data);

            return response()->json([
                'category' => $category,
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
        try {
            $user = $request->user();
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id ?: null;
            $category->status = $request->status;
            $category->updated_by = $user->id;

            // Image Upload
            if ($request->hasFile('image')) {

                // Delete old image
                if ($category->image && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $image->move(public_path('categories'), $imageName);

                $category->image = 'categories/' . $imageName;
            }

            $category->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Category updated successfully',
                'category'   => $category
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $category = Category::findOrFail($id);

        if (!$category) {
            throw new \Exception('Customer not found.');
        }

    //    if($category->prodect()->count() > 0) {
    //         throw new \Exception('Cannot delete customer with existing orders.');
    //     }

    //     if ($category->transactions()->count() > 0) {
    //         throw new \Exception('Cannot delete customer with existing transactions.');
    //     }

        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $category = Category::with([ 'children', 'parent', 'createdBy', 'updatedBy'])->findOrFail($id);

        if (!$category) {
            throw new \Exception('Customer not found.');
        }

        return response()->json([
            'status' => 'success',
            'category' => $category,
        ]);
    }
}
