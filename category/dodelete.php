<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$cid = $_POST['categoryid'];
foreach ($cid as $value) {
  if ($value == null || $value == '') $errmsg = 'Invalid Category ID';
  $res = mysql_query("SELECT ProdCateId FROM Product WHERE ProdCateId=$value");
  if ($row = mysql_fetch_array($res)) $errmsg = 'There are products under this category!!';
}

if (!$errmsg) {
  foreach ($cid as $value) {
    mysql_query("DELETE FROM ProdCate WHERE ProdCateId=$value");
  }
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Categories deleted!")';
  echo '</script>';
  require '../employee.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>