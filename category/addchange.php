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
$cid = '';
if (isset($_POST['categoryid'])) {
  $cid = $_POST['categoryid'];
  $res = mysql_query("SELECT ProdCateId,ProdCateName,ProdCateDesc 
    FROM ProdCate WHERE ProdCateId=$cid");
  $row = mysql_fetch_array($res);
}
if (!$cid) {
  echo '<h1 align="center">Add a category</h1>';
  echo '<form id="addchange" method="post" onsubmit="return onSubmitAction()">';
} else {
  echo '<h1 align="center">Change a category</h1>';
  echo '<form id="addchange" method="post" onsubmit="return onSubmitAction()">';
}
?>
<input type="hidden" name="cateId" value="<?php echo $cid ?>">
  <table>
    <tr><td>Name: </td><td><input type="text" name="cateName" id="cateName" value="<?php echo $row['ProdCateName'] ?>"></td></tr>
    <tr><td>Description: </td><td><input type="text" size="50" name="cateDesc" value="<?php echo $row['ProdCateDesc'] ?>"></td></tr>
  </table>
<p>
<?php
echo '<input type="submit" onclick="document.pressed=this.value" value="';
if (!$cid) echo 'Add';
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
    if (!checkNames(document.getElementById('cateName'))) {
      return false;
    } else if (!checkNames(document.getElementById('cateDesc'))) {
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
</script>
</html>