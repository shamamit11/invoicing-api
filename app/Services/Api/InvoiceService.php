<?php
namespace App\Services\Api;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;

class InvoiceService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $items = Invoice::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->paginate(100);
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
                $qItem->amount = $i['amount'];
                $qItem->save();
                $i_amount = $i_amount + $i['amount'];
            }
            $invoice->total_amount = $i_amount;
            $i_tax = $i_amount * $invoice->tax_percent / 100;
            $invoice->total_tax = $i_tax;
            $invoice->save();
            
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

}