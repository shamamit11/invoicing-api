<?php
namespace App\Services\Api;

use App\Models\Receipt;

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
                $item = Receipt::findOrFail($id);
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

}