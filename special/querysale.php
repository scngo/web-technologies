<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || ($_SESSION['usertype']!='E' && $_SESSION['usertype']!='M')) header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';

$sql = "SELECT ProdCateName, ProdName, ProdPrice, SalePrice, StartDate, EndDate FROM ProdCate,Product,SpecialSale 
    WHERE ProdCate.ProdCateId=Product.ProdCateId AND Product.ProdId=SpecialSale.ProdId";
  if ($pName = mysql_real_escape_string($_POST['prodName'])) $sql .= " AND ProdName LIKE '%$pName%'";
  if ($pPrice1 = mysql_real_escape_string($_POST['prodPrice1'])) $sql .= " AND (ProdPrice>=$pPrice1 OR SalePrice>=$pPrice1)";
  if ($pPrice2 = mysql_real_escape_string($_POST['prodPrice2'])) $sql .= " AND (ProdPrice<=$pPrice2 OR SalePrice<=$pPrice2)";
  if ($sDate1 = mysql_real_escape_string($_POST['saleDate1'])) $sql .= " AND StartDate>='$sDate1'";
  if ($sDate2 = mysql_real_escape_string($_POST['saleDate2'])) $sql .= " AND EndDate<='$sDate2'";
$res = mysql_query("$sql");
mysql_close($con);
?>
<style type="text/css">
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
</style>
  <h4>Result</h4>
  <table class="gridtable">
  <tr><th>Category</th><th>Name</th><th>Original price</th>
    <th>Sale price</th><th>Sale starts</th><th>Sale ends</th></tr>
<?php
while($row = mysql_fetch_array($res)) {
  echo "<tr><td>".$row['ProdCateName']."</td><td>".$row['ProdName']."</td><td>".$row['ProdPrice']."</td>";
  echo "<td>".$row['SalePrice']."</td><td>".$row['StartDate']."</td><td>".$row['EndDate']."</td></tr>";
}
?>
</table>