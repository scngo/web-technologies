<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  session_start();
}
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$errmsg = '';

$username = $_POST['username'];
if ($username == null || $username == '') $errmsg .= 'User must be filled out. ';
elseif (!preg_match("/^[A-Za-z]+$/",$username)) $errmsg .= 'User name contains special characters. ';
else {
  $res1 = mysql_query("SELECT * FROM Customer WHERE UserName='$username'");
  if ($row1 = mysql_fetch_array($res1)) $errmsg .= 'This user name is registered. ';
}

if ($_POST['password1']==$_POST['password2']) {
  $password = $_POST['password1'];
  if ($password == null || $password == '') $errmsg .= 'Password must be filled out. ';
}
else $errmsg .= 'Passwords not match. ';

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
  mysql_query("INSERT INTO Customer(UserName,Password,LastName,FirstName,DateOfBirth,Sex,AddStreet,AddCity,AddState,Phone,Email)
    VALUES ('$username', password('$password'), '$lname', '$fname', '$DOB', '$sex', 
    '$addstreet', '$addcity', '$addstate', $pnum, '$email')");
  mysql_close($con);
  header("Location: /customer/signin.php");
} else {
  echo '<script type="text/javascript">';
  echo 'alert("'.$errmsg.'")';
  echo '</script>';
  require "./signup.php";
}
?>