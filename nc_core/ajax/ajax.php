<? header('Content-Type: text/html; charset=utf-8'); ?>
<div id="result">
<?php
//Load Nebula CMS Core
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/nc_core/load.php";
if(!include_once($path))
{
	//Nebula CMS Core is required for operation
	echo 'Critical Failure - Unable to launch Nebula CMS Core!';
}
else
{
	//Request via POST is required to fetch ajax content
	if(isset($_POST['request']))
	{
		//Popup Creator
		if($_POST['request'] == 'getPopup')
		{
			//Edit Post
			echo '<div id="container">';
			if(userCheck() & $_POST['name'] == 'Edit Post' & is_numeric($_POST['content']))
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				$request = $_POST['ajax'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup">
				<span class="popupContent">';
				popupHeader($name.' '.$content);
				echo '<div class=\'scrollingPopup\'>';
				postCreationForm($content, true);
				echo '
				</div>
				</div>
				</span>
				</div>';
			}
			//Error Message
			elseif($_POST['name'] == 'Error')
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup" class="popupError">
				<span class="popupContent">';
				popupHeader($name);
				echo 
				$content. '
				</span>
				<span class="right"><input type="submit" value="Close" class="submitButton closeButton"/></span>
				</div>';
			}
			//Success Message
			elseif($_POST['name'] == 'Congratulations!')
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup" class="popupError">
				<span class="popupContent">';
				popupHeader($name);
				echo 
				$content. '
				</div>';
			}
			//Edit Page
			elseif(userCheck() & $_POST['name'] == 'Edit Page' & is_numeric($_POST['content']))
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				$request = $_POST['ajax'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup">
				<span class="popupContent">';
				popupHeader($name.' '.$content);
				echo '<div class=\'scrollingPopup\'>';
				pageCreationForm($content, true);
				echo '
				</div>
				</div>
				</span>
				</div>';
			}
			//Single Image Viewer
			elseif($_POST['name'] == 'View Image')
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				$request = $_POST['ajax'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup">
				<span class="popupContent noSelect">';
				popupHeader($name);
				echo '
				<img src="'.$content.'" />
				</span>
				</div>';
			}
			//General Popup
			else
			{
				$name = $_POST['name'];
				$content = $_POST['content'];
				$request = $_POST['ajax'];
				echo '
				<script>
				animatePopupShow();
				</script>
				<div id="popup" class="popupError">
				<span class="popupContent">';
				popupHeader($name);
				echo $content.'.';
				if(isset($request))
				{
					echo 'Request ID: '.$request.'.';
				}
				echo '
				</span>
				</div>';
			}
			echo '</div>';
		}
		if($_POST['request'] == 'editComment')
		{
			?>
			<script>
			  /* attach a submit handler to the form */
			  $("#editForm").submit(function(event) {

				/* stop form from submitting normally */
				event.preventDefault();

				/* Send the data using post and put the results in a div */
				$.post("comments/edit_process.php", $("#editForm").serialize());
				
				$('#comment_<? echo $comm_id; ?>').load('comments/single.php?comm_id=<? echo $comm_id; ?>&adminui=<? echo $adminui; ?>&page_id=<? echo $page_id; if(isset($_GET["delete"])){echo '&delete=1';} ?> #container');
				
			  });
			</script>
			<?
			if(isset($_GET["delete"])){
				if(isset($_COOKIE['catserveruser'])){
					$i = 0;
					$username = $_COOKIE['catserveruser'];
					$query="SELECT * from `page_comments` WHERE `id` = '$comm_id'";
					$result = mysql_query($query);
					$name = strtoupper(mysql_result($result,$i,"name"));
					if($name == strtoupper($_COOKIE['catserveruser']) or $adminui == 1){
						echo '<div>
						Are you sure you want to delete this comment?
						<form name="edit" action="comments/edit_process.php" id="editForm" method="post" enctype="multipart/form-data">
						<input type="hidden" name="page_id" value="'.$page_id.'" />
						<input type="hidden" name="comm_id" value="'.$comm_id.'" />
						<input type="hidden" name="delete" value="1" />
						<br />
						<p align="right">';
						echo "<button type=\"button\" onclick=\"ajax_loadContent('comment_". $comm_id."','comments/single.php?comm_id=". $comm_id."&page_id=". $page_id."&adminui=". $adminui."');\" name=\"Cancel\" title=\"Cancel\"  class=\"submitButton\">Cancel</button>";
						echo "&nbsp;<input type=\"submit\" value=\"Delete\" class=\"submitButton\"/>
							</form></div>";
					}
					else
					{
						echo '<div>
						You\'re not authorized to edit this comment.
						</div>';
					}
				}
				else
				{
					echo '<div>
					You must login before making any changes.
					</div>';
				}
			}
			else
			{
			?>
			<script type="text/javascript">
			window.onload = function() {
			document.form.comments.focus();
			}
			</script>
			<script>
			$('textarea.resizer').autoResize({
				// On resize:
				onResize : function() {
					$(this).css({opacity:1});
				},
				// After resize:
				animateCallback : function() {
					$(this).css({opacity:1});
				},
				// Quite slow animation:
				animateDuration : 300,
				// More extra space:
				extraSpace : 10
			});
			</script>
			<?php
			if(!isset($_GET["comm_id"])){
			//no variable error
				echo 'PHP Error! Please try again!';
				} else{
				$comm_id = $_GET["comm_id"];
				$page_id = $_GET["page_id"];
				if ($commoff != 1) 
				{

					//checks cookies to make sure they are logged in
					if(isset($_COOKIE['catserveruser']))
						{
							$i = 0;
							$username = $_COOKIE['catserveruser'];
							$result = database("SELECT * from `page_comments` WHERE `id` = '$comm_id'");
							$name = strtoupper(mysql_result($result,$i,"name"));
							$comments = mysql_result($result,$i,"comments");
							if($name == strtoupper($_COOKIE['catserveruser']) or $adminui = 1){
							?>
							<?php
							echo '
							<script>
							$(function() {
							  $("#edit_'.$page_id.'").focus();
							});
							</script>
							<div>
							<form name="edit" action="comments/edit_process.php" id="editForm" method="post" enctype="multipart/form-data">
							<input type="hidden" name="page_id" value="'.$page_id.'" />
							<input type="hidden" name="comm_id" value="'.$comm_id.'" />
							<textarea id="edit_'.$page_id.'" name="comments" class="resizer" rows="2" tabindex="1">'.$comments.'</textarea>
							<br />
							<p align="right">';
							if(!isset($adminui))
							{
								$adminui = 0;
							}
							echo "<button type=\"button\" onclick=\"ajax_loadContent('comment_". $comm_id."','comments/single.php?comm_id=". $comm_id."&page_id=". $page_id."&adminui=". $adminui."');\" name=\"Cancel\" title=\"Cancel\"  class=\"submitButton\">Cancel</button>";
							echo "&nbsp;<input type=\"submit\" value=\"Submit\" class=\"submitButton\"/>
								</form></div>";
							}
						}
					}
				}
			}
		}
		
		//Page Creation Form
		if($_POST['request'] == 'getPageForm')
		{
			if(isset($_POST['content']))
			{
				pageCreationForm($_POST['content'], true);
			}
			else
			{
				pageCreationForm('', false);
			}
		}
		//Page Creation Form Processor
		if($_POST['request'] == 'pageProcessor')
		{
			if(adminCheck() & !empty($_POST['name']) & !empty($_POST['content']) & !empty($_POST['creator']) & !isset($_POST['edit']))
			{
				$page_id = addslashes($_POST['page_id']);
				$name = addslashes($_POST['name']);
				$content = addslashes($_POST['content']);
				$sidebar = addslashes($_POST['sidebar']);
				$url = addslashes($_POST['url']);
				$secure = addslashes($_POST['secure']);
				$super_secure = addslashes($_POST['super_secure']);
				$tags = addslashes($_POST['tags']);
				$isFeatured = addslashes($_POST['isFeatured']);
				$featuredImg = addslashes($_POST['featuredImg']);
				$status = addslashes($_POST['status']);
				$creator = addslashes($_POST['creator']);
				
				$result = addslashes(createPage($page_id, $name, $content, $sidebar, $url, $creator, $status, $secure, $super_secure, $tags, $isFeatured, $featuredImg));
				if($result == 'true' and tagEnter($tags, 'page', $page_id))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Congratulations!';
					var content = 'Page was created successfully.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'edit' & isset($_POST['edit_id']))
			{
				$page_id = addslashes($_POST['page_id']);
				$name = addslashes($_POST['name']);
				$content = addslashes($_POST['content']);
				$sidebar = addslashes($_POST['sidebar']);
				$url = addslashes($_POST['url']);
				$secure = addslashes($_POST['secure']);
				$super_secure = addslashes($_POST['super_secure']);
				$tags = addslashes($_POST['tags']);
				$isFeatured = addslashes($_POST['isFeatured']);
				$featuredImg = addslashes($_POST['featuredImg']);
				$status = addslashes($_POST['status']);
				$creator = addslashes($_POST['creator']);
				
				$edit = 'edit';
				$edit_id = $_POST['edit_id'];
				$result = addslashes(createPage('', $name, $content, $sidebar, $url, $creator, $status, $secure, $super_secure, $tags, $isFeatured, $featuredImg, $edit, $edit_id));
				if($result == 'true' and tagEnter($tags, 'page', $page_id))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Edit successfull';
					var content = 'Changes to the page $name have been saved.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'delete' & isset($_POST['edit_id']))
			{
				$name = addslashes($_POST['name']);
				
				$edit = 'delete';
				$edit_id = $_POST['edit_id'];
				$result = addslashes(createPage('', '', '', '', '', '', '', '', '', $edit, $edit_id));
				if($result == 'true')
				{
					echo "<script type=\"text/javascript\">
					var name = 'Delete successfull';
					var content = 'Page $name has been deleted.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			else
			{
				echo "<script type=\"text/javascript\">
				var name = 'Error';
				var content = 'You did not fill out all the required fields!';
				$(document).ready(function(e) {
					openPopup(name, content);
				});
				</script>";
			}
		}
		
		//Blog post form
		if($_POST['request'] == 'getBlogForm')
		{
			if(isset($_POST['content']))
			{
				postCreationForm($_POST['content'], true);
			}
			else
			{
				postCreationForm('', false);
			}
		}
		
		//Blog Post Update/Edit Processor
		if($_POST['request'] == 'blogProcessor')
		{
			if(adminCheck() & !empty($_POST['name']) & !empty($_POST['content']) & !empty($_POST['creator']) & !empty($_POST['tags']) & !empty($_POST['isFeatured']) & !empty($_POST['featuredImg']) & !isset($_POST['edit']))
			{
				$post_id = addslashes($_POST['post_id']);
				$name = addslashes($_POST['name']);
				$content = addslashes($_POST['content']);
				$tags = addslashes($_POST['tags']);
				$isFeatured = addslashes($_POST['isFeatured']);
				$featuredImg = addslashes($_POST['featuredImg']);
				$status = $_POST['status'];
				$creator = addslashes($_POST['creator']);
				
				$result = addslashes(createPost($post_id, $name, $content, $creator, $status, $tags, $isFeatured, $featuredImg, $status));
				if($result == 'true' and tagEnter($tags, 'post', $post_id))
				{
					echo "
					<script type=\"text/javascript\">
					var name = 'Congratulations!';
					var content = 'Post was created successfully.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "
					<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'edit' & isset($_POST['edit_id']))
			{
				$name = addslashes($_POST['name']);
				$content = addslashes($_POST['content']);
				$tags = addslashes($_POST['tags']);
				$isFeatured = addslashes($_POST['isFeatured']);
				$featuredImg = addslashes($_POST['featuredImg']);
				$status = $_POST['status'];
				$creator = addslashes($_POST['creator']);
				$edit = 'edit';
				$edit_id = $_POST['edit_id'];
				
				$result = addslashes(createPost('', $name, $content, $creator, $status, $tags, $isFeatured, $featuredImg, $edit, $edit_id));
				if($result == 'true' and tagEnter($tags, 'post', $post_id))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Edit successful';
					var content = 'Changes to the post $name have been saved.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'delete' & isset($_POST['edit_id']))
			{
				$name = addslashes($_POST['name']);
				$edit = 'delete';
				$edit_id = $_POST['edit_id'];
				
				$result = addslashes(createPost('', '', '', '', '', $edit, $edit_id));
				if($result == 'true')
				{
					echo "<script type=\"text/javascript\">
					var name = 'Delete successful';
					var content = 'Post $name has been deleted. $edit';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			else
			{
				echo "<script type=\"text/javascript\">
				var name = 'Error';
				var content = 'You did not fill out all the required fields!';
				$(document).ready(function(e) {
					openPopup(name, content);
				});
				</script>";
			}
		}
		
		//Snippet creation/edit form
		if($_POST['request'] == 'getSnipForm')
		{
			if(isset($_POST['content']))
			{
				snippetCreationForm($_POST['content'], true);
			}
			else
			{
				snippetCreationForm('', false);
			}
		}
		
		//Snippet Post/Edit Processor
		if($_POST['request'] == 'snipProcessor')
		{
			if(adminCheck() & !empty($_POST['content']) & !empty($_POST['creator']) & !empty($_POST['tags']) & !isset($_POST['edit']))
			{
				$snip_id = addslashes($_POST['snip_id']);
				$content = addslashes($_POST['content']);
				$tags = addslashes($_POST['tags']);
				$creator = addslashes($_POST['creator']);
				$status = $_POST['status'];
				
				$result = addslashes(createSnippet($snip_id, $content, $status, $creator, $tags));
				if($result == 'true' and tagEnter($tags, 'snippet', $snip_id))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Congratulations!';

					var content = 'Snippet was created successfully.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'edit' & isset($_POST['edit_id']))
			{
				$name = addslashes($_POST['name']);
				$content = addslashes($_POST['content']);
				$status = $_POST['status'];
				$creator = addslashes($_POST['creator']);
				$edit = 'edit';
				$edit_id = $_POST['edit_id'];
				
				$result = addslashes(createSnippet('', $content, $tags, $creator, $status, $edit, $edit_id));
				if($result == 'true' and tagEnter($tags, 'snippet', $edit_id))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Edit successful';
					var content = 'Changes to the snippet $name have been saved.';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					 echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			elseif(adminCheck() & $_POST['edit'] == 'delete' & isset($_POST['edit_id']))
			{
				$name = addslashes($_POST['name']);
				$edit = 'delete';
				$edit_id = $_POST['edit_id'];
				
				$result = addslashes(createSnippet('', '', '', '', '', $edit, $edit_id));
				if($result == 'true')
				{
					echo "<script type=\"text/javascript\">
					var name = 'Delete successful';
					var content = 'Snippet $edit_id has been deleted. $edit';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = '$result';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			else
			{
				echo "<script type=\"text/javascript\">
				var name = 'Error';
				var content = 'You did not fill out all the required fields!';
				$(document).ready(function(e) {
					openPopup(name, content);
				});
				</script>";
			}
		}
		//Profile Information Processor
		if($_POST['request'] == 'profileProcessor')
		{
			if(userCheck())
			{	
				//Prerequisites
				$name = addslashes($_POST['name']);
				$email = addslashes($_POST['email']);
				$location = addslashes($_POST['location']);
				$website = addslashes($_POST['website']);
				$about = addslashes($_POST['about']);
				$image = addslashes($_POST['image']);
				$username = userData('current', 'username');
				include("nc_core/strip_tags.php");
				$sname = strip_tags($name, $tags);
				$semail = strip_tags($email, $tags);
				$slocation = strip_tags($location, $tags);
				$swebsite = strip_tags($website, $tags);
				$simage = strip_tags($image, $tags);
				$sabout = strip_tags($about, $tags);
				$private = addslashes($_POST['isPrivate']);
				$theme = addslashes($_POST['theme']);
				$scss = addslashes($_POST['style']);
				//
				if(adminCheck() & isset($_POST['adminedit']))
				{
					unset($username);
					$username = addslashes($_POST['username']);
					$token = addslashes($_POST['token']);
					$admin = addslashes($_POST['isAdmin']);
					$super = addslashes($_POST['isMod']);
					if(database("UPDATE `nc_users` SET `name` = '$sname', `email` = '$semail', `location` = '$slocation', `website` = '$swebsite', `image` = '$simage', `about` = '$sabout', `theme` = '$theme', `css` = '$scss', `admin` = '$admin', `admin_token` = '$token', `private` = '$private', `super` = '$super' WHERE `username` = '$username'"))
					{
						echo "<script type=\"text/javascript\">
						var name = 'Edit successful';
						var content = 'The information has been updated (Admin Mode)';
						$(document).ready(function(e) {
							openPopup(name, content);
						});
						</script>";
					}
					else
					{
						echo "<script type=\"text/javascript\">
						var name = 'Error';
						var content = 'Something went wrong! Nothing was saved';
						$(document).ready(function(e) {
							openPopup(name, content);
						});
						</script>";
					}
				}
				elseif(!isset($_POST['adminedit']) & database("UPDATE `nc_users` SET `name` = '$sname', `email` = '$semail', `location` = '$slocation', `website` = '$swebsite', `image` = '$simage', `about` = '$sabout', `css` = '$scss', `theme` = '$theme', `private` = '$private' WHERE `username` = '$username'"))
				{
					echo "<script type=\"text/javascript\">
					var name = 'Edit successful';
					var content = 'Your account information has been updated. You must reload the page to view your changes<input type=\"button\" value=\"Reload\" class=\"submitButton right\" onClick=\"window.location.reload()\">';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
				else
				{
					echo "<script type=\"text/javascript\">
					var name = 'Error';
					var content = 'Something went wrong! Nothing was saved';
					$(document).ready(function(e) {
						openPopup(name, content);
					});
					</script>";
				}
			}
			else
			{
				header('location: /');
			}			
		}
		
		//Select user (for Get and modify)
		if($_POST['request'] == 'getUserSelect'){
			$result = database("SELECT `username` from `nc_users`");
			echo "<p class=\"wider\">Select a user:</p>
			<form class=\"editUserForm\" name=\"selectUser\">
			<select class=\"fancySelect wider\" title=\"Select User\" name=\"user\" onChange=\"loadAjax('default',this.value,'getUserInfo', '#editUsers')\">
			<option selected=\"selected\" disabled=\"disabled\">Select...</option>";
			while ($row = mysql_fetch_assoc($result)) // loop through all results
			{ 
				echo "<option value=\"". $row['username']."\">". $row['username']."</option>\n";
			}// loop done
			echo "</select><span class=\"right\"><button onclick=\"ajax_loadContent('txtResult','includes/newUser.php');\" type=\"button\" class=\"submitButton\">Create New</button></span>
			<div id=\"txtResult\"></div>
			</form>\n";
		}
		
		//Get and modify user information
		if($_POST['request'] == 'getUserInfo')
		{
			accountForm($_POST['content'], 'true');
		}
		
		//File Upload Form
		if($_POST['request'] == 'fileUploadProcessor')
		{
			if(!$_FILES['userfile']['name'])
			{
				echo "<script type=\"text/javascript\">
				var name = 'File Upload';
				var content = '";
				$max_filesize = 128000000; 
				$upload_path = 'dropbox/'; 

				$filename = $_FILES['userfile']['name'];
				$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1);

				if(filesize($_FILES['userfile']['tmp_name']) > $max_filesize)
				{
					echo 'The file you attempted to upload is too large.';
				}
				if(!is_writable($upload_path))
				{
					echo 'You cannot upload to the specified directory, please CHMOD it to 777.';
				}
				if(move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_path . $filename))
				{ 
					echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>';
				}
				else
				{
					echo 'There was an error during the file upload.  Please try again.';
				}
				echo "';
				$(document).ready(function(e) {
					openPopup(name, content);
				});
				</script>";
			}
			else
			{
				echo "<script type=\"text/javascript\">
				var name = 'Error';
				var content = 'No file data received';
				$(document).ready(function(e) {
					openPopup(name, content);
				});
				</script>";
			}
		}
		 
		//Image Upload Form
		if ($_POST['request'] == 'getImageForm')
		{
			?>
			<iframe id="myframe" src="nc_load.php?url=nc_core/imageupload/galleryupload.php" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:hidden; width:100%;min-height:300px;"></iframe>
			<?php
		}
		
		//Gallery select/creator
		if ($_POST['request'] == 'getGalleryEditor')
		{
				//Prerequisites
				$upload_id = $_POST['content'];
				$images = database("SELECT * FROM `nc_images` WHERE `upload_id` = '$upload_id'");
				//
				echo "
				<form action=\"nc_core/ajax/ajax.php\"  method=\"post\" enctype=\"multipart/form-data\" id=\"imageEditForm\">
				<div id=\"editAccount\">
				";
				$side = 'left';
				$imageNum = 0;
				while ($row = mysql_fetch_assoc($images)) // loop through images
				{ 
					$imageNum++;
					echo "
					<span class=\"".$side." padding10\">
					<div class=\"padding10\" style=\"background:url('".$row['dir']."') rgba(0,0,0,0.2);background-position:center;background-repeat:no-repeat;background-size:contain;width:100%;height:256px;margin-bottom:15px;\">
					</div>
					<p class=\"fancySelectHead\">Title:</p>
					<textarea name=\"name".$imageNum."\" class=\"form\">".$row['title']."</textarea>
					<p class=\"fancySelectHead\">Description:</p>
					<textarea name=\"desc".$imageNum."\" class=\"form\">".$row['desc']."</textarea>
					<p class=\"fancySelectHead\">Tags:</p>
					<textarea name=\"tags".$imageNum."\" class=\"form\"></textarea>
					<p class=\"fancySelectHead wider\">Post Status:</p>
					<select class=\"fancySelect wider\" name=\"status".$imageNum."\">
					<option value=\"live\" selected=\"selected\">Live</option>
					<option value=\"pending\">Pending</option>
					<option value=\"deleted\">Deleted</option>
					</select>
					<input type=\"hidden\" name=\"imageId".$imageNum."\" value=\"".$row['id']."\" />
					</span>
					";
					if($side == 'left')
					{
						$side = 'right';
					}
					else
					{
						$side = 'left';
					}
					echo "
					<input type=\"hidden\" name=\"numberOfImages\" value=\"".$imageNum."\" />
					<input type=\"hidden\" name=\"upload_id\" value=\"".$upload_id."\" />
					";
				}// loop done
				echo "
				</div>
				<div class=\"clearFix right buttonFix\">
				<input type=\"submit\" value=\"Save\" class=\"submitButton\" id=\"imageEditSave\"/>
				</div>
				<input type=\"hidden\" name=\"request\" value=\"galleryEditProcessor\" />
				</form>
				<script>
				  $(function() {
							$(\"#imageEditForm\").on(\"submit\", function(event) {
								event.preventDefault();
			 
								$.ajax({
									url: \"nc_core/ajax/ajax.php\",
									type: \"post\",
									data: $(this).serialize(),
									success: function(d) {
										parent.openPopup('Success',d);
									}
								});
							});
						});
				</script>
				<script type=\"text/javascript\">
				parent.autoResize('myframe');
				</script>
				";
		}	
				
		//Image Editor (for Image Upload Form)
		$successful = 0;
		if ($_POST['request'] == 'galleryEditProcessor')
		{
			$imageNum = $_POST['numberOfImages'];
			$upload_id = $_POST['upload_id'];
			while ($imageNum > 0) // loop through images
				{ 
					$id = $_POST['imageId'.$imageNum];
					$title = $_POST['name'.$imageNum];
					$desc = $_POST['desc'.$imageNum];
					$tags = $_POST['tags'.$imageNum];
					$status = $_POST['status'.$imageNum];
					$imageNum--;
					if(database("UPDATE `nc_images` SET `title` = '$title', `desc` = '$desc', `tags` = '$tags', `status` = '$status' WHERE `id` = '$id'") and tagEnter($tags, 'image', $id))
					{
						$successful++;
					}
				}// loop done
			if($successful = $_POST['numberOfImages'])
			{
				echo "All changes were saved. You can view your images at <a href=\"gallery-$upload_id\">gallery-$upload_id</a>";
			}
			else
			{
				echo 'One or more errors occurred';
			}
		}
		
		//Get Services Status
		if ($_POST['request'] == 'getServiceStatus')
			{
				// Turn off all error reporting because fsockopen always causes dumb warnings
				error_reporting(0);

				$servicesNum = 0;

				echo '<p align="center" class="animateFast portalServices">';
				if(serviceStatus(21, 'localhost'))
				{
					echo '<a href="page-services"><img src="'.themeDir().'images/icons/ftp_large.png" alt="ftp" /></a>';
					++$servicesNum;
				}
				else
				{
					echo '<a href="page-services"><img class="offline" src="'.themeDir().'images/icons/ftp_large.png" alt="Apache" /></a>';
				}
				if(serviceStatus(25565, 'localhost'))
				{
					echo '<a href="page-minecraft"><img src="'.themeDir().'images/icons/minecraft_large.png" alt="Minecraft" /></a>';
					++$servicesNum;
				}
				else
				{
					echo '<a href="page-minecraft"><img class="offline" src="'.themeDir().'images/icons/minecraft_large.png" alt="Apache" /></a>';
				}
				if(serviceStatus(8123, 'localhost'))
				{
					echo '<a href="http://minecraft.catserver.net/" target="blank"><img src="'.themeDir().'images/icons/map.png" alt="Minecraft Map" /></a>';
					++$servicesNum;
				}
				else
				{
					echo '<a href="http://minecraft.catserver.net/" target="blank"><img class="offline" src="'.themeDir().'images/icons/map.png" alt="Apache" /></a>';
				}
				if(serviceStatus(80, 'localhost'))
				{
					echo '<a href="page-services"><img src="'.themeDir().'images/icons/apache.png" alt="Apache" /></a>';
					++$servicesNum;
				}
				else
				{
					echo '<a href="page-services"><img class="offline" src="'.themeDir().'images/icons/apache.png" alt="Apache" /></a>';
				}

				echo '</p><p align="center">';

				if($servicesNum == 4)
				{
					echo 'All Nebula Core services are operating normally.';
				}
				else
				{
					echo 'One or more Nebula Core services are offline. Check blog for updates.';
				}
				echo '</p>';
			}
	}
	else
	{
		header('location: /');
	}
}
?>
