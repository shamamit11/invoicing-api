<?php
namespace App\Services\Api;

use App\Models\Customer;

class CustomerService
{
    public function list()
    {
        try {
            $user = auth()->user();
            $customers = Customer::where([['user_id', $user->id], ['is_deleted', 0]])->latest()->paginate(100);
            return [
                "status" => 200,
                "data" => $customers
            ];
        } 
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function show()
    {
        try {
            $user = auth()->user();
            $customers = Customer::where([['user_id', $user->id], ['status', 1], ['is_deleted', 0]])->get();
            return [
                "status" => 200,
                "data" => $customers
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
                $customer = Customer::findOrFail($id);  
            } 
            else {
                $id = 0;
                $customer = new Customer;
                $customer->code = $user->id.'_'.date_timestamp_get($date);
                $customer->user_id = $user->id;      
            }
            
            $customer->name = $request['name'];
            $customer->phone = $request['phone'];
            $customer->email = $request['email'];
            $customer->address_1 = $request['address_1'];
            $customer->address_2 = $request['address_2'];
            $customer->city = $request['city'];
            $customer->country = $request['country'];
            $customer->trn_no = $request['trn_no'];
            $customer->country = $request['country'];
            $customer->country = $request['country'];
            $customer->status = isset($request['status']) ? 1 : 0;
            $customer->is_deleted = 0;
            $customer->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $customer
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function updateStatus($request)
    {
        try {
            $id = $request['id'];

            Customer::where('id', $id)->update([
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
            
            $item = Customer::findOrFail($id); 

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