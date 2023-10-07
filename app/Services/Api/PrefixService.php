<?php
namespace App\Services\Api;

use App\Models\Invoice;
use App\Models\Prefix;
use App\Models\Quotation;
use App\Models\Receipt;

class PrefixService
{
    public function prefix()
    {
        try {
            $user = auth()->user();

            $prefix = Prefix::where('user_id', $user->id)->first();
            if ($prefix) {
                return [
                    "status" => 200,
                    "data" => $prefix
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

            $prefixObject = Prefix::where('user_id', $user->id)->first();

            if ($prefixObject) {
                $prefix = $prefixObject;
            } else {
                $prefix = new Prefix;
                $prefix->user_id = $user->id;
                $prefix->receipt_start_no = $request['receipt_start_no'];
                $prefix->quote_start_no = $request['quote_start_no'];
                $prefix->invoice_start_no = $request['invoice_start_no'];
            }
            $prefix->receipt_prefix = $request['receipt_prefix'];
            $prefix->quote_prefix = $request['quote_prefix'];
            $prefix->invoice_prefix = $request['invoice_prefix'];
            $prefix->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $prefix
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function generateReceiptNo()
    {
        try {
            $user = auth()->user();
            $receiptObj = Receipt::where('user_id', $user->id)->latest('id')->first();

            if ($receiptObj) {
                $receiptCurrentNumber = $receiptObj->receipt_no;
                $r_exp = explode("-", $receiptCurrentNumber);
                $lastElement = end($r_exp);

                $receiptNo = $lastElement + 1;

                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $prefix = $prefixObj->receipt_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "receipt_no" => $receiptNo
                ];
            } 
            else {
                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $receiptNo = $prefixObj->receipt_start_no;
                $prefix = $prefixObj->receipt_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "receipt_no" => $receiptNo
                ];
            }

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }

    }

    public function generateQuotationtNo()
    {
        try {
            $user = auth()->user();
            $quoteObj = Quotation::where('user_id', $user->id)->latest('id')->first();

            if ($quoteObj) {
                $quoteCurrentNumber = $quoteObj->quote_no;
                $explode = explode("-", $quoteCurrentNumber);
                $lastElement = end($explode);

                $quoteNo = $lastElement + 1;

                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $prefix = $prefixObj->quote_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "quote_no" => $quoteNo
                ];
            } 
            else {
                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $quoteNo = $prefixObj->quote_start_no;
                $prefix = $prefixObj->quote_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "quote_no" => $quoteNo
                ];
            }

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function generateInvoiceNo()
    {
        try {
            $user = auth()->user();
            $invoiceObj = Invoice::where('user_id', $user->id)->latest('id')->first();

            if ($invoiceObj) {
                $invoiceCurrentNumber = $invoiceObj->invoice_no;
                $explode = explode("-", $invoiceCurrentNumber);
                $lastElement = end($explode);

                $invoiceNo = $lastElement + 1;

                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $prefix = $prefixObj->invoice_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "invoice_no" => $invoiceNo
                ];
            } 
            else {
                $prefixObj = Prefix::where('user_id', $user->id)->first();
                $invoiceNo = $prefixObj->invoice_start_no;
                $prefix = $prefixObj->invoice_prefix;

                return [
                    "status" => 200,
                    "message" => 'success',
                    "prefix" => $prefix,
                    "quote_no" => $invoiceNo
                ];
            }

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}