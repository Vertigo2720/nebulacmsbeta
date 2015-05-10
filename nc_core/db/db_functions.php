<?
//////////////////////
//DATABASE FUNCTIONS//
//////////////////////

//Select raw information from database
function database($query = null)
{
	dbConn();
	$result = mysql_query($query);
	if($result)
	{
		return $result;
	}
	else
	{
		return false;
	}
	mysql_close();
}

//Select array of information from database
function databaseArray($query = null)
{
	dbConn();
	$result = mysql_query($query);
	if($result)
	{
		return mysql_fetch_assoc($result);
	}
	else
	{
		return false;
	}
	mysql_close();
}
?>