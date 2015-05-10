<?
//Load comment system settings from database//
if($comments_enabled = 1)
{
	$results = databaseArray("SELECT * FROM nc_comment_settings WHERE id = $settings_in_use");
	if($results)
	{
		global $comm_settings_id, $num_comments_show;
		$comm_settings_id = $results['id'];
		$num_comments_show = $results['num_comments_show'];
		$public_comments_enable = $results['public_comments_enable'];
		$comments_disable_post = $results['comments_disable_post'];
		$comments_disable_edit = $results['comments_disable_edit'];
		$comments_disable_delete = $results['comments_disable_delete'];
	}
}
?>