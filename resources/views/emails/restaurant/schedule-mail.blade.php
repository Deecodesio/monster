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
                  <td><img alt="logo" src="{{ asset('restaurant_uploads/logo.png') }}" width="200"></td>
                  <td height="70" style="border-bottom:2px solid #3cb2d0;">Welcome</td>
                  </tr>
                </tbody>
              </table>
              </span></td>
          </tr>
          <tr>
            <td height="150" align="left" valign="middle">Dear {{$restaurant->restaurant_name}},<br/>
              <p> Greetings from {{APP_NAME}}! </p>
              <p>
                  You have some scheduled order  
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
                    © {{date('Y')}} <span style="color:#3cb2d0; font-weight: bold;">{{APP_NAME}}</span>. All Rights Reserved. 
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
