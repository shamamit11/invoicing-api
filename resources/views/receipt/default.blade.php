<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
  xmlns:v="urn:schemas-microsoft-com:vml"
  lang="en"
>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
      body {
        font-family: sans-serif;
      }

      table,
      tr,
      td {
        padding: 0;
      }

      .main {
        margin: 0 auto;
        width: 95%;
        padding: 10px;
        background-color: #fff;
      }

      .wrapper {
        width: 100%;
        border: 1px solid #363636;
        background: rgba(217, 217, 217, 0);
      }

      .header {
        height: 50px;
        background: #363636;
        color: #fff;
        font-size: 21px;
        font-weight: 700;
        text-align: center;
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

    </style>
  </head>

  <body>
    <div class="main">
      <div class="wrapper">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td class="header">Receipt Voucher</td>
          </tr>
        </table>

        <div class="content">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
              <td width="70%">
                <img
                  src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path.@$organization->org_logo))) }}"
                  width="150"
                />
              </td>
              <td width="30%">
                <table border="0" cellpadding="1" cellspacing="1" width="100%">
                  <tr>
                    <td class="label-holder">Receipt #:</td>
                    <td class="text-holder">{{ $item->receipt_no }}</td>
                  </tr>
                  <tr>
                    <td class="label-holder">Date:</td>
                    <td class="text-holder">{{ $item->date }}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <div class="spacer"></div>
          <div class="spacer"></div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
                <td class="row-label"> Received From: </td>
                <td class="row-text"> {{ $customer_name}} </td>
            </tr>
          </table>

          <div class="spacer"></div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
                <td class="row-label"> Paid For: </td>
                <td class="row-text"> {{ $item->paid_for}} </td>
            </tr>
          </table>

          <div class="spacer"></div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
                <td class="row-label"> Sum of: </td>
                <td class="row-text"> {{ $amount_words }} only.</td>
            </tr>
          </table>

          <div class="spacer"></div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
                <td class="row-label"> Amount: ({{ @$organization->org_currency }}) </td>
                <td class="row-text"> {{ $item->total_amount}}</td>
            </tr>
          </table>

          <div class="spacer"></div>
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr style="vertical-align: middle">
                <td class="row-label"> Payment Mode: </td>
                <td class="row-text"> {{ $item->payment_method}} </td>
            </tr>
          </table>

          <table border="0" cellpadding="10" cellspacing="10" width="150" align="right">
            <tr>
                <td width="50%" style="text-align: right;">
                    <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path.@$organization->org_signature))) }}"
                        width="150" />
                </td>
                <td width="50%" style="text-align: right;">
                    <img
                        src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path($image_path.@$organization->org_stamp))) }}"
                        width="150"
                />
                </td>
            </tr>
          </table>
        </div>

        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td class="footer">
              {{ $organization->org_name }} | {{ $organization->org_address }}, {{ $organization->org_city }}, {{ $organization->org_country }}
            </td>
          </tr>
        </table>
      </div>
    </div>
  </body>
</html>
