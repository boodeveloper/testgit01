<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conDB = "localhost";
$database_conDB = "boolifestyle00";
$username_conDB = "root";
$password_conDB = "";
$conDB = mysql_pconnect($hostname_conDB, $username_conDB, $password_conDB) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES UTF8"); 
?>