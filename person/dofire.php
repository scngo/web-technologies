<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='A') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$eid = $_POST['employeeid'];
foreach ($eid as $value) {
  if ($value == null || $value == '') $errmsg = 'Invalid Product ID';
}

if (!$errmsg) {
  foreach ($eid as $value) {
    mysql_query("DELETE FROM User WHERE EmpId=$value");
    mysql_query("DELETE FROM Employee WHERE EmpId=$value");
  }
  mysql_close($con);
  require '../admin.php';
  echo '<script type="text/javascript">';
  echo 'alert("Employee profile deleted!")';
  echo '</script>';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../admin.php";
}
?>