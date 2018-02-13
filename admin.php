<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='A') header("Location: login.php");
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
    <tr><td>
      <form action="/person/listemp.php" method="post">
        <input type="submit" name="showemployee" value="View"/>
      </form>
      <form action="/person/hiremod.php" method="post">
        <input type="submit" name="hire" value="Hire"/>
      </form>
      <form action="/person/modfire.php" method="post">
        <input type="submit" name="modfire" value="Modify"/>
        <input type="submit" name="modfire" value="Fire"/>
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