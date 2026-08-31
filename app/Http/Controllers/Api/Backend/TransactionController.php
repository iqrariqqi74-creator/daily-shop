<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\Backend\TransactionInterface;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Http\Requests\Backend\transactions\CreateTransactionRequest;
use App\Http\Requests\Backend\transactions\DeleteTransactionRequest;
use App\Http\Requests\Backend\transactions\ShowTransactionRequest;
use App\Http\Requests\Backend\transactions\UpdateTransactionRequest;
use App\Http\Requests\Backend\transactions\EditTransactionRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private $transaction_repository;

    public function __construct(TransactionInterface $transaction_interface)
    {
        $this->transaction_repository = $transaction_interface;
    }

    public function index(ShowTransactionRequest $request)
    {
        try {
            return $this->transaction_repository->getTransactions($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function store(CreateTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $transaction = $this->transaction_repository->create($request);

            DB::commit();

            return response()->json([
                'data' => $transaction,
                'message' => 'transaction created successfully.',
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

    public function update(UpdateTransactionRequest $request, $id)
    {
         DB::beginTransaction();
        try {
           $transaction = $this->transaction_repository->update($request, $id);
            DB::commit();

            return response()->json([
                'data' => $transaction,
                'message' => 'transaction updated successfully.',
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

    public function destroy(DeleteTransactionRequest $request, $id)
    {
        try {
            return $this->transaction_repository->delete($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function show(EditTransactionRequest $request, $id)
    {
        try {
            return $this->transaction_repository->getById($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    public function getVendorsByTransaction(Request $request, $id)
    {

    $transactions = Transaction::where('vendor_id', $id)->get();
    $transactions->load('vendor', 'customer');

     return response()->json([
                'status' => 'success',
                'transactions' => $transactions,
            ]);

    }
    public function updateStatus(Request $request, $id)
        {
            $transaction = Transaction::findOrFail($id);

            $transaction->status = $request->status;
            $transaction->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
}