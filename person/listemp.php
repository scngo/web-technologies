<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || ($_SESSION['usertype']!='A' && $_SESSION['usertype']!='M')) header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
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
  input[type=button] {
    background: Tan;
    color: Brown;
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
</style>
<script type="text/javascript">
function queryEmp() {
  if (window.XMLHttpRequest) xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  xmlhttp.onreadystatechange=handleReply;
  var empName = document.getElementById('empName').value;
  var empAge1 = document.getElementById('empAge1').value;
  var empAge2 = document.getElementById('empAge2').value;
  var empSalary1 = document.getElementById('empSalary1').value;
  var empSalary2 = document.getElementById('empSalary2').value;
  var empType = document.getElementById('empType').value;
  queryString = "empName=" + empName + "&empAge1=" + empAge1 + "&empAge2=" + empAge2;
  queryString += "&empSalary1=" + empSalary1 + "&empSalary2=" + empSalary2 + "&empType=" + empType;
  xmlhttp.open("POST", "queryemp.php", true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send(queryString);
}
function handleReply() {
  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
    document.getElementById("showRes").innerHTML=xmlhttp.responseText;
  }
}
</script>
</head>
<body>
<div align="center">
<form>
  <h2>Search employees</h2>
  <table>
    <tr><td>By name: </td><td><input type="text" id="empName"></td></tr>
    <tr><td rowspan="2">By age: </td><td>Older than <input type="text" id="empAge1"></td></tr>
    <tr><td>Younger than <input type="text" id="empAge2"></td></tr>
    <tr><td rowspan="2">By salary: </td><td>Higher than <input type="text" id="empSalary1"></td></tr>
    <tr><td>Lower than <input type="text" id="empSalary2"></td></tr>
    <tr><td>By type: </td><td><input type="text" id="empType"></td></tr>
  </table>
<p><input type="button" value="Search" onClick="queryEmp()"/><span style="margin: 0 50px;"></span>
<?php
if ($_SESSION['usertype']=='A') echo '<input type="submit" value="Return" formaction="/admin.php">';
if ($_SESSION['usertype']=='M') echo '<input type="submit" value="Return" formaction="/manager.php">';
?>
</p>
</form>
<span id="showRes"></span>
</div>
</body>
</html>