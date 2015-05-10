<?php
require_once($_SERVER['DOCUMENT_ROOT']."/nc_load.php");
$username = userData('current','username');

if(isset($_FILES["file"]["type"]))
{
$validextensions = array("jpeg", "jpg", "png", "JPG");
$temporary = explode(".", $_FILES["file"]["name"]);
$file_extension = end($temporary);
if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")
	) && in_array($file_extension, $validextensions)) {
	if ($_FILES["file"]["error"] > 0)
	{
	echo "Return Code: " . $_FILES["file"]["error"];
	}
	else
	{
		if (file_exists($_SERVER['DOCUMENT_ROOT']."/nc_images/uploaded/accounts/" . $_FILES["file"]["name"]))
		{
			unlink($_SERVER['DOCUMENT_ROOT']."/nc_images/uploaded/accounts/" . $_FILES["file"]["name"]);
			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
			$targetPath = $_SERVER['DOCUMENT_ROOT']."/nc_images/uploaded/accounts/".$_FILES['file']['name']; // Target path where file is to be stored
			move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
			if(database("UPDATE `nc_users` SET `image` = 'nc_images/uploaded/accounts/".$_FILES["file"]["name"]."' WHERE `username` = '".$username."'"))
			{
				echo "New profile image set (replaced existing file).";
			}
			else
			{
				echo "The image was uploaded but couldn't be set :(";
			}
		}
		else
		{
			$sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
			$targetPath = $_SERVER['DOCUMENT_ROOT']."/nc_images/uploaded/accounts/".$_FILES['file']['name']; // Target path where file is to be stored
			move_uploaded_file($sourcePath,$targetPath) ; // Moving Uploaded file
				if(database("UPDATE `nc_users` SET `image` = 'nc_images/uploaded/accounts/".$_FILES["file"]["name"]."' WHERE `username` = '".$username."'"))
				{
					echo "New profile image set.";
				}
				else
				{
					echo "The image was uploaded but couldn't be set :(";
				}
		}
	}
}
else
{
echo "Invalid image :(";
}
}
?>