<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding: 0;">
    <div id="wrapper" dir="ltr" style="background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%; -webkit-text-size-adjust: none;">
      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tr>
          <td align="center" valign="top">
            <div id="template_header_image" style="margin-bottom:25px;">
              <img src="{{ $org_logo }}" alt="{{ $organization_name }}" style="height: 60px;">
            </div>
            <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">
              <tr>
                <td align="center" valign="top">
                  <!-- Header -->
                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style='background-color: #8c0000; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;'>
                    <tr>
                      <td id="header_wrapper" style="padding: 25px 25px; display: block;">
                        <h1 style='font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 20px; font-weight: 500; margin: 0; text-align: left; color: #fff;'> Quotation# {{ $quote_no }}</h1>
                      </td>
                    </tr>
                  </table>
                  <!-- End Header -->
                </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <!-- Body -->
                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                    <tr>
                      <td valign="top" id="body_content" style="background-color: #ffffff;">
                        <!-- Content -->
                        <table border="0" cellpadding="20" cellspacing="0" width="100%">
                          <tr>
                            <td valign="top" style="padding: 25px 25px 0;">
                              <div id="body_content_inner" style='color: #303030; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;'>
                                <p style="margin: 0 0 16px;">
                                  <b>Hello {{ $customer_name }},</b>
                                  <br>
                                </p>
                                <p style="margin: 0 0 16px;">{{ $organization_name }} has send you a Quotation. Please click the link below to download. </p>
                                <p style="font-size:16px; font-weight: bold">
                                  <a href="{{ $pdf_link }}" style="color:blue; text-decoration:none" target="_blank">Click Here to View or Download </a>
                                </p>
                                <p>Thank You.</p>
                                <p style="font-weight: bold">EZ Invoicing Team <br />
                                </p>
                                <p>&nbsp; <br />
                                </p>
                              </div>
                            </td>
                          </tr>
                        </table>
                        <!-- End Content -->
                      </td>
                    </tr>
                  </table>
                  <!-- End Body -->
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <div style="margin-top:25px; text-align:center; font-family: Helvetica Neue, Helvetica, Roboto, Arial, sans-serif; font-size: 16px; font-weight:bold">
        <a href="https://ezinvoicing.online" target="_blank" style="text-decoration:none; color:#8c0000;">www.ezinvoicing.online</a>
      </div>
    </div>
  </body>
</html>