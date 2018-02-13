<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['timeout'] = time();
$customerid = $_SESSION['customerid'];
include '../mysqllogin.php';
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
  <h3 align="center">Your Profile</h3>
  <form method="post" action="/customer/updateinfo.php">
    <input type="hidden" name="customerid" value="<?php echo $customerid;?>">
    <table>
      <tr><td colspan="4"><h4>Personal</h4></td></tr>
      <tr style="height: 2.5em;"><td>First name: </td><td width="250"> <?php echo $row['FirstName'];?> </td>
        <td>Last name: </td><td> <?php echo $row['LastName'];?> </td></tr>
      <tr style="height: 2.5em;"><td>Date of birth: </td><td> <?php echo $row['DateOfBirth'];?> </td>
        <td>Sex: </td><td> <?php echo $row['Sex'];?> </td></tr>
      <tr><td colspan="4"><h4>Contacts</h4></td></tr>
      <tr style="height: 2.5em;"><td>Address: </td><td colspan="3"> 
        <?php echo $row['AddStreet'].', '.$row['AddCity'].', '.$row['AddState'];?> </td></tr>
      <tr style="height: 2.5em;"><td>Phone number: </td><td colspan="3"> 
        <?php echo $row['Phone'];?> </td></tr>
      <tr style="height: 2.5em;"><td>E-mail address: </td><td colspan="3"> 
        <?php echo $row['Email'];?> </td></tr>
    </table>
    <p><input type="submit" value="Update"><span style="margin: 0 30px;"></span>
    <input type="submit" value="Return" formaction="/customer.php"/></p>
  </form>
</div>
</body>
</html>