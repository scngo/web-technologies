<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$customerid = $_SESSION['customerid'];

$fname = $_POST['fname'];
if ($fname == null || $fname == '') $errmsg .= 'First name must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$fname)) $errmsg .= 'First name contains special characters. ';

$lname = $_POST['lname'];
if ($lname == null || $lname == '') $errmsg .= 'Last name must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$lname)) $errmsg .= 'Last name contains special characters. ';

$DOB = $_POST['DOB'];
if ($DOB == null || $DOB == '') $errmsg .= 'Date of birth must be filled out. ';
elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/",$DOB)) $errmsg .= 'Invalid date of birth format. ';
else {
  list($year, $month, $day) = explode('-', $DOB);
  if (!checkdate ($month,$day,$year)) $errmsg .= 'Invalid date of birth range. ';
}

$sex = $_POST['sex'];

$addstreet = $_POST['addstreet'];
if ($addstreet == null || $addstreet == '') $errmsg .= 'Street address must be filled out. ';
elseif (!preg_match("/^[A-Za-z0-9-' .,]+$/",$addstreet)) $errmsg .= 'Street address contains special characters. ';

$addcity = $_POST['addcity'];
if ($addcity == null || $addcity == '') $errmsg .= 'City address must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$addcity)) $errmsg .= 'City address contains special characters. ';

$addstate = $_POST['addstate'];
if ($addstate == null || $addstate == '') $errmsg .= 'State must be filled out. ';
elseif (!preg_match("/^[A-Z]{2}$/",$addstate)) $errmsg .= 'Invalid state name. ';

$pnum = $_POST['pnum'];
if (!preg_match("/^[0-9]+$/",$pnum)) $errmsg .= 'Invalid telephone number. ';

$email = $_POST['email'];
if (!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/",$email)) $errmsg .= 'Invalid email. ';

if (!$errmsg) {
  mysql_query("UPDATE Customer SET LastName='$lname', FirstName='$fname', DateOfBirth='$DOB', Sex='$sex',
    AddStreet='$addstreet', AddCity='$addcity', AddState='$addstate', Phone=$pnum, Email='$email' WHERE CustId=$customerid");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Your profile is updated!")';
  echo '</script>';
  require '../customer.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "./updateinfo.php";
}
?>