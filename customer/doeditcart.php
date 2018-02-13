<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
if (isset($_SESSION['customerid'])) $customerid = $_SESSION['customerid'];

$productid = $_POST['productid'];
$productqty = $_POST['productqty'];

if ($customerid) {
  include '../mysqllogin.php';
  for ($x=0; $x<count($productid); $x++) {
    if ($productqty[$x]==0) {
      mysql_query("DELETE FROM Cart WHERE CustId=$customerid AND ProdId=$productid[$x]");
    } else {
      mysql_query("UPDATE Cart SET ProdQty=$productqty[$x] WHERE CustId=$customerid AND ProdId=$productid[$x]");
    }
  }
  mysql_close($con);
} else {
  unset($_SESSION['cart']);
  $_SESSION['cart'] = array();
  for ($i=0; $i<count($productqty); $i++) {
    if ($productqty[$i]!=0) {
      array_push($_SESSION['cart'], array($productid[$i],$productqty[$i]));
    }
  }
}
require './showcart.php';
?>