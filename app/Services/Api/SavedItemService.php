<?php
namespace App\Services\Api;

use App\Models\SavedItem;

class SavedItemService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $items = SavedItem::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->paginate(100);
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
            $item = SavedItem::where([['user_id', $user->id], ['id', $id]])->first();
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

            if ($request['id']) {
                $id = $request['id'];
                $item = SavedItem::where([['user_id', $user->id], ['id', $id]])->first();
            } 
            else {
                $id = 0;
                $item = new SavedItem;
                $item->user_id = $user->id;
            }
            $item->description = $request['description'];
            $item->amount = $request['amount'];
            $item->status = isset($request['status']) ? 1 : 0;
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

            SavedItem::where('id', $id)->update([
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
            
            $item = SavedItem::findOrFail($id); 

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