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
    <h2>Order Report</h2>
  
    <table class="table table-bordered">
        <tr>
            <th>Order Id</th>
            <th>Order Time</th>
            <th>Customer Name</th>

            <th>Phone Number</th>
            <th>DeliveryBoy Name</th>
            <th>DeliveryBoy Phone</th>

            <th>Restaurant Name</th>
            <th>Item Totals</th>
            <th>Tax</th>

            <th>Offer Discount</th>
            <th>Admin Commision</th>
            <th>DeliveryBoy Commision</th>
            <th>Restaurant Commision</th>
            <th>Status</th>

        </tr>
        @foreach($res_fields as $eachProduct)
        <tr>
            <td>{{ $eachProduct["order_id"] }}</td>
            <td>{{ $eachProduct["ordered_time"] }}</td>
            <td>{{ $eachProduct["customer_name"] }}</td>
            <td>{{ $eachProduct["phones"] }}</td>
            <td>{{ $eachProduct["driver_name"] }}</td>
            <td>{{ $eachProduct["driver_phone"] }}</td>
            <td>{{ $eachProduct["restaurant_name"] }}</td>
            <td>{{ $eachProduct["item_total"] }}</td>
            <td>{{ $eachProduct["tax"] }}</td>
            <td>{{ $eachProduct["offer_discount"] }}</td>

            <td>{{ $eachProduct["admin_commision"] }}</td>
            <td>{{ $eachProduct["delivery_boy_commision"] }}</td>
            <td>{{ $eachProduct["restaurant_commision"] }}</td>
            <td>{{ $eachProduct["status"] }}</td>
       
        </tr>
        @endforeach
        
    </table>
  
</body>
</html>
