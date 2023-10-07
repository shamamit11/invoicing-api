<?php
namespace App\Services\Api;

use App\Models\Prefix;
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
                $receiptOldNumber = $receiptObj->receipt_no;
                $r_exp = explode("-", $receiptOldNumber);
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

    }

    public function generateInvoiceNo()
    {
        
    }

}