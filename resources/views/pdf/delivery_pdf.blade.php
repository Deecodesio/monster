<!DOCTYPE html>
<html>
<head>
<style>
  .table{
    max-width: 2480px;
    width:100%;
    table-layout: fixed;
    font-size: 10px
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
    <h2>Delivery People Report</h2>
  
    <table class="table table-bordered">
        <tr>
            <th class="item2">Name</th>
            <th class="item">Email</th>
            <th  class="item1">Phone Number</th>

            <th class="item">Address</th>
            <th class="item">City</th>
            <th>Area</th>

            <th>Total Orders</th>
            <th>Delivery Boy Commision</th>
            <th>Pending Payouts</th>
            
        </tr>
        @foreach($res_fields as $eachProduct)
        <tr>
            <td>{{ $eachProduct["name"] }}</td>
            <td>{{ $eachProduct["email"] }}</td>
            <td>{{ $eachProduct["phone"] }}</td>
            <td>{{ $eachProduct["address"] }}</td>
            <td>{{ $eachProduct["city_name"] }}</td>
            <td>{{ $eachProduct["area"] }}</td>
            <td>{{ $eachProduct["total_orders"] }}</td>
            <td>{{ $eachProduct["delivery_commision"] }}</td>
            <td>{{ $eachProduct["pending_payout"] }}</td>
           
        </tr>
        @endforeach
        
    </table>
  
</body>
</html>
