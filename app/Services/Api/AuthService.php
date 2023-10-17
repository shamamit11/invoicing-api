<?php
namespace App\Services\Api;
use App\Models\Account;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login($request)
    {
        $user = User::where('email', $request['email'])->first();

        if($user->status == 0) {
            throw ValidationException::withMessages([
                'message' => 'User is not active !',
            ]);
        }

        if($user->is_deleted == 1) {
            throw ValidationException::withMessages([
                'message' => 'User not found !',
            ]);
        }
        
        $credentials = array('email' => $request['email'], 'password' => $request['password'], 'is_deleted' => 0, 'status' => 1, 'email_verified' => 1);

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('token-name');
            return [
                'name' => $user->name,
                'token' => $token->plainTextToken
            ];
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register($request) {
        $date = date_create();
        try {
            $user = new User();
            $user->usercode = date_timestamp_get($date);
            $user->name = $request['name'];
            $user->mobile = $request['mobile'];
            $user->email = $request['email'];
            $user->password =  Hash::make($request['password']);
            $user->email_verified = 0;
            $user->status = 1;
            $user->device_id = isset($request['device_id']) ? $request['device_id'] : null;
            $user->is_deleted = 0;
            $user->save();

            DB::table('accounts')->insert([
                ['user_id' => $user->id, 'name' => 'Cash', 'balance' => 0, 'is_system_data' => 1, 'is_deleted' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
                ['user_id' => $user->id, 'name' => 'Bank', 'balance' => 0, 'is_system_data' => 1, 'is_deleted' => 0, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            ]);

            //send verification email
            $token = encode_param($user->usercode);
            $emailData = [
                'name' => $user->name,
                'token' => $token
            ];

            Mail::send('email.verify_account', $emailData, function ($message) use ($request) {
                $message->to($request['email']);
                $message->subject('EZ Invoicing: Verify Your Account');
            });
        } 
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}