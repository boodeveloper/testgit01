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

mysql_select_db($database_conDB, $conDB);
$query_showmember = "SELECT * FROM tb_member ORDER BY m_id ASC";
$showmember = mysql_query($query_showmember, $conDB) or die(mysql_error());
$row_showmember = mysql_fetch_assoc($showmember);
$totalRows_showmember = mysql_num_rows($showmember);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="40" align="center" bgcolor="#009900"><strong>ID</strong></td>
    <td align="center" bgcolor="#009900"><strong>Username</strong></td>
    <td align="center" bgcolor="#009900"><strong>Password</strong></td>
    <td align="center" bgcolor="#009900"><strong>Name</strong></td>
    <td align="center" bgcolor="#009900"><strong>Date of Register</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_showmember['m_id']; ?></td>
      <td><?php echo $row_showmember['m_username']; ?></td>
      <td><?php echo $row_showmember['m_password']; ?></td>
      <td><?php echo $row_showmember['m_name']; ?></td>
      <td><?php echo $row_showmember['m_datesave']; ?></td>
    </tr>
    <?php } while ($row_showmember = mysql_fetch_assoc($showmember)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($showmember);
?>
