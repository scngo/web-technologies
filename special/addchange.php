<?php
//ini_set('display_errors', 'On');
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username']=='') header("Location: /login.php");
if (!isset($_SESSION['usertype']) || $_SESSION['usertype']!='E') header("Location: /login.php");
if ($_SESSION['timeout'] + 600 < time()) header("Location: /login.php");
$_SESSION['timeout'] = time();
include '../mysqllogin.php';
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
  </style>
</head>
<body>
<div align="center">
<?php
$pid = $_POST['productid'];
$sid = '';
if (isset($_POST['saleid'])) {
  $sid = $_POST['saleid'];
  $res = mysql_query("SELECT ProdCateName, ProdName, ProdPrice, SalePrice, StartDate, EndDate FROM ProdCate,Product,SpecialSale 
    WHERE ProdCate.ProdCateId=Product.ProdCateId AND Product.ProdId=SpecialSale.ProdId AND SpecialSale.SpslId=$sid");
} else {
  $res = mysql_query("SELECT ProdId,ProdCateName,ProdName,ProdPrice
  FROM ProdCate,Product WHERE ProdCate.ProdCateId=Product.ProdCateId AND ProdId=$pid");
}
$row = mysql_fetch_array($res);
if (!$sid) {
  echo '<h1 align="center">Add a special sale</h1>';
  echo '<form id="addchange" method="post" onsubmit="return onSubmitAction()">';
} else {
  echo '<h1 align="center">Change a special sale</h1>';
  echo '<form id="addchange" method="post" onsubmit="return onSubmitAction()">';
}
?>
<input type="hidden" name="saleId" value="<?php echo $sid ?>">
<input type="hidden" name="prodId" value="<?php echo $pid ?>">
  <table>
    <tr><td>Category: </td><td><?php echo $row['ProdCateName'] ?></td></tr>
    <tr><td>Product name: </td><td><?php echo $row['ProdName'] ?></td></tr>
    <tr><td>Original Price: </td><td><?php echo $row['ProdPrice'] ?></td></tr>
    <tr><td>Sale price: </td><td><input type="text" name="salePrice" id="salePrice" value="<?php echo $row['SalePrice'] ?>"></td></tr>
    <tr><td>Sale start date: </td><td><input type="date" name="saleSdate" id="saleSdate" value="<?php echo $row['StartDate'] ?>"></td></tr>
    <tr><td>Sale end date: </td><td><input type="date" name="saleEdate" id="saleEdate" value="<?php echo $row['EndDate'] ?>"></td></tr>
  </table>
<p>
<?php
echo '<input type="submit" onclick="document.pressed=this.value" value="';
if (!$sid) echo 'Add';
else echo 'Change';
echo '"/>';
mysql_close($con);
?>
    <span style="margin: 0 20px;"></span><input type="submit" formaction="../employee.php" onclick="document.pressed=this.value" value="Cancel"/></p>
  </form>
</div>
</body>
<script type="text/javascript">
  function onSubmitAction() {
    if(document.pressed == 'Add') {
        document.getElementById('addchange').action ="doadd.php";
        return checkForm();
      } else if (document.pressed == 'Change') {
        document.getElementById('addchange').action ="dochange.php";
        return checkForm();
      } else if (document.pressed == 'Cancel') {
        document.getElementById('addchange').action ="../employee.php";
        return true;
      }
    }
  function checkForm() {
    if (!checkNum(document.getElementById('salePrice'))) return false;
    else if (!checkDate(document.getElementById('saleSdate'))) return false;
    else if (!checkDate(document.getElementById('saleEdate'))) return false;
    else return true;
  }
  function checkNum(x) {
    var numChk = /^[0-9.]+$/;
    if (x.value == null || x.value == "") {
      window.alert('Price must be filled out!');
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(numChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert('Invalid number!');
      x.style.border = "solid red";
      return false;
    }
  }
  function checkDate(x) {
    var validformat = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD
    if (validformat.test(x.value)) {
      var yearfield = x.value.split("-")[0];
      var monthfield = x.value.split("-")[1];
      var dayfield = x.value.split("-")[2];
    } else {
      alert('Invalid Date Format.');
      x.style.border = "solid red";
      return false;
    }
    var dayobj = new Date(yearfield, monthfield-1, dayfield);
    if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield)) {
      alert('Invalid Day, Month, or Year range.');
      x.style.border = "solid red";
      return false;
    } else {
      x.style.border = '';
      return true;
    }
  }
</script>
</html>