<?php
//Set include path to site root--//
$site_root = $_SERVER['DOCUMENT_ROOT'];
set_include_path($site_root);
require_once('nc_core/load.php');
$upload_dir = '../../nc_images/uploaded';

if(!empty($_FILES))
{
	$date = date("Y-m-d");
	$username = userdata('current','username');
	if($_POST['gallerySelect'] == 'new' and !empty($_POST['gallery']))
	{
		$gallery = addslashes($_POST['gallery']);
		
		//Make sure we can actually use that gallery name for a folder
		$bad = array_merge(
			array_map('chr', range(0,31)),
			array("<", ">", ":", '"', "/", "\\", "|", "?", "*", "."));
		$galleryFolder = str_replace($bad, "", $gallery);
		//
		if(!is_writable($upload_dir.'/'.$galleryFolder))
		{
			mkdir($upload_dir.'/'.$galleryFolder);
		}
		if(!is_writable($upload_dir.'/'.$galleryFolder.'/'.$date))
		{
			mkdir($upload_dir.'/'.$galleryFolder.'/'.$date);
		}
	}
	elseif($_POST['gallerySelect'] == 'none')
	{
		$galleryFolder = 'uncategorized';
		$gallery = 'uncategorized';
		$folder = 'uncategorized' . DIRECTORY_SEPARATOR . $date;
		//
		if(!is_writable($upload_dir. DIRECTORY_SEPARATOR . 'uncategorized'. DIRECTORY_SEPARATOR . $date))
		{
			mkdir($upload_dir. DIRECTORY_SEPARATOR . 'uncategorized'. DIRECTORY_SEPARATOR . $date);
		}
	}
	else
	{
		$galleryFolder = addslashes($_POST['gallerySelect']);
		if($galleryFolder == 'new')
		{
			$galleryFolder = 'uncategorized';
		}
		$gallery = $galleryFolder;
		$folder = $gallery . DIRECTORY_SEPARATOR . $date;
		//
		if(!is_writable($upload_dir . DIRECTORY_SEPARATOR . $gallery)){
			mkdir($upload_dir . DIRECTORY_SEPARATOR . $gallery);
		}
		if(!is_writable($upload_dir . DIRECTORY_SEPARATOR . $gallery . DIRECTORY_SEPARATOR . $date)){
			mkdir($upload_dir . DIRECTORY_SEPARATOR . $gallery . DIRECTORY_SEPARATOR . $date);
		}
	}
	$tempFile = $_FILES['file']['tmp_name'];
	$targetPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR . $galleryFolder . DIRECTORY_SEPARATOR . $date . DIRECTORY_SEPARATOR;
	//Check to see if a file with the same name has already been uploaded. If so, add a time stamp to the file name to prevent conflict. 
	$mainFile = $targetPath . $_FILES['file']['name'];
	if(file_exists($mainFile)){
		unset($mainFile);
		$mainFile = $targetPath.time().'-'. $_FILES['file']['name'];
		$image = time().'-'. $_FILES['file']['name'];
	}
	else
	{
		$image = $_FILES['file']['name'];
	}
	move_uploaded_file($tempFile,$mainFile);
	$upload_id = addslashes($_POST['upload_id']);
	$dir = 'nc_images/uploaded/' . $galleryFolder . '/' . $date . '/' . $image;
	database("INSERT INTO `nc_images` (upload_id, creator, gallery, image, dir, title, date, status) VALUES ('$upload_id','$username','$gallery','$image','$dir','$image',NOW(),'pending')");
}
?>