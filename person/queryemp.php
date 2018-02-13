<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || ($_SESSION['usertype']!='A' && $_SESSION['usertype']!='M')) header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';

$sql = "SELECT Employee.EmpId,FirstName,LastName,Age,DateOfBirth,DateInOffice,Salary,UserName,UserType 
    FROM Employee,User WHERE Employee.EmpId=User.EmpId";
  if ($eName = mysql_real_escape_string($_POST['empName'])) $sql .= " AND (LastName LIKE '%$eName%' OR FirstName LIKE '%$eName%')";
  if ($empAge1 = mysql_real_escape_string($_POST['empAge1'])) $sql .= " AND Age>=$empAge1";
  if ($empAge2 = mysql_real_escape_string($_POST['empAge2'])) $sql .= " AND Age<=$empAge2";
  if ($eSalary1 = mysql_real_escape_string($_POST['empSalary1'])) $sql .= " AND Salary>=$eSalary1";
  if ($eSalary2 = mysql_real_escape_string($_POST['empSalary2'])) $sql .= " AND Salary<=$eSalary2";
  if ($eType = mysql_real_escape_string($_POST['empType'])) $sql .= " AND UserType LIKE '%$eType%'";
$res = mysql_query("$sql");
mysql_close($con);
?>
<style type="text/css">
  table.gridtable {
    font-size:12px;
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
  <h4>Result</h4>
  <table class="gridtable">
  <tr><th>Last name</th><th>First name</th><th>Age</th><th>Date of birth</th>
    <th>In office since</th><th>Salary</th><th>User name</th><th>User type</th></tr>
<?php
while($row = mysql_fetch_array($res)) {
  echo "<tr><td>".$row['FirstName']."</td><td>".$row['LastName']."</td><td>".$row['Age']."</td><td>".$row['DateOfBirth']."</td>";
  echo "<td>".$row['DateInOffice']."</td><td>".$row['Salary']."</td><td>".$row['UserName']."</td><td>".$row['UserType']."</td></tr>";
}
?>
</table>