<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  session_start();
}
$_SESSION['timeout'] = time();
include 'mysqllogin.php';
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
<form method="post" action="/customer/showitem.php">
<p class="info" align="right">
<input type="submit" value="Shopping Cart" formaction="/customer/showcart.php">
<span style="margin: 0 30px;"></span>
<?php
if (isset($_SESSION['customerid'])) {
  $customerid = $_SESSION['customerid'];
  $res0 = mysql_query("SELECT FirstName FROM Customer WHERE CustId='$customerid'");
  $row0 = mysql_fetch_array($res0);
  echo 'Welcome back, '.$row0['FirstName'].'! ';
  echo '<input type="submit" value="Manage" formaction="/customer.php">';
  echo '<input type="submit" value="Sign out" formaction="/signout.php">';
} else {
  echo 'Welcome!! ';
  echo '<input type="submit" value="Sign in" formaction="/customer/signin.php">';
  echo '<input type="submit" value="Sign up" formaction="/customer/signup.php">';
}
?>
</p>
<p>
<?php
$res1 = mysql_query("SELECT ProdName,ProdPrice,SalePrice FROM Product,SpecialSale
  WHERE Product.ProdId=SpecialSale.ProdId AND StartDate<=CURDATE() AND EndDate>=CURDATE() ORDER BY RAND()");
$row1 = mysql_fetch_array($res1);
echo '<span style="font-size:1.5em;color:red;">Special Sale: </span>';
echo '<span style="font-size:1.5em;"> '.$row1['ProdName'].' now only $'.$row1['SalePrice'].'</span> (was $'.$row1['ProdPrice'].')';
?>
</p>
  <table class="gridtable">
  <tr><th></th><th>Category</th><th>Name</th><th>Price</th><th>Description</th></tr>
<?php
$sql2 = "SELECT Product.ProdId,ProdCateName,ProdName,ProdPrice,SalePrice,ProdDesc FROM Product 
  INNER JOIN ProdCate ON ProdCate.ProdCateId=Product.ProdCateId
  LEFT JOIN SpecialSale ON Product.ProdId = SpecialSale.ProdId AND StartDate<=CURDATE() AND EndDate>=CURDATE()";
$res2 = mysql_query($sql2);
while($row2 = mysql_fetch_array($res2)) {
  echo '<tr><td><input type="radio" name="productid" value="'.$row2['ProdId'].'"></td>';
  echo "<td>".$row2['ProdCateName']."</td><td>".$row2['ProdName']."</td><td>";
  if ($row2['SalePrice']) echo '<del>'.$row2['ProdPrice'].'</del> <span style="color:red;">'.$row2['SalePrice'].'</span>';
  else echo $row2['ProdPrice'];
  echo "</td><td>".$row2['ProdDesc']."</td></tr>";
}
mysql_close($con);
?>
</table>
<p><input type="submit" value="Show Product"></p>
</form>
</div>
</body>
</html>