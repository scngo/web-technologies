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
  </style>
</head>
<body>
<div align="center">
<?php
$eid = '';
if (isset($_POST['employeeid'])) {
  $eid = $_POST['employeeid'];
  $res = mysql_query("SELECT FirstName,LastName,Age,DateOfBirth,DateInOffice,Salary,UserName,Password,UserType 
    FROM Employee,User WHERE Employee.EmpId=User.EmpId AND Employee.EmpId=$eid");
  $row = mysql_fetch_array($res);
}
if (!$eid) {
  echo '<h1 align="center">Hire a person</h1>';
  echo '<form id="hiremod" method="post" onsubmit="return onSubmitAction()">';
} else {
  echo '<h1 align="center">Modify personal profile</h1>';
  echo '<form id="hiremod" method="post" onsubmit="return onSubmitAction()">';
}
?>
<input type="hidden" name="empId" value="<?php echo $eid ?>">
  <table>
    <tr><td colspan="4" width="600px"><h3>Personal</h3></td></tr>
    <tr><td>First Name:</td><td><input type="text" name="empFname" id ="empFname" value="<?php echo $row['FirstName'] ?>"></td>
      <td>Last Name:</td><td><input type="text" name="empLname" id ="empLname" value="<?php echo $row['LastName'] ?>"></td></tr>
    <tr><td>Age:</td><td><input type="text" name="empAge" id ="empAge" value="<?php echo $row['Age'] ?>"></td>
      <td>Date of birth:</td><td><input type="date" name="empDOB" id ="empDOB" value="<?php echo $row['DateOfBirth'] ?>"></td></tr>
    <tr><td>In office since:</td><td><input type="date" name="empInOff" id ="empInOff" value="<?php echo $row['DateInOffice'] ?>"></td>
      <td>Salary:</td><td><input type="text" name="empSalary" id ="empSalary" value="<?php echo $row['Salary'] ?>"></td></tr>
    <tr><td colspan="4" width="600px"><span style="color:grey;font-size:0.8em">(Dates in format YYYY-MM-DD)</span></td></tr>
    <tr><td colspan="4" width="600px"><h3>Network</h3></td></tr>
    <tr><td>User name:</td><td><input type="text" name="empUName" id ="empUName" value="<?php echo $row['UserName'] ?>"></td>
      <td>User type:</td><td><input type="text" name="empUtype" id ="empUtype" value="<?php echo $row['UserType'] ?>"></td></tr>
    <tr><td>Password:</td><td><input type="password" name="empPword" id ="empPword"></td>
      <td colspan="2"> password must be reset in every modification</td></tr>
  </table>
<p>
<?php
echo '<input type="submit" onclick="document.pressed=this.value" value="';
if (!$eid) echo 'Hire';
else echo 'Modify';
echo '"/>';
mysql_close($con);
?>
    <span style="margin: 0 20px;"></span><input type="submit" formaction="../admin.php" onclick="document.pressed=this.value" value="Cancel"/></p>
  </form>
</div>
</body>
<script type="text/javascript">
  function onSubmitAction() {
    if(document.pressed == 'Hire') {
        document.getElementById('hiremod').action ="dohire.php";
        return checkForm();
      } else if (document.pressed == 'Modify') {
        document.getElementById('hiremod').action ="domodify.php";
        return checkForm();
      } else if (document.pressed == 'Cancel') {
        document.getElementById('hiremod').action ="../admin.php";
        return true;
      }
    }
  function checkForm() {
    if (checkNames(document.getElementById('empFname')) && checkNames(document.getElementById('empLname'))
      && checkNum(document.getElementById('empAge')) && checkDate(document.getElementById('empDOB'))
      && checkDate(document.getElementById('empInOff')) && checkNum(document.getElementById('empSalary'))) {
      if (checkNames(document.getElementById('empUName')) && checkPass(document.getElementById('empPword'))) {
        return true;
      } else return false;
    } else return false;
  }
  function checkNames(x) {
    var nameChk = /^[A-Za-z-' ]+$/;
    if (x.value == null || x.value == "") {
      window.alert("Name must be filled out");
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(nameChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert("Name contains special characters");
      x.style.border = "solid red";
      return false;
    }
  }
  function checkSl (x) { //Selection list
    slValue = false;
    for (i=0;i<x.length;i++) {
      slValue = slValue || x[i].selected;
    }
    return slValue;
  }
  function checkNum(x) {
    var numChk = /^[0-9.]+$/;
    if (x.value == null || x.value == "") {
      window.alert('Number must be filled out!');
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(numChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert('Invalid number!');
      x.style.border = "solid red";
      return false;
    }
  }
  function checkDate(x) {
    var validformat = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD
    if (validformat.test(x.value)) {
      var yearfield = x.value.split("-")[0];
      var monthfield = x.value.split("-")[1];
      var dayfield = x.value.split("-")[2];
    } else {
      alert('Invalid Date Format.');
      x.style.border = "solid red";
      return false;
    }
    var dayobj = new Date(yearfield, monthfield-1, dayfield);
    if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield)) {
      alert('Invalid Day, Month, or Year range.');
      x.style.border = "solid red";
      return false;
    } else {
      x.style.border = '';
      return true;
    }
  }
  function checkPass(x) {
    if (x.value == null || x.value == "") {
      alert("Password must be setup");
      x.style.border = "solid red";
      return false;
    } else return true;
  }
</script>
</html>