<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
$res = mysql_query("SELECT SpslId,Product.ProdId,ProdCateName,ProdName,ProdPrice,SalePrice,StartDate,EndDate
  FROM ProdCate,Product,SpecialSale 
  WHERE ProdCate.ProdCateId=Product.ProdCateId AND Product.ProdId=SpecialSale.ProdId;");
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Employee's Homepage</title>
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
  table.gridtable {
    font-family: arial,sans-serif;
    font-size:12px;
    color:#333333;
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
  </style>
</head>
<body>
<div align="center">
<?php
$cdset = $_POST['changedelete'];
switch ($cdset) {
  case 'Change':
  echo '<h1 align="center">Change a special sale</h1>';
  echo '<form action="addchange.php" method="post">';
  break;
  case 'Delete':
  echo '<h1 align="center">Delete special sales</h1>';
  echo '<form action="dodelete.php" method="post">';
  break;
}
?>
  <table class="gridtable">
  <tr><th> </th><th>Category</th><th>Name</th><th>Original price</th>
    <th>Sale price</th><th>Sale starts</th><th>Sale ends</th></tr>
<?php
while($row = mysql_fetch_array($res)) {
  switch ($cdset) {
    case 'Change':
    echo '<tr><td><input type="radio" name="saleid" value="'.$row['SpslId'].'">
    <input type="hidden" name="productid" value="'.$row['ProdId'].'"></td>';
    break;
    case 'Delete':
    echo '<tr><td><input type="checkbox" name="saleid[]" value="'.$row['SpslId'].'"></td>';
    break;
  }
  echo "<td>".$row['ProdCateName']."</td><td>".$row['ProdName']."</td><td>".$row['ProdPrice']."</td>";
  echo "<td>".$row['SalePrice']."</td><td>".$row['StartDate']."</td><td>".$row['EndDate']."</td></tr>";
}
?>
</table>
<p>
<?php
echo '<input type="submit" value="';
switch ($cdset) {
  case 'Change':
  echo 'Change';
  break;
  case 'Delete':
  echo 'Delete';
  break;
}
echo '"/>';
mysql_close($con);
?>
<span style="margin: 0 20px;"></span><input type="submit" formaction="/employee.php" value="Cancel"/></p>
</form>
</div>
</body>
</html>