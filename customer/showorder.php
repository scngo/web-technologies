<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$customerid = $_SESSION['customerid'];
$res = mysql_query("SELECT * FROM CustOrder WHERE CustId=$customerid ORDER BY OrderDate DESC");
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
<h3 align="center">My Historical Orders</h3>
<form method="post" action="/customer.php">
  <table class="gridtable">
  <tr><th>Order date</th><th>Total cost</th><th>Shipping address</th><th>Billing address</th><th>Selection</th></tr>
<?php
while($row = mysql_fetch_array($res)) {
  echo '<tr><td>'.$row['OrderDate'].'</td><td>'.$row['TotalCost'].'</td><td>'.$row['ShipAdd'].'</td><td>'.$row['BillAdd'].'</td>';
  echo '<td><button type="submit" name="orderid" value="'.$row['OrderId'].'" formaction="/customer/showdetail.php">Show details</button>';
}
?>
</table>
<p><input type="submit" value="Return"></p>
</form>
</div>
</body>
</html>