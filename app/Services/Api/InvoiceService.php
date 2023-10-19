<?php
namespace App\Services\Api;

use App\Models\Customer;
use App\Models\EmailSetting;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\Organization;
use Config;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $items = Invoice::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->get();
            foreach ($items as $item) {
                $customer = Customer::find($item->customer_id);
                $item->customer_name = $customer->name;
            }
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
            $item = Invoice::where([['user_id', $user->id], ['id', $id]])->first();
            
            $customer = Customer::find($item->customer_id);
            $item->customer_name = $customer->name;

            $listItems = InvoiceItem::where('invoice_id', $id)->get();
            $listPayments = InvoicePayment::where('invoice_id', $id)->get();

            if(count($listItems) > 0) {
                $list_items = $listItems;
            } else {
                $list_items = [];
            }

            if(count($listPayments) > 0) {
                $list_payments = $listPayments;
            } else {
                $list_payments = [];
            }

            return [
                "status" => 200,
                "data" => $item,
                "items" => $list_items,
                "payments" => $list_payments,
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

            $item = new Invoice;
            $item->user_id = $user->id;
            $item->customer_id = $request['customer_id'];
            $item->invoice_code = $user->id.'_'.date_timestamp_get($date);
            $item->invoice_no = $request['invoice_no'];
            $item->date = $request['date'];
            $item->tax_percent = $request['tax_percent'];
            $item->send_email = 0;
            $item->total_paid_amount = 0;
            $item->terms_conditions = isset($request['terms_conditions']) ? $request['terms_conditions'] : '';
            $item->is_deleted = 0;
            $item->save();

            $insertId = $item->id;
            $invoice = Invoice::where([['user_id', $user->id], ['id', $insertId]])->first();

            $invoiceItems = $request['invoice_items'];

            $i_amount = 0;
            $i_tax = 0;

            foreach($invoiceItems as $i) {
                $qItem = new InvoiceItem;
                $qItem->invoice_id = $insertId;
                $qItem->description = $i['description'];
                $qItem->qty = $i['qty'];
                $qItem->amount = $i['amount'] * $i['qty'];
                $qItem->save();
                $i_amount = $i_amount + $i['amount'] * $i['qty'];
            }
            $invoice->total_amount = $i_amount;
            $i_tax = $i_amount * $invoice->tax_percent / 100;
            $invoice->total_tax = $i_tax;
            $invoice->save();

            $customer = Customer::where('id', $item->customer_id)->first();
            $organization = Organization::where('user_id', $user->id)->first();
            $image_path = '/app/public/'.$user->usercode.'/organization/';
            $invoice = Invoice::where('id', $insertId)->first();
            $invoiceItems = InvoiceItem::where('invoice_id', $insertId)->get();

            $pdf_path = '/'.$user->usercode.'/invoices/';
            $pdf_name = $item->invoice_code.'.pdf';
            $pdf = PDF::loadView('invoice.default', compact('image_path', 'invoice', 'invoiceItems', 'customer', 'organization'))->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
            $pdf->save($pdf_path. $pdf_name, 'public');

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $invoice
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function delete($request)
    {
        try {
            $id = $request['id'];
            
            $item = Invoice::findOrFail($id); 

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

    public function storePayment($request) {
        try {
            $payment = new InvoicePayment;
            $payment->invoice_id = $request['invoice_id'];
            $payment->description = $request['description'];
            $payment->paid_amount = $request['paid_amount'];
            $payment->paid_date = $request['paid_date'];
            $payment->save();

            $user = auth()->user();
            $invoice = Invoice::where([['user_id', $user->id], ['id', $request['invoice_id']]])->first();
            $invoice->total_paid_amount = $invoice->total_paid_amount + $request['paid_amount'];
            $invoice->save();

            return [
                "status" => 201,
                "data" => $payment,
                "message" => 'success',
            ];
        } 
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function getPDFLink($id) {
        try {
            $user = auth()->user();
            $invoice = Invoice::where([['id', $id], ['user_id', $user->id]])->first();

            $pdf_link = env('APP_URL').'/storage/'.$user->usercode.'/invoices/'.$invoice->invoice_code.'.pdf';

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

    public function sendEmail($id) {
        try {
            $user = auth()->user();
            $invoice = Invoice::where([['id', $id], ['user_id', $user->id]])->first();
            $organization = Organization::where('user_id', $user->id)->first();
            $emailSetting = EmailSetting::where('user_id', $user->id)->first();
            $customer = Customer::where('id', $invoice->customer_id)->first();

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
                'pdf_link' => env('APP_URL').'/storage/'.$user->usercode.'/invoices/'.$invoice->invoice_code.'.pdf',
                'org_logo' => env('APP_URL').'/storage/'.$user->usercode.'/organization/'.$organization->org_logo,
                'invoice_no' => $invoice->invoice_no,
                'invoiceItems' => InvoiceItem::where('invoice_id', $id)->get()
            ];

            Mail::send('invoice.email', $emailData, function ($message) use ($invoice, $organization, $customer) {
                $message->to($customer->email, $customer->name)
                    ->subject('Invoice# '. $invoice->invoice_no .' from '. $organization->org_name);
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