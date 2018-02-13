<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='M') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$res1 = mysql_query("SELECT ProdCateId,ProdCateName FROM ProdCate");
$res2 = mysql_query("SELECT ProdId,ProdName FROM Product");
$res3 = mysql_query("SELECT SpslId,ProdName,StartDate,EndDate
  FROM SpecialSale,Product WHERE SpecialSale.ProdId=Product.ProdId");
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
function queryOrdSum() {
  if (window.XMLHttpRequest) xmlhttp=new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
  else xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
  xmlhttp.onreadystatechange=handleReply;
  var list = document.getElementById('list').value;
  var choice = document.getElementById('choice').value;
  var Date1 = document.getElementById('Date1').value;
  var Date2 = document.getElementById('Date2').value;
  var order1 = document.getElementById('order1').value;
  var order2 = document.getElementById('order2').value;
  queryString = "list=" + list + "&choice=" + choice  + "&Date1=" + Date1 + "&Date2=" + Date2 + "&order1=" + order1 + "&order2=" + order2;
  xmlhttp.open("POST", "queryordsum.php", true);
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
  <h1>Search sales summary</h1>
  <table>
    <tr><td>List: </td><td><select name="list" id="list" onChange="showChoices(document.getElementById('list'));">
      <option value=""> </option>
      <option value="1">By category</option>&nbsp;&nbsp;&nbsp;
      <option value="2">By product</option>&nbsp;&nbsp;&nbsp;
      <option value="3">By special sales</option>
    </select> <span id="choices"></span></td></tr>
    <tr><td rowspan="2">By date: </td><td>From <input type="date" id="Date1"></td></tr>
    <tr><td>to <input type="date" id="Date2"></td></tr>
    <tr><td>Order by: </td><td> <select name="order1" id="order1">
      <option value="1">Total number of items sold</option>
      <option value="2">Total amount of money in sales</option>
    </select></td></tr>
    <tr><td>Order: </td><td> <select name="order2" id="order2">
      <option value="1">Decreasing</option>
      <option value="2">Increasing</option>
    </select></td></tr>
  </table>
<p><input type="button" value="Search" onClick="queryOrdSum()"/><span style="margin: 0 50px;"></span>
<input type="submit" value="Return" formaction="/manager.php">
</p>
</form>
<span id="showRes"></span>
</div>
</body>
<script type="text/javascript">
function showChoices(x) {
  var listElement = document.getElementById("choices");
    while (listElement.firstChild) {
      listElement.removeChild(listElement.firstChild);
  }
  if (x.value==1) {
    document.getElementById('choices').innerHTML += '<?php
echo '<select name="choice" id="choice">';
echo '<option value="">Show all categories</option>';
while ($row1=mysql_fetch_array($res1)) echo '<option value="'.$row1['ProdCateId'].'">'.$row1['ProdCateName'].'</option>';
echo '</select>';
?>';
  } else if (x.value==2) {
    document.getElementById('choices').innerHTML += '<?php
echo '<select name="choice" id="choice">';
echo '<option value="">Show all products</option>';
while ($row2=mysql_fetch_array($res2)) echo '<option value="'.$row2['ProdId'].'">'.$row2['ProdName'].'</option>';
echo '</select>';
?>';
  } else if (x.value==3) {
    document.getElementById('choices').innerHTML += '<?php
echo '<select name="choice" id="choice">';
while ($row3=mysql_fetch_array($res3)) {
  echo '<option value="'.$row3['SpslId'].'">'.$row3['ProdName'].' from '.$row3['StartDate'].' to '.$row3['EndDate'].'</option>';
}
echo '</select>';
?>';
  }
}
</script>
</html>