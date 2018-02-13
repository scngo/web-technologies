<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$sPid = $_POST['prodId'];
$res = mysql_query("SELECT ProdName FROM Product WHERE ProdId=$sPid");
if (!($row = mysql_fetch_array($res))) $errmsg .= 'Invalid Product. ';

$sPrice = $_POST['salePrice'];
if ($sPrice == null || $sPrice == '') $errmsg .= 'Price must be filled out. ';
elseif (!preg_match("/^[0-9.]+$/",$sPrice)) $errmsg .= 'Invalid price. ';

$sSdate = $_POST['saleSdate'];
if ($sSdate == null || $sSdate == '') $errmsg .= 'Date of birth must be filled out. ';
elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/",$sSdate)) $errmsg .= 'Invalid date of birth format. ';
else {
  list($year, $month, $day) = explode('-', $sSdate);
  if (!checkdate ($month,$day,$year)) $errmsg .= 'Invalid date of birth range. ';
}

$sEdate = $_POST['saleEdate'];
if ($sEdate == null || $sEdate == '') $errmsg .= 'Date of birth must be filled out. ';
elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/",$sEdate)) $errmsg .= 'Invalid date of birth format. ';
else {
  list($year, $month, $day) = explode('-', $sEdate);
  if (!checkdate ($month,$day,$year)) $errmsg .= 'Invalid date of birth range. ';
}

if ($sEdate<$sSdate) $errmsg .= 'End date is before start date. ';

if (!$errmsg) {
  mysql_query("INSERT INTO SpecialSale (ProdId, SalePrice, StartDate, EndDate)
    VALUES ('$sPid', '$sPrice', '$sSdate', '$sEdate')");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("New sale created!")';
  echo '</script>';
  require '../employee.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>