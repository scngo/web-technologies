<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['customerid'])) header("Location: /customer/signin.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /customer/signin.php");
$_SESSION['page'] = 'pay';
$_SESSION['timeout'] = time();
$customerid = $_SESSION['customerid'];
include '../mysqllogin.php';
$res0 = mysql_query("SELECT FirstName,LastName FROM Customer WHERE CustId=$customerid");
  $row0 = mysql_fetch_array($res0);
$res = mysql_query("SELECT SUM(ProdQty * IF(SalePrice IS NOT NULL,SalePrice,ProdPrice))
  AS Sum FROM Cart INNER JOIN Product ON Cart.ProdID = Product.ProdID
  LEFT JOIN SpecialSale ON Cart.ProdID = SpecialSale.ProdID WHERE CustId=$customerid");
$row = mysql_fetch_array($res);
if (isset($_SESSION['customerid']) && $row['Sum']<=0) header("Location: /index.php");
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
  <h3 align="center">Place Your Order</h3>
  <form id="payment" method="post" onsubmit="return onSubmitAction()">
    <input type="hidden" name="customerid" value="<?php echo $customerid;?>">
    <table>
      <tr><td><span style="font-size:1.2em">Your total amount: </span></td>
        <td> <span style="font-size:1.2em">$<?php echo $row['Sum'];?></span> </td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr><td>Name: </td><td> <?php echo $row0['FirstName'];?> <?php echo $row0['LastName'];?> </td></tr>
      <tr><td>Credit card number: </td><td> <input type="text" name="cardnum" id="cardnum" 
        onBlur="checkCard(document.getElementById('cardnum'))"/> </td></tr>
      <tr><td>Security code: </td><td> <input type="text" name="cardsec" id="cardsec" 
        onBlur="checkCode(document.getElementById('cardsec'))"/> </td></tr>
      <tr><td>Shipping address: </td><td> <input type="text" name="addship" id="addship" style="width:400px;"
        onBlur="checkAddr(document.getElementById('addship'))"/> </td></tr>
      <tr><td>Billing address: </td><td> <input type="text" name="addbill" id="addbill" style="width:400px;"
        onBlur="checkAddr(document.getElementById('addbill'))"/> </td></tr>
    </table>
    <p><input type="submit" value="Make Payment" onclick="document.pressed=this.value"><span style="margin: 0 30px;"></span>
    <input type="submit" value="Cancel" onclick="document.pressed=this.value"/></p>
  </form>
</div>
</body>
<script type="text/javascript">
function onSubmitAction() {
  if(document.pressed == 'Make Payment') {
    document.getElementById('payment').action ="/customer/doorder.php";
    return checkForm();
  } else if (document.pressed == 'Cancel') {
    document.getElementById('payment').action ="/customer/showcart.php";
    return true;
  }
}
function checkForm() {
  if (checkCard(document.getElementById('cardnum')) && checkCode(document.getElementById('cardsec'))
      && checkAddr(document.getElementById('addship')) && checkAddr(document.getElementById('addbill'))) return true;
  else return false;
}
function checkCard(x) {
  var numChk = /^[0-9]{15,16}$/;
  if (x.value == null || x.value == "") {
    window.alert('Credit card number must be filled out!');
    x.style.border = "solid red";
    return false;
  } else if (x.value.match(numChk)) {
    x.style.border = '';
    return true;
  } else {
    window.alert('Invalid credit card number!');
    x.style.border = "solid red";
    return false;
  }
}
function checkCode(x) {
  var numChk = /^[0-9]{3,4}$/;
  if (x.value == null || x.value == "") {
    window.alert('Security code must be filled out!');
    x.style.border = "solid red";
    return false;
  } else if (x.value.match(numChk)) {
    x.style.border = '';
    return true;
  } else {
    window.alert('Invalid security code!');
    x.style.border = "solid red";
    return false;
  }
}
function checkAddr(x) {
  var nameChk = /^[A-Za-z0-9-' .,]+$/;
  if (x.value == null || x.value == "") {
    window.alert('Address must be filled out!');
    x.style.border = "solid red";
    return false;
  } else if (x.value.match(nameChk)) {
    x.style.border = '';
    return true;
  } else {
    window.alert('Invalid address format!');
    x.style.border = "solid red";
    return false;
  }
}
</script>
</html>