<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$cName = $_POST['cateName'];
if ($cName == null || $cName == '') $errmsg = 'Name must be filled out';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$cName)) $errmsg = 'Name contains special characters';
$res2 = mysql_query("SELECT * FROM ProdCate WHERE ProdCateName='$cName'");
if ($row2 = mysql_fetch_array($res2)) $errmsg .= 'Category name already exists. ';

$cDesc = $_POST['cateDesc'];

if (!$errmsg) {
  mysql_query("INSERT INTO ProdCate(ProdCateName, ProdCateDesc) VALUES ('$cName', '$cDesc')");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("New category added!")';
  echo '</script>';
  require '../employee.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>