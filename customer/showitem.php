<?php
//ini_set('display_errors', 'On');
session_start();
if (isset($_SESSION['customerid']) && $_SESSION['timeout'] + 600 < time()) {
  session_destroy();
  header("Location: /customer/signin.php");
}
$_SESSION['timeout'] = time();
$_SESSION['page'] = 'item';
include '../mysqllogin.php';
if (!isset($_POST['productid'])) header("Location: /index.php");
$productid = $_POST['productid'];
$res = mysql_query("SELECT * FROM Product
  LEFT JOIN SpecialSale ON Product.ProdId=SpecialSale.ProdId AND StartDate<=CURDATE() AND EndDate>=CURDATE() 
  WHERE Product.ProdId=$productid");
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
  <h3 align="center">Product Details</h3>
  <form method="post" action="/customer/addtocart.php" onSubmit="return checkQty()">
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
    <input type="hidden" name="productid" value="<?php echo $productid;?>">
    <table>
      <tr><td rowspan="4"><img src="<?php echo $row['Figure'];?>" height="100"></td>
        <td>Name: </td><td> <?php echo $row['ProdName'];?> </td></tr>
      <tr><td>Price: </td><td>
<?php
if ($row['SalePrice']) {
  echo '<del>$'.$row['ProdPrice'].'</del> $'.$row['SalePrice'].' <span style="color:grey;">(Deal ends at '.$row['EndDate'].')</span>';
} else echo '$'.$row['ProdPrice'];
?>
      </td></tr>
      <tr><td>Description: </td><td> <?php echo $row['ProdDesc'];?> </td></tr>
      <tr><td>Quantity needed: </td><td> <input type="number" name="productqty" id="productqty" min="1" value="1"> </td></tr>
    </table>
    <p><input type="submit" value="Add to cart"><span style="margin: 0 30px;"></span>
    <input type="submit" value="Return" formaction="/index.php"/></p>
<p>
<?php
$res1 = mysql_query("SELECT P2.ProdId,P2.ProdName,P2.ProdPrice,SalePrice FROM ProdCate,Product P1,Product P2,SpecialSale
  WHERE ProdCate.ProdCateId=P1.ProdCateId AND P1.ProdCateId=P2.ProdCateId AND P2.ProdId=SpecialSale.ProdId AND P1.ProdId=$productid
  AND StartDate<=CURDATE() AND EndDate>=CURDATE() ORDER BY RAND() LIMIT 0,1");
  if (($row1 = mysql_fetch_array($res1)) && ($productid!=$row1['ProdId'])) {
    echo '<span style="color:red;">Special Sale: </span>';
    echo $row1['ProdName'].' now only $'.$row1['SalePrice'].' <span style="font-size:0.8em;color:grey;">(was $'.$row1['ProdPrice'].')</span>';
    echo '&nbsp;<button type="submit" name="productid" value="'.$row1['ProdId'].'" 
    formaction="/customer/showitem.php">Check this out</button><br>';
  }
mysql_close($con);
?>
</p>
  </form>
</div>
</body>
<script type="text/javascript">
function checkQty() {
  var qty = document.getElementById('productqty').value;
  if (qty<=0) {
    alert('Invalid Quantity');
    return false;
  } else return true;
}
</script>
</html>