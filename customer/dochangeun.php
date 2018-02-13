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

$username = $_POST['username'];
if ($username == null || $username == '') $errmsg .= 'User must be filled out. ';
elseif (!preg_match("/^[a-z]+$/",$username)) $errmsg .= 'User name contains special characters. ';
else {
  $res1 = mysql_query("SELECT * FROM Customer WHERE UserName=$username");
  if ($row1 = mysql_fetch_array($res1)) $errmsg .= 'This user name is registered. ';
}

if (!$errmsg) {
  mysql_query("UPDATE Customer SET UserName='$username' WHERE CustId=$customerid");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("User name changed!")';
  echo '</script>';
  require '../customer.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "./changeun.php";
}
?>