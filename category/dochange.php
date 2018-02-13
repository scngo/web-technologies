<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$cId = $_POST['cateId'];
$res = mysql_query("SELECT ProdCateId FROM ProdCate WHERE ProdCateId=$cId");
if (!($row = mysql_fetch_array($res))) $errmsg = 'Invalid Category ID!!!';

$cName = $_POST['cateName'];
if ($cName == null || $cName == '') $errmsg = 'Name must be filled out';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$cName)) $errmsg = 'Name contains special characters';

$cDesc = $_POST['cateDesc'];

if (!$errmsg) {
  mysql_query("UPDATE ProdCate SET ProdCateName='$cName', ProdCateDesc='$cDesc' WHERE ProdCateId='$cId'");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Category changed!")';
  echo '</script>';
  require '../employee.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>