<?php
namespace App\Services\Api;

use App\Models\Customer;
use App\Models\EmailSetting;
use App\Models\Organization;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Config;
use Illuminate\Support\Facades\Mail;

class QuotationService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $items = Quotation::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->paginate(100);
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
            $item = Quotation::where([['user_id', $user->id], ['id', $id]])->first();
            $listItems = QuotationItem::where('quotation_id', $id)->get();

            if(count($listItems) > 0) {
                $list_items = $listItems;
            } else {
                $list_items = [];
            }

            return [
                "status" => 200,
                "data" => $item,
                "items" => $list_items,
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

            $item = new Quotation;
            $item->user_id = $user->id;
            $item->customer_id = $request['customer_id'];
            $item->quote_code = $user->id.'_'.date_timestamp_get($date);
            $item->quote_no = $request['quote_no'];
            $item->date = $request['date'];
            $item->tax_percent = $request['tax_percent'];
            $item->send_email = 0;
            $item->terms_conditions = isset($request['terms_conditions']) ? $request['terms_conditions'] : '';
            $item->is_deleted = 0;
            $item->save();

            $insertId = $item->id;
            $quotation = Quotation::where([['user_id', $user->id], ['id', $insertId]])->first();

            $quotationItems = $request['quote_items'];

            $q_amount = 0;
            $q_tax = 0;

            foreach($quotationItems as $q) {
                $qItem = new QuotationItem;
                $qItem->quotation_id = $insertId;
                $qItem->description = $q['description'];
                $qItem->qty = $q['qty'];
                $qItem->amount = $q['amount'] * $q['qty'];
                $qItem->save();
                $q_amount = $q_amount + $q['amount'] * $q['qty'];
            }
            $quotation->total_amount = $q_amount;
            $q_tax = $q_amount * $quotation->tax_percent / 100;
            $quotation->total_tax = $q_tax;
            $quotation->save();

            $customer = Customer::where('id', $item->customer_id)->first();
            $organization = Organization::where('user_id', $user->id)->first();
            $image_path = '/app/public/'.$user->usercode.'/organization/';
            $quote = Quotation::where('id', $insertId)->first();
            $quoteItems = QuotationItem::where('quotation_id', $insertId)->get();

            $pdf_path = '/'.$user->usercode.'/quotations/';
            $pdf_name = $item->quote_code.'.pdf';
            $pdf = Pdf::loadView('quotation.default', compact('image_path', 'quote', 'quoteItems', 'customer', 'organization'))->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
            $pdf->save($pdf_path. $pdf_name, 'public');
            
            return [
                "status" => 201,
                "message" => 'success',
                "data" => $quotation
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function delete($request)
    {
        try {
            $id = $request['id'];
            
            $item = Quotation::findOrFail($id); 

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

    public function getPDFLink($qid) {
        try {
            $user = auth()->user();
            $quotation = Quotation::where([['id', $qid], ['user_id', $user->id]])->first();

            $pdf_link = env('APP_URL').'/storage/'.$user->usercode.'/quotations/'.$quotation->quote_code.'.pdf';

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

    public function sendEmail($qid) {
        try {
            $user = auth()->user();
            $quotation = Quotation::where([['id', $qid], ['user_id', $user->id]])->first();
            $organization = Organization::where('user_id', $user->id)->first();
            $emailSetting = EmailSetting::where('user_id', $user->id)->first();
            $customer = Customer::where('id', $quotation->customer_id)->first();

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
                'pdf_link' => env('APP_URL').'/storage/'.$user->usercode.'/quotations/'.$quotation->quote_code.'.pdf',
                'org_logo' => env('APP_URL').'/storage/'.$user->usercode.'/organization/'.$organization->org_logo,
                'quote_no' => $quotation->quote_no,
                'quoteItems' => QuotationItem::where('quotation_id', $qid)->get()
            ];

            Mail::send('quotation.email', $emailData, function ($message) use ($quotation, $organization, $customer) {
                $message->to($customer->email, $customer->name)
                    ->subject('Quotation# '. $quotation->quote_no .' from '. $organization->org_name);
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