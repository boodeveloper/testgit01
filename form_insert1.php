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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_regis")) {
  $insertSQL = sprintf("INSERT INTO tb_member (m_username, m_password, m_name) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['m_username'], "text"),
                       GetSQLValueString($_POST['m_password'], "text"),
                       GetSQLValueString($_POST['m_name'], "text"));

  mysql_select_db($database_conDB, $conDB);
  $Result1 = mysql_query($insertSQL, $conDB) or die(mysql_error());

  $insertGoTo = "form_insert1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form_regis" name="form_regis" method="POST" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="64" colspan="2" align="center" bgcolor="#999999">Form Register</td>
    </tr>
    <tr>
      <td width="178" bgcolor="#CCCCCC">&nbsp;</td>
      <td width="506" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Username : </td>
      <td bgcolor="#CCCCCC"><label for="m_username"></label>
      <input type="text" name="m_username" id="m_username" required="required"/></td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Password : </td>
      <td bgcolor="#CCCCCC"><label for="m_password"></label>
      <input type="password" name="m_password" id="m_password" required="required"/></td>
    </tr>
    <tr>
      <td align="right" bgcolor="#CCCCCC">Name&nbsp;: </td>
      <td bgcolor="#CCCCCC"><label for="m_name"></label>
      <input type="text" name="m_name" id="m_name" /></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC">&nbsp;</td>
      <td bgcolor="#CCCCCC"><input type="submit" name="save" id="save" value="บันทึก" required="required"/></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <input type="hidden" name="MM_insert" value="form_regis" />
</form>
</body>
</html>