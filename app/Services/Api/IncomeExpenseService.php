<?php
namespace App\Services\Api;

use App\Models\AccountTransaction;
use App\Models\IncomeExpense;

class IncomeExpenseService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $transactions = IncomeExpense::where([['user_id', $user->id]])->latest()->get();
            return [
                "status" => 200,
                "data" => $transactions
            ];
        } 
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();
            $transaction = IncomeExpense::where([['user_id', $user->id], ['id', $id]])->first();
            return [
                "status" => 200,
                "data" => $transaction
            ];
        } 
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function store($request)
    {
        try {
            $user = auth()->user();

            if ($request['id']) {
                $id = $request['id'];
                $transaction = IncomeExpense::where([['user_id', $user->id], ['id', $id]])->first();
            } 
            else {
                $id = 0;
                $transaction = new IncomeExpense;
                $transaction->user_id = $user->id;
            }
            $transaction->date = $request['date'];
            $transaction->transaction_type = $request['transaction_type'];
            $transaction->customer_id = $request['customer_id'];
            $transaction->account_id = $request['account_id'];
            $transaction->description = $request['description'];
            $transaction->amount = $request['amount'];
            $transaction->save();

            if($request['id']) {
                $accountTransaction = AccountTransaction::where('transaction_id', $request['id'])->first();
                $accountTransaction->account_id = $request['account_id'];
                $accountTransaction->transaction_type = $request['transaction_type'];
                if($request['transaction_type'] == 'income') {
                    $accountTransaction->amount = $request['amount'];
                } 
                else {
                    $accountTransaction->amount = -$request['amount'];
                }
                $accountTransaction->save();
            } 
            else {
                $accountTransaction = new AccountTransaction;
                $accountTransaction->user_id = $user->id;
                $accountTransaction->account_id = $request['account_id'];
                $accountTransaction->transaction_id = $transaction->id;
                $accountTransaction->transaction_type = $request['transaction_type'];

                if($request['transaction_type'] == 'income') {
                    $accountTransaction->amount = $request['amount'];
                } 
                else {
                    $accountTransaction->amount = -$request['amount'];
                }
                $accountTransaction->save();
            }

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $transaction
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function delete($request)
    {
        try {
            $id = $request['id'];
            $user = auth()->user();
            
            $transaction = IncomeExpense::where([['user_id', $user->id], ['id', $id]])->first();

            if($transaction) {
                $transaction->delete();

                $accountTransaction = AccountTransaction::where('transaction_id', $id)->first();
                $accountTransaction->delete();

                return [
                    "status" => 201,
                    "message" => 'success',
                ];
            } 
            else {
                return [
                    "status" => 400,
                    "message" => 'not_found',
                ];
            }
        } 
        catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}