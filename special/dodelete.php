<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$sid = $_POST['saleid'];
foreach ($sid as $value) {
  if ($value == null || $value == '') $errmsg = 'Invalid Product ID';
}

if (!$errmsg) {
  foreach ($sid as $value) {
    mysql_query("DELETE FROM SpecialSale WHERE SpslId=$value");
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