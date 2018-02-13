<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || ($_SESSION['usertype']!='E' && $_SESSION['usertype']!='M')) header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';

$sql = "SELECT ProdCateName,ProdName,ProdPrice,ProdDesc 
  FROM ProdCate,Product WHERE ProdCate.ProdCateId=Product.ProdCateId";
  if ($pName = mysql_real_escape_string($_POST['prodName'])) $sql .= " AND ProdName LIKE '%$pName%'";
  if ($pDesc = mysql_real_escape_string($_POST['prodDesc'])) $sql .= " AND ProdDesc LIKE '%$pDesc%'";
  if ($pPrice1 = mysql_real_escape_string($_POST['prodPrice1'])) $sql .= " AND ProdPrice>=$pPrice1";
  if ($pPrice2 = mysql_real_escape_string($_POST['prodPrice2'])) $sql .= " AND ProdPrice<=$pPrice2";
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
  <tr><th>Category</th><th>Name</th><th>Price</th><th>Description</th></tr>
<?php
while($row = mysql_fetch_array($res)) {
  echo "<tr><td>".$row['ProdCateName']."</td><td>".$row['ProdName']."</td>";
  echo "<td>".$row['ProdPrice']."</td><td>".$row['ProdDesc']."</td></tr>";
}
?>
</table>