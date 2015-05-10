<?
//------------------------------------------//
//FORMS FOR LOGGED IN USERS (USER/ADMIN UI)//
//-----------------------------------------//

//Snippet Creation Form
function snippetCreationForm($edit_id = 1, $edit = true)
{
	if(isset($_POST['edit']))
			{
				$edit = true;
				$edit_id = $_POST['content'];
				$query = databaseArray("SELECT * FROM `nc_snippets` WHERE `snip_id` = '$edit_id'");
				$content = $query['content'];
				$status = $query['status'];
				$snip_id = $query['snip_id'];
				$tags = $query['tags'];
			}
			else
			{
				$snip_id = round(microtime(true));
				$edit = false;
				$status = false;
			}
			?>
			<form action="nc_core/ajax/ajax.php" method="post" enctype="multipart/form-data" id="snipPoster">
            <p class="fancySelectHead">Unique ID:</p>
            <textarea class="form" rows="1" readonly="readonly"><? echo $snip_id; ?></textarea>
            
			<p class="fancySelectHead">Content:</p>
			<textarea name="content" class="form tall" rows="3"><? if($edit){ echo $content; } ?></textarea>
			
			<p class="fancySelectHead">Tags:</p>
            <textarea name="tags" class="form" rows="1"><? if($edit){ echo $tags; }else{ echo " ";} ?></textarea>
			
			<p class="fancySelectHead">Publication Status</p>
			<select class="fancySelect" name="status">
				<option value="live" <? if($edit & $status == 'live'){ echo 'selected="selected"'; } if(!$edit){ echo 'selected="selected"'; } ?>>Live</option>
                <option value="archived" <? if($edit & $status == 'archived'){ echo 'selected="selected"'; } ?>>Archived</option>
				<option value="pending" <? if($edit & $status == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
				<option value="deleted" <? if($edit & $status == 'deleted'){ echo 'selected="selected"'; } ?>>Deleted</option>
			</select>
			
			<input type="hidden" name="creator" value="<? userCheck('echoCurrent'); ?>" />
			
			<span class="right">
			<input type="submit" value="Post" class="submitButton"/>
			</span>
			</form>
            <?
			if($edit)
			{
				?>
				<script type="text/javascript">
				$('#delete').click(function () {
						
						var request = 'snipProcessor';
						var name = '<? echo $edit_id; ?>';
						var edit_id = '<? echo $edit_id; ?>';
						var creator = '<? userCheck('echoCurrent'); ?>';
						var edit	= 'delete';
							
						$.post('nc_core/ajax/ajax.php', { name: name, edit_id: edit_id, edit: edit, request: request },
						function( data ) {
						var content = $( data );
						$( "#returned" ).empty().append( content );
						});
				});
				</script>
				<span class="right"><button class="submitButton" id="delete">Delete</button></span>
				<?
			}
			?>
			<div id="clearfix"></div>
			<script>
			  /* attach a submit handler to the form */
			  $("#snipPoster").submit(function(event) {
			
				/* stop form from submitting normally */
				event.preventDefault(); 
					
				/* get some values from elements on the page: */
				var request = 'snipProcessor';
				var snip_id = '<? echo $snip_id; ?>';
				
				<? if($edit)
				{
					echo "var edit = 'edit';"; 
					echo "var edit_id = '" .$edit_id. "';"; 
				}
				?>
				
				var $form = $( this ),
					content = $form.find( 'textarea[name="content"]' ).val()
					status = $form.find( 'select[name="status"]' ).val()
					tags = $form.find( 'textarea[name="tags"]' ).val()
					creator = $form.find( 'input[name="creator"]' ).val();
			
				/* Send the data using post and put the results in a div */
				$.post( 'nc_core/ajax/ajax.php', { snip_id: snip_id, content: content, status: status, tags: tags, creator: creator, <? if($edit){ echo 'edit: edit, edit_id: edit_id, '; } ?>request: request },
				  function( data ) {
					  var content = $( data );
					  $( "#returned" ).empty().append( content );
				  }
				);
			  });
			</script>
			<?
}

//Page Creation Form
function pageCreationForm($edit_id = 1, $edit = true)
{
	if($edit == true)
	{
		$edit = true;
		$query = databaseArray("SELECT * FROM `nc_pages` WHERE `page_id` = '$edit_id' AND `status` = 'live'");
		$page_id = $edit_id;
		$url = stripslashes($query['url']);
		$name = stripslashes($query['name']);
		$content = stripslashes($query['content']);
		$sidebar = stripslashes($query['sidebar']);
		$tags = stripslashes($query['tags']);
		$featuredImg = stripslashes($query['featuredImg']);
		$isFeatured = $query['isFeatured'];
		$status = $query['status'];
		$secure = $query['secure'];
		$super_secure = $query['super_secure'];
	}
	else
	{
		$page_id = round(microtime(true));
		$edit = false;
		$isFeatured = false;
		$status = false;
		$secure = false;
		$super_secure = false;
	}
	?>
	<form action="nc_core/ajax/ajax.php" method="post" enctype="multipart/form-data" id="pageMaker">
	<p class="fancySelectHead">Unique ID:</p>
	<textarea class="form" rows="1" readonly="readonly"><? echo $page_id; ?></textarea>

	<p class="fancySelectHead">Title:</p>
	<textarea name="name" class="form"><? if($edit){ echo $name; } ?></textarea>

	<p class="fancySelectHead">Content:</p>
	<textarea name="content" class="form tall" rows="10"><? if($edit == true){ echo $content; } ?></textarea>
	
	<p class="fancySelectHead">Sidebar:</p>
	<textarea name="sidebar" class="form tall" rows="10" placeholder="Leave blank for default..."><? if($edit){ echo $sidebar; } ?></textarea>
	
	<p class="fancySelectHead">Desired URL:</p>
	<textarea name="url" class="form" placeholder="Leave blank for default..."><? if($edit){ echo $url; } ?></textarea>
	
	<p class="fancySelectHead">Tags:</p>
	<textarea name="tags" class="form" rows="1"><? if($edit){ echo $tags; }else{ echo " ";} ?></textarea>
		
	<p class="fancySelectHead">Featured Image:</p>
	<textarea name="featuredImg" class="form" rows="1" placeholder="Leave blank for default..."><? if($edit){ echo $featuredImg; } ?></textarea>
	
	<p class="fancySelectHead">Featured</p>
	<select class="fancySelect" name="isFeatured">
		<option value="1" <? if($edit & $isFeatured == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
		<option value="0" <? if($edit & $isFeatured == '0'){ echo 'selected="selected"'; }else{ echo 'selected="selected"'; } ?>>No</option>
	</select>
	
	<p class="fancySelectHead">Requre User</p>
	<select class="fancySelect" name="secure">
		<option value="1" <? if($edit & $secure == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
		<option value="0" <? if($edit & $secure == '0'){ echo 'selected="selected"'; } if(!$edit){ echo 'selected="selected"'; } ?>>No</option>
	</select>
	
	<p class="fancySelectHead">Requre Admin</p>
	<select class="fancySelect" name="super_secure">
		<option value="1" <? if($edit & $super_secure == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
		<option value="0" <? if($edit & $super_secure == '0'){ echo 'selected="selected"'; } if(!$edit){ echo 'selected="selected"'; } ?>>No</option>
	</select>
	
	<p class="fancySelectHead">Publication Status</p>
	<select class="fancySelect" name="status">
		<option value="live" <? if($edit & $status == 'live'){ echo 'selected="selected"'; } if(!$edit){ echo 'selected="selected"'; } ?>>Live</option>
		<option value="archived" <? if($edit & $status == 'archived'){ echo 'selected="selected"'; } ?>>Archived</option>
		<option value="pending" <? if($edit & $status == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
		<option value="deleted" <? if($edit & $status == 'deleted'){ echo 'selected="selected"'; } ?>>Deleted</option>
	</select>
	
	<input type="hidden" name="creator" value="<? userCheck('echoCurrent'); ?>" />

	<span class="right"><input type="submit" value="Create" class="submitButton" /></span>
	</form>
	<?
	if($edit)
	{
		?>
		<script type="text/javascript">
		$('#delete').click(function () {
				var $form = $("#pageMaker"),
				name = $form.find( 'textarea[name="name"]' ).val();
				
				var request = 'pageProcessor';
				var edit_id = '<? echo $edit_id; ?>';
				var creator = '<? userCheck('echoCurrent'); ?>';
				var edit	= 'delete';
					
				$.post('nc_core/ajax/ajax.php', { name: name, creator: creator, edit: edit, edit_id: edit_id, request: request },
				function( data ) {
				var content = $( data );
				$( "#returned" ).empty().append( content );
				});
		});
		</script>
		<span class="right"><button class="submitButton" id="delete">Delete</button></span>
		<?
	}
	?>
	<div id="clearfix"></div>
	<script>
	  /* attach a submit handler to the form */
	  $("#pageMaker").submit(function(event) {
	
		/* stop form from submitting normally */
		event.preventDefault(); 
			
		/* get some values from elements on the page: */
		var request = 'pageProcessor';
		var page_id = '<? echo $page_id; ?>';
		
		<? if($edit)
		{
			echo "var edit = 'edit';"; 
			echo "var edit_id = '$edit_id';"; 
		}
		?>
		
		var $form = $( this ),
			name = $form.find( 'textarea[name="name"]' ).val()
			content = $form.find( 'textarea[name="content"]' ).val()
			sidebar = $form.find( 'textarea[name="sidebar"]' ).val()
			url = $form.find( 'textarea[name="url"]' ).val()
			tags = $form.find( 'textarea[name="tags"]' ).val()
			isFeatured = $form.find( 'select[name="isFeatured"]' ).val()
			featuredImg = $form.find( 'textarea[name="featuredImg"]' ).val()
			secure = $form.find( 'select[name="secure"]' ).val()
			super_secure = $form.find( 'select[name="super_secure"]' ).val()
			status = $form.find( 'select[name="status"]' ).val()
			creator = $form.find( 'input[name="creator"]' ).val();
	
		/* Send the data using post and put the results in a div */
		$.post( 'nc_core/ajax/ajax.php', { page_id: page_id, url: url, name: name, content: content, sidebar: sidebar, url: url, creator: creator, status: status, secure: secure, super_secure: super_secure, tags: tags, isFeatured: isFeatured, featuredImg: featuredImg, <? if($edit){ echo 'edit: edit, edit_id: edit_id, '; } ?>request: request},
		  function( data ) {
			  var content = $( data );
			  $( "#returned" ).empty().append( content );
		  }
		);
	  });
	</script>
	<?
}
	
//Blog post form
function postCreationForm($edit_id = 1, $edit = true)
{
	if($edit == true)
	{
		$edit = true;
		$edit_id = $_POST['content'];
		$query = databaseArray("SELECT * FROM `nc_posts` WHERE `post_id` = '$edit_id' AND `status` = 'live'");
		$post_id = $query['post_id'];
		$name = stripslashes($query['name']);
		$content = stripslashes($query['content']);
		$tags = stripslashes($query['tags']);
		$featuredImg = stripslashes($query['featuredImg']);
		$isFeatured = $query['isFeatured'];
		$status = $query['status'];
	}
	else
	{
		$post_id = round(microtime(true));
		$edit = false;
		$status = false;
	}
	?>
	<form action="nc_core/ajax/ajax.php" method="post" enctype="multipart/form-data" id="blogPoster">
	<p class="fancySelectHead">Unique ID:</p>
	<textarea class="form" rows="1" readonly="readonly"><? echo $post_id; ?></textarea>
	
	<p class="fancySelectHead">Title:</p>
	<textarea name="name" class="form"><? if($edit){ echo $name; } ?></textarea>
	
	<p class="fancySelectHead">Content:</p>
	<textarea name="content" class="form tall" rows="3"><? if($edit){ echo $content; } ?></textarea>
				
	<p class="fancySelectHead">Tags:</p>
	<textarea name="tags" class="form" rows="1"><? if($edit){ echo $tags; }else{ echo " ";} ?></textarea>
		
	<p class="fancySelectHead">Featured Image:</p>
	<textarea name="featuredImg" class="form" rows="1" placeholder="Leave blank for default..."><? if($edit){ echo $featuredImg; } ?></textarea>
	
	<p class="fancySelectHead">Featured</p>
	<select class="fancySelect" name="isFeatured">
		<option value="1" <? if($edit & $isFeatured == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
		<option value="0" <? if($edit & $isFeatured == '0'){ echo 'selected="selected"'; }else{ echo 'selected="selected"'; } ?>>No</option>
	</select>
	
	<p class="fancySelectHead">Publication Status</p>
	<select name="status" class="fancySelect" name="status">
		<option value="live" <? if($edit & $status == 'live'){ echo 'selected="selected"'; } if(!$edit){ echo 'selected="selected"'; } ?>>Live</option>
		<option value="archived" <? if($edit & $status == 'archived'){ echo 'selected="selected"'; } ?>>Archived</option>
		<option value="pending" <? if($edit & $status == 'pending'){ echo 'selected="selected"'; } ?>>Pending</option>
		<option value="deleted" <? if($edit & $status == 'deleted'){ echo 'selected="selected"'; } ?>>Deleted</option>
	</select>
	
	<input type="hidden" name="creator" value="<? userCheck('echoCurrent'); ?>" />
	
	<span class="right">
	<input type="submit" value="Post" class="submitButton"/>
	</span>
	</form>
	<?
	if($edit)
	{
		?>
		<script type="text/javascript">
		$('#delete').click(function () {
			
				var $form = $("#blogPoster"),
				name = $form.find( 'textarea[name="name"]' ).val();
				
				var request = 'blogProcessor';
				var edit_id = '<? echo $edit_id; ?>';
				var creator = '<? userCheck('echoCurrent'); ?>';
				var edit	= 'delete';
					
				$.post('nc_core/ajax/ajax.php', { name: name, edit_id: edit_id, edit: edit, request: request },
				function( data ) {
				var content = $( data );
				$( "#returned" ).empty().append( content );
				});
		});
		</script>
		<span class="right"><button class="submitButton" id="delete">Delete</button></span>
		<?
	}
	?>
	<div id="clearfix"></div>
	<script>
	  /* attach a submit handler to the form */
	  $("#blogPoster").submit(function(event) {
	
		/* stop form from submitting normally */
		event.preventDefault(); 
			
		/* get some values from elements on the page: */
		var request = 'blogProcessor';
		var post_id = '<? echo $post_id; ?>';
		
		<? if($edit)
		{
			echo "var edit = 'edit';"; 
			echo "var edit_id = '" .$edit_id. "';"; 
		}
		?>
		
		var $form = $( this ),
			name = $form.find( 'textarea[name="name"]' ).val()
			content = $form.find( 'textarea[name="content"]' ).val()
			tags = $form.find( 'textarea[name="tags"]' ).val()
			isFeatured = $form.find( 'select[name="isFeatured"]' ).val()
			featuredImg = $form.find( 'textarea[name="featuredImg"]' ).val()
			status = $form.find( 'select[name="status"]' ).val()
			creator = $form.find( 'input[name="creator"]' ).val();
	
		/* Send the data using post and put the results in a div */
		$.post( 'nc_core/ajax/ajax.php', { post_id: post_id, name: name, content: content, tags: tags, isFeatured: isFeatured,  featuredImg: featuredImg, status: status, creator: creator,<? if($edit){ echo 'edit: edit, edit_id: edit_id, '; } ?>request: request },
		  function( data ) {
			  var content = $( data );
			  $( "#returned" ).empty().append( content );
		  }
		);
	  });
	</script>
	<?
}
//Account Settings Update Form
function accountForm($userForForms='current', $administrate='false')
{
	?>
	<div id="editAccount">
	<?php
	if(userCheck())
		{
		//Prerequisites
		$themes = database('SELECT `id`, `name` FROM `themes`');

		if($userForForms!='current')
		{
			$userData = userDataArray($userForForms);
			$name = $userData['name'];
			$email = $userData['email'];
			$location = $userData['location'];
			$website = $userData['website'];
			$about = $userData['about'];
			$image = $userData['image'];
			$private = $userData['private'];
			$super = $userData['super'];
			$admin = $userData['admin'];
			$token = $userData['admin_token'];
			$style = $userData['css'];
		}
		else
		{
			$name =$GLOBALS['name'];
			$email = $GLOBALS['email'];
			$location = $GLOBALS['location'];
			$website = $GLOBALS['website'];
			$about = $GLOBALS['about'];
			$image = $GLOBALS['image'];
			$private = $GLOBALS['private'];
		}
		//
			generalHeader('Account and Configuration');
			
			if($administrate=='false')
			{
				echo "<span>Customize your account to make your Catserver experience a little more personal. Filling in basic information and writing a little about yourself helps other users feel more connected with the content you contribute.</span>";
			}
			?>
			<form id="profileUpdater" enctype="multipart/form-data">
			<?
			if($administrate=='true')
			{
				?>
				<p class="fancySelectHead">Username:</p>
				<textarea name="username" class="form" readonly="readonly"><? echo $userForForms; ?></textarea>
				<?
			}
			?>
			<div class="left">
			<p class="fancySelectHead">Name:</p>
			<textarea name="name" class="form"><?php echo $name; ?></textarea>
			<p class="fancySelectHead">Email:</p>
			<textarea name="email" class="form"><?php echo $email; ?></textarea>
			</div>
			<div class="right">
			<p class="fancySelectHead">Location:</p>
			<textarea name="location" class="form"><?php echo $location; ?></textarea>
			<p class="fancySelectHead">Website:</p>
			<textarea name="website" class="form"><?php echo $website; ?></textarea>
			</div>
			<div class="clearFix"></div>
			<p class="fancySelectHead wider">A little about yourself:</p>
			<textarea name="about" class="form tall" rows="10"><?php echo $about; ?></textarea>
			<span class="clearFix"></span>
			<?php
			generalHeader('Profile Image');
			
			echo '<div class="left">';
			
			if($administrate=='false')
			{
				echo "<span>It's recommended that you choose a profile image, but it doesn't have to be of your person - anything will do, though please keep it PG. For best results, choose an image that's at least 2 megapixels (1920x1080). You can select an image hosted elsewhere via a URL, you can upload one, or you can use your Gravatar (default).</span>";
			}
			?>
			<p class="fancySelectHead wider">Current Image URL:</p>
			<textarea name="image" class="form"><?php echo $image; ?></textarea>
			</div>
			<div class="right">
			<p class="fancySelectHead">Profile Image:</p>
			<iframe id="imageBox" src="nc_load.php?url=nc_core/imageupload/profileImage.php&user=<? echo $userForForms;?>"></iframe>
			</div>
			<span class="clearFix"></span>
			<?php
			generalHeader('Settings');
			
			if($administrate=='false')
			{
				echo "<span>By default, all Catserver.net accounts are marked as private upon creation, preventing anyone who isn't logged in from viewing any personal information you provide. This includes your display picture.</span>";
			}
			?>
			<p class="fancySelectHead">Make Private:</p>
			<select class="fancySelect" name="isPrivate">
				<option value="1" <?php if($private == '1'){ echo 'selected="selected"'; } ?>>Private</option>
				<option value="0" <?php if($private == '0'){ echo 'selected="selected"'; } ?>>Public</option>
			</select>
			<?php
			if($administrate=='true')
			{
				?>
				<input type="hidden" name="adminedit" value="true" />
				<p class="fancySelectHead">Moderator:</p>
				<select class="fancySelect" name="isMod">
					<option value="1" <?php if($super == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
					<option value="0" <?php if($super == '0'){ echo 'selected="selected"'; } ?>>No</option>
				</select>
				<p class="fancySelectHead">Administrator:</p>
				<select class="fancySelect" name="isAdmin">
					<option value="1" <?php if($admin == '1'){ echo 'selected="selected"'; } ?>>Yes</option>
					<option value="0" <?php if($admin == '0'){ echo 'selected="selected"'; } ?>>No</option>
				</select>
				<p class="fancySelectHead">Admin Token:</p>
				<textarea name="token" class="form"><?php echo $token; ?></textarea>
				<?
			}
			if($administrate=='false')
			{
				echo "<span>You can change the appearance of Catserver.net to one of the available pre-defined themes. Optionally, themes may have several variants or \"styles\" (such as light and dark colors). This setting will be applied upon login.</span>";
			}
			?>
			<p class="fancySelectHead wider">Theme:</p>
			<select class="fancySelect wider" name="theme">
			<?php
			$currentTheme = userData($userForForms, 'theme');
			
			while ($row = mysql_fetch_assoc($themes)) // loop through themes
				{ 
					echo "<option value=\"". $row['id']."\"";
					if($currentTheme == $row['id'])
					{
						echo ' selected="selected"';
					}
					echo ">". $row['name']."</option>\n";		
			}// loop done
			?>
			</select>
			 <p class="fancySelectHead">Style:</p>
			<select class="fancySelect" name="style">
			<?php
			$result = database("SELECT `style` FROM `themes` WHERE `id` = '$currentTheme'");
			$stylesArray = unserialize(mysql_result($result, 0, 'style'));
			
			if(!empty($stylesArray))
			{
				while(list($key, $val) = each($stylesArray)) //loop through styles
					{ 
						echo "<option value=\"". $val."\"";
						if(userData($userForForms, 'css') == $val)
						{
							echo ' selected="selected"';
						}
						echo ">". $key."</option>\n";
				}// loop done
			}
			else
			{
				echo "<option value=\"".userData($userForForms, 'css')."\" selected=\"selected\">No Styles</option>\n";
			}
			?>
			</select>
			<div class="right buttonFix">
			<input type="submit" value="Save" class="submitButton"/>
			</div>
			<div class="right buttonFix">
			<button onClick="" type="button" title="Change Password" class="submitButton">Password...</button>
			</div>
			</form>
			<script>
			  /* attach a submit handler to the form */
			  $("#profileUpdater").submit(function(event) {
			
				/* stop form from submitting normally */
				event.preventDefault(); 
					
				/* get some values from elements on the page: */
				var request = 'profileProcessor';
				
				var $form = $( this ),
					username = $form.find( 'textarea[name="username"]' ).val(),
					name = $form.find( 'textarea[name="name"]' ).val(),
					email = $form.find( 'textarea[name="email"]' ).val(),
					location = $form.find( 'textarea[name="location"]' ).val(),
					website = $form.find( 'textarea[name="website"]' ).val(),
					about = $form.find( 'textarea[name="about"]' ).val(),
					image = $form.find( 'textarea[name="image"]' ).val(),
					isPrivate = $form.find( 'select[name="isPrivate"]' ).val(),
					theme = $form.find( 'select[name="theme"]' ).val(),
					<?
						if($administrate == 'true')
						{
							?>
							adminedit = $form.find( 'input[name="adminedit"]' ).val(),
							token = $form.find( 'textarea[name="token"]' ).val(),
							isMod = $form.find( 'select[name="isMod"]' ).val(),
							isAdmin = $form.find( 'select[name="isAdmin"]' ).val(),
							<?
						}
					?>
					style = $form.find( 'select[name="style"]' ).val();
				/* Send the data using post and put the results in a div */
				$.post( 'nc_core/ajax/ajax.php', {username: username, name: name, email: email, location: location, website: website, about: about, image: image, isPrivate: isPrivate, theme: theme,<? if($administrate == 'true'){ echo " token: token, isMod: isMod, isAdmin: isAdmin, adminedit: adminedit,"; } ?> style: style, request: request },
				  function( data ) {
					  var content = $( data );
					  $( "#returned" ).empty().append( content );
				  }
				);
			  });
			</script>
	<?php
		}
		else
		{
			generalHeader('Account and Configuration');
			echo 'An error occurred while attempting to locate your account info. Dude, are you even logged in?';
		}
	?>
	</div>
<?php
}
?>