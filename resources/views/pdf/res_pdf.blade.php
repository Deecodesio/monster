<!DOCTYPE html>
<html>
<head>
<style>
  .table{
    max-width: 2480px;
    width:100%;
    table-layout: fixed;
    font-size: 14px
  }
  .table td{
    width: auto;
    overflow: hidden;
    word-wrap: break-all;
  }
  .item {
     width: 16%;
   }
   .item1 {
     width: 13%;
   }
   .item2 {
     width: 10%;
   }
  @page {
    size: landscape;
}
</style>
    <title></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <h1>Store Report</h1>
  
    <table class="table table-bordered">
        <tr>
            <th >Store</th>
            <th class="item">Email</th>
            <th>Phone Number</th>

            <th >Address</th>
            <th >City</th>
            <th >Area</th>

            <th>Total Orders</th>
            <th>Total Store Earnings</th>
            <th>Pending Payouts</th>

            <th>Payouts Completed</th>
            
        </tr>
        @foreach($res_fields as $eachProduct)
        <tr>
            <td>{{ $eachProduct["restaurant_name"] }}</td>
            <td>{{ $eachProduct["email"] }}</td>
            <td>{{ $eachProduct["contact"] }}</td>
            <td>{{ $eachProduct["address"] }}</td>
            <td>{{ $eachProduct["city"] }}</td>
            <td>{{ $eachProduct["area"] }}</td>
            <td>{{ $eachProduct["total_orders"] }}</td>
            <td>{{ $eachProduct["restaurant_commision"] }}</td>
            <td>{{ $eachProduct["pending_payout"] }}</td>
            <td>{{ $eachProduct["payout_done"] }}</td>
        </tr>
        @endforeach
        
    </table>
  
</body>
</html>
