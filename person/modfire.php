<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='A') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
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
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
  table.gridtable {
    font-family: arial,sans-serif;
    font-size:12px;
    color:#333333;
    border-width: 1px;
    border-color: #666666;
    border-collapse: collapse;
  }
  table.gridtable th {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #666666;
    background-color: #cccccc;
  }
  table.gridtable td {
    border-width: 1px;
    padding: 8px;
    border-style: solid;
    border-color: #666666;
    background-color: #dddddd;
  }
  </style>
</head>
<body>
<div align="center">
<?php
$cdset = $_POST['modfire'];
switch ($cdset) {
  case 'Modify':
  echo '<h1 align="center">Modify personal profile</h1>';
  echo '<form action="hiremod.php" method="post">';
  break;
  case 'Fire':
  echo '<h1 align="center">Delete profiles</h1>';
  echo '<form action="dofire.php" method="post">';
  break;
}
?>
  <table class="gridtable">
  <tr><th> </th><th>Last name</th><th>First name</th><th>Age</th><th>Date of birth</th>
    <th>In office since</th><th>Salary</th><th>User name</th><th>User type</th></tr>
<?php
$res = mysql_query("SELECT Employee.EmpId,FirstName,LastName,Age,DateOfBirth,DateInOffice,Salary,UserName,UserType 
    FROM Employee,User WHERE Employee.EmpId=User.EmpId");
while($row = mysql_fetch_array($res)) {
  switch ($cdset) {
    case 'Modify':
    echo '<tr><td><input type="radio" name="employeeid" value="'.$row['EmpId'].'"></td>';
    break;
    case 'Fire':
    echo '<tr><td><input type="checkbox" name="employeeid[]" value="'.$row['EmpId'].'"></td>';
    break;
  }
  echo "<td>".$row['FirstName']."</td><td>".$row['LastName']."</td><td>".$row['Age']."</td><td>".$row['DateOfBirth']."</td>";
  echo "<td>".$row['DateInOffice']."</td><td>".$row['Salary']."</td><td>".$row['UserName']."</td><td>".$row['UserType']."</td></tr>";
}
?>
</table>
<p>
<?php
echo '<input type="submit" value="';
switch ($cdset) {
  case 'Modify':
  echo 'Modify';
  break;
  case 'Fire':
  echo 'Fire';
  break;
}
echo '"/>';
mysql_close($con);
?>
<span style="margin: 0 20px;"></span><input type="submit" formaction="../admin.php" value="Cancel"/></p>
</form>
</div>
</body>
</html>