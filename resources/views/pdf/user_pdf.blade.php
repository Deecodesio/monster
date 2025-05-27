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
            <th>User</th>
            <th>Phone Number</th>
            <th>Orders</th>
            <th>Amount</th>
           
            
        </tr>
        @foreach($res_fields as $eachProduct)
        <tr>
            <td>{{ $eachProduct["name"] }}</td>
            <td>{{ $eachProduct["phone"] }}</td>
            <td>{{ $eachProduct["users_count"] }}</td>
            <td>{{ $eachProduct["paidsums"] }}</td>
           
        </tr>
        @endforeach
        
    </table>
  
</body>
</html>
