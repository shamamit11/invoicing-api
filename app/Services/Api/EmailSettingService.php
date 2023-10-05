<?php
namespace App\Services\Api;

use App\Models\EmailSetting;

class EmailSettingService
{
    public function smtp()
    {
        try {
            $user = auth()->user();

            $email_setting = EmailSetting::where('user_id', $user->id)->first();
            if ($email_setting) {
                return [
                    "status" => 200,
                    "data" => $email_setting
                ];
            } else {
                return [
                    "status" => 200,
                    "data" => []
                ];
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function store($request)
    {
        try {
            $user = auth()->user();

            $emailSettingObject = EmailSetting::where('user_id', $user->id)->first();

            if ($emailSettingObject) {
                $email_setting = $emailSettingObject;
            } 
            else {
                $email_setting = new EmailSetting;
                $email_setting->user_id = $user->id;
            }
            $email_setting->host = $request['host'];
            $email_setting->port = $request['port'];
            $email_setting->user_name = $request['user_name'];
            $email_setting->password = $request['password'];
            $email_setting->encryption = $request['encryption'];
            $email_setting->mail_from_address = $request['mail_from_address'];
            $email_setting->mail_from_name = $request['mail_from_name'];
            $email_setting->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $email_setting
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}