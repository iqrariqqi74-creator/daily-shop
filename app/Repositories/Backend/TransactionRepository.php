<?php

namespace App\Repositories\backend;


use App\Interfaces\Backend\TransactionInterface;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionRepository implements TransactionInterface
{

    public function getOptions($request)
    {
        $query = Transaction::query();

        $query->orderBy('id', 'asc');
        // $query->where('status', '1');

        $transactions = $query->get();

        $transaction_options = [];
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $transaction_options[] = [
                    'id' => $transaction->id,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'transactions' => $transaction_options,
        ]);
    }

    public function getTransactions($request)
    {
        $query = Transaction::query();

        // Search filters
        $search = $request->get('search', null);
        $status = $request->get('status', null);


        $from_date = $request->get('from_date', null);
        $to_date = $request->get('to_date', null);


        if ($from_date && $to_date) {

            try {
                $from = Carbon::createFromFormat('Y-m-d', $from_date)->startOfDay();
                $to = Carbon::createFromFormat('Y-m-d', $to_date)->endOfDay();
                $query->whereBetween('transaction_date_time', [$from, $to]);

                // dd($query->get());
            } catch (\Exception $e) {
                // Invalid date format, ignore the date filter
            }
        }


        if ($status !== null && $status !== '') {
            $query->where('transactions.status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('transactions.service_type', 'like', '%' . $search . '%')
                    ->orWhere('transactions.payment_method', 'like', '%' . $search . '%')
                    ->orWhere('transactions.amount', 'like', '%' . $search . '%')
                    ->orWhere('transactions.transaction_number', 'like', '%' . $search . '%');

                    // customer name search email phone search
                    $q->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });

                    // vendor name search email phone search
                    $q->orWhereHas('vendor', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });


                // Search by status (0 or 1)
                if (strtolower($search) === 'completed') {
                    $q->orWhere('transactions.status', 'completed');
                } elseif (strtolower($search) === 'cancelled') {
                    $q->orWhere('transactions.status', 'cancelled');
                } elseif (is_numeric($search)) {
                    $q->orWhere('transactions.status', $search);
                }

                // Search by date in DD-MM-YYYY format
                try {
                    $date = Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d');
                    $q->orWhereDate('transactions.created_at', $date);
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
                $sort_column = 'transactions.' . $sort_column;
            }
            $query->orderBy($sort_column, $sort_order);
        } else {
            $query->orderBy('transactions.id', 'asc');
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
            'transactions.*',

        ]);

        // Load relationships
        $query->with(['customer', 'vendor']);

        // Fetch transaction
        $transactions = $query->get();

        // Return response
        return response()->json([
            'status' => 'success',
            'transactions' => $transactions ,
            'total_records' => $total_records,
            'records_per_page' => $records_per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'page' => $page,
        ]);
    }

    public function create($request)
    {
        $user = $request->user();
        $data = $request->all();

        $data['transaction_number'] = 'TRX-' . strtoupper(uniqid());

        $transaction = Transaction::create($data);

        return response()->json([
            'transaction' => $transaction,
            'status' => 'success',
            'message' => 'transaction created successfully'
        ], 201);
    }


    public function update($request, $id)
    {
        $user = $request->user();
        $data = $request->all();

        $transaction = Transaction::findOrFail($id);

        if (!$transaction) {
            throw new \Exception('You are trying to update an invalid transaction.');
        }

        $data = $request->all();
        $data['updated_by'] = $user->id;


        $transaction->update($data);

        return response()->json([
            'transaction' => $transaction,
            'status' => 'success',
            'message' => 'transaction updated successfully'
        ], 200);
    }

    public function delete($request, $id)
    {
        $user = $request->user();

        $transaction = Transaction::findOrFail($id);

        if (!$transaction) {
            throw new \Exception('transaction not found.');
        }


        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'transaction deleted successfully'
        ], 200);
    }

    public function getById($request, $id)
    {
        $transaction = Transaction::with(['customer', 'vendor'])->findOrFail($id);
        if (!$transaction) {
            throw new \Exception('transaction not found.');
        }

        return response()->json([
            'status' => 'success',
            'transaction' => $transaction,
        ]);
    }
}