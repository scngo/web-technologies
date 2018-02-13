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
$pid = '';
if (isset($_POST['productid'])) {
  $pid = $_POST['productid'];
  $res = mysql_query("SELECT ProdCateName,ProdName,ProdPrice,ProdDesc,Figure FROM ProdCate,Product 
    WHERE ProdCate.ProdCateId=Product.ProdCateId AND ProdId=$pid");
  $row = mysql_fetch_array($res);
}
if (!$pid) {
  echo '<h1 align="center">Add a product</h1>';
  echo '<form id="addchange" method="post" enctype="multipart/form-data" onsubmit="return onSubmitAction()">';
} else {
  echo '<h1 align="center">Change a product</h1>';
  echo '<form id="addchange" method="post" enctype="multipart/form-data" onsubmit="return onSubmitAction()">';
}
?>
<input type="hidden" name="prodId" value="<?php echo $pid ?>">
  <table>
    <tr><td rowspan="6">
<?php
if ($pid) echo '<img border="0" src="'.$row['Figure'].'" width="150" height="100"></td>';
?>
      <td>Category:</td><td><select name="prodCate" id ="prodCate">
<?php
  $res2 = mysql_query("SELECT ProdCateId,ProdCateName FROM ProdCate");
  while($row2 = mysql_fetch_array($res2)) {
    echo '<option value="'.$row2['ProdCateId'].'"';
    if ($row2['ProdCateName']==$row['ProdCateName']) echo ' selected';
    echo '>'.$row2['ProdCateName'].'</option>';
  }
?>
    </select></td></tr>
    <tr><td>Name: </td><td><input type="text" size="50" name="prodName" id="prodName" value="<?php echo $row['ProdName'] ?>"></td></tr>
    <tr><td>Price: </td><td><input type="text" size="50" name="prodPrice" id="prodPrice" value="<?php echo $row['ProdPrice'] ?>"></td></tr>
    <tr><td>Description: </td><td><input type="text" size="50" name="prodDesc" value="<?php echo $row['ProdDesc'] ?>"></td></tr>
    <tr><td>New figure: </td><td><input type="file" name="prodFigure" id="prodFigure"></td></tr>
    <tr><td colspan="2"><span style="color:grey;">(a gif, jpeg, jpg or png file less than 500 KB is allowed)</span></td></tr>
  </table>
<p>
<?php
echo '<input type="submit" onclick="document.pressed=this.value" value="';
if (!$pid) echo 'Add';
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
    if (!checkNames(document.getElementById('prodName'))) {
      return false;
    } else if (!checkSl(document.getElementById('prodCate'))) {
      //alert('Category!')
      return false;
    } else if (!checkNum(document.getElementById('prodPrice'))) {
      return false;
    } else return true;
  }
  function checkNames(x) {
    var nameChk = /^[A-Za-z-' ]+$/;
    if (x.value == null || x.value == "") {
      window.alert("Name must be filled out");
      x.style.border = "solid red";
      return false;
    } else if (x.value.match(nameChk)) {
      x.style.border = '';
      return true;
    } else {
      window.alert("Name contains special characters");
      x.style.border = "solid red";
      return false;
    }
  }
  function checkSl (x) { //Selection list
    slValue = false;
    for (i=0;i<x.length;i++) {
      slValue = slValue || x[i].selected;
    }
    return slValue;
  }
  function checkNum(x) {
    var numChk = /^[0-9.]+$/;
    if (x.value == null || x.value == "") {
      window.alert('Number must be filled out!');
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
</script>
</html>