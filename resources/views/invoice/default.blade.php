@php 
    use Rmunate\Utilities\SpellNumber;
@endphp

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:v="urn:schemas-microsoft-com:vml" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        @page { margin: 0px; }
        html, body {
            font-family: sans-serif;
            padding: 0;
            margin: 0;
        }

        table,
        tr,
        td {
            padding: 0;
        }

        small {
            font-size: 10px;
        }

        .main {
            margin: 0 auto;
            width: 95%;
            padding: 10px;
            background-color: #fff;
        }

        .wrapper {
            width: 100%;
        }

        .header {
            height: 50px;
            background: #363636;
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            text-align: center;
        }

        .invoice-title {
            font-size: 35px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .footer {
            height: 30px;
            background: #363636;
            text-align: center;
            color: #fff;
            font-size: 10px;
        }

        .content {
            padding: 20px;
            position: relative;
            z-index: 90;
        }

        .label-holder {
            width: 40%;
            background: #e7e7e7;
            font-weight: 600;
            padding-left: 6px;
            font-size: 10px;
            height: 25px;
        }

        .text-holder {
            width: 60%;
            border: 1px solid #e7e7e7;
            background: #f9f9f9;
            font-weight: 500;
            text-align: right;
            padding-right: 6px;
            font-size: 10px;
            height: 25px;
        }

        .row-label {
            width: 25%;
            font-weight: 600;
        }

        .row-text {
            width: 75%;
            border-bottom: 1px dashed #363636;
            padding-bottom: 3px;
        }

        .spacer {
            height: 25px;
        }

        th.th-header {
            height: 30px;
            background-color: #f0f8ff;
        }

        .items-row {
            height: 40px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="wrapper">
            <div class="content">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            <div class="invoice-title">TAX INVOICE</div>
                            <div style="margin-bottom: 10px;"><strong>Invoice#:</strong> {{ $invoice->invoice_no }}
                            </div>
                            <div><strong>Invoice Date:</strong> {{ $invoice->date }} </div>
                        </td>
                        <td style="text-align: right">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path . @$organization->org_logo))) }}"
                                width="200" />
                        </td>
                    </tr>
                </table>
                <div class="spacer"></div>
                <div class="spacer"></div>
                <table border="0" cellpadding="5" cellspacing="5" width="100%">
                    <tr style="vertical-align: top">
                        <td width="50%" style="line-height: 24px;">
                            <div style="margin-bottom: 12px; font-size: 20px; font-weight: 600;">Bill From</div>
                            <div>{{ $organization->org_name }}</div>
                            <div>{{ $organization->org_address }}, {{ $organization->org_city }},
                                {{ $organization->org_country }}</div>
                            <div><strong>Contact #: </strong> {{ $organization->org_phone }}</div>
                            <div><strong>Email: </strong> {{ $organization->org_email }}</div>
                            @if (@$organization->org_trn_no)
                                <div><strong>TRN#: </strong> {{ @$organization->org_trn_no }}</div>
                            @endif
                        </td>
                        <td width="50%" style="line-height: 24px;">
                            <div style="margin-bottom: 12px; font-size: 20px; font-weight: 600;">Bill To</div>
                            <div>{{ $customer->name }}</div>
                            <div>{{ $customer->address_1 }}, {{ $customer->city }}, {{ $customer->country }}</div>
                            <div><strong>Contact #: </strong> {{ $customer->phone }}</div>
                            <div><strong>Email: </strong> {{ $customer->email }}</div>
                            @if (@$customer->trn_no)
                                <div><strong>TRN#: </strong> {{ @$customer->trn_no }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="spacer"></div>

                <table border="0" cellpadding="8" cellspacing="2" width="100%" style="background-color: #f0f8ff;">
                    <thead>
                        <tr>
                            <th class="th-header" width="5%">#</th>
                            <th class="th-header" style="text-align: left">Description</th>
                            <th class="th-header" width="8%">Qty</th>
                            <th class="th-header" width="20%" style="text-align: right">Price</th>
                            <th class="th-header" width="20%" style="text-align: right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $count = 1;
                            $subtotal = 0;
                        @endphp
                        @foreach ($invoiceItems as $item)
                            <tr style="vertical-align: middle;">
                                <td class="items-row" style="text-align: center">{{ $count }}</td>
                                <td class="items-row" style="text-align: left; padding-left: 8px;">{{ $item->description }}</td>
                                <td class="items-row" style="text-align: center">{{ $item->qty }}</td>
                                <td class="items-row" style="text-align: right; padding-right: 8px"><small>{{ $organization->org_currency }}</small>
                                    {{ $item->amount / $item->qty }}</td>
                                <td class="items-row" style="text-align: right; padding-right: 8px"><small>{{ $organization->org_currency }}</small>
                                    {{ $item->amount }}</td>
                            </tr>
                            @php 
                                $subtotal = $subtotal + $item->amount;
                                $count++;
                            @endphp
                        @endforeach
                        <tr>
                            <td class="items-row">&nbsp;</td>
                            <td class="items-row">&nbsp;</td>
                            <td class="items-row">&nbsp;</td>
                            <td class="items-row">&nbsp;</td>
                            <td class="items-row">&nbsp;</td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px">
                                <strong>Subtotal</strong></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px"><small>{{ $organization->org_currency }}</small> {{ $subtotal }}
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px"><strong>Tax
                                    ({{ $invoice->tax_percent }}%)</strong></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px"><small>{{ $organization->org_currency }}</small> {{ $invoice->total_tax }}
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px"><strong>Total
                                    Amount</strong></td>
                            <td class="items-row" style="text-align: right; padding-right: 8px"><small>{{ $organization->org_currency }}</small> {{ $subtotal + $invoice->total_tax }}
                            </td>
                        </tr>

                    </tbody>
                </table>
                @php 
                    if($organization->org_currency == 'AED') {
                        $amount_words = SpellNumber::value($subtotal + $invoice->total_tax)->currency('dirhams')->fraction('fils')->toMoney();
                    }
                    else if($organization->org_currency == 'USD') {
                        $amount_words = SpellNumber::value($subtotal + $invoice->total_tax)->currency('dollars')->fraction('cents')->toMoney();
                    }
                    else {
                        $amount_words = SpellNumber::value($subtotal + $invoice->total_tax)->toLetters(); 
                    }
                @endphp
                <div style="margin-top: 15px; margin-bottom: 10px; text-align: right;"><strong>In Words:</strong> {{ $amount_words }} Only.</div>
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" align="right">
                    <tr style="vertical-align: middle;">
                        <td style="width:50%">
                            @if (@$invoice->terms_conditions)
                                <div><strong>Terms & Conditions</strong></div>
                                <div>{{ $invoice->terms_conditions }}</div>
                            @else
                                <div>&nbsp;</div>
                            @endif
                        </td>
                        <td style="width:25%; text-align: right">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path . @$organization->org_signature))) }}"
                                width="150" />
                        </td>
                        <td style="text-align: right">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path . @$organization->org_stamp))) }}"
                                width="150" />
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
