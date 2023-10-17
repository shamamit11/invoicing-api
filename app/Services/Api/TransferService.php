<?php
namespace App\Services\Api;

use App\Models\AccountTransaction;
use App\Models\Transfer;
use DB;

class TransferService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $transfers = Transfer::where([['user_id', $user->id]])->latest()->get();
            return [
                "status" => 200,
                "data" => $transfers
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
            $transfer = Transfer::where([['user_id', $user->id], ['id', $id]])->first();
            return [
                "status" => 200,
                "data" => $transfer
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
                $transfer = Transfer::where([['user_id', $user->id], ['id', $id]])->first();
            } 
            else {
                $id = 0;
                $transfer = new Transfer;
                $transfer->user_id = $user->id;
            }
            $transfer->date = $request['date'];
            $transfer->from_account_id = $request['from_account_id'];
            $transfer->to_account_id = $request['to_account_id'];
            $transfer->description = $request['description'];
            $transfer->amount = $request['amount'];
            $transfer->save();

            $accountTransactions = AccountTransaction::where('transaction_id', $request['id'])->get();
            if(count($accountTransactions) > 0) {
                foreach($accountTransactions as $res) {
                    $res->delete();
                }
            }

            DB::table('account_transactions')->insert([
                ['user_id' => $user->id, 'account_id' => $request['from_account_id'], 'transaction_type' => 'transfer', 'transaction_id' => $transfer->id, 'amount' => -$request['amount'], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['user_id' => $user->id, 'account_id' => $request['to_account_id'], 'transaction_type' => 'transfer', 'transaction_id' => $transfer->id, 'amount' => $request['amount'], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ]);

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $transfer
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
            
            $transaction = Transfer::where([['user_id', $user->id], ['id', $id]])->first();

            if($transaction) {
                $accountTransactions = AccountTransaction::where('transaction_id', $id)->get();
                if(count($accountTransactions) > 0) {
                    foreach($accountTransactions as $res) {
                        $res->delete();
                    }
                }

                $transaction->delete();

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