<? header('Content-Type: text/html; charset=utf-8'); ?>
<html>
<head>
<?
//Nebula CMS v0.5 Launcher-----------------------------//
//Copyright 2015 Victor Rossi. All rights reserved-----//
//Load Nebula CMS--------------------------------------//

require_once("nc_core/load.php");
//Load Theme and Style---------------------------------//
$css = themeDir().'site.css';
if(userCheck())
{
	$data = userDataArray();
	$usertheme = $data["theme"];
	$userstyle = $data["css"];
	$result = databaseArray('SELECT * FROM `themes` WHERE `id` = \''.$usertheme.'\'');
	//Load Theme CSS (If it's there)-------------------//
	$css = themeDir().'site.css';
	if(is_readable($css))
	{
		echo '<link href="' .$css. '" rel="stylesheet" type="text/css" />';
	}
	//Theme--------------------------------------------//
	if($result)
	{
		unset($theme);
		$theme = $result['url'];
	}
	//Style--------------------------------------------//
	if(($userstyle != 'site.css') and (!empty($userstyle)))
	{
		echo '<link href="' .themeDir().$userstyle. '" rel="stylesheet" type="text/css" />';
	}
}
elseif(is_readable($css))
{
	echo '<link href="' .$css. '" rel="stylesheet" type="text/css" />';
}

//Load Site-------------------------------------------//
set_include_path("nc_themes/$theme/");
include("index.php");
?>