<?
//Nebula CMS Loader--------------//
//Everything required to run-----//
//ORDER MATTERS!!----------------//

//Set include path to site root--//
$site_root = $_SERVER['DOCUMENT_ROOT'];
$site_root .= "/nc_core/load.php";
set_include_path($site_root);
//Load NC Config-----------------//
session_start();
require_once('config/config.php');
require_once('db/db_conn.php');
require_once('db/db_functions.php');
require_once('load_settings.php');

if(!isset($constantsDefined))
{
	require_once('config/constants.php');
	$constantsDefined = true;
}

//Check if comments are enabled--//
if($comments_enabled == 1)
{
	require_once('load_comment_settings.php');
}

//Load NC Core------------------//
require_once('functions.php');

//Load JAVASCRIPT---------------//
echo "
<script type=\"text/javascript\" src=\"nc_core/js/jquery.js\"></script>
<script type=\"text/javascript\" src=\"nc_core/js/jquery-ui.min.js\"></script>
<script type=\"text/javascript\" src=\"nc_core/js/jquery-css-transform.js\"></script>
<script type=\"text/javascript\" src=\"nc_core/js/jquery-animate-css-rotate-scale.js\"></script>
<script type=\"text/javascript\" src=\"nc_core/js/js_functions.js\"></script>
<script type=\"text/javascript\" src=\"nc_core/js/dropzone.js\"></script>
<!--Nebula CMS 0.5 Loaded - Copyright 2015 Nebula Foundry-->
";
//Check for a login attempt-----//
if(isset($_POST['username']) && $_POST['pass'])
{
	login($_POST['username'], $_POST['pass']);
}
//Check for a logout attempt----//
if(isset($_POST['logout']))
{
	logout($_POST['logout']);
}
?>