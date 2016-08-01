<?php require_once('Connections/conDB.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['m_id'])) && ($_POST['m_id'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tb_member WHERE m_id=%s",
                       GetSQLValueString($_POST['m_id'], "int"));

  mysql_select_db($database_conDB, $conDB);
  $Result1 = mysql_query($deleteSQL, $conDB) or die(mysql_error());

  $deleteGoTo = "show_data2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_showmember2 = 10;
$pageNum_showmember2 = 0;
if (isset($_GET['pageNum_showmember2'])) {
  $pageNum_showmember2 = $_GET['pageNum_showmember2'];
}
$startRow_showmember2 = $pageNum_showmember2 * $maxRows_showmember2;

mysql_select_db($database_conDB, $conDB);
$query_showmember2 = "SELECT * FROM tb_member";
$query_limit_showmember2 = sprintf("%s LIMIT %d, %d", $query_showmember2, $startRow_showmember2, $maxRows_showmember2);
$showmember2 = mysql_query($query_limit_showmember2, $conDB) or die(mysql_error());
$row_showmember2 = mysql_fetch_assoc($showmember2);

if (isset($_GET['totalRows_showmember2'])) {
  $totalRows_showmember2 = $_GET['totalRows_showmember2'];
} else {
  $all_showmember2 = mysql_query($query_showmember2);
  $totalRows_showmember2 = mysql_num_rows($all_showmember2);
}
$totalPages_showmember2 = ceil($totalRows_showmember2/$maxRows_showmember2)-1;

$colname_showuserlogin = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_showuserlogin = $_SESSION['MM_Username'];
}
mysql_select_db($database_conDB, $conDB);
$query_showuserlogin = sprintf("SELECT * FROM tb_member WHERE m_username = %s", GetSQLValueString($colname_showuserlogin, "text"));
$showuserlogin = mysql_query($query_showuserlogin, $conDB) or die(mysql_error());
$row_showuserlogin = mysql_fetch_assoc($showuserlogin);
$totalRows_showuserlogin = mysql_num_rows($showuserlogin);

$queryString_showmember2 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_showmember2") == false && 
        stristr($param, "totalRows_showmember2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_showmember2 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_showmember2 = sprintf("&totalRows_showmember2=%d%s", $totalRows_showmember2, $queryString_showmember2);
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
    <td height="40" colspan="7" align="center" bgcolor="#CC0066"><p>&nbsp;</p>
      <p>ยินดีต้อนรับ คุณ <?php echo $row_showuserlogin['m_name']; ?></p>
      <p><a href="<?php echo $logoutAction ?>">Logout</a></p>
    <p><strong>แสดงข้อมูลที่&nbsp;<?php echo ($startRow_showmember2 + 1) ?> ถึง <?php echo min($startRow_showmember2 + $maxRows_showmember2, $totalRows_showmember2) ?></strong></p></td>
  </tr>
  <tr>
    <td height="40" colspan="7" align="center" bgcolor="#CC0066"><strong>มีข้อมูลทั้งสิ้น&nbsp;<?php echo $totalRows_showmember2 ?> รายการ</strong></td>
  </tr>
  <tr>
    <td height="40" align="center" bgcolor="#CC0066"><strong>ID</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Username</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Password</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Name</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Date of Register</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Edit</strong></td>
    <td align="center" bgcolor="#CC0066"><strong>Delete</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_showmember2['m_id']; ?></td>
      <td><?php echo $row_showmember2['m_username']; ?></td>
      <td><?php echo $row_showmember2['m_password']; ?></td>
      <td><?php echo $row_showmember2['m_name']; ?></td>
      <td><?php echo $row_showmember2['m_datesave']; ?></td>
      <td><a href="form_edit1.php?m_id=<?php echo $row_showmember2['m_id']; ?>">Edit</a></td>
      <td><form id="form1" name="form1" method="post" action="">
        <input type="submit" name="button" id="button" value="Delete" onclick="return confirm('ยืนยันการลบข้อมูล')" />
        <input name="m_id" type="hidden" id="m_id" value="<?php echo $row_showmember2['m_id']; ?>" />
      </form></td>
    </tr>
    <?php } while ($row_showmember2 = mysql_fetch_assoc($showmember2)); ?>
</table>
<p>&nbsp;
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_showmember2 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_showmember2=%d%s", $currentPage, 0, $queryString_showmember2); ?>"><img src="First.gif" /></a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_showmember2 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_showmember2=%d%s", $currentPage, max(0, $pageNum_showmember2 - 1), $queryString_showmember2); ?>"><img src="Previous.gif" /></a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_showmember2 < $totalPages_showmember2) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_showmember2=%d%s", $currentPage, min($totalPages_showmember2, $pageNum_showmember2 + 1), $queryString_showmember2); ?>"><img src="Next.gif" /></a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_showmember2 < $totalPages_showmember2) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_showmember2=%d%s", $currentPage, $totalPages_showmember2, $queryString_showmember2); ?>"><img src="Last.gif" /></a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
</p>
</body>
</html>
<?php
mysql_free_result($showmember2);

mysql_free_result($showuserlogin);
?>
