<?php
//Database connection parameters//
//Set by config/config.php//
function dbConn()
{
	//Prerequisites
	$db_server = $GLOBALS['db_server'];
	$db_user = $GLOBALS['db_user'];
	$db_user_pass = $GLOBALS['db_user_pass'];
	$db_name = $GLOBALS['db_name'];
	//
	mysql_connect("$db_server", "$db_user", "$db_user_pass") or die(mysql_error());
	mysql_select_db("$db_name") or die(mysql_error());
}
?>