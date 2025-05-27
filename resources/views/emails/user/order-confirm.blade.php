<!doctype html>
<html>
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    
<title>Welcome</title>
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
                    <td><img alt="logo" src="{{URL::asset(RESTAURANT_UPLOADS_PATH.SITE_LOGO)}}" width="200"></td></tr><tr>
                  <td height="70" style="border-bottom:2px solid #3cb2d0;"></td>
                  </tr>
                </tbody>
              </table>
              </span></td>
          </tr>
          <tr>
            <td height="150" align="left" valign="middle">@lang('constants.dear') {{$user->name}},<br/>
              <p> @lang('constants.greet_from')  {{APP_NAME}}! </p>
              <p>
              @lang('constants.order_confirm_mail') {{$order->order_id}}.
              </p>

              <p>@lang('constants.thanks_regard')<br>
              {{APP_NAME}} <br>
              @lang('constants.for_more_info') <br>
              @lang('constants.visit_site') <a href="#"></a></p>
          </tr>
          <tr>
            <td height="30">&nbsp;</td>
          </tr>
        
         
            <td height="60" align="center">
                <span style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#000; line-height:30px;">
                     {{date('Y')}} <span style="color:#3cb2d0; font-weight: bold;">{{APP_NAME}}</span>. @lang('constants.all_rights_reserved'). 
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
