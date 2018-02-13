<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css">
  body {
    background-color: #dddddd;
  }
  div {
    margin:0 auto;
    width: 800px;
    border:thick solid SkyBlue;
    padding:.5in;
    background: #fcfcfc;
    -webkit-border-radius: 30;
    -moz-border-radius: 30;
    border-radius: 30px;
  }
  h1 {
    font-family:sans-serif;
    text-shadow: 5px 5px 5px #FF00FF;
  }
  form {
    display: inline-block;
  }
  input[type=submit] {
    background: Tan;
    color: Brown;
    font-size: 20px;
    padding: 10px 20px 10px 20px;
    text-decoration: none;
  }
</style>
</head>
<body>
<div align="center">
  <h1 align="center">Tyanshan-sky Travel Service</h1>
  <table>
    <tr><td><h3>My Account:</h3></td><td>
      <form action="/customer/changeun.php" method="post">
        <input type="submit" value="Change User Name"/>
      </form>
      <form action="/customer/changepw.php" method="post">
        <input type="submit" value="Change Password"/>
      </form></td></tr>
    <tr><td><h3>My Profile:</h3></td><td>
      <form action="/customer/showinfo.php" method="post">
        <input type="submit" value="View Profile"/>
      </form>
      <form action="/customer/updateinfo.php" method="post">
        <input type="submit" value="Update Profile"/>
      </form></td></tr>
    <tr><td><h3>My Orders:</h3></td><td>
      <form action="/customer/showcart.php" method="post">
        <input type="submit" value="Shopping Cart"/>
      </form>
      <form action="/customer/showorder.php" method="post">
        <input type="submit" value="Ordering History"/>
      </form></td></tr>
    <tr height="15px"></tr>
    <tr><td colspan="2" align="right" method="post">
      <form action="/index.php">
         <input type="submit" style="font-size: 15px;padding: 5px 10px 5px 10px;" value="Return"/>
      </form></td></tr>
  </table>
</div>
</body>
</html>