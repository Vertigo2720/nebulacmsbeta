<?
require('config/config.php');
//Load site settings from database//
$results = databaseArray("SELECT * FROM nc_settings WHERE id = $settings_in_use");
if($results)
{
	global $nc_settings_id, $site_name, $theme, $comments_enabled, $registration_enabled, $open_registration_enabled, $address, $admin_name, $admin_email;
	$nc_settings_id = $results['id'];
	$site_name = $results['site_name'];
	$theme = $results['theme'];
	$comments_enabled = $results['comments_enabled'];
	$registration_enabled = $results['registration_enabled'];
	$open_registration_enabled = $results['open_registration_enabled'];
	$login_duration = $results['login_duration'];
	$address = $results['address'];
	$admin_name = $results['admin_name'];
	$admin_email = $results['admin_email'];
}
?>
