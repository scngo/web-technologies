<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$pid = $_POST['prodId'];
if ($pid == null || $pid == '') $errmsg .= 'Invalid Product ID';

$pCateId = $_POST['prodCate'];
$res = mysql_query("SELECT ProdCateName FROM ProdCate WHERE ProdCateId=$pCateId");
if (!($row = mysql_fetch_array($res))) $errmsg .= 'Invalid Category. ';

$pName = $_POST['prodName'];
if ($pName == null || $pName == '') $errmsg .= 'Name must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$pName)) $errmsg .= 'Name contains special characters. ';

$pPrice = $_POST['prodPrice'];
if ($pPrice == null || $pPrice == '') $errmsg .= 'Price must be filled out. ';
elseif (!preg_match("/^[0-9.]+$/",$pPrice)) $errmsg .= 'Invalid price. ';

$pDesc = $_POST['prodDesc'];

$res2 = mysql_query("SELECT Figure FROM Product WHERE ProdId=$pid");
$row2 = mysql_fetch_array($res2);
$pFigure = $row2['Figure'];

if ($_FILES["prodFigure"]) {
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["prodFigure"]["name"]);
$extension = end($temp);
if ((($_FILES["prodFigure"]["type"] == "image/gif") || ($_FILES["prodFigure"]["type"] == "image/jpeg")
  || ($_FILES["prodFigure"]["type"] == "image/jpg") || ($_FILES["prodFigure"]["type"] == "image/pjpeg")
  || ($_FILES["prodFigure"]["type"] == "image/x-png") || ($_FILES["prodFigure"]["type"] == "image/png"))
  && ($_FILES["prodFigure"]["size"] < 500000) && in_array($extension, $allowedExts)) {
  if ($_FILES["prodFigure"]["error"] > 0) $errmsg .= $_FILES["prodFigure"]["error"];
  else {
    if (file_exists("../figure/" . $_FILES["prodFigure"]["name"])) $errmsg .= $_FILES["prodFigure"]["name"] . " already exists. ";
    else {
      unlink($_SERVER['DOCUMENT_ROOT']."$pFigure");
      move_uploaded_file($_FILES["prodFigure"]["tmp_name"], "../figure/" . $_FILES["prodFigure"]["name"]);
      $pFigure = "/figure/" . $_FILES["prodFigure"]["name"];
    }
  }
} else $errmsg .= "Invalid file";
}

if (!$errmsg) {
  mysql_query("UPDATE Product SET ProdCateId=$pCateId, ProdName='$pName',
    ProdPrice=$pPrice, ProdDesc='$pDesc', Figure='$pFigure' WHERE ProdId=$pid");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Product changed!")';
  echo '</script>';
  require '../employee.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../employee.php";
}
?>