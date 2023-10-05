<?php
namespace App\Services\Api;

use App\Models\Organization;
use App\Traits\StoreImageTrait;

class OrganizationService
{
    use StoreImageTrait;
    public function profile()
    {
        try {
            $user = auth()->user();

            $organization = Organization::where('user_id', $user->id)->first();
            if ($organization) {
                return [
                    "status" => 200,
                    "data" => $organization
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

            $storage_path = '/'.$user->usercode.'/organization/';

            $organizationObject = Organization::where('user_id', $user->id)->first();

            if ($organizationObject) {
                $organization = $organizationObject;
            } 
            else {
                $organization = new Organization;
                $organization->user_id = $user->id;
            }
            $organization->org_name = $request['org_name'];
            $organization->org_email = $request['org_email'];
            $organization->org_phone = $request['org_phone'];
            $organization->org_website = $request['org_website'];
            $organization->org_address = $request['org_address'];
            $organization->org_address_1 = $request['org_address_1'];
            $organization->org_address_2 = $request['org_address_2'];
            $organization->org_city = $request['org_city'];
            $organization->org_country = $request['org_country'];
            $organization->org_license_no = $request['org_license_no'];
            $organization->org_logo = isset($request['org_logo']) ? $this->StoreImage($request['org_logo'], $storage_path) : null;
            $organization->org_signature = isset($request['org_signature']) ? $this->StoreImage($request['org_signature'], $storage_path) : null;
            $organization->org_stamp = isset($request['org_stamp']) ? $this->StoreImage($request['org_stamp'], $storage_path) : null;
            $organization->org_trn_no = $request['org_trn_no'];
            $organization->org_terms_conditions = $request['org_terms_conditions'];
            $organization->org_currency = $request['org_currency'];
            $organization->tax_percent = $request['tax_percent'];
            $organization->save();

            return [
                "status" => 201,
                "message" => 'success',
                "data" => $organization
            ];

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

}