<html>
<head>
<?php
if(userCheck())
{
$image = userData($_GET['user'],'image');
	if(!file_exists($image))
	{
		$image = get_gravatar(userData($_GET['user'], 'email'), 2048);
	}
	?>
	<title>Catserver.net Profile Image Module</title>
	<link rel="stylesheet" href="nc_core/imageupload/style.css" />
	<script src="nc_core/imageupload/script.js"></script>
	</head>
	<body>
	<div class="currentImage" style="background-image:url('<?php echo $image ?>');"></div>
	<div class="preview"></div>
	<div class="main">
	<div id='loading'></div>
	<div id="message"></div>
	<form id="uploadimage" action="" method="post" enctype="multipart/form-data">
	<div id="selectImage">
	<input type="file" name="file" id="file" required />
	<input type="submit" value="Save" class="submit" />
	</div>
	</form>
	</div>
	</body>
	</html>
	<?php
}
else
{
	$_SESSION['notify'] = "This page requires an active Catserver.net account.";
	header("refresh: 0; url=/");
}
?>