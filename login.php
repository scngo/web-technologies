<?php
//ini_set('display_errors', 'On');
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
$_SESSION['timeout'] = time();
$errmsg = '';
if (strlen($username)==0 && strlen($password)==0) $errmsg = '';
elseif (strlen($username)==0 || strlen($password)==0) $errmsg = 'Username or password is blank!!';
else {
  include 'mysqllogin.php';
  $res = mysql_query("SELECT Usertype FROM User WHERE Username='$username' AND Password=password('$password')");
  if (!($row = mysql_fetch_array($res))) $errmsg = 'Invalid login!!!';
}
if (strlen($errmsg)>0) {
  require 'prelogin.html';
  echo '<h3 align="center">'.$errmsg.'</h3>';
  require 'postlogin.html';
} elseif (!$res) {
  //Blank login
  require 'prelogin.html';
  require 'postlogin.html';
} else {
  //Login succeeded
  $_SESSION['username'] = $username;
  $_SESSION['password'] = $password;
  $_SESSION['usertype'] = mysql_result($res,0);
  switch (mysql_result($res,0)) {
    case 'A':
    header("Location: admin.php");
    break;
    case 'M':
    header("Location: manager.php");
    break;
    case 'E':
    header("Location: employee.php");
    break;
  }
}
?>