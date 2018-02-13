<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
$customerid = $_SESSION['customerid'];
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
  <h3 align="center">Change password</h3>
  <form id="change" method="post" onsubmit="return onSubmitAction()">
    <input type="hidden" name="customerid" value="<?php echo $customerid;?>">
    <table>
      <tr><td>Old password: </td><td> <input type="password" name="oldpassword" id="oldpassword"/> </td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr><td>New password: </td><td> <input type="password" name="password1" id="password1"/> </td></tr>
      <tr><td>Confirm new password: </td><td> <input type="password" name="password2" id="password2"/> </td></tr>
    </table>
    <p><input type="submit" value="Submit" onclick="document.pressed=this.value"><span style="margin: 0 30px;"></span>
    <input type="submit" value="Cancel" onclick="document.pressed=this.value"/></p>
  </form>
</div>
</body>
<script type="text/javascript">
function onSubmitAction() {
  if(document.pressed == 'Submit') {
    document.getElementById('change').action ="/customer/dochangepw.php";
    return checkPassword();
  } else if (document.pressed == 'Cancel') {
    document.getElementById('change').action ="/customer.php";
    return true;
  }
}
function checkPassword() {
  var password1 = document.getElementById('password1').value;
  var password2 = document.getElementById('password2').value;
  if (oldpassword == null || oldpassword == "" || password1 == null || password1 == "" || password2 == null || password2 == "") {
    alert('Passwords are blank!');
    return false;
  } else if (password1!=password2) {
    alert('Please enter same passwords in the two fields!');
    return false;
  } else return true;
}
</script>
</html>