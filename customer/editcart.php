<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
if (isset($_SESSION['customerid'])) $customerid = $_SESSION['customerid'];
$_SESSION['page'] = 'editcart';
include '../mysqllogin.php';
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
<h3 align="center">Edit My Shopping Cart</h3>
<form method="post" action="/customer/doeditcart.php">
<p class="info" align="right">
<?php
if (isset($_SESSION['customerid'])) {
  $customerid = $_SESSION['customerid'];
  $res0 = mysql_query("SELECT FirstName FROM Customer WHERE CustId=$customerid");
  $row0 = mysql_fetch_array($res0);
  echo 'Welcome back, '.$row0['FirstName'].'! ';
  echo '<input type="submit" value="Manage" formaction="/customer.php">';
  echo '<input type="submit" value="Sign out" formaction="/signout.php">';
} else {
  echo 'Welcome!! ';
  echo '<input type="submit" value="Sign in" formaction="/customer/signin.php">';
  echo '<input type="submit" value="Sign up" formaction="/customer/signup.php">';
}
?>
</p>
<p><span style="font-size:0.8em">(enter zero to delete items)</span></p>
  <table class="gridtable">
  <tr><th>Product name</th><th>Price per item</th><th>Quantity</th></tr>
<?php
if (isset($_SESSION['customerid'])) {
  $customerid = $_SESSION['customerid'];
  $sql = "SELECT ProdName,ProdPrice,ProdQty,Cart.ProdId FROM Product,Cart 
    WHERE CustId=$customerid AND Product.ProdId = Cart.ProdId";
  $res = mysql_query($sql);
  while($row = mysql_fetch_array($res)) {
    echo '<tr><td>'.$row['ProdName'].'</td><td align="right">'.$row['ProdPrice'].'</td>';
    echo '<td> <input type="hidden" name="productid[]" value="'.$row['ProdId'].'">';
    echo '<input type="number" name="productqty[]" min="0" value="'.$row['ProdQty'].'"></td></tr>';
  }
  mysql_close($con);
} else {
  $cart = $_SESSION['cart'];
  if ($cart) {
  foreach ($cart as $entry) {
    list($productid,$productqty) = $entry;
    $res = mysql_query("SELECT ProdName,ProdPrice,ProdId FROM Product WHERE ProdId = $productid");
    $row = mysql_fetch_array($res);
    echo '<tr><td>'.$row['ProdName'].'</td><td align="right">'.$row['ProdPrice'].'</td>';
    echo '<td> <input type="hidden" name="productid[]" value="'.$row['ProdId'].'">';
    echo '<input type="number" name="productqty[]" min="0" value="'.$productqty.'"></td></tr>';
  }
  }
}
?>
</table>
<p><input type="submit" value="Save"/><span style="margin: 0 30px;"></span>
<input type="submit" value="Cancel" formaction="/customer/showcart.php"/></p>
</form>
</div>
</body>
</html>