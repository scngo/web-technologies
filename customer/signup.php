<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  session_start();
}
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
</head>
<body>
<div>
<h1 align="center">Tyanshan-sky Travel Service</h1>
<h3 align="center">New Customer Sign Up</h3>
<form id="customer" method="post" onsubmit="return onSubmitAction()">
<table>
  <tr><td colspan="4"><h4>Account</h4></td></tr>
  <tr style="height: 2.5em;"><td>User name: </td><td><input type="text" name="username" id="username"></td></tr>
  <tr style="height: 2.5em;"><td>Password: </td><td><input type="password" name="password1" id="password1"></td>
    <td>Password: </td><td><input type="password" name="password2" id="password2">
    <span style="color:grey;font-size:0.8em">(Confirmation)</span></td></tr>

  <tr><td colspan="4"><h4>Personal</h4></td></tr>
  <tr style="height: 2.5em;"><td>First name: </td>
  <td><input type="text" name="fname" id="fname" onBlur="checkNames(document.getElementById('fname'));"></td>
  <td>Last name: </td>
  <td><input type="text" name="lname" id="lname" onBlur="checkNames(document.getElementById('lname'));"></td></tr>
  <tr style="height: 2.5em;"><td>Date of birth: </td>
  <td><input type="date" name="DOB" id="DOB" onBlur="checkDate(document.getElementById('DOB'));"></td>
  <td>Sex: </td><td>
    <input type="radio" name="sex" value="Male">Male
    <input type="radio" name="sex" value="Female">Female
    <input type="radio" name="sex" value="Unknown" onClick="window.alert('Please visit us!');">Unknown</td></tr>
  <tr><td colspan="4"><span style="color:grey;font-size:0.8em">(In format YYYY-MM-DD)</span></td></tr>

  <tr><td colspan="4"><h4>Contacts</h4></td></tr>
  <tr style="height: 2.5em;"><td>Street address: </td>
  <td><input type="text" name="addstreet" id="addstreet" style="width:250px;"
      onBlur="checkAddr(document.getElementById('addstreet'));"></td>
  <td>City, State: </td><td>
      <input type="text" name="addcity" id="addcity" onBlur="checkAddr(document.getElementById('addcity'));">
      <span style="margin: 0 10px;"></span>
      <select name="addstate" id="addstate">
        <option value="AZ">Arizona</option>
        <option value="CA">California</option>
        <option value="ID">Idaho</option>
        <option value="NV">Nevada</option>
        <option value="OR">Oregon</option>
        <option value="UT">Utah</option>
        <option value="WA">Washington</option>
      </select>
      <script type="text/javascript">
        document.getElementById("addstate").selectedIndex = -1;
      </script></td></tr>
    <tr style="height: 2.5em;"><td>Phone number: </td>
    <td><input type="text" name="pnum" id="pnum" onBlur="checkNum(document.getElementById('pnum'));"></td>
    <td>E-mail: </td><td><input type="text" name="email" id="email" onBlur="checkEmail(document.getElementById('email'));"></td></tr>
  </table>
  <br>
    <p align="center"><input type="submit" onclick="document.pressed=this.value" value="Sign up"><span style="margin: 0 30px;">
    </span><input type="button" value="Reset" onClick="document.getElementById('customer').reset();"/>
    <span style="margin: 0 30px;"></span><input type="submit" onclick="document.pressed=this.value" value="Cancel"/></p>
</form>
</body>
<script type="text/javascript">
  function onSubmitAction() {
    if(document.pressed == 'Sign up') {
      document.getElementById('customer').action ="/customer/dosignup.php";
      return checkForm();
    } else if (document.pressed == 'Cancel') {
      document.getElementById('customer').action ="/index.php";
      return true;
    }
  }
  function checkForm() {
    if (checkUsername() && checkPassword()
      && checkNames(document.getElementById('fname')) && checkNames(document.getElementById('lname'))
      && checkRB(document.getElementsByName('sex')) && checkDate(document.getElementById('DOB'))
      && checkNum(document.getElementById('pnum')) && checkEmail(document.getElementById('email'))) {
      if (checkAddr(document.getElementById('addstreet'))
        && checkAddr(document.getElementById('addcity')) && checkSl(document.getElementById('addstate'))) {
        alert('You profile is submitted. Please log in with your account!');
        return true;
      } else {
        alert('Address not completed');
        return false;
      }
    } else return false;
  }
  function checkUsername() {
    var x = document.getElementById('username');
    var usernameChk = /^[A-Za-z]+$/;
    if (x.value == null || x.value == "") {
      window.alert("User name must be filled out");
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(usernameChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert("User name contains special characters");
      x.style.border = "solid red";
      return false;
    }
  }
  function checkPassword() {
    var password1 = document.getElementById('password1').value;
    var password2 = document.getElementById('password2').value;
    if (password1 == null || password1 == "" || password2 == null || password2 == "") {
      window.alert("Passwords must be filled out");
      x.style.border = "solid red";
      return false;
    } else if (password1 != password2) {
      window.alert("Two passwords must be identical");
      return false;
    } else return true;
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