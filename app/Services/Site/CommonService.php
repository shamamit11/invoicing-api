<?php
namespace App\Services\Site;

use App\Models\User;

class CommonService
{

    public function verifyUserEmail($token)
    {
        try {
            $decoded_token = decode_param($token);
            $user = User::where('usercode', $decoded_token)->first();
            if($user) {
                $user->status = 1;
                $user->email_verified = 1;
                $user->save();
                return redirect()->route('user-verified');
            }
            else {
                return redirect()->route('user-not-verified');
            }
           
        } catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}