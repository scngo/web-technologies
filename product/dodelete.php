<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$pid = $_POST['productid'];
foreach ($pid as $value) {
  if ($value == null || $value == '') $errmsg = 'Invalid Product ID';
  $res = mysql_query("SELECT ProdId FROM SpecialSale WHERE ProdId=$value");
  if ($row = mysql_fetch_array($res)) $errmsg = 'There are special sales under this product!!';
}

if (!$errmsg) {
  foreach ($pid as $value) {
    $res2 = mysql_query("SELECT Figure FROM Product WHERE ProdId=$value");
    $row2 = mysql_fetch_array($res2);
    unlink($_SERVER['DOCUMENT_ROOT'].$row2['Figure']);
    mysql_query("DELETE FROM Product WHERE ProdId=$value");
  }
  mysql_close($con);
  require '../employee.php';
  echo '<script type="text/javascript">';
  echo 'alert("Products deleted!")';
  echo '</script>';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>