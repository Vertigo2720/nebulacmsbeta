<?php
if(userCheck())
{
	$upload_id = round(microtime(true));
	?>
	<script type="text/javascript">
	var upload_id = '<? echo $upload_id; ?>';
	</script>
	<link href="nc_core/js/dropzone.css" rel="stylesheet" type="text/css" />
	<div id="imageEditor">
	<? getSnippet('1428616182'); ?>
	<div id="editAccount">
	<form action="nc_core/imageupload/galleryuploadprocess.php" class="dropzone">
	<p class="fancySelectHead wider">Current Galleries:</p>
	<select class="fancySelect wider" name="gallerySelect">
	<?php
	$galleries = database("SELECT DISTINCT `gallery` FROM `nc_images`");
	
	echo "<option selected=\"selected\" value=\"new\">New</option>\n";
	while ($row = mysql_fetch_assoc($galleries )) // loop through galleries
	{ 
		echo "<option value=\"". $row['gallery']."\">". $row['gallery']."</option>\n";
	}
	echo "<option value=\"none\">None</option>\n";
	?>
	</select>
	<p class="fancySelectHead">New Gallery:</p>
	<textarea name="gallery" class="form"></textarea>
	<input type="hidden" name="upload_id" value="<? echo $upload_id; ?>" />
	</form>
	</div>
	</div>
	<?php
}
else
{
	$_SESSION['notify'] = "This page requires an active Catserver.net account.";
	header("refresh: 0; url=/");
}
?>