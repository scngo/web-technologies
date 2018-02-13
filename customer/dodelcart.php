<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
if (isset($_SESSION['customerid'])) $customerid = $_SESSION['customerid'];

if ($customerid) {
  include '../mysqllogin.php';
  mysql_query("DELETE FROM Cart WHERE CustId=$customerid");
  mysql_close($con);
} else {
  unset($_SESSION['cart']);
}
require '../index.php';
?>