<?php
namespace App\Services\Api;

use App\Models\Quotation;
use App\Models\QuotationItem;

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
                $qItem->amount = $q['amount'];
                $qItem->save();
                $q_amount = $q_amount + $q['amount'];
            }
            $quotation->total_amount = $q_amount;
            $q_tax = $q_amount * $quotation->tax_percent / 100;
            $quotation->total_tax = $q_tax;
            $quotation->save();
            
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

}