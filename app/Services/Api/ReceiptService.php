<?php
namespace App\Services\Api;

use App\Models\Customer;
use App\Models\EmailSetting;
use App\Models\Organization;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Receipt;
use Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Rmunate\Utilities\SpellNumber;

class ReceiptService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $items = Receipt::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->paginate(100);
            return [
                "status" => 200,
                "data" => $items
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
            $item = Receipt::where([['user_id', $user->id], ['id', $id]])->first();
            return [
                "status" => 200,
                "data" => $item
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
            $date = date_create();

            if ($request['id']) {
                $id = $request['id'];
                $item = Receipt::where([['user_id', $user->id], ['id', $id]])->first();
            } 
            else {
                $id = 0;
                $item = new Receipt;
                $item->user_id = $user->id;
                $item->customer_id = $request['customer_id'];
                $item->receipt_code = $user->id.'_'.date_timestamp_get($date);
                $item->receipt_no = $request['receipt_no'];
                $item->date = $request['date'];
                $item->total_amount = $request['total_amount'];
            }
            $item->payment_method = $request['payment_method'];
            $item->paid_for = $request['paid_for'];
            $item->status = 1;
            $item->is_deleted = 0;
            $item->save();

            $customer = Customer::where('id', $item->customer_id)->first();
            $customer_name = $customer->name;
            $organization = Organization::where('user_id', $user->id)->first();

            if($organization->org_currency == 'AED') {
                $amount_words = SpellNumber::value($item->total_amount)->currency('dirhams')->fraction('fils')->toMoney();
            }
            else if($organization->org_currency == 'USD') {
                $amount_words = SpellNumber::value($item->total_amount)->currency('dollars')->fraction('cents')->toMoney();
            }
            else {
                $amount_words = SpellNumber::value($item->total_amount)->toLetters(); 
            }
            
            $image_path = '/app/public/'.$user->usercode.'/organization/';

            //create pdf
            $pdf_path = '/'.$user->usercode.'/receipts/';
            $pdf_name = $item->receipt_code.'.pdf';
            $pdf = PDF::loadView('receipt.default', compact('image_path', 'item', 'customer_name', 'amount_words', 'organization'))->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled', true]);
            $pdf->save($pdf_path. $pdf_name, 'public');

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $item
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function updateStatus($request)
    {
        try {
            $id = $request['id'];

            Receipt::where('id', $id)->update([
                'status' => $request['status'],
            ]);

            return [
                "status" => 201,
                "message" => 'success',
            ];
        } 
        catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function delete($request)
    {
        try {
            $id = $request['id'];
            
            $item = Receipt::findOrFail($id); 

            if($item) {
                $item->status = 0;
                $item->is_deleted = 1;
                $item->save();

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

    public function getPDFLink($rid) {
        try {
            $user = auth()->user();
            $receipt = Receipt::where([['id', $rid], ['user_id', $user->id]])->first();

            $pdf_link = env('APP_URL').'/storage/'.$user->usercode.'/receipts/'.$receipt->receipt_code.'.pdf';

            return [
                "status" => 200,
                "link" => $pdf_link,
                "message" => 'success',
            ];
        }
        catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function sendEmail($rid) {
        try {
            $user = auth()->user();
            $receipt = Receipt::where([['id', $rid], ['user_id', $user->id]])->first();
            $organization = Organization::where('user_id', $user->id)->first();
            $emailSetting = EmailSetting::where('user_id', $user->id)->first();
            $customer = Customer::where('id', $receipt->customer_id)->first();

            if($emailSetting) {
                Config::set('mail.mailers.smtp.host', $emailSetting->host);
                Config::set('mail.mailers.smtp.port', $emailSetting->port);
                Config::set('mail.mailers.smtp.encryption', $emailSetting->encryption);
                Config::set('mail.mailers.smtp.username', $emailSetting->user_name);
                Config::set('mail.mailers.smtp.password', $emailSetting->password);
                Config::set('mail.from.address', $emailSetting->mail_from_address);
                Config::set('mail.from.name', $emailSetting->mail_from_name);
            }

            $emailData = [
                'customer_name' => $customer->name,
                'organization_name' => $organization->org_name,
                'pdf_link' => env('APP_URL').'/storage/'.$user->usercode.'/receipts/'.$receipt->receipt_code.'.pdf',
                'org_logo' => env('APP_URL').'/storage/'.$user->usercode.'/organization/'.$organization->org_logo,
                'receipt_no' => $receipt->receipt_no
            ];

            Mail::send('receipt.email', $emailData, function ($message) use ($receipt, $organization, $customer) {
                $message->to($customer->email, $customer->name)
                    ->subject('Receipt Voucher# '. $receipt->receipt_no .' from '. $organization->org_name);
            });

            return [
                "status" => 201,
                "message" => 'success',
            ];
        }
        catch (\Exception$e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }
}