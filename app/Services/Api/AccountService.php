<?php
namespace App\Services\Api;

use App\Models\Account;

class AccountService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $accounts = Account::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->get();
            return [
                "status" => 200,
                "data" => $accounts
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
            $account = Account::where([['user_id', $user->id], ['id', $id]])->first();
            return [
                "status" => 200,
                "data" => $account
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
                $account = Account::where([['user_id', $user->id], ['id', $id]])->first();
            } 
            else {
                $id = 0;
                $account = new Account;
                $account->user_id = $user->id;
                $account->is_system_data = 0;
            }
            $account->name = $request['name'];
            $account->is_deleted = 0;
            $account->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $account
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
            
            $account = Account::where([['user_id', $user->id], ['id', $id]])->first();

            if($account) {
                $account->is_deleted = 1;
                $account->save();

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