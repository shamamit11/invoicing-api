<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Organization;
use App\Models\User;

class InvoiceController extends Controller {

    public function viewInvoice($id)
    {
        $user = User::where('id', 5)->first();
        $data['image_path'] = '/app/public/'.$user->usercode.'/organization/';

        $data['organization'] = Organization::where('user_id', 5)->first();
        $data['invoice'] = $invoice = Invoice::where('id', $id)->first();
        $data['invoiceItems'] = InvoiceItem::where('invoice_id', $id)->get();
        $data['customer'] = Customer::where('id', $invoice->customer_id)->first();
        return view('invoice.default', $data);
    }
}