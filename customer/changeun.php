<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
$customerid = $_SESSION['customerid'];
include '../mysqllogin.php';
$res = mysql_query("SELECT UserName FROM Customer WHERE CustId='$customerid'");
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
  p.info {
    font-family:cursive;
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
  <div align="center">
  <h1 align="center">Tyanshan-sky Travel Service</h1>
  <h3 align="center">Change User Name</h3>
  <form id="change" method="post" onsubmit="return onSubmitAction()">
    <input type="hidden" name="customerid" value="<?php echo $customerid;?>">
    <table>
      <tr><td>Present user name: </td><td> <?php echo $row['UserName'];?> </td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr><td>New user name: </td><td> <input type="text" name="username" id="username"/> </td></tr>
    </table>
    <p><input type="submit" value="Submit" onclick="document.pressed=this.value"><span style="margin: 0 30px;"></span>
    <input type="submit" value="Cancel" onclick="document.pressed=this.value"/></p>
  </form>
</div>
</body>
<script type="text/javascript">
function onSubmitAction() {
  if(document.pressed == 'Submit') {
    document.getElementById('change').action ="/customer/dochangeun.php";
    return checkUsername();
  } else if (document.pressed == 'Cancel') {
    document.getElementById('change').action ="/customer.php";
    return true;
  }
}
function checkUsername() {
  var usernameChk = /^[a-z]+$/;
  var username = document.getElementById('username').value;
  if (username == null || username == "") {
    alert('User name is blank!');
    return false;
  } else if (username.match(usernameChk)) {
    return true;
  } else {
    alert('Username can only contain lower case alphabets (a to z)');
    return false;
  }
}
</script>
</html>