<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
if (isset($_SESSION['customerid'])) $customerid = $_SESSION['customerid'];
$_SESSION['page'] = 'showcart';
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
<h3 align="center">My Shopping Cart</h3>
<form method="post" action="/customer/pay.php">
<p class="info" align="right">
<?php
if (isset($customerid)) {
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
  <table class="gridtable">
  <tr><th>Product name</th><th>Price per item</th><th>Quantity</th><th>Subtotal</th></tr>
<?php
if (isset($customerid)) {
  $sql = "SELECT ProdName,ProdPrice,SalePrice,ProdQty FROM Cart 
    INNER JOIN Product ON Product.ProdId = Cart.ProdId
    LEFT JOIN SpecialSale ON SpecialSale.ProdId = Cart.ProdId
    WHERE CustId=$customerid";
  $res = mysql_query($sql);
  $total = 0;
  while($row = mysql_fetch_array($res)) {
    echo '<tr><td>'.$row['ProdName'].'</td><td align="right">';
    if ($row['SalePrice']) {
      echo '<del>'.$row['ProdPrice'].'</del> '.$row['SalePrice'];
    } else echo $row['ProdPrice'];
    echo'</td><td>'.$row['ProdQty'].'</td>';
    if ($row['SalePrice']) {
      $subtotal = $row['SalePrice']*$row['ProdQty'];
      $total += $row['SalePrice']*$row['ProdQty'];
    } else {
      $subtotal = $row['ProdPrice']*$row['ProdQty'];
      $total += $row['ProdPrice']*$row['ProdQty'];
    }
    echo '<td align="right">'.$subtotal.'</td></tr>';
  }
  mysql_close($con);
} else {
  $cart = $_SESSION['cart'];
  $total = 0;
  if ($cart) {
  foreach ($cart as $entry) {
    list($productid,$productqty) = $entry;
    $res = mysql_query("SELECT ProdName,ProdPrice,SalePrice FROM Product
      LEFT JOIN SpecialSale ON Product.ProdId=SpecialSale.ProdId WHERE Product.ProdId = $productid");
    $row = mysql_fetch_array($res);
    echo '<tr><td>'.$row['ProdName'].'</td><td align="right">';
    if ($row['SalePrice']) {
      echo '<del>'.$row['ProdPrice'].'</del> '.$row['SalePrice'];
    } else echo $row['ProdPrice'];
    echo '</td><td>'.$productqty.'</td>';
    if ($row['SalePrice']) {
      $subtotal = $row['SalePrice']*$productqty;
      $total += $row['SalePrice']*$productqty;
    } else {
      $subtotal = $row['ProdPrice']*$productqty;
      $total += $row['ProdPrice']*$productqty;
    }
    echo '<td align="right">'.$subtotal.'</td></tr>';
  }
  }
}
?>
</table>
<?php
echo '<p style="font-size:1.5em;">Your Grand Total is $<span id="total">'.$total.'</span></p>';
$_SESSION['carttotal'] = $total;
?>
<p><input type="submit" value="Check out"/><span style="margin: 0 10px;"></span>
<input type="submit" value="Edit cart" formaction="/customer/editcart.php"/><span style="margin: 0 10px;"></span>
<input type="submit" value="Delete cart" formaction="/customer/dodelcart.php"/><span style="margin: 0 10px;"></span>
<input type="submit" value="Continue shopping" formaction="/index.php"/></p>
</form>
</div>
</body>
</html>