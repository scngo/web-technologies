<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$customerid = $_SESSION['customerid'];
$orderid = $_POST['orderid'];
$res1 = mysql_query("SELECT FirstName,LastName FROM Customer WHERE CustId=$customerid");
$row1 = mysql_fetch_array($res1);
$res2 = mysql_query("SELECT OrderDate,TotalCost,BillAdd,ShipAdd FROM CustOrder WHERE OrderId=$orderid");
$row2 = mysql_fetch_array($res2);
$res3 = mysql_query("SELECT ProdName,OrderDetail.ProdPrice,ProdQty FROM OrderDetail,Product 
  WHERE Product.ProdId=OrderDetail.ProdId AND OrderId=$orderid");
mysql_close($con);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
  body {
    background-color: #dddddd;
  }
  div {
    margin:0 auto;
    width: 800px;
    border:thick solid SkyBlue;
    padding:.5in;
    background: #fcfcfc;
    -webkit-border-radius: 30;
    -moz-border-radius: 30;
    border-radius: 30px;
  }
  h1 {
    font-family:sans-serif;
    text-shadow: 5px 5px 5px #FF00FF;
  }
  p.info {
    font-family:cursive;
  }
  table.gridtable {
    font-size:12px;
    border-width: 1px;
    border-color: #666666;
    border-collapse: collapse;
  }
  table.gridtable th {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #666666;
    background-color: #cccccc;
  }
  table.gridtable td {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #666666;
    background-color: #dddddd;
  }
  input[type=submit] {
    background: Tan;
    color: Brown;
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
  button {
    background: Tan;
    color: Brown;
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
  </style>
</head>
<body>
<div align="center">
<h1 align="center">Tyanshan-sky Travel Service</h1>
<h3 align="center">Order Details</h3>
<form method="post" action="/customer/showorder.php">
<table>
<tr><td>Customer Name: </td><td width="250"> <?php echo $row1['FirstName'].' '.$row1['LastName'];?> </td>
  <td>Order Date: </td><td> <?php echo $row2['OrderDate'];?> </td></tr>
<tr><td>Total cost: </td><td colspan="3"> <?php echo $row2['TotalCost'];?> </td></tr>
<tr><td>Shipping address: </td><td colspan="3"> <?php echo $row2['ShipAdd'];?> </td></tr>
<tr><td>Billing address: </td><td colspan="3"> <?php echo $row2['BillAdd'];?> </td></tr>
</table>
<table class="gridtable">
  <tr><th>Product</th><th>Price</th><th>Quantity ordered</th></tr>
<?php
while($row3 = mysql_fetch_array($res3)) {
  echo '<tr><td>'.$row3['ProdName'].'</td><td>'.$row3['ProdPrice'].'</td><td>'.$row3['ProdQty'].'</td></tr>';
}
?>
</table>
<p><input type="submit" value="Return"></p>
</form>
</div>
</body>
</html>