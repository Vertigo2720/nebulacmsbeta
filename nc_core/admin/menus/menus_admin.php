<?
//----------------------------------------//
//MENUS AND OPTIONS FOR LOGGED IN ACCOUNTS//
//----------------------------------------//

//User Menu
function userUI()
{
	if(userCheck())
	{
		//Prerequisites
		$username = userCheck('returnCurrent');
		?>
		<div class="userMenu noselect">
		<p align="left">
		<ul id="userMenuNav">
		<li><span class="userMenuText"><a href="account"><? echo $username ?></a></span></li>
		<li><span class="userMenuText"><a href="#">Tools</a></span>
				<ul class="navDropWidth">
						<li class="navDrop">
						<?
						if (modCheck() or adminCheck())
							{
							echo "<a href='page-imageupload'>Image Uploader</a>";
							}
						?>
						<a href='page-storage'>Your Storage</a>
						<a href='upload'>File Uploader</a>
						<? //<a href='ilynx'>iLynx.us</a> ?>
						<a href='services'>Full Status</a>
						</li>
				</ul>
		</li>
		<?
		if (modCheck() or adminCheck())
		{
			?>
			<li><span class="userMenuText"><a href="#"><? if(adminCheck()){ $admin = 1; echo "Administrator"; } else { $admin = 0; echo "Moderator";} ?></a></span>
				<ul class="navDropWidth">
						<li class="navDrop">
						<a href='page-utorrent'>Torrents</a>
						<a href='page-mcmyadmin'>McMyAdmin</a>
						<?
						if($admin == 1)
						{
							echo "<a href='page-storage'>Browse Files</a>";
						}
						?>
						</li>
				 </ul>
			</li>
			<?
		}
		?>
		<li><span class="userMenuText"><form action="#" method="post"><input type="hidden" name="logout" /><input type="submit" name="submit" value="Log Out" /></form></span></li>
		</ul>
		</p>
		<?
		//<div id='cse' style='width: 100%;'>Loading</div>
		//<script src='//www.google.com/jsapi' type='text/javascript'></script>
		//<script type='text/javascript'>
		//google.load('search', '1', {language: 'en', style: google.loader.themes.V2_DEFAULT});
		//google.setOnLoadCallback(function() {
		//  var customSearchOptions = {};
		//  var orderByOptions = {};
		//  orderByOptions['keys'] = [{label: 'Relevance', key: ''} , {label: 'Date', key: 'date'}];
		//  customSearchOptions['enableOrderBy'] = true;
		//  customSearchOptions['orderByOptions'] = orderByOptions;
		//  customSearchOptions['overlayResults'] = true;
		//  var customSearchControl =   new google.search.CustomSearchControl('009771454284285984078:onao_9ihiqw', customSearchOptions);
		//  customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
		//  var options = new google.search.DrawOptions();
		//  options.setAutoComplete(true);
		//  customSearchControl.draw('cse', options);
		//}, true);
		//</script>
		?>
		</div>
<?php
	}
}

//Admin Menu
function adminUI()
{
	if(adminCheck())
	{
		?>
		<div class="editUsersBanner banner animate noselect"><p align="center"><h1 class="animate">Edit user database<span class="right" id="editUsersLoading" style="display:none;"><img src="<? echo themeDir(); ?>images/icons/loading_small.gif" height="20px"/></span></h1></p></div>
		<script type="text/javascript">
		AjaxShowHide('editUsers', '.editUsersBanner', 'getUserSelect');
		</script>
		<div class="adminDropBox" id="editUsers">
		</div>
		<div class="createSnipBanner banner animate noselect"><p align="center"><h1 class="animate">Make a snippet<span class="right" id="createSnipLoading" style="display:none;"><img src="<? echo themeDir(); ?>images/icons/loading_small.gif" height="20px"/></span></h1></p></div>
		<script type="text/javascript">
		AjaxShowHide('createSnippet', '.createSnipBanner', 'getSnipForm');
		</script>
		<div class="adminDropBox" id="createSnippet">
		</div>
		<div class="createPageBanner banner animate noselect"><p align="center"><h1 class="animate">Create or modify a page<span class="right" id="createPageLoading" style="display:none;"><img src="<? echo themeDir(); ?>images/icons/loading_small.gif" height="20px"/></span></h1></p></div>
		<script type="text/javascript">
		AjaxShowHide('createPage', '.createPageBanner', 'getPageForm');
		</script>
		<div class="adminDropBox" id="createPage">
		</div>
		<div class="postBlogUpdateBanner banner animate noselect"><p align="center"><h1 class="animate">Post blog update<span class="right" id="postBlogUpdateLoading" style="display:none;"><img src="<? echo themeDir(); ?>images/icons/loading_small.gif" height="20px"/></span></h1></p></div>
		<script type="text/javascript">
		AjaxShowHide('postBlogUpdate', '.postBlogUpdateBanner', 'getBlogForm');
		</script>
		<div class="adminDropBox" id="postBlogUpdate">
		</div>
		<div class="uploadImagesBanner banner animate noselect"><p align="center"><h1 class="animate">Upload Images<span class="right" id="uploadImagesLoading" style="display:none;"><img src="<? echo themeDir(); ?>images/icons/loading_small.gif" height="20px"/></span></h1></p></div>
		<script type="text/javascript">
		AjaxShowHide('uploadImages', '.uploadImagesbanner', 'getImageForm');
		</script>
		<div class="adminDropBox" id="uploadImages">
		</div>
		<?
	}
}
