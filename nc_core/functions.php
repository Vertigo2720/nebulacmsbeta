<?
//-------------------------//
//NEBULA CMS CORE FUNCTIONS//
//-------------------------//

//No php_http? No problem!
if(!function_exists('http_post_fields'))
{
	function http_post_fields($url, $data, $headers=null) {
		
		$data1 = http_build_query($data);	
		$opts = array('http' => array('method' => 'POST', 'content' => $data1));
		
		if($headers) {
			$opts['http']['header'] = $headers;
		}
		$st = stream_context_create($opts);
		$fp = fopen($url, 'rb', false, $st);
		
		if(!$fp) {
			return false;
		}
		return stream_get_contents($fp);
	}
}

//Get Gravatar
function get_gravatar( $email, $s = 2048, $d = '404', $r = 'x', $img = false, $atts = array() )
{
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

//Find current page URL, Pretty important for comments and stuff
function getUrl()
{
 $pageURL = 'http';
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

//Finds the name of the current page (use will have to be confirmed)
function getPageName()
{
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

//Prints the root directory of the current theme
function themeDir()
{
	//Prerequisites
	$theme = $GLOBALS['theme'];
	//
	return "nc_themes/$theme/";
}

//Create Cookie
function bakeCookie($title, $data, $duration)
{
	//Prerequisites
	$title_s = addslashes($title);
	$data_s = addslashes($data);
	$duration_s = addslashes($duration);
	//
	setcookie($title_s, $data_s, $duration_s, '/');
}

//Create general text block/item header
function generalHeader($name = 'Default', $anchor = '_none')
{
	echo '<div class="mainDivHeader"><h1>'.$name.'';
	if($anchor != '_none')
	{
		echo  '<a name="'.$anchor.'"></a>';
	}
	echo '</h1></div>';
}

//Create general header with a link
function linkHeader($name = 'Default', $link = '#', $anchor = '_none')
{
	echo '<div class="mainDivHeader"><a href="'. $link .'" target="_blank"><h1>'.$name.'';
	if($anchor != '_none')
	{
		echo  '<a name="'.$anchor.'"></a>';
	}
	echo '</h1></a></div>';

}

//Create header for main blog/front page
function blogHeader($name = 'Default', $date = '00-00-00', $id = '0', $showall = '0', $anchor = '_none')
{
	echo '<div class="mainDivHeader"><h1>'.$date.':&nbsp;'.$name.'';
	if($anchor != '_none')
	{
		echo '<a name="'.$anchor.'"></a>';
	}
	echo '</h1>';
	if ($showall == 1){
		echo '<a class="moreArrow" href="/">◄';
	}
	else
	{
		echo '<a class="moreArrow" href="blog-'.$id.'">►';
	}
	echo '</a></div>';
}

//Create Image header for main blog/front page
function imageHeader($name = 'Default', $date = '00-00-00', $id = '0', $image = false, $creator = 'Default', $showall = '0', $anchor = '_none')
{
	if($image == true)
	{
		echo '<div style="background-image:url(\''.themeDir().$image.'\');" ';
		if($showall == 1)
		{
			echo 'class="mainDivImageHeader mainDivImageHeaderL" onClick="openPopup(\'View Image\',\''.themeDir().$image.'\',\'getImageViewer\')">';
			if(!empty($date))
			{
				echo $name.'<p>'.$date.' by '.$creator.'</p>';
			}
			else
			{
				echo $name;
			}
		}
		else
		{
			echo 'class="mainDivImageHeader" onClick="window.location.href=\'blog-'.$id.'\'"><h4>';
			if(!empty($date))
			{
				echo $date.':&nbsp;'.$name.'';
			}
			else
			{
				echo $name;
			}
		}
	}
	else
	{
		echo '<div class="mainDivHeader"><h1>';
		if(!empty($date))
		{
			echo $date.':&nbsp;'.$name;
		}
		else
		{
			echo $name;
		}
		if($anchor != '_none')
		{
			echo '<a name="'.$anchor.'"></a>';
		}
	}
	if($showall != 1 || $image == false)
	{
		echo '</h4>';
	}
	else
	{
		echo '</h1>';
	}
	if ($showall == 1 && !empty($id)){
		echo '<a class="moreArrow" href="/">◄';
	}
	elseif(!empty($id))
	{
		echo '<a class="moreArrow" href="blog-'.$id.'">►';
	}
	echo '</a></div>';
}

//Error message generator
function errMsg($message = '_default')
{
	?>
	<script type="text/javascript">
	$( window ).load(function() {
		openPopup('<? echo $GLOBALS['site_name']."','".$message; ?>','getPopup')
	});
	</script>
	<?
}

//Show Featured Slider
function getFeatured($catagories = null)
{
	?>
	<?php
	//Prerequisites
	unset($latestPage, $latestPost, $featuredPage, $featuredPost);
	$latestPage = databaseArray("SELECT * FROM nc_pages WHERE `status` = 'live' AND `isFeatured` = '0' ORDER BY id DESC LIMIT 1");
	$latestPost = databaseArray("SELECT * FROM nc_posts WHERE `status` = 'live' AND `isFeatured` = '0' ORDER BY id DESC LIMIT 1");
	$featuredPage = databaseArray("SELECT * FROM nc_pages WHERE `status` = 'live' AND `isFeatured` = TRUE ORDER BY id DESC LIMIT 1");
	$featuredPost = databaseArray("SELECT * FROM nc_posts WHERE `status` = 'live' AND `isFeatured` = TRUE ORDER BY id DESC LIMIT 1");
	$numFeatured = 0;
	$name1 = 0;
	$name2 = 0;
	$name3 = 0;
	$name4 = 0;
	
////Begin Featured Box
	echo "<div class=\"featuredBox noSelect\" id=\"";
	if(!userCheck())
	{ 
		echo 'portalFeatured';
	}else
	{
		echo 'featured';
	}
	echo "\">";
	
////Function for making the TABS
	function featuredTab($number = 1, $name = 'Default', $date = null, $url = 'home', $featuredImage = null, $author = "Default")
	{
		if(empty($date))
		{
			$headerText = $name;
		}
		else
		{
			$headerText = $name.'<p>'.$date.' by '.$author.'.</p>';
		}
		echo "<div onClick=\"window.location.href='".$url."'\" id=\"featuredTab".$number."\" class=\"featuredPadding featuredTab\"
		style=\"background: url('";
		if(!empty($featuredImage))
		{
			echo themeDir().$featuredImage;
		}
		else{
			echo themeDir(). 'images/featured/postPlaceholder.png';
		}
		echo "') no-repeat top center; background-size:cover;\">
		<span class=\"featuredText\">$headerText</span>
		</div>";
	}
////
	
	echo "<div class=\"featuredTabContainer\">";
	
////Featured Post
	if(!empty($featuredPost))
	{
		$id = $featuredPost['id'];
		$name3 = stripslashes($featuredPost['name']);
		$post_id = $featuredPost['post_id'];
		$date = $featuredPost['date'];
		$featuredImage = $featuredPost['featuredImg'];
		$isFeatured = $featuredPost['isFeatured'];
		$author = $featuredPost['creator'];
		$url = 'blog-'.$post_id;
		
		if(!empty($id))
		{
			featuredTab(1, $name3, $date, $url, $featuredImage);
		}
		++$numFeatured;
	}
	unset($id, $post_id, $date, $featuredImage, $isFeatured, $url, $author);
	
////Featured Page
	if(!empty($featuredPage))
		{
		$id = $featuredPage['id'];
		$name4 = stripslashes($featuredPage['name']);
		$page_id = $featuredPage['page_id'];
		$date = substr($featuredPage['date'], 0, 10);
		$url = $featuredPage['url'];
		$featuredImage = $featuredPage['featuredImg'];
		$isFeatured = $featuredPage['isFeatured'];
		$author = $featuredPage['creator'];
		$url = 'page-'.$url;
		
		if(!empty($id))
		{
			featuredTab(2, $name4, $date, $url, $featuredImage, $author);
		}
		++$numFeatured;
	}
	unset($id, $page_id, $date, $featuredImage, $isFeatured, $url);
	
////Latest Post
	if(!empty($latestPost))
	{
		$id = $latestPost['id'];
		$name1 = stripslashes($latestPost['name']);
		$post_id = $latestPost['post_id'];
		$date = $latestPost['date'];
		$featuredImage = $latestPost['featuredImg'];
		$author = $latestPost['creator'];
		$url = 'blog-'.$post_id;
		
		if(!empty($id))
		{
			featuredTab(3, $name1, $date, $url, $featuredImage, $author);
		}
		++$numFeatured;
	}
	unset($id, $post_id, $date, $featuredImage, $url, $author);
	
////Latest Page
	if(!empty($latestPage))
	{
		$id = $latestPage['id'];
		$name2 = stripslashes($latestPage['name']);
		$page_id = $latestPage['page_id'];
		$date = substr($latestPage['date'], 0, 10);
		$url = $latestPage['url'];
		$featuredImage = $latestPage['featuredImg'];
		$author = $latestPage['creator'];
		$url = 'page-'.$url;
	
		if(!empty($id))
		{
			featuredTab(4, $name2, $date, $url, $featuredImage, $author);
		}
		++$numFeatured;
	}
	unset($id, $page_id, $date, $featuredImage, $url, $author);
	
	echo "</div><div id=\"featuredSelect\">";
	
////Featured Select Buttons
	if($name3){echo "<span class=\"animateFast\" onMouseOver=\"ShowContent('featuredTab1'), HideContent('featuredTab2'), HideContent('featuredTab3'), HideContent('featuredTab4')\" class=\"featuredSelector featuredSelector1\"><h4>". substr($name3, 0, 25) ."</h4></span>";}
	else{echo "<span class=\"featuredNoLink\"></span>";}
	if($name4){echo "<span class=\"animateFast\" onMouseOver=\"ShowContent('featuredTab2'), HideContent('featuredTab1'), HideContent('featuredTab3'), HideContent('featuredTab4')\" class=\"featuredSelector featuredSelector2\"><h4>". substr($name4, 0, 25) ."</h4></span>";}
	else{echo "<span class=\"featuredNoLink\"></span>";}
	if($name1){echo "<span class=\"animateFast\" onMouseOver=\"ShowContent('featuredTab3'), HideContent('featuredTab1'), HideContent('featuredTab2'), HideContent('featuredTab4')\" class=\"featuredSelector featuredSelector3\"><h4>". substr($name1, 0, 25) ."</h4></span>";}
	else{echo "<span class=\"featuredNoLink\"></span>";}
	if($name2){echo "<span class=\"animateFast\" onMouseOver=\"ShowContent('featuredTab4'), HideContent('featuredTab1'), HideContent('featuredTab2'), HideContent('featuredTab3')\" class=\"featuredSelector featuredSelector4\"><h4>". substr($name2, 0, 25) ."</h4></span>";}
	else{echo "<span class=\"featuredNoLink\"></span>";}
	
////End Featured Box
	echo "
	</div>
	</div>";
}

//--------------//
//USER FUNCTIONS//
//--------------//

//Find current user information breakdown
function userData($type = 'current', $value = 'none')
{
	//Prerequisites
	$site_name = $GLOBALS['site_name'];
	//
	if($type == 'current' && isset($site_name) && isset($_COOKIE["$site_name" . "user"]))
	{
		$username = $_COOKIE["$site_name" . "user"];
		$result = databaseArray("SELECT * from `nc_users` WHERE `username` = '$username'");
	}
	elseif($type == 'all')
	{
		$users = database("SELECT * from `nc_users`");
	}
	else
	{
		$username = $type;
		$result = databaseArray("SELECT * from `nc_users` WHERE `username` = '$username'");
	}
	if(isset($result))
	{
		if($value != 'none' & isset($result["$value"]))
			{
				return $result["$value"];
			}
		else
			{
				global $username, $name, $email, $joindate, $location, $website, $image, $about, $css, $usertheme, $private, $super, $admin;
				$username = $result["username"];
				$name = $result["name"];
				$email = $result["email"];
				$joindate = $result["joindate"];
				$location = $result["location"];
				$website = $result["website"];
				$image = $result["image"];
				$about = $result["about"];
				$css = $result["css"];
				$usertheme = $result["theme"];
				$private = $result["private"];
				$super = $result["super"];
				$admin = $result["admin"];
			}
	}
	elseif(isset($users))
	{
		while($row = mysql_fetch_array($users))
			{
				$userArray[] = $row;
			}
			return $userArray;
	}
	else
	{
		return false;
	}
}

//Find current user information array
function userDataArray($type = 'current')
{
	$site_name = $GLOBALS['site_name'];
	if($type == 'current')
	{
		if(isset($_COOKIE["$site_name" . "user"]))
		{
			$username = $_COOKIE["$site_name" . "user"];
			$result = databaseArray("SELECT * from `nc_users` WHERE `username` = '$username'");
		}
	}
	elseif($type == 'all')
	{
		$users = database("SELECT * from `nc_users`");
	}
	else
	{
		$username = $type;
		$result = databaseArray("SELECT * from `nc_users` WHERE `username` = '$username'");
	}
	if(isset($result))
	{
		return $result;
	}
	elseif(isset($users))
	{
		while($row = mysql_fetch_array($users))
			{
				$userArray[] = $row;
			}
			return $userArray;
	}
	else
	{
		return false;
	}
}

//Checks if a user is logged in.
function userCheck($current = 'checkCookieStatus')
{
	$site_name = $GLOBALS['site_name'];
	if(isset($_COOKIE["$site_name" . "user"]))
	{
		if($current == 'returnCurrent'){
			return $_COOKIE["$site_name" . "user"];
		}
		elseif($current == 'echoCurrent'){
			echo $_COOKIE["$site_name" . "user"];
		}
		else
		{
		return true;
		}
	}
	else
	{
		return false;
	}
}

//Checks if the user is logged in as an Admin. If they are, confirm with the database, then enter the admin UI
function adminCheck()
{
	$site_name = $GLOBALS['site_name'];
	if(isset($_COOKIE["$site_name" . "user"]))
	{
		if(!empty(userData($_COOKIE["$site_name" . "user"], 'admin')))
		{	
			return true;
		}
	}
	else
	{
		return false;
	}
}

//Checks if the user is logged in Moderator
function modCheck()
{
	$site_name = $GLOBALS['site_name'];
	if(!empty(userData($_COOKIE["$site_name" . "user"], 'super')))
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Log a user or administrator in//
function login($username = null, $pass = null, $admin = null)
{
	//Prerequisites
	$username_s = addslashes($username);
	$pass_s = addslashes($pass);
	$result = userDataArray($username_s);
	$md5pass = md5($pass_s);
	$site_name = $GLOBALS['site_name'];
	//
	
	// if form has been submitted
	if (isset($_POST['submit']))
	{
		// makes sure they filled it in
		if(!$_POST['username'] | !$_POST['pass']) {
			$_SESSION['notify'] = 'You forgot a field!';
			header("refresh: 0; url=/");
			exit;
		}
		// checks if the user exists
		if(!userData($username_s, 'username')){
			$_SESSION['notify'] = 'Sorry, that username is not in our database.';
			header("refresh: 0; url=/");
			exit;
		}
		// checks if the user logging in is an administrator
		if(userData($username_s, 'admin') == 1){
			$admin_token = userData($username_s, 'admin_token');
			$admin_login = true;
		}
		// checks if the password is correct
		$saved_pass = $result['password'];
		if($md5pass =! $saved_pass)
		{
			$_SESSION['notify'] = 'Sorry, incorrect password.';
			header("refresh: 0; url=/");
			exit;
		}
		else
		{
		// if login is ok then we add a cookie
		bakeCookie("$site_name" . "user", $username_s, time() + $GLOBALS['login_duration']);
		bakeCookie("$site_name" . "pass", $md5pass, time() + $GLOBALS['login_duration']);
		if($admin_login == true)
		{
			bakeCookie("$site_name" . "admin", $admin_token, time() + $GLOBALS['login_duration']);
		}
		header("refresh: 0; url=/");
		exit;
		}
	}
}

//Logout a user or administrator//
function logout($totality = "all"){
	//Prerequisites
	$site_name = $GLOBALS['site_name'];
	//
	
	if($totality == "admin"){
		bakeCookie("$site_name" . "admin", '', time() - 3600);
	}
	else
	{
		bakeCookie("$site_name" . "user", '', time() - 3600);
		bakeCookie("$site_name" . "admin", '', time() - 3600);
	}
	header("refresh: 0; url=/page-loggedout");
}

//------------------------//
//COMMENT SYSTEM FUNCTIONS//
//------------------------//

//Function to make the date pretty
function niceday($dated) 
{
	$dated = str_replace(array(" ",":"),"-",$dated);
	list($year,$month,$day,$hour,$minute) = explode("-",$dated);
	// you can edit this line to display date/time in your preferred notation
	$niceday = @date("\P\o\s\\t\e\d\ \a\\t\ g:ia \o\\n\ l, F jS, Y",mktime($hour,$minute,0,$month,$day,$year));
	echo $niceday;
}

//Show the number of comments associated with a post
function numComments($page_id = 1)
{
	$result = database("SELECT count(*) FROM `nc_comments` WHERE `page_id` = '$page_id'");
	return mysql_result($result,0,"count(*)");
}

//Function to generate the 'Post a Comment' link. Used by getComments()
function getCommentOptions($page_id = 1)
{
	//Prerequisites
	$public_comments_enable = $GLOBALS['public_comments_enable'];
	$comments_disable_post = $GLOBALS['comments_disable_post'];
	$loggedin = 0;
	if(userCheck())
	{
		$loggedin = 1;
		userData('current');
	}
	$url = getUrl();
	$count = numComments($page_id);
	//
	if($comments_disable_post != 1)
	{
		if($loggedin == 1 & $count == 0){
			if (!isset($user))
			{
				$user = 'Unknown';
			}
			echo "<a onClick=\"ReverseContentDisplay('".$page_id."');\" name=\"Post\" title=\"Post\" class=\"noselect\"> Post a comment...</a>";
		}
		else
		{
			if($loggedin != 1 & $public_comments_enable != 1){
			echo " Login to post.";
			}
			elseif(($loggedin != 1 & $public_comments_enable = 1) & $count == 0)
			{
				echo "<a onClick=\"ReverseContentDisplay('".$page_id."');\" name=\"Post\" title=\"Post\" class=\"noselect\"> Post comment as guest...</a>";
			}
		}
	}
	else
	{
		echo 'Comments have been disabled.';
	}
}

//Function to build the comment output. Used by getComments()
function getCommentOutput($page_id = 67, $comm_start = false, $comm_show = false, $comm_limit = false)
{	
	//Prerequisites
	if(userCheck()){
		$username = userData('current', 'username');
	}
	$comments_disable_edit = $GLOBALS['comments_disable_edit'];
	$comments_disable_delete = $GLOBALS['comments_disable_delete'];
	
	if($comm_show && $comm_limit)
	{
		$result = database("SELECT * FROM `nc_comments` WHERE page_id=$page_id ORDER by dated ASC LIMIT $comm_show, $comm_limit");
	}
	elseif($comm_start && !isset($comm_show))
	{
		$result = database("SELECT * FROM `nc_comments` WHERE page_id=$page_id ORDER by dated ASC LIMIT $comm_start");
	}
	else
	{
		$result = database("SELECT * FROM `nc_comments` WHERE page_id=$page_id ORDER by dated ASC");
	}
	//
	echo "<div class='commentBlock'>";
	
	if ($result)
	{
		while ($myrow = mysql_fetch_assoc($result)) // loop through all results
		{ 
			echo "<div class='comment' id='comment_".$myrow['id']."'><a name='".$myrow['id']."'></a><p class='commentName'>";
			if($myrow['is_approved'] == 0)
			{
				echo 'This comment has been deleted.';
			}
			else
			{
				if (!$myrow['name']) {
					echo "Anonymous";
				}
				else
				{
					echo "<a href='profile-". $myrow['name']."'>". $myrow['name']."</a>";
				}
				echo " - ";
				if (!$myrow['location']) {
					echo 'Location not available';
				}
				else
				{
					echo $myrow['location'];
				}
				echo "<p class='commentText'>";
				$comments = stripslashes($myrow['comments']);
				echo nl2br($comments);
				echo "</p><br /><p class='commentDate'>";
				niceday($myrow['dated']);
				if(userCheck() and strtoupper(userCheck('current')) == strtoupper($myrow['name']) or adminCheck() == true)
				{
					if($comments_disable_edit != 1)
					{
						
						echo "</p><p class=\"commentEdit\"><a onclick=\"loadAjax('". $myrow['id']."', 'page_id=".$page_id."', 'editComment', 'comment_". $myrow['id']."');\">Edit</a>";
					}
					if($comments_disable_delete != 1)
					{
						echo "<a style=\"margin-left:5px;\" onclick=\"loadAjax('". $myrow['id']."', 'page_id=".$page_id."', 'editComment', 'comment_". $myrow['id']."');\">Delete</a>";
					}
				}
			}
			echo "</p></div>\n";
		}
	}// loop done
	echo "</div>\n";
}

//Function to get the Post Comment field

function getCommentPost($page_id = 1) 
{
	//Prerequisites
	if(userCheck())
	{
		$loggedin = 1;
		$username = userData('current', 'username');
		$location = userData('current', 'location');
	}
	$url = getUrl();
	$count = numComments($page_id);
	$public_comments_enable = $GLOBALS['public_comments_enable'];
	//
	if($loggedin = 1 or $public_comments_enable = 1)
		{	
		echo '
		<div class="comment">
		<div id="'.$page_id.'_replyShow">
		<form id="'.$page_id.'_mainForm" name="form" action="nc_comments/comments_process.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="page_id" value="'.$page_id.'" />
		<input type="hidden" name="url" value="'.$url.'" />
		<textarea id="form_'.$page_id.'" name="comments" rows="2" tabindex="1" placeholder="Enter a comment..." onfocus="ShowContent(\''.$page_id.'_commentControls\');"></textarea>
		<span style="display:none;" id="'.$page_id.'_commentControls">';
		if(!isset($username))
		{
			echo '<p align="right"><span class="commentGuestForm"><img id="captcha" src="nc_core/securimage/securimage_show.php" alt="CAPTCHA Image" /><br />
				  <a href="#" onclick="document.getElementById(\'captcha\').src = \'/securimage/securimage_show.php?\' + Math.random(); return false">[ Different Image ]</a><br />
				  <input type="text" name="captcha_code" maxlength="6" placeholder="Captcha..."/><br />
				  <input type="text" name="name" placeholder="Name..." /><br />
				  <input type="text" name="location" placeholder="Location..." /><br />';
		}
		else
		{
			echo '<input type="hidden" name="name" value="'.$username.'" />
				  <input type="hidden" name="location" value="'.$location.'" />
				  <p align="right"><span>';
		}
		if ($count == 0){
			echo '<button type="button" onclick="animateShowHide('.$page_id.');" name="Cancel" title="Cancel">Cancel</button>';
		}
		echo '<input type="submit" value="Submit" class="button" />
			</span>
			</p>
			</span>
			</form>
            </div>';?>
           	<script>
			/* attach a submit handler to the form */
			$("#<? echo $page_id; ?>_mainFrom").submit(function(event) {
			
			/* stop form from submitting normally */
			event.preventDefault(); 
			
			/* get some values from elements on the page: */
			var $form = $( this ),
			code = $form.find( 'input[name="code"]' ).val()
			name = $form.find( 'input[name="name"]' ).val();
			
			/* Send the data using post and put the results in a div */
			$.post( 'nc_comments/comments_process.php', { name: name, code: code },
			function( data ) {
			var content = $( data );
			$( "#<? echo $page_id; ?>_replyShow" ).empty().append( content );
			}
			);
			});
			</script>
            </div>
            <?
		}
}

//Function to build the comment block
function getComments($page_id = 1, $showall = 0)
{
	//Prerequisites
	if(userCheck())
	{
		$loggedin = 1;
	}
	else
	{
		$loggedin = 0;
	}
	if(adminCheck())
	{
		$admin = 1;
	}
	else
	{
		$admin = 0;
	}
	$url = getUrl();
	
	$comm_limit = $GLOBALS['num_comments_show'];
	$public_comments_enable = $GLOBALS['public_comments_enable'];
	$comments_disable_edit = $GLOBALS['comments_disable_edit'];
	$comments_disable_delete = $GLOBALS['comments_disable_delete'];
	$comments_enabled = $GLOBALS['comments_enabled'];
	//
	if($comments_enabled == 1)
	{
		echo "<div class=\"commentHR\"><div class=\"postComment\"><strong>";
		$count = numComments($page_id);
		if(!$count){
			echo 'No comments have been posted.';
			getCommentOptions($page_id);
			echo "</strong></div></div>";
		}
		else
		{	
				
			if($count == 1){
				echo 'There is one comment.';
				if($comments_enabled == 1)
				getCommentOptions($page_id);
			}
			else
			{
				if($count > $comm_limit){
						if($showall == 1){
							echo 'There are '. $count.' comments.';
							getCommentOptions($page_id);
						}
						else
						{	
							echo "<div id='". $page_id."_morebutton' class='noselect animate'><a onClick=\"AnimateShowHide('".$page_id."_more'); ReverseContentDisplay('".$page_id."_morebutton'); ReverseContentDisplay('".$page_id."_lessbutton')\" name=\"More\" title=\"More\" class=\"noselect\">View all ". $count." comments.</a>";

							getCommentOptions($page_id);
							echo "</div>";
							echo "<div id='". $page_id."_lessbutton' style='display:none;' class='noselect animate'><a onClick=\"AnimateShowHide('".$page_id."_more'); ReverseContentDisplay('".$page_id."_lessbutton'); ReverseContentDisplay('".$page_id."_morebutton')\" name=\"Less\" title=\"Less\" class=\"noselect\">View less comments.</a>";
							getCommentOptions($page_id);
							echo "</div>";
						}
					}
					else
					{
						echo 'There are '. $count.' comments.';
						getCommentOptions($page_id);
					}
			}
		echo "</strong></div></div>";
		$comm_start = $count - $comm_limit;
		$display = '';
		if ($count <= 3){
			$comm_show = $comm_limit;
		}
		else
		{	
			$comm_show = $comm_start;
			$display = 'display: none;';
		}
		if ($showall == 1){
			$display = 'display: block;';
		}
		echo '<div id="'.$page_id.'_more" style="'.$display.'">';
		getCommentOutput($page_id, $comm_start);
		echo '</div>';
		getCommentOutput($page_id, '', $comm_show, $comm_limit);
		}
		if ($loggedin == 1 or $public_comments_enable == 1)
		{
			if($count != 0)
			{
				$display = 'display: block;';
			}
			else
			{
				$display = 'display: none;';
			}
			echo "<div id='". $page_id."' style='".$display."'>";
			getCommentPost($page_id);
			echo "</div>";
		}
	}
}

//--------------------------------------------//
//TAGS, CODES, AND ADVANCED CONTENT MANAGEMENT//
//--------------------------------------------//

//Enter tags for object into database
function tagEnter($tags = false, $type = 'post', $object = '1')
{
	//Prerequisites
	$tags = explode(',',$tags);
	$total = count($tags);
	$complete = 0;
	//
	foreach($tags as $tag) 
	{
		database("INSERT INTO `nc_tags` (tag, type, object) VALUES ('$tag', '$type', '$object')");
		$complete++;
	}
	if($total == $complete)
	{
		return true;
	}
}

//Find tags for object in database
function tagFinder($tags = false, $type = 'all')
{
	//Prerequisites
	//
	if(is_array($tags))
	{
		$allResults = array();
		foreach($tags as $tag) 
		{
			$result = database("SELECT `object` FROM `nc_tags` WHERE `tag` = '$tag' AND `type` = '$type'");
			while ($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				$objects[] = $row[0];
			}
			$allResults[$tag] = $objects;
		}
		return $allResults;
	}
	elseif($tags)
	{
		$result = database("SELECT `object` FROM `nc_tags` WHERE `tag` = '$tags' AND `type` = '$type'");
		while ($row = mysql_fetch_array($result, MYSQL_NUM))
		{
			$objects[] = $row[0];
		}
		return $objects;
	}
	else
	{
		return false;
	}
}

//Scan site content for custom code and replace with desired content
function regexFindCode($scan = null, $display = 'full')
{
	//Prerequisites
	$regex = '/\[([\a-zA-Z0-9,: -\\/]+)\]/U';
	$breakdownCode = function($matches)
	{
		$matches = array_filter($matches);
		$match = $matches[1];
		if(!empty($match))
		{
			$isFunction = explode(',', $match);
			$display = 'full';
			
			//$bbcodeRegex = '/([a-zA-Z]+)/';
			//preg_match($bbcodeRegex, $match, $isBBCode);
			
			//$bbcodeCloseRegex = '/(\/)([a-zA-Z]+)/';
			//preg_match($bbcodeCloseRegex, $match, $isBBCodeClose);
			//print_r($isBBCode);
			if(!empty($isFunction))
			{
				$functionAr = explode(':', $isFunction[0]);
				$function = $functionAr[0];
				
				if(count($isFunction) > 2)
				{
					$isFunction[0] = $functionAr[1];
					$requested = $isFunction;
				}
				else
				{
					$requested = $functionAr[1];
				}
				$matchedString = serialize($isFunction);
				if(function_exists($function) and in_array($function, allowForEmbed))
				{
					if($display == 'preview')
					{
						return $function($requested, $display);
					}
					elseif($display == 'full')
					{
						return $function($requested);
					}
					else
					{
						return "<span class=\"redText\">Nebula CMS Error: Invalid display mode ($display). Please check your configuration.</span>";
					}
				}
				elseif((!in_array($function, allowForEmbed)))
				{
					return "<span class=\"redText\">Nebula CMS Error: The requested operation is not allowed - $function - $requested - ($matchedString). Please check your configuration.</span>";
				}
				else
				{
					return "<span class=\"redText\">Nebula CMS Error: Could not find requested content in ($matchedString).</span>";
				}
			}
			elseif(!empty($isBBCode and !empty($isBBCodeClose)))
			{
				$code = $isBBCode[1];
				return codeToHtml($code);
			}
			elseif(!empty($isBBCodeClose))
			{
				$close = 'close';
				return codeToHtml($code, $close);
			}
			else
			{
				$matchedString = $match;
				return "<span class=\"redText\">Nebula CMS Error: Could parse code in ($matchedString).</span>";
			}
		}
	};
	//
	return preg_replace_callback(
		$regex,
		$breakdownCode,
		$scan);
}

//Parse parameters passed via Nebula CMS Code Syntax to function
function autoCodeParse($type = 'image', $parameters = null)
{
	//Prerequisites
	$tagRequest = $userRequest = $galRequest = $limitRequest = $dateRequest = null;
	$parameters = implode(',',$parameters);
	$buildRequestString = "SELECT * FROM `nc_".$type."s` WHERE (`status` = 'LIVE' ";
	////Things to look for
		preg_match("/(user)(\:)([a-zA-Z]+)/x", $parameters, $user);
		preg_match("/(gal)(\:)([a-zA-Z]+)/x", $parameters, $gal);
		preg_match_all("/(tag)(\:)([a-zA-Z0-9]+)/x", $parameters, $allTags);
		preg_match("/(tags)(\:)(\")([a-zA-Z0-9, ]+)(\")/x", $parameters, $multiTag);
		preg_match("/(limit)(\:)([0-9]+)/x", $parameters, $limit);
		preg_match("/(latest)/x", $parameters, $latest);
		preg_match("/(newest)/x", $parameters, $newest);
		preg_match("/(oldest)/x", $parameters, $oldest);
		preg_match("/(date)(\:)([a-zA-Z0-9-_ ]+)/x", $parameters, $date);
		preg_match("/(quiet)/x", $parameters, $quiet);
		preg_match("/(silent)/x", $parameters, $silent);
	////
	
	//
	
	//Check for and define tags
	if(in_array('tag', $allTags[1]) or in_array('tags',$multiTag))
	{
		$buildRequestString = $buildRequestString."AND `id` IN (";
		if(in_array('tags',$multiTag))
		{
			$tags = explode(' ',$multiTag[4]);
		}
		else
		{
			$tags = $allTags[3];
		}
		$tag = $tags[0];
		//Do this if there's more than one tag defined (in one way or another)
		if(count($tags) > 1)
		{
			$tagRequest = "tagged as \"";
			$totalTags = count($tags);
			$onTag = 0;
			foreach ($tags as $tag)
			{
				$taggedobjects = tagFinder($tag, $type);
				$totalobjects = count($taggedobjects);
				$onobject = 0;
				foreach($taggedobjects as $taggedobject)
				{
					$buildRequestString = $buildRequestString.$taggedobject;
					$onobject++;
					if($totalobjects != $onobject)
					{
						$buildRequestString = $buildRequestString.',';
					}
				}
				$onTag++;
				if($totalTags != $onTag)
				{
					$buildRequestString = $buildRequestString.',';
					$tagRequest = $tagRequest.$tag.", ";
				}
				else
				{
					$tagRequest = $tagRequest.$tag."\" ";
				}
			}
		}
		//Or do this if there's only one tag
		else
		{
			$taggedobjects = tagFinder($tag, 'object');
			$totalobjects = count($taggedobjects);
			$onobject = 0;
			foreach($taggedobjects as $taggedobject)
			{
				$buildRequestString = $buildRequestString.$taggedobject;
				$onobject++;
				if($totalobjects != $onobject)
				{
					$buildRequestString = $buildRequestString.',';
				}
			}
			$tagRequest = "tagged as ".$tag;
		}
		$buildRequestString = $buildRequestString.') ';
	}
	//Check for and define parameters
	if(in_array('gal', $gal) or in_array('parameters', $gal))
	{
		$gal = $gal[3];
		$buildRequestString = $buildRequestString."AND `gallery` = '$gal' ";
		$galRequest = "in parameters ".$gal." ";
	}
	//Check for and define user
	if(in_array('user', $user))
	{
		$creator = $user[3];
		$buildRequestString = $buildRequestString."AND `creator` = '$creator' ";
		$userRequest = "uploaded by ".$creator." ";
	}
	//Check for and define date range
	if(in_array('date', $date) xor in_array('newest', $newest) xor in_array('latest', $latest) xor in_array('oldest', $oldest))
	{
		if(($date[3] == 'newest') xor isset($newest[0]) xor isset($latest[0]))
		{
			$buildRequestString = $buildRequestString.") ORDER BY `date` DESC";
			$dateRequest = " starting with the ".$date[3].$newest[0].$latest[0];
		}
		elseif(($date[3] == 'oldest') xor isset($oldest[0]))
		{
			$buildRequestString = $buildRequestString.") ORDER BY `date` ASC";
			$dateRequest = " starting with the ".$date[3].$oldest[0];
		}
		else
		{	
			$newDate = date("Y-m-d", strtotime($date[3]));
			$buildRequestString = $buildRequestString."AND `date` = '$newDate') ORDER BY `date` DESC";
			$dateRequest = " on ".$date[3];
		}
	}
	//Default action, display random objects within defined limitations
	else
	{
		$buildRequestString = $buildRequestString.") ORDER BY RAND()";
	}
	//Check for and define object number limit
	if(in_array('limit', $limit))
	{
		$newLimit = $limit[3];
		$buildRequestString = $buildRequestString." LIMIT $newLimit";
		$limitRequest = "limited to ".$limit[3]." results";
	}
	//Build the error with some technical details in case the database call fails. If quiet is specified, a simple report is given. If silent, there is no failure report.
	if(in_array('quiet', $quiet))
	{
		$parametersError = "No ".$type."s.";
	}
	elseif(in_array('silent', $silent))
	{
		$parametersError = "";
	}
	else
	{
		$parametersError = "<span class=\"redText\">Could not build Output: No results in scope. You asked for one or more ".$type."s ".$tagRequest.$galRequest.$userRequest.$limitRequest.$dateRequest.". Perhaps that was too specific? SQL QUERY: \"$buildRequestString\"</span>";
	}
	$query = $buildRequestString;
	return array('query' => $query, 'error' => $parametersError, 'parameters' => $parameters);
}

//Convert general Nebula CMS Codes and BBCode to HTML
function codeToHtml($code = null, $parameters = null)
{
	if($parameters == null)
	{
		if($code == ('b' xor 'i' xor 'u' xor 's' xor 'code'))
		{
			return "<span class=\"$code\">";
		}
	}
	elseif($parameters == 'close')
	{
		return "</span>";
	}
	else
	{
		return 'ERROR';
	}
}

//----------------------------------//
//FUNCTIONS FOR IMAGES AND GALLERIES//
//----------------------------------//

//Returns single image with information
function image($image = 0)
{
	//Prerequisites
	$layout = 'basic';
	if(is_array($image))
	{
		if(in_array('verbose', $image))
		{
			$layout = 'verbose';
		}
		//"Enhanced" embedded image output (Auto Image ON)
		if(in_array('auto', $image))
		{
			$image[] = "limit:1";
			$returned = autoCodeParse('image', $image);
			$result = databaseArray($returned['query']);
			$imageError = $returned['error'];
		}
		//Normal embedded image output, if the input was an array (Auto Image OFF)
		else
		{
			$image = $image[0];
			$result = databaseArray("SELECT * FROM `nc_images` WHERE `id` = '$image' AND `status` = 'live' LIMIT 1");
			$imageError = 'An image with the ID "'.$image.'" was not found.';
		}
	}
	//Normal embedded image output, if the input was a string (typical, Auto Image OFF)
	else
	{
		$result = databaseArray("SELECT * FROM `nc_images` WHERE `id` = '$image' AND `status` = 'live' LIMIT 1");
		$imageError = 'An image with the ID "'.$image.'" was not found.';
	}
	//
	
	if($result)
	{
		$upload_id = $result['upload_id'];
		$dir = $result['dir'];
		$title = $result['title'];
		$desc = $result['desc'];
		$creator = $result['creator'];
		$date = $result['date'];
		$gallery = $result['gallery'];
		$tags = $result['tags'];
		$numInSet = databaseArray("SELECT COUNT(`upload_id`) AS count FROM `nc_images` WHERE `upload_id` = '$upload_id' AND `status` = 'live'");
		$numInGal = databaseArray("SELECT COUNT(`gallery`) AS count FROM `nc_images` WHERE `gallery` = '$gallery' AND `status` = 'live'");
		$count = $numInSet['count'];
		$galCount = $numInGal['count'];
		if($count > 1)
		{
			$setInfo = "It was uploaded with $count other images,";
		}
		else
		{
			$setInfo = "It is the only image in its set,";
		}
		if($galCount > 1)
		{
			$galInfo = "and there are $galCount other images in <a href=\"gallery-$gallery\">\"$gallery\"</a>";
		}
		else
		{
			$galInfo = "and there are no other images in <a href=\"gallery-$gallery\">\"$gallery\"</a>";
		}
		//
		
		if($layout == 'verbose')
		{
			return "<div class=\"mainDivHeader\"><h1>$title</h1></div><div id=\"editAccount\"><span class=\"padding10\"><div class=\"padding10\" style=\"background:url('$dir') rgba(0,0,0,0.2);background-position:center;background-repeat:no-repeat;background-size:contain;width:100%;height:256px;margin-bottom:15px;cursor:pointer;\" onClick=\"openPopup('View Image','$dir','getImageViewer')\"></div><span class=\"left\">$desc</span><span class=\"right\"><p class=\"fancySelectHead\">About:</p><div class=\"form\">\"$title\" was uploaded by $creator to <a href=\"gallery-$gallery\">\"$gallery\"</a> on $date. $setInfo $galInfo.</div><p class=\"fancySelectHead\">Tags:</p><div class=\"form\">$tags</div></span></span></div>";
		}
		elseif($layout == 'basic')
		{
			return "
			<img width=\"25%\" src=\"$dir\" alt=\"$title\" onClick=\"openPopup('View Image','$dir','getImageViewer')\" />
			";
		}
	}
	else
	{
		return $imageError;
	}
}
//Returns a gallery of images
function gallery($gallery = 'uncategorized')
{
	//Prerequisites
	if(is_array($gallery))
	{
		//"Enhanced" embedded gallery output (Auto Gallery ON)
		if(in_array('auto', $gallery))
		{
			$returned = autoCodeParse('image', $gallery);
			$result = database($returned['query']);
			$galleryError = $returned['error'];
		}
		//Normal embedded gallery output, if the input was an array (Auto Gallery OFF)
		else
		{
			$gallery = $gallery[0];
			$result = database("SELECT * FROM `nc_images` WHERE (`upload_id` = '$gallery' OR `gallery` = '$gallery') AND `status` = 'live' ORDER BY `date` DESC");
			$galleryError = "<span class=\"redText\">No gallery \"$gallery\" could be found.</span>";
		}
	}
	//Normal embedded gallery output, if the input was a string (typical, Auto Gallery OFF)
	else
	{
		$result = database("SELECT * FROM `nc_images` WHERE (`upload_id` = '$gallery' OR `gallery` = '$gallery') AND `status` = 'live' ORDER BY `date` DESC");
		$galleryError = "<span class=\"redText\">No gallery \"$gallery\" could be found.</span>";
	}
	//
	
	//If the database call succeeded, build the gallery
	if(mysql_num_rows($result))
	{
		$willReturn = "
		<link  href=\"nc_core/js/fotorama/fotorama.css\" rel=\"stylesheet\">
		<script src=\"nc_core/js/fotorama/fotorama.js\"></script>
		<div class=\"fotorama\" data-width=\"100%\" data-height=\"80%\" data-nav=\"thumbs\" style=\"position:relative\">";
		
		while ($row = mysql_fetch_assoc($result)) // loop through images
		{
			$upload_id = $row['upload_id'];
			$dir = $row['dir'];
			$title = $row['title'];
			$desc = $row['desc'];
			if(empty($desc))
			{
				$desc = "No description.";
			}
			$creator = $row['creator'];
			$date = $row['date'];
			$gallery = $row['gallery'];
			$tags = $row['tags'];
			$willReturn = $willReturn."
			<img src=\"$dir\" data-caption=\"$title - $desc Uploaded by $creator on $date to $gallery.\">
			";
		}
		$willReturn = $willReturn."</div>";
		return $willReturn;
	}
	//If it didn't, show that error we built earlier
	else
	{
		return $galleryError;
	}
}

//----------------------------------------//
//FUNCTIONS FOR POSTS, PAGES, AND SNIPPETS//
//----------------------------------------//

//Get an embeddable box containing a preview of the requested content
function getContentPreview($content = array(), $id = null)
{
	//Prerequisites
	if(is_array($content))
	{
		//"Enhanced" embedded gallery output
		if(in_array('auto', $content))
		{
			$returned = autoCodeParse('image', $gallery);
			$result = database($returned['query']);
			$galleryError = $returned['error'];
		}
		//Normal embedded output
		else
		{
			$content = $content[0];
			$result = database("SELECT * FROM `nc_".$content."s` WHERE `id` = '$id' AND `status` = 'live' ORDER BY `date` DESC");
			$galleryError = "<span class=\"redText\">No ".$content." \"$id\" could be found.</span>";
		}
	}
	//Normal embedded output, if the input was a string
	else
	{
		$result = database("SELECT * FROM `nc_".$content."s` WHERE WHERE `id` = '$id' AND `status` = 'live' ORDER BY `date` DESC");
		$galleryError = "<span class=\"redText\">No ".$content." \"$id\" could be found.</span>";
	}
	//
	
	?>
	<div class="contentPreviewBox">
	<?
	echo 'Things';
	?>
	</div>
	<?
}
//Returns array of data from single post
function post($post = 67, $show_deleted = false)
{
	if($show_deleted == true)
	{
		$postData = databaseArray("SELECT * FROM `nc_posts` WHERE `id` = '$post'");
	}
	else
	{
		$postData = databaseArray("SELECT * FROM `nc_posts` WHERE `id` = '$post' AND `status` = 'live'");
	}
	if(isset($postData))
	{
		return $postData;
	}
}

//Retrieves array of all posts in selected range, or all posts if no range specified
function posts($start = 0, $end = 5, $user = 'all', $show_deleted = false)
{
	if($user != 'all')
	{
		if($show_deleted == true)
		{
			$result = database("SELECT * FROM `nc_posts` WHERE `creator` = '$user' ORDER BY `id` DESC LIMIT $start, $end");
		}
		else
		{
			$result = database("SELECT * FROM `nc_posts` WHERE `status` = 'live' AND `creator` = '$user' ORDER BY `id` DESC LIMIT $start, $end");
		}
	}
	elseif($show_deleted == true)
	{
		$result = database("SELECT * FROM `nc_posts` ORDER BY `id` DESC LIMIT $start, $end");
	}
	else
	{
		$result = database("SELECT * FROM `nc_posts` WHERE `status` = 'live' ORDER BY `id` DESC LIMIT $start, $end");
	}
	if($result)
	{
		while($row = mysql_fetch_array($result))
			{
				$postArray[] = $row;
			}
			return $postArray;
	}
}

//Shows single post block
function getPost($post_id = 1, $showall = 1, $textLimit = 0, $comments = 1, $show_deleted = false)
{
	//Prerequisites
	$admin = adminCheck();
	
	if($comments == 0)
	{
		$comments_enabled = $comments;
	}
	else
	{
		$comments_enabled = $GLOBALS['comments_enabled'];
	}
	//
	if($show_deleted == true)
	{
		$result = databaseArray("SELECT * FROM `nc_posts` WHERE `post_id` = '$post_id'");
	}
	else
	{
		if($post_id == 'latest')
		{
			$result = databaseArray("SELECT * FROM nc_posts WHERE `status` = 'live' ORDER BY `date` DESC LIMIT 1");
		}
		else
		{
			$result = databaseArray("SELECT * FROM `nc_posts` WHERE `post_id` = '$post_id' AND `status` = 'live'");
		}
	}
	if($result)
	{
		$id = $result['id'];
		$name = stripslashes($result['name']);
		$post_id = $result['post_id'];
		$featuredImg = stripslashes($result['featuredImg']);
		$creator = stripslashes($result['creator']);
		$content = nl2br(regexFindCode(stripslashes($result['content'])));
		$date = $result['date'];
		$anchor = "default";
		echo "<div id='updateblock' class='mainDiv' ";
		if ($admin == 1){
			echo "onmouseover=\"ShowContent('editHere_". $post_id."');\" onmouseout=\"HideContent('editHere_". $post_id."');\">";
			imageHeader($name, $date, $post_id, $featuredImg, $creator, $showall, $anchor);
			echo "<div id='editHere_". $post_id."' class='editHere noselect' ondblclick=\"openPopup('Edit Post','$post_id','getBlogForm');\">Double-click to edit</div><div id='blogContent_". $post_id."' class='blogContent'></div>";
		}
		else
		{
		echo ">";
		imageHeader($name, $date, $post_id, $featuredImg, $creator, $showall, $anchor);
		}
		if($textLimit != 0)
		{
			$shortened = substr($content, 0, $textLimit);
			echo "$shortened";
			if($content != $shortened){
				echo '... <a href="blog-'.$post_id.'">[Read more]</a>';
			}
		}
		else
		{
			echo $content;
		}
		if($comments_enabled == 1)
		{
			getComments($post_id, $showall);
		}
		echo '</div>';
	}
}

//Shows posts block
function getPosts($start = 0, $end = 5, $showall = 0, $user = 'all', $show_deleted = false)
{
	//Prerequisites
	$admin = adminCheck();
	$comments_enabled = $GLOBALS['comments_enabled'];
	$noOlderPosts = false;
	$noNewerPosts = false;
	if(isset($_GET['home']))
	{
		$previous = $_GET['home'];
		$start = $previous * 5;
		$end = 5;
	}
	//
	
	if($user != 'all')
	{
		if($show_deleted == true)
		{
			$result = database("SELECT * FROM `nc_posts` WHERE `creator` = '$user' ORDER BY `date` DESC LIMIT $start, $end");
		}
		else
		{
			$result = database("SELECT * FROM `nc_posts` WHERE `status` = 'live' AND `creator` = '$user' ORDER BY `date` DESC LIMIT $start, $end");
		}
	}
	elseif($show_deleted == true)
	{
		$result = database("SELECT * FROM `nc_posts` ORDER BY `date` DESC LIMIT $start, $end");
	}
	else
	{
		$result = database("SELECT * FROM `nc_posts` WHERE `status` = 'live' ORDER BY `date` DESC LIMIT $start, $end");
	}
	if($result)
	{
		$num = mysql_num_rows($result);
		
		for($i = $end - $num; $i <= $num - 1 ; $i++){
		$name = stripslashes(mysql_result($result,$i,"name"));
		$id = mysql_result($result,$i,"id");
		$post_id = mysql_result($result,$i,"post_id");
		$content = nl2br(regexFindCode(stripslashes(mysql_result($result,$i,"content")), 'preview'));
		$featuredImg = stripslashes(mysql_result($result,$i,"featuredImg"));
		$creator = stripslashes(mysql_result($result,$i,"creator"));
		$date = mysql_result($result,$i,"date");
		$anchor = $post_id;
		echo "<div id='updateblock' class='mainDiv' ";
		if ($admin == 1){
			echo "onmouseover=\"ShowContent('editHere_". $post_id."');\" onmouseout=\"HideContent('editHere_". $post_id."');\">";
			imageHeader($name, $date, $post_id, $featuredImg, $creator, $showall, $anchor);
			echo "<div id='editHere_$post_id' class='editHere noselect' ondblclick=\"openPopup('Edit Post',$post_id,'getBlogForm')\">Double-click to edit</div><div id='blogContent_$post_id' class='blogContent'></div>";
		}
		else
		{
		echo ">";
		imageHeader($name, $date, $post_id, $featuredImg, $creator, $showall, $anchor);
		}
		$shortened = substr($content, 0, 1000); 
		echo "$shortened";
		if($content != $shortened){
			echo '... <a href="blog-'.$post_id.'">[Read more]</a>';
		}
		if($comments_enabled == 1)
		{
			getComments($post_id, $showall);
		}
		echo '</div>';
		}
	}
	//Page Controls
	echo '<div id="pagecontrols"><p align="center">';
	$numposts = database("SELECT COUNT(*) FROM nc_posts WHERE status = 'live'");
	if (0 < $numposts) $numposts = mysql_result($numposts, 0 , 'COUNT(*)');
	$numpages = round($numposts / 5, 0, PHP_ROUND_HALF_UP);
	
	if(isset($_GET['home'])){
		if($numpages > $_GET['home'] + 1){
			$goBack = $_GET['home'] + 1;
			if(empty($_GET['home']) or $_GET['home'] == 0){
				$noNewerPosts = true;
			}
			if(empty($_GET['home']) or $_GET['home'] == 0){
				$goForward = 0;
				$noNewerPosts = true;
			}
			else
			{
				$goForward = $_GET['home'] - 1;
			}
		}
		else
		{
			$noOlderPosts = true;
			if(empty($_GET['home']) or $_GET['home'] == 0){
				$goForward = 0;
				$noNewerPosts = true;
			}
			else
			{
				$goForward = $_GET['home'] - 1;
			}
		}
	}
	else
	{
		if($numpages <= 1){
			$noOlderPosts = true;
			$noNewerPosts = true;
		}
		else
		{
			$goBack = 1;
			$noNewerPosts = true;
		}
	}
	for($i=$numpages; $i>=1; $i=$i-1){
		$p = $i - 1;
		echo "<a class='pageButton animateFast numeric"; if($numpages == 1){ echo ' pageButtonDis\'>'; }else{ echo "' href='?home=$p'>"; } echo $i ?></a>
	<?
	}
	if($noNewerPosts != true)
	{ 
		$nextButton = "?home=$goForward";
	}
	else
	{
		$nextButton = "#";
	}
	?>
	<span>
	<a class="pageButton oldest animateFast<? if($noOlderPosts == true){ echo ' pageButtonDis">'; }else{?>" href="<?
	$oldest = $numpages - 1;
	echo "?home=$oldest";
	?>"><? } ?>&lt;&lt; Oldest</a>
	<a class="pageButton previous animateFast<? if($noOlderPosts == true){ echo ' pageButtonDis">'; }else{?>" href="<?
	if($noOlderPosts != true)
	{ 
		echo "?home=$goBack";
	}
	else
	{
		echo "#";
	}
	?>"><? } ?>&lt; Previous</a>
	<a class="pageButton next animateFast<? if($noNewerPosts == true){ echo ' pageButtonDis">'; }else{?>"  href="<? echo $nextButton; ?>"><? } ?>Next ></a>
	<a class="pageButton newest animateFast<? if($noNewerPosts == true){ echo ' pageButtonDis">'; }else{?>"  href="<? echo "?home=0"; ?>"><? } ?>Newest >></a>
	<?
	echo '</span></p></div>';
}
//Create a post
function createPost($post_id = '_empty', $name = '_empty', $content = '_empty', $creator = '_empty', $status = 'live', $tags = '_empty', $isFeatured = '0', $featuredImg ='_empty', $edit = false, $edit_id = '_empty')
{
	if($name != '_empty' & $content != '_empty' & $creator != '_empty')
	{
		//Prerequisites
		$sname = addslashes($name);
		$scontent = addslashes($content);
		$screator = addslashes($creator);
		$tags = addslashes($tags);
		$sfeaturedImg = addslashes($featuredImg);
		if($post_id == '_empty')
		{
			$post_id = microtime(true);
		}
		$revision = '1';
		//
		if($edit == 'edit' & $edit_id != '_empty' & mysql_num_rows(database("SELECT * FROM `nc_posts` WHERE `post_id` = '".$edit_id."'")) != 0)
		{
			$result1 = database("UPDATE `nc_posts` SET `status` = 'archived' WHERE `post_id` = '$edit_id' AND `status` = 'live'");
			$result2 = database("INSERT INTO `nc_posts` (post_id, name, content, date, revision, status, creator, tags, featuredImg, isFeatured) SELECT post_id,'$name','$content',date,revision + 1,'$status',creator,'$tags','$sfeaturedImg','$isFeatured' FROM `nc_posts` WHERE `post_id` = '$edit_id' ORDER BY id DESC LIMIT 1");
			if($result1 & $result2)
			{
				return 'true';
			}
			else
			{
				return "Database call failed before edit could complete. Please try again.";
			}
		}
		elseif($edit == 'delete' & $edit_id != '_empty')
		{
			$result1 = database("UPDATE `nc_posts` SET `status` = 'deleted' WHERE `post_id` = '$edit_id' AND `status` = 'live'");
			if($result1)
			{	
				return 'true';
			}
			else
			{
				return "Database call failed and the post wasn't deleted. Please try again.";
			}
		}
		elseif(!isset($edit) || $edit_id == '_empty')
		{
			if(database("INSERT INTO `nc_posts` (post_id, name, content, date, revision, status, creator, tags, featuredImg, isFeatured) VALUES ('$post_id','$sname','$scontent',NOW(),'$revision','$status','$screator','$tags','$featuredImg','$isFeatured')"))
			{
				return 'true';
			}
			else
			{
				return "Database call failed and the post wasn't created. Please try again.";
			}
		}
		else
		{
			return "The server was unable to process your request due to an error. ($edit $edit_id)";
		}
	}
	else
	{
		return "Required fields were not filled.";
	}
}
function test($data)
{
	if(is_array($data))
	{
		$data = $data[4];
	}
	echo "dasfsdfasdfasdfasdf";
}

//Shows single page
function getPage($url = 1, $showall = 1, $show_deleted = false)
{
	//Prerequisites
	$mod = modCheck();
	$admin = adminCheck();
	$user = userCheck();
	//
	if($show_deleted == true)
	{
		$result = databaseArray("SELECT * FROM `nc_pages` WHERE `url` = '$url'");
		$secure = $result['secure'];
		$super_secure = $result['super_secure'];
	}
	else
	{
		$result = databaseArray("SELECT * FROM `nc_pages` WHERE `url` = '$url' AND `status` = 'live'");
		$secure = $result['secure'];
		$super_secure = $result['super_secure'];
	}
	if($result) 
	{
		if(($secure == '0' & $super_secure == '1' & ($admin || $mod)) || ($secure == '1' & $super_secure == '1' & ($admin || $mod)) || ($secure == '1' & $super_secure == '0' & $user) || ($secure == '0' & $super_secure == '0') || ($super_secure == '2' & $admin))
		{
			$id = $result['id'];
			$page_id = $result['page_id'];
			$name = stripslashes($result['name']);
			$content = nl2br(regexFindCode(stripslashes($result['content'])));
			$featuredImg = stripslashes($result['featuredImg']);
			if(!empty($result['sidebar']))
			{
				global $sidebarData;
				$sidebarData = $result['sidebar'] . "</div>";

			}
			$date = $result['date'];
			$revision = $result['revision'];
			$status = $result['status'];
			$secure = $result['secure'];
			$super_secure = $result['super_secure'];
			$creator = $result['creator'];
			if(adminCheck())
			{
				echo "<p align=\"center\"><a onclick=\"openPopup('Edit Page','$page_id','getPageForm');\"><- Edit this Page -></a></p>";
			}
			imageHeader($name,'','',$featuredImg, $creator, 1);
			echo "<div>";
			echo $content;
			echo '</div>';
		}
		else
		{
			generalHeader('401: Unauthorized');
			echo "<div>";
			echo "You don't have sufficient privileges to view this page.";
			echo '</div>';
		}
			
	}
	else
	{
		$name = '404';
		generalHeader($name);
		getSnippet('1425583142');
	}
}

//Create a page
function createPage($page_id = '_empty', $name = '_empty', $content = '_empty', $sidebar = '_empty', $url = '_empty', $creator = '_empty', $status = 'live', $secure = '0', $super_secure = '0', $tags = '_empty', $isFeatured = '0', $featuredImg = false, $edit = false, $edit_id = '_empty')
{
	if($name != '_empty' & $content != '_empty' & $creator != '_empty')
		{
		//Prerequisites
		$surl = addslashes($url);
		$sname = addslashes($name);
		$scontent = addslashes($content);
		$ssidebar = addslashes($sidebar);
		$screator = addslashes($creator);
		$stags = addslashes($tags);
		$sfeaturedImg = addslashes($featuredImg);
		if($page_id == '_empty')
		{
			$page_id = microtime(true);
		}
		$revision = '1';
		//
		if($edit == 'edit' & $edit_id != '_empty')
		{
			$result1 = database("UPDATE `nc_pages` SET `status` = 'archived' WHERE `page_id` = '$edit_id' AND `status` = 'live'");
			$result2 = database("INSERT INTO `nc_pages` (page_id, url, name, content, sidebar, date, revision, status, secure, super_secure, creator, tags, featuredImg, isFeatured) SELECT page_id, '$surl', '$sname', '$scontent', '$ssidebar', date, revision + 1, '$status', '$secure', '$super_secure', creator, '$stags','$sfeaturedImg','$isFeatured' FROM `nc_pages` WHERE `page_id` = '$edit_id' ORDER BY `id` DESC LIMIT 1");
			if($result1 && $result2)
			{
				return "true";
			}
			else
			{
				return "Database call failed before edit could complete. Please try again. ($edit $edit_id)";
			}
		}
		elseif($edit == 'delete' & $edit_id != '_empty')
		{
			$result1 = database("UPDATE `nc_pages` SET `status` = 'deleted' WHERE `page_id` = '$edit_id' AND `status` = 'live'");
			if($result1)
			{
				return 'true';
			}
			else
			{
				return "Database call failed before the page could be deleted. Please try again. ($edit $edit_id)";
			}
		}
		elseif(!isset($edit) || $edit_id == '_empty')
		{
			if($url == '_empty' || empty($url))
			{
				$url = preg_replace("/[^A-Za-z0-9]/", "", $sname);
			}
			if(database("INSERT INTO `nc_pages` (page_id, url, name, content, sidebar, date, revision, status, secure, super_secure, creator, tags, featuredImg, isFeatured) VALUES ('$page_id','$surl','$sname','$scontent','$ssidebar',NOW(),'$revision','$status','$secure','$super_secure','$screator','$stags','$sfeatured','$isFeatured')"))
			{
				return 'true';
			}
			else
			{
				return "Database call failed and the page wasn't created. Please try again. ($edit $edit_id)";
			}
		}
		else
		{
			return "The server was unable to process your request due to an error. ($edit $edit_id)";
		}
	}
	else
	{
		return "Required fields were not filled.";
	}
}

//Returns a single snippet
function snippet($snip_id = 1, $show_deleted = false)
{
	if($show_deleted == true)
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id'");
	}
	else
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id' AND `status` = 'live'");
	}
	if($result) 
	{
        $content = nl2br(regexFindCode(stripslashes($result['content'])));
        //
        return $content;
	}
	else
	{
		return 'The snippet you were looking for was not found.';
	}
}

//Shows a single snippet
function getSnippet($snip_id = 1, $show_deleted = false)
{
	if($show_deleted == true)
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id'");
	}
	else
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id' AND `status` = 'live'");
	}
	if($result) 
	{
        $content = regexFindCode(stripslashes($result['content']));
        //
        echo $content;
	}
	else
	{
		echo 'The snippet you were looking for was not found.';
	}
}

//Returns an array of all snippets
function getSnippetArray($snip_id = 1, $showall = 1, $show_deleted = false)
{
	if($show_deleted == true)
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id'");
	}
	else
	{
		$result = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$snip_id' AND `status` = 'live'");
	}
	if($result) 
	{
    	return $result;
	}
	else
	{
		return false;
	}
}

//Create a snippet
function createSnippet($snip_id = '_empty', $content = '_empty', $status = 'live', $creator = '_empty', $tags = '_empty', $edit = false, $edit_id = '_empty')
{
	if($content != '_empty' & $creator != '_empty')
		{
		//Prerequisites
		$content = addslashes($content);
		$creator = addslashes($creator);
		$revision = '1';
		if($snip_id == '_empty')
		{
			$snip_id = microtime(true);
		}
		//
		if($edit == 'edit' & $edit_id != '_empty')
		{
			$result1 = database("UPDATE `nc_snippets` SET `status` = 'archived' WHERE `snip_id` = '$edit_id' AND `status` = 'live'");
			$result2 = database("INSERT INTO `nc_snippets` (snip_id, content, date, revision, status, creator, tags) SELECT snip_id, '$content', date, revision + 1, 'live', creator, '$tags' FROM `nc_snippets` WHERE `snip_id` = '$edit_id'");
			if($result1 & $result2)
			{
				return 'true';
			}
			else
			{
				return "Database call failed before edit could complete. Please try again. ($edit $edit_id)";
			}
		}
		elseif($edit == 'delete' & $edit_id != '_empty')
		{
			$result1 = database("UPDATE `nc_snippets` SET `status` = 'deleted' WHERE `snip_id` = '$edit_id' AND `status` = 'live'");
			if($result1)
			{
				return 'true';
			}
			else
			{
				return "Database call failed before the snippet could be deleted. Please try again. ($edit $edit_id)";
			}
		}
		elseif(!isset($edit) || $edit_id == '_empty')
		{
			if(database("INSERT INTO `nc_snippets` (snip_id, content, date, revision, status, creator, tags) VALUES ('$snip_id','$content',NOW(),'$revision','$status','$creator','$tags')"))
			{
				return 'true';
			}
			else
			{
				return "Database call failed and the snippet wasn't created. Please try again. ($edit $edit_id)";
			}
		}
		else
		{
			return "The server was unable to process your request due to an error. ($edit $edit_id)";
		}
	}
	else
	{
		return "Required fields were not filled.";
	}
}

//------------------------------------------//
//FORMS FOR LOGGED IN USERS (USER/ADMIN UI)//
//-----------------------------------------//

if(userCheck())
{
	require_once('admin/forms/forms_admin.php');
}

//------------------------------------------//
//MENUS FOR LOGGED IN USERS (USER/ADMIN UI)//
//-----------------------------------------//

if(userCheck())
{
	require_once('admin/menus/menus_admin.php');
}


//-----------------------------------//
//CATSERVER.NET SPECIALIZED FUNCTIONS//
//-----------------------------------//

//Function to generate a users ID card
function getIdCard($user = 'current')
{
	//Prerequisites
	if($user == 'current')
	{
		$result = userDataArray();
		$noAccess = false;
	}
	else
	{
		$result = userDataArray($user);
	}
	$username = $result["username"];
	$name = $result["name"];
	$email = $result["email"];
	$joindate = $result["joindate"];
	$location = $result["location"];
	$website = $result["website"];
	$image = $result["image"];
	if($image == null)
	{	
		$tempImage = $tempImage = urlencode('http://beta.catserver.net/'.themeDir().'images/accounts/noimage.png');
		$image = get_gravatar($email, 320, $tempImage);
	}
	$about = $result["about"];
	$css = $result["css"];
	$usertheme = $result["theme"];
	$private = $result["private"];
	$super = $result["super"];
	$admin = $result["admin"];
			$url = 'account';
	if($user == 'current')
	{
		$url = 'profile-'.$username;
	}
	else
	{
		if($private & !userCheck())
		{
			$username = "Account is Private";
			$name = '';
			$email = '';
			$joindate = '';
			$location = '';
			$website = '';
			$image = themeDir().'images/accounts/noimage.png';
			$url = '#';
			$noAccess = true;
		}
		else
		{
			$url = 'profile-'.$username;
			$noAccess = false;
		}
	}
	//
	
	echo '<a href="'.$url.'" class="noLinkStyle"><p class="idCard animateFast" style="background:url(' .$image. ');background-size:cover;">
	<span class="idCardText"><span class="idCardName">'. $username .'</span><br />';
	if(!$noAccess)
	{
		echo 'Member Since '. substr($joindate, 0, 10);
	}
	else
	{
		echo '<br />';
	}
	echo '<br />
	'. $location .'<br />
	'. $email .'</span>
	</p></a>';
}
//Function to generate service status box
function getServiceStatus($port = '21', $title = 'FTP', $domain = 'catserver.net', $desc = 'ftp://catserver.net')
{
	if ($handle = fsockopen ($domain, $port, $errnum, $errmsg, 1))
	{
		echo ('<div class="serviceStatus animateFast" style="background-image:url(\'' .themeDir(). 'images/icons/' .$title. '.png\') top left no-repeat">' .$title. ' <span class="greenText">●</span></div>');
		fclose ($handle);
	}
	else
	{
		echo ('<div class="serviceStatus serviceStatusOff" style="background-image:url(\'' .themeDir(). 'images/icons/' .$title. '.png\') top left no-repeat">' .$title. ' <span class="redText">●</span></div>');
	}
}
//Check if a port is open
function serviceStatus($port = '21', $domain = 'catserver.net')
{
	if ($handle = fsockopen ($domain, $port, $errnum, $errmsg, 1))
	{
		fclose ($handle);
		return true;
	}
	else
	{
		return false;
	}
}
//Special Header for popup boxes
function popupHeader($name = 'Default')
{
	echo '
	<script type="text/javascript">
	animatePopupHide();
	</script>
	<div class="mainDivHeader"><h1>'.$name.'<span class="closeButton closeButtonStyle">X</span></h1></div>';
}
//Create the sidebar
function sidebar($sidebarData = '_empty')
{
	if($sidebarData == '_empty')
	{
		include(themeDir() . "includes/sidebar.php");
	}
	else
	{
		echo $sidebarData;
	}
}
//Mini Nav
function miniNav()
{
	?>
	<ul id="miniMenuNav">
		<li><span class="miniMenuText"><img class="animateFast" alt="nav" width="55px" src="nc_themes/catservernine/images/icons/mininav.png"></span>
				<ul class="navDropWidth">
						<li class="navDrop">
						<a href='/'>Home</a>
								<a href='page-about'>About</a>
								<a href='page-services'>Services</a>
								<a href='page-contact'>Contact</a>
								<a href='http://minecraft.catserver.net'>Minecraft</a>
								<?php
								if(userCheck())
								{
									?>
									<a href='profiles'>Users</a>
									<a href='page-archive'>Archive</a>
									<a href='page-tools'>Tools</a>
									<a href='account'>Settings</a>
									<?php
								}
								?>
					</li>
				</ul>
		</li>
		<?php
		if(!userCheck())
		{
			?>
			<li><span class="miniMenuText"><img class="animateFast" alt="login" width="55px" src="nc_themes/catservernine/images/icons/login.png"></span>
					<ul>
							<li>	
									<span class="userMenuText"><form action="#" method="post"><input type="hidden" name="logout" /><input type="submit" name="submit" value="Log Out" /></form></span>

									<form action="#" class="login" method="post">
									<p class="login">
									<p><input type="text" name="username" class="loginForm" maxlength="40" placeholder="Username..."></p>
									<p><input type="password" name="pass" class="loginForm" maxlength="50" placeholder="Password..."></p>
									<p align="right"><input type="submit" name="submit" class="submitButton" value="Login"></p>
									</p>
									</form>

							</li>
					</ul>
			</li>
			<?php
			}
		?>
	</ul>
	<?php
}
?>
