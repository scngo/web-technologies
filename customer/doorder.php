<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
$customerid = $_SESSION['customerid'];
include '../mysqllogin.php';
$errmsg = '';

$cardnum = $_POST['cardnum'];
if ($cardnum == null || $cardnum == '') $errmsg .= 'Card number must be filled out. ';
elseif (!preg_match("/^[0-9]{15,16}$/",$cardnum)) $errmsg .= 'Card number contains special characters. ';

$cardsec = $_POST['cardsec'];
if ($cardsec == null || $cardsec == '') $errmsg .= 'Security code must be filled out. ';
elseif (!preg_match("/^[0-9]{3,4}$/",$cardsec)) $errmsg .= 'Security code contains special characters. ';

$addship = $_POST['addship'];
if ($addship == null || $addship == '') $errmsg .= 'Shipping address must be filled out. ';
elseif (!preg_match("/^[A-Za-z0-9-' .,]+$/",$addship)) $errmsg .= 'Shipping address contains special characters. ';

$addbill = $_POST['addbill'];
if ($addbill == null || $addbill == '') $errmsg .= 'Billing address must be filled out. ';
elseif (!preg_match("/^[A-Za-z0-9-' .,]+$/",$addbill)) $errmsg .= 'Billing address contains special characters. ';

if (!$errmsg) {
$res1 = mysql_query("SELECT SUM(ProdQty * IF(SalePrice IS NOT NULL,SalePrice,ProdPrice))
  AS Sum FROM Cart INNER JOIN Product ON Cart.ProdID = Product.ProdID
  LEFT JOIN SpecialSale ON Cart.ProdID = SpecialSale.ProdID WHERE CustId=$customerid");
$row1 = mysql_fetch_array($res1);
$totalcost = $row1['Sum'];
mysql_query("INSERT INTO CustOrder (CustId, OrderDate, TotalCost, CardNum, CardSec, BillAdd, ShipAdd)
  VALUES ($customerid, CURDATE(), $totalcost, $cardnum, $cardsec, '$addbill', '$addship')");

$res2 = mysql_query("SELECT Cart.ProdId,ProdPrice,SalePrice,ProdQty FROM Cart 
    INNER JOIN Product ON Product.ProdId = Cart.ProdId
    LEFT JOIN SpecialSale ON SpecialSale.ProdId = Cart.ProdId
    WHERE CustId=$customerid");
while ($row2 = mysql_fetch_array($res2)) {
  $productid = $row2['ProdId'];
  $productqty = $row2['ProdQty'];
  $sql = "INSERT INTO OrderDetail (ProdId, ProdPrice, ProdQty, OrderId) VALUES ($productid,";
  if ($row2['SalePrice']!=null) $sql .= $row2['SalePrice'];
  else $sql .= $row2['ProdPrice'];
  $sql .= ", $productqty, LAST_INSERT_ID())";
  mysql_query($sql);
}
mysql_query("DELETE FROM Cart WHERE CustId=$customerid");
mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Thank you for the payment!!");';
  echo '</script>';
  require "../index.php";
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "./pay.php";
}
?>