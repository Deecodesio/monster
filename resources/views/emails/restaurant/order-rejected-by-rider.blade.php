<!doctype html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	
<title></title>
<style type="text/css">
body {
    background-color: #f1f1f1;
    margin: 0px;
    font-family: 'Open Sans', Arial, sans-serif;   
}
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td align="center"><table width="600" border="0" cellspacing="20" cellpadding="0" bgcolor="#ffffff">
        <tbody>
          <tr>
            <td align="center" valign="middle"><span style="font-family: 'Open Sans', Arial, sans-serif; font-size:36px; color:#3b3b3b; font-weight: bold; letter-spacing:4px;"><table width="100" border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <!-- <td height="70" style="border-bottom:2px solid #3cb2d0;"></td> -->
                  </tr>
                </tbody>
              </table>
              </span></td>
          </tr>
          <tr>
            <td height="150" align="left" valign="middle">@lang('emails.dear_name', ['name' => $order->restaurant_name]),<br/>
              <p>
                  @lang('emails.order_rejected_by_driver', ['order' => $order->order_id, 'driver' => $driver->name])  
                  <br><br>@lang('emails.link_to_panel', ['url' => url('/admin')])
              </p>
          </tr>
          <tr>
            <td height="30">&nbsp;</td>
          </tr>
        
          <tr>
            <td height="30">&nbsp;</td>
          </tr>
          <tr>
            <td height="60" align="center">
                <span style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#000; line-height:30px;">
                    &#169; {{date('Y')}} <span style="color:#3cb2d0; font-weight: bold;">{{APP_NAME}}</span>. @lang('emails.all_rights_reserved') 
                </span>
            </td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>
