<?php
namespace App\Services\Api;

use App\Models\User;
use Hash;

class UserService
{
    public function profile()
    {
        try {
            $user = auth()->user();
            return [
                "status" => 200,
                "data" => $user
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
            $user->name = $request['name'];
            $user->mobile = $request['mobile'];
            $user->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $user
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function updatePassword($request)
    {
        try {
            $user = auth()->user();

            if (Hash::check($request['old_password'], $user->password)) {
                User::whereId($user->id)->update([
                    'password' => Hash::make($request['new_password']),
                ]);
                return [
                    "status" => 201,
                    "message" => 'success',
                ];
            } 
            else {
                return [
                    "status" => 401,
                    "message" => 'error',
                ];
            }
        } 
        catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}