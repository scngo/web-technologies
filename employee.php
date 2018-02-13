<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: login.php");
$_SESSION['timeout'] = time();
include 'mysqllogin.php';
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Employee's Homepage</title>
<style type="text/css">
  body {
    background-color: #cccccc;
  }
  div {
    margin:0 auto;
    width: 800px;
    border:thick solid Blue;
    padding:.5in;
    background: #eeeeff;
    background-image: -webkit-linear-gradient(top, #eeeeff, #bbbbff);
    background-image: -moz-linear-gradient(top, #eeeeff, #bbbbff);
    background-image: -ms-linear-gradient(top, #eeeeff, #bbbbff);
    background-image: -o-linear-gradient(top, #eeeeff, #bbbbff);
    background-image: linear-gradient(to bottom, #eeeeff, #bbbbff);
    -webkit-border-radius: 30;
    -moz-border-radius: 30;
    border-radius: 30px;
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
<?php
$username = $_SESSION['username'];
$res = mysql_query("SELECT FirstName, LastName FROM Employee,User 
  WHERE Username='$username' AND Employee.EmpId=User.EmpId");
$row = mysql_fetch_array($res);
echo "<h1>Hello, " . $row['FirstName'] ."&nbsp;". $row['LastName'] . "!</h1>";
mysql_close($con);
?>
  <table>
    <tr><td><h3>Categories:</h3></td><td>
      <form action="/category/listcate.php" method="post">
        <input type="submit" name="showproduct" value="View"/>
      </form>
      <form action="/category/addchange.php" method="post">
        <input type="submit" name="addproduct" value="Add"/>
      </form>
      <form action="/category/changedelete.php" method="post">
        <input type="submit" name="changedelete" value="Change"/>
        <input type="submit" name="changedelete" value="Delete"/>
      </form></td></tr>
    <tr height="15px"></tr>
    <tr><td><h3>Products:</h3></td><td>
      <form action="/product/listprod.php" method="post">
        <input type="submit" name="showproduct" value="View"/>
      </form>
      <form action="/product/addchange.php" method="post">
        <input type="submit" name="addproduct" value="Add"/>
      </form>
      <form action="/product/changedelete.php" method="post">
        <input type="submit" name="changedelete" value="Change"/>
        <input type="submit" name="changedelete" value="Delete"/>
      </form></td></tr>
    <tr height="15px"></tr>
    <tr><td><h3>Special Sales:</h3></td><td>
      <form action="/special/listsale.php" method="post">
        <input type="submit" name="showproduct" value="View"/>
      </form>
      <form action="/special/addsel.php" method="post">
        <input type="submit" name="addproduct" value="Add"/>
      </form>
      <form action="/special/changedelete.php" method="post">
        <input type="submit" name="changedelete" value="Change"/>
        <input type="submit" name="changedelete" value="Delete"/>
      </form></td></tr>
    <tr height="15px"></tr>
    <tr><td colspan="2" align="right" method="post">
      <form action="/logout.php">
         <input type="submit" name="logout" style="font-size: 15px;padding: 5px 10px 5px 10px;" value="Logout"/>
      </form></td></tr>
  </table>
</div>
</body>
</html>