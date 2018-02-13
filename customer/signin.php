<?php
//ini_set('display_errors', 'On');
session_start();
$_SESSION['timeout'] = time();
$username = $_POST['username'];
$password = $_POST['password'];
$page = $_SESSION['page'];

$errmsg = '';
if (strlen($username)==0 && strlen($password)==0) $errmsg = '';
elseif (strlen($username)==0 || strlen($password)==0) $errmsg = 'Username or password is blank!!';
else {
  include '../mysqllogin.php';
  $res = mysql_query("SELECT CustId FROM Customer WHERE Username='$username' AND Password=password('$password')");
  if (!($row = mysql_fetch_array($res))) $errmsg = 'Invalid login!!!';
}
if (strlen($errmsg)>0) {
  require './presignin.html';
  echo '<h3 align="center">'.$errmsg.'</h3>';
  require './postsignin.html';
} elseif (!$res) {
  //Blank login
  require './presignin.html';
  require './postsignin.html';
} else {
  //Login succeeded
  $_SESSION['customerid'] = $row['CustId'];
  $customerid = $_SESSION['customerid'];
  $cart = $_SESSION['cart'];
  if ($cart) {
  foreach ($cart as $entry) {
    list($productid,$productqty) = $entry;
    $res2 = mysql_query("SELECT ProdQty FROM Cart WHERE CustId=$customerid AND ProdId=$productid");
    if ($row2 = mysql_fetch_array($res2)) {
      $newqty = $row2['ProdQty'] + $productqty;
      mysql_query("UPDATE Cart SET ProdQty=$newqty WHERE CustId=$customerid AND ProdId=$productid");
    } else {
      mysql_query("INSERT INTO Cart (ProdId,ProdQty,CustId) VALUES ($productid, $productqty, $customerid)");
    }
  }
  }
  unset($_SESSION['cart']);
  switch ($page) {
    case 'pay':
    header("Location: /customer/pay.php");
    break;
    case 'editcart':
    header("Location: /customer/editcart.php");
    break;
    case 'showcart':
    header("Location: /customer/showcart.php");
    break;
    default:
    header("Location: /index.php");
  }
  unset($page);
  mysql_close($con);
}
?>