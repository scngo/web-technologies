<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$customerid = $_SESSION['customerid'];
$res = mysql_query("SELECT * FROM Customer WHERE CustId='$customerid'");
$row = mysql_fetch_array($res);
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
  input[type=submit] {
    background: Tan;
    color: Brown;
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
  button {
    background: Tan;
    color: Brown;
    font-size: 15px;
    padding: 5px 10px 5px 10px;
    text-decoration: none;
  }
</style>
</head>
<body>
<div>
<h1 align="center">Tyanshan-sky Travel Service</h1>  
<h3 align="center">Update Your Profile</h3>
<form id="customer" method="post" onsubmit="return onSubmitAction()">
<table>
  <tr><td colspan="4"><h4>Personal</h4></td></tr>
  <tr style="height: 2.5em;"><td>First name: </td>
  <td><input type="text" name="fname" id="fname" value="<?php echo $row['FirstName'];?>"/></td>
  <td>Last name: </td>
  <td><input type="text" name="lname" id="lname" value="<?php echo $row['LastName'];?>"/></td></tr>
  <tr style="height: 2.5em;"><td>Date of birth: </td>
  <td><input type="date" name="DOB" id="DOB" value="<?php echo $row['DateOfBirth'];?>"/></td>
  <td>Sex: </td><td>
    <input type="radio" name="sex" value="Male" <?php if ($row['Sex']=='Male') echo 'checked';?>>Male
    <input type="radio" name="sex" value="Female" <?php if ($row['Sex']=='Female') echo 'checked';?>>Female
    <input type="radio" name="sex" value="Unknown" onClick="window.alert('Please visit us!');" 
    <?php if ($row['Sex']=='Unknown') echo 'checked';?>>Unknown</td></tr>
  <tr><td colspan="4"><span style="color:grey;font-size:0.8em">(In format YYYY-MM-DD)</span></td></tr>

  <tr><td colspan="4"><h4>Contacts</h4></td></tr>
  <tr style="height: 2.5em;"><td>Street address: </td>
  <td><input type="text" name="addstreet" id="addstreet" style="width:250px;"
      value="<?php echo $row['AddStreet'];?>"></td>
  <td>City, State: </td><td>
      <input type="text" name="addcity" id="addcity" value="<?php echo $row['AddCity'];?>">
      <span style="margin: 0 10px;"></span>
      <select name="addstate" id="addstate">
        <option value="AZ" <?php if ($row['AddState']=='AZ') echo 'selected';?>>Arizona</option>
        <option value="CA" <?php if ($row['AddState']=='CA') echo 'selected';?>>California</option>
        <option value="ID" <?php if ($row['AddState']=='ID') echo 'selected';?>>Idaho</option>
        <option value="NV" <?php if ($row['AddState']=='NV') echo 'selected';?>>Nevada</option>
        <option value="OR" <?php if ($row['AddState']=='OR') echo 'selected';?>>Oregon</option>
        <option value="UT" <?php if ($row['AddState']=='UT') echo 'selected';?>>Utah</option>
        <option value="WA" <?php if ($row['AddState']=='WA') echo 'selected';?>>Washington</option>
      </select></td></tr>
    <tr style="height: 2.5em;"><td>Phone number: </td>
    <td><input type="text" name="pnum" id="pnum" value="<?php echo $row['Phone'];?>"></td>
    <td>E-mail: </td><td><input type="text" name="email" id="email" value="<?php echo $row['Email'];?>"></td></tr>
  </table>
  <br>
    <p align="center"><input type="submit" value="Update" onclick="document.pressed=this.value" /><span style="margin: 0 30px;"></span>
    <input type="submit" value="Cancel" onclick="document.pressed=this.value" ></p>
</form>
</body>
<script type="text/javascript">
  function onSubmitAction() {
    if(document.pressed == 'Update') {
      document.getElementById('customer').action ="/customer/doupdateinfo.php";
      return checkForm();
    } else if (document.pressed == 'Cancel') {
      document.getElementById('customer').action ="/customer.php";
      return true;
    }
  }
  function checkForm() {
    if (checkNames(document.getElementById('fname')) && checkNames(document.getElementById('lname'))
      && checkRB(document.getElementsByName('sex')) && checkDate(document.getElementById('DOB'))
      && checkNum(document.getElementById('pnum')) && checkEmail(document.getElementById('email'))) {
      if (checkAddr(document.getElementById('addstreet'))
        && checkAddr(document.getElementById('addcity')) && checkSl(document.getElementById('addstate'))) {
        return true;
      } else {
        alert('Address not completed');
        return false;
      }
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
  function checkNum(x) {
    var numChk = /^[0-9]+$/;
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
  function checkRB (x) { //Radio button abd Check box
    rbValue = false;
    for (i=0;i<x.length;i++) {
      rbValue = rbValue || x[i].checked;
    }
    return rbValue;
  }
  function checkSl (x) { //Selection list
    slValue = false;
    for (i=0;i<x.length;i++) {
      slValue = slValue || x[i].selected;
    }
    return slValue;
  }
  function checkEmail(x) {
    var emlChk = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
    if (x.value == null || x.value == "") {
      window.alert("E-mail must be filled out");
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(emlChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert("Not a valid e-mail address");
      x.style.border = "solid red";
      return false;
    }
  }
  function checkAddr(x) {
    var nameChk = /^[A-Za-z0-9-' .,]+$/;
    if (x.value == null || x.value == "") {
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(nameChk)) {
      x.style.border = '';
      return true;
    } else {
      x.style.border = "solid red";
      return false;
    }
  }
</script>
</html>