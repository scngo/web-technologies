<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='M') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';

$list = mysql_real_escape_string($_POST['list']);
if ($list==1) {
  $sql = "SELECT sum(ProdQty) AS CPD, sum(OrderDetail.ProdPrice*ProdQty) AS SPD,
    ProdCateName, ProdName, OrderDate FROM ProdCate,Product,CustOrder,OrderDetail 
    WHERE ProdCate.ProdCateId=Product.ProdCateId AND OrderDetail.ProdId=Product.ProdId AND OrderDetail.OrderId=CustOrder.OrderId ";
  if ($choice = mysql_real_escape_string($_POST['choice'])) $sql .= " AND Product.ProdCateId=$choice";
  if ($Date1 = mysql_real_escape_string($_POST['Date1'])) $sql .= " AND OrderDate>='$Date1'";
  if ($Date2 = mysql_real_escape_string($_POST['Date2'])) $sql .= " AND OrderDate<='$Date2'";
  $sql .= " GROUP BY ProdCate.ProdCateId ORDER BY ";
  if ($order1 = mysql_real_escape_string($_POST['order1'])) {
    if ($order1==1) $sql .= " CPD";
    else if ($order1==2) $sql .= " SPD";
  }
  if ($order2 = mysql_real_escape_string($_POST['order2'])) {
    if ($order2==1) $sql .= " DESC";
  }
} else if ($list==2) {
  $sql = "SELECT sum(ProdQty) AS CPD, sum(OrderDetail.ProdPrice*ProdQty) AS SPD,
    OrderDetail.ProdId, ProdName, OrderDate FROM Product,CustOrder,OrderDetail 
    WHERE OrderDetail.ProdId=Product.ProdId AND OrderDetail.OrderId=CustOrder.OrderId ";
  if ($choice = mysql_real_escape_string($_POST['choice'])) $sql .= " AND Product.ProdId=$choice";
  if ($Date1 = mysql_real_escape_string($_POST['Date1'])) $sql .= " AND OrderDate>='$Date1'";
  if ($Date2 = mysql_real_escape_string($_POST['Date2'])) $sql .= " AND OrderDate<='$Date2'";
  $sql .= " GROUP BY OrderDetail.ProdId ORDER BY ";
  if ($order1 = mysql_real_escape_string($_POST['order1'])) {
    if ($order1==1) $sql .= " CPD";
    else if ($order1==2) $sql .= " SPD";
  }
  if ($order2 = mysql_real_escape_string($_POST['order2'])) {
    if ($order2==1) $sql .= " DESC";
  }
} else if ($list==3) {
  $choice = mysql_real_escape_string($_POST['choice']);
  $res0 = mysql_query("SELECT SpslId,SpecialSale.ProdId,StartDate,EndDate 
    FROM SpecialSale,Product WHERE SpecialSale.ProdId=Product.ProdId AND SpslId=$choice");
  $row0 = mysql_fetch_array($res0);
  $sProd = $row0['ProdId'];
  $sDate1 = $row0['StartDate'];
  $sDate2 = $row0['EndDate'];
  $sql = "SELECT sum(ProdQty) AS CPD, sum(OrderDetail.ProdPrice*ProdQty) AS SPD,
    OrderDetail.ProdId, ProdName, OrderDate FROM Product,CustOrder,OrderDetail 
    WHERE OrderDetail.ProdId=Product.ProdId AND OrderDetail.OrderId=CustOrder.OrderId
    AND OrderDetail.ProdId=$sProd AND OrderDate>='$sDate1' AND OrderDate<='$sDate2'";
  if ($Date1 = mysql_real_escape_string($_POST['Date1'])) $sql .= " AND OrderDate>='$Date1'";
  if ($Date2 = mysql_real_escape_string($_POST['Date2'])) $sql .= " AND OrderDate<='$Date2'";
  $sql .= " GROUP BY OrderDetail.ProdId ORDER BY ";
  if ($order1 = mysql_real_escape_string($_POST['order1'])) {
    if ($order1==1) $sql .= " CPD";
    else if ($order1==2) $sql .= " SPD";
  }
  if ($order2 = mysql_real_escape_string($_POST['order2'])) {
    if ($order2==1) $sql .= " DESC";
  }
}
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
<?php
if ($list==1) {
  echo '<tr><th>Category</th><th>Items sold</th><th>Money in sales</th></tr>';
  while($row = mysql_fetch_array($res)) {
    echo "<tr><td>".$row['ProdCateName']."</td><td>".$row['CPD']."</td><td>".$row['SPD']."</td></tr>";
  }
} else if ($list==2) {
  echo '<tr><th>Product</th><th>Items sold</th><th>Money in sales</th></tr>';
  while($row = mysql_fetch_array($res)) {
    echo "<tr><td>".$row['ProdName']."</td><td>".$row['CPD']."</td><td>".$row['SPD']."</td></tr>";
  }
} if ($list==3) {
  echo '<tr><th>Product on Sale</th><th>Items sold</th><th>Money in sales</th></tr>';
  while($row = mysql_fetch_array($res)) {
    echo "<tr><td>".$row['ProdName']."</td><td>".$row['CPD']."</td><td>".$row['SPD']."</td></tr>";
  }
}
?>
</table>