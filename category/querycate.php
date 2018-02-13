<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || ($_SESSION['usertype']!='E' && $_SESSION['usertype']!='M')) header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';

$sql = "SELECT ProdCateName,ProdCateDesc FROM ProdCate WHERE ProdCateName=ProdCateName";
  if ($cName = $_POST['cateName']) $sql .= " AND ProdCateName LIKE '%$cName%'";
  if ($cDesc = $_POST['cateDesc']) $sql .= " AND ProdCateDesc LIKE '%$cDesc%'";
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
  <tr><th>Category name</th><th>Category description</th></tr>
<?php
while($row = mysql_fetch_array($res)) echo "<tr><td>".$row['ProdCateName']."</td><td>".$row['ProdCateDesc']."</td></tr>";
?>
</table>