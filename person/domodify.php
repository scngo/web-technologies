<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='A') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$eId = $_POST['empId'];
if ($eId == null || $eId == '') $errmsg .= 'Invalid employee ID. ';

$eFname = $_POST['empFname'];
if ($eFname == null || $eFname == '') $errmsg .= 'First name must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$eFname)) $errmsg .= 'First name contains special characters. ';

$eLname = $_POST['empLname'];
if ($eLname == null || $eLname == '') $errmsg .= 'Last name must be filled out. ';
elseif (!preg_match("/^[A-Za-z-' ]+$/",$eLname)) $errmsg .= 'Last name contains special characters. ';

$eAge = $_POST['empAge'];
if ($eAge == null || $eAge == '') $errmsg .= 'Age must be filled out. ';
elseif (!preg_match("/^[0-9]+$/",$eAge)) $errmsg .= 'Invalid age range. ';

$eDOB = $_POST['empDOB'];
if ($eDOB == null || $eDOB == '') $errmsg .= 'Date of birth must be filled out. ';
elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/",$eDOB)) $errmsg .= 'Invalid date of birth format. ';
else {
  list($year, $month, $day) = explode('-', $eDOB);
  if (!checkdate ($month,$day,$year)) $errmsg .= 'Invalid date of birth range. ';
}

$eInOff = $_POST['empInOff'];
if ($eInOff == null || $eInOff == '') $errmsg .= 'First day in office must be filled out. ';
elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/",$eInOff)) $errmsg .= 'Invalid date format. ';
else {
  list($year, $month, $day) = explode('-', $eInOff);
  if (!checkdate ($month,$day,$year)) $errmsg .= 'Invalid date range. ';
}

$eSalary = $_POST['empSalary'];
if ($eSalary == null || $eSalary == '') $errmsg .= 'Salary must be filled out. ';
elseif (!preg_match("/^[0-9.]+$/",$eSalary)) $errmsg .= 'Invalid salary range. ';

$eUName = $_POST['empUName'];
if ($eUName == null || $eUName == '') $errmsg .= 'User name be filled out. ';
elseif (!preg_match("/^[A-Za-z]+$/",$eUName)) $errmsg .= 'User name contains special characters. ';

$ePword = $_POST['empPword'];
if ($ePword == null || $ePword == '') $errmsg .= 'Password be setup. ';

$eUtype = $_POST['empUtype'];
if ($eUtype == null || $eUtype == '') $errmsg .= 'User type unspecified. ';

switch ($eUtype) {
  case 'A':
  $eType = 'Admin';
  break;
  case 'M':
  $eType = 'Manager';
  break;
  case 'E':
  $eType = 'Sales';
  break;
}

if (!$errmsg) {
  mysql_query("UPDATE Employee SET LastName='$eLname', FirstName='$eFname', Age=$eAge, DateOfBirth='$eDOB',
    DateInOffice='$eInOff', EmpType='$eType', Salary=$eSalary WHERE EmpId='$eId'");
  mysql_query("UPDATE User SET Username='$eUName', Password=password('$ePword'), Usertype='$eUtype'
    WHERE EmpId='$eId'");
  mysql_close($con);
  echo '<script type="text/javascript">';
  echo 'alert("Employee profile modified!")';
  echo '</script>';
  require '../admin.php';
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "../admin.php";
}
?>