<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\TaxInterface;
use App\Models\Tax;
use Carbon\Carbon;

class TaxRepository implements TaxInterface
{

    public function getOptions($request)
    {
        $query = Tax::query();

        $query->orderBy('name', 'asc');
        $query->where('status', 'active');

        $taxes = $query->get();

        $tax_options = [];
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                $tax_options[] = [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'image' => $tax->image,
                ];
            }
        }


        return response()->json([
            'status' => 'success',
            'taxes' => $tax_options,
        ]);
    }

    public function getTaxes($request)
    {
        $query = Tax::query();

        $query->with(['createdBy', 'updatedBy', 'deletedBy', ]);

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        if ($status !== null && $status !== '') {
            $query->where('taxes.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search by name, email, phone, location using LIKE
                $q->where('taxes.name', 'like', '%' . $search . '%')
                ->orWhere('taxes.value', 'like', '%' . $search . '%');

                // Search by status (0 or 1)
                if (strtolower($search) === 'active') {
                    $q->orWhere('taxes.status', 'active');
                } elseif (strtolower($search) === 'inactive') {
                    $q->orWhere('taxes.status', 'inactive');
                } elseif (is_numeric($search)) {
                    $q->orWhere('taxes.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('taxes.created_at', $date);
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
                $sort_column = 'taxes.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('taxes.name', 'asc');
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
            'taxes.*'
        ]);

        $taxes = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'taxes' => $taxes,
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

            $tax = Tax::create($data);

            return response()->json([
                'tax' => $tax,
                'status' => 'success',
                'message' => 'tax created successfully'
            ], 201);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update($request, $id)
    {
        try {

            $tax = Tax::findOrFail($id);

            $user = $request->user();
            $user_id = $user->id;

            $tax->name = $request->name;
            $tax->updated_by = $user_id;

            $tax->save();

            return response()->json([
                'status' => 'success',
                'message' => 'tax updated successfully.',
                'tax' => $tax->fresh()
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

        $tax = Tax::findOrFail($id);

        if (!$tax) {
            throw new \Exception('tax not found.');
        }

        $tax->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'tax deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $tax = Tax::with(['createdBy', 'updatedBy'])->findOrFail($id);

        if (!$tax) {
            throw new \Exception('tax not found.');
        }

        return response()->json([
            'status' => 'success',
            'tax' => $tax,
        ]);
    }
}
