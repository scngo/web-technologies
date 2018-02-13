<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
if (isset($_SESSION['customerid'])) $customerid = $_SESSION['customerid'];
$page = $_SESSION['page'];
$productid = $_POST['productid'];
$productqty = $_POST['productqty'];
if ($productqty<=0) header("Location: /index.php");

include '../mysqllogin.php';
$res1 = mysql_query("SELECT ProdName FROM Product WHERE ProdId=$productid");
$row1 = mysql_fetch_array($res1);
if ($customerid) {
  $res2 = mysql_query("SELECT ProdQty FROM Cart WHERE CustId=$customerid AND ProdId=$productid");
  if ($row2 = mysql_fetch_array($res2)) {
    $newqty = $row2['ProdQty'] + $productqty;
    mysql_query("UPDATE Cart SET ProdQty=$newqty WHERE CustId=$customerid AND ProdId=$productid");
  } else {
    mysql_query("INSERT INTO Cart (ProdId,ProdQty,CustId) VALUES ($productid, $productqty, $customerid)");
  }
} else {
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = array();
  $newitem = true;
  for ($i=0; $i<count($_SESSION['cart']); $i++) {
    if ($_SESSION['cart'][$i][0] == $productid) {
      $_SESSION['cart'][$i][1] += $productqty;
      $newitem = false;
    }
  }
  if ($newitem) array_push($_SESSION['cart'], array($productid,$productqty));
}
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
<h3 align="center">New Items in Cart</h3>
<form method="post" action="/index.php">
<p class="info" align="right">
<?php
if (isset($customerid)) {
  $customerid = $_SESSION['customerid'];
  $res0 = mysql_query("SELECT FirstName FROM Customer WHERE CustId='$customerid'");
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
<p align="center">You have added <?php echo $row1['ProdName']; ?> <br>
  of quantity <?php echo $productqty; ?> to the shopping cart</p>
<p><input type="submit" value="Shopping Cart" formaction="/customer/showcart.php">
<span style="margin: 0 30px;"></span>
<input type="submit" value="Continue Shopping"></p>
<p>
<?php
$res1 = mysql_query("SELECT count(OD2.ProdId), OD2.ProdId, ProdName FROM OrderDetail OD1,OrderDetail OD2, Product
  WHERE OD1.OrderId=OD2.OrderId AND Product.ProdId=OD2.ProdId AND OD1.ProdId=$productid AND OD2.ProdId!=$productid
  GROUP BY OD2.ProdId ORDER BY count(OD2.ProdId) DESC");
  if ($row1 = mysql_fetch_array($res1)) {
    echo '<span style="color:red;">People also buy it together with: </span>';
    echo $row1['ProdName'].'&nbsp;<button type="submit" name="productid" value="'.$row1['ProdId'].'" 
    formaction="/customer/showitem.php">Check this out</button><br>&nbsp;&nbsp;&nbsp;';
  }
mysql_close($con);
?>
</p>
</form>
</div>
</body>
</html>