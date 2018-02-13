<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$customerid = $_SESSION['customerid'];
if ($_SESSION['customerid']!=$_POST['customerid']) $errmsg .= 'Invalid user. ';

$oldpassword = $_POST['oldpassword'];
$res1 = mysql_query("SELECT Password FROM Customer WHERE CustId=$customerid AND Password=password('$oldpassword')");
if (!($row1 = mysql_fetch_array($res1))) $errmsg .= 'Wrong old password. ';

if ($_POST['password1']==$_POST['password2']) {
  $newpassword = $_POST['password1'];
  if ($newpassword == null || $newpassword == '') $errmsg .= 'Password must be filled out. ';
} else $errmsg .= 'Passwords not match. ';

if (!$errmsg) {
  mysql_query("UPDATE Customer SET Password=password('$newpassword') WHERE CustId=$customerid");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Password changed!")';
  echo '</script>';
  require '../customer.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "./changepw.php";
}
?>