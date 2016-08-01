<?php require_once('Connections/conDB.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_edit")) {
  $updateSQL = sprintf("UPDATE tb_member SET m_password=%s, m_name=%s WHERE m_id=%s",
                       GetSQLValueString($_POST['m_password'], "text"),
                       GetSQLValueString($_POST['m_name'], "text"),
                       GetSQLValueString($_POST['m_id'], "int"));

  mysql_select_db($database_conDB, $conDB);
  $Result1 = mysql_query($updateSQL, $conDB) or die(mysql_error());

  $updateGoTo = "show_data2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit = "-1";
if (isset($_GET['m_id'])) {
  $colname_edit = $_GET['m_id'];
}
mysql_select_db($database_conDB, $conDB);
$query_edit = sprintf("SELECT * FROM tb_member WHERE m_id = %s", GetSQLValueString($colname_edit, "int"));
$edit = mysql_query($query_edit, $conDB) or die(mysql_error());
$row_edit = mysql_fetch_assoc($edit);
$totalRows_edit = mysql_num_rows($edit);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form_edit" name="form_edit" method="POST" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="64" colspan="2" align="center" bgcolor="#999999">Form Edit</td>
    </tr>
    <tr>
      <td width="178" bgcolor="#CCCCCC">&nbsp;</td>
      <td width="506" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Username : </td>
      <td bgcolor="#CCCCCC"><label for="m_username"></label>
      <?php echo $row_edit['m_username']; ?></td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Password : </td>
      <td bgcolor="#CCCCCC"><label for="m_password"></label>
        <input name="m_password" type="password" required="required" id="m_password" value="<?php echo $row_edit['m_password']; ?>"/></td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Name&nbsp;: </td>
      <td bgcolor="#CCCCCC"><label for="m_name"></label>
        <input name="m_name" type="text" id="m_name" value="<?php echo $row_edit['m_name']; ?>" />
        <input name="m_id" type="hidden" id="m_id" value="<?php echo $row_edit['m_id']; ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td bgcolor="#CCCCCC"><input type="submit" name="save" id="save" value="บันทึก" required="required"/></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_update" value="form_edit" />
</form>
</body>
</html>
<?php
mysql_free_result($edit);
?>
