<h1>Site Settings</h1>
<?=Session::flash("sysmsg");?>
<div style="clear: both;"></div>
<form action="/admin/settings/save-site-settings" method="post">
	
	<?if(Account::GetData("level") == Account::ADMINISTRATOR || Account::GetData("level") == Account::SUPER_ADMINISTRATOR){?>
	<div id="bg-white" class="main-settings">

	<fieldset>
		<legend class="main" style="margin-top: 0px;"><span class="admin">Administration Settings</span></legend>
		
		<p>
			<label for="SITE_TITLE">Site Name:</label> <input type="text" id="SITE_TITLE" name="SITE_TITLE" value="<?=$administration->SiteName;?>" />
		</p>
		<p>
			<label for="ADMIN_POSTS_PER_PAGE">Posts Per Page:</label> <input type="text" id="ADMIN_POSTS_PER_PAGE" name="ADMIN_POSTS_PER_PAGE" value="<?=$administration->PostsPerPage;?>" />
		</p>
	</fieldset>
	
	<fieldset>
		<legend><span class="site">Site Settings</span></legend>
		
		<p>
			<label for="FRONT_POSTS_PER_PAGE">Blog Posts Per Page:</label> <input type="text" id="FRONT_POSTS_PER_PAGE" name="FRONT_POSTS_PER_PAGE" value="<?=$front->PostsPerPage;?>" />
		</p>
		<p>
			<label for="FAVICON_STANDARD">Favicon:</label> <input type="text" id="FAVICON_STANDARD" name="FAVICON_STANDARD" value="<?=$front->Favicon;?>" />
		</p>
		<p>
			<label for="FAVICON_IPHONE">Favicon IPhone:</label> <input type="text" id="FAVICON_IPHONE" name="FAVICON_IPHONE" value="<?=$front->FaviconIPhone;?>" />
		</p>
		<p>
			<label for="FAVICON_IPHONE4">Favicon IPhone4:</label> <input type="text" id="FAVICON_IPHONE4" name="FAVICON_IPHONE4" value="<?=$front->FaviconIPhone4;?>" />
		</p>
		<p>
			<label for="FAVICON_IPAD">Favicon IPad:</label> <input type="text" id="FAVICON_IPAD" name="FAVICON_IPAD" value="<?=$front->FaviconIPad;?>" />
		</p>
	</fieldset>
	
	<fieldset>
		
		<legend><span class="social">Social Media<br /><em>(note: Blank values will not show on the site)</em></span></legend>
		<p>
			<label for="SM_FACEBOOK">Facebook:</label> <input type="text" id="SM_FACEBOOK" name="SM_FACEBOOK" value="<?=$social->Facebook;?>" />
		</p>
		<p>
			<label for="SM_TWITTER">Twitter:</label> <input type="text" id="SM_TWITTER" name="SM_TWITTER" value="<?=$social->Twitter;?>" />
		</p>
		<p>
			<label for="SM_YOUTUBE">YouTube:</label> <input type="text" id="SM_YOUTUBE" name="SM_YOUTUBE" value="<?=$social->YouTube;?>" />
		</p>
		
		
	</fieldset>
	</div>
	<?}?>
	
	<?if(Account::GetData("level") == Account::SUPER_ADMINISTRATOR) {?>
	<div id="bg-white" class="main-settings" style="margin-top: 16px;">
	<fieldset>
		<legend class="main"><span class="developer">Site Developer Settings</span></legend>
		
		<fieldset>
			<legend style="margin-top: 0px"><span class="blog">Blog Settings</span></legend>
			<p>
				<label for="BLOG_MAX_IMAGE_WIDTH">Max Image Width:</label> <input type="text" id="BLOG_MAX_IMAGE_WIDTH" name="BLOG_MAX_IMAGE_WIDTH" value="<?=$super->Blog_MaxImageWidth;?>" />
			</p>
			<p>
				<label for="BLOG_MAX_IMAGE_HEIGHT">Max Image Height:</label> <input type="text" id="BLOG_MAX_IMAGE_HEIGHT" name="BLOG_MAX_IMAGE_HEIGHT" value="<?=$super->Blog_MaxImageHeight;?>" />
			</p>
		</fieldset>
		<fieldset>
			<legend><span class="gallery">Gallery Settings</span></legend>
			<p>
				<label for="GALLERY_MAX_IMAGE_WIDTH">Max Image Width:</label> <input type="text" id="GALLERY_MAX_IMAGE_WIDTH" name="GALLERY_MAX_IMAGE_WIDTH" value="<?=$super->Gallery_MaxImageWidth;?>" />
			</p>
			<p>
				<label for="GALLERY_MAX_IMAGE_HEIGHT">Max Image Height:</label> <input type="text" id="GALLERY_MAX_IMAGE_HEIGHT" name="GALLERY_MAX_IMAGE_HEIGHT" value="<?=$super->Gallery_MaxImageHeight;?>" />
			</p>
		</fieldset>
		<fieldset>
			<legend><span class="slider">Slider Settings</span></legend>
			<p>
				<label for="SLIDER_MAX_IMAGE_WIDTH">Max Image Width:</label> <input type="text" id="SLIDER_MAX_IMAGE_WIDTH" name="SLIDER_MAX_IMAGE_WIDTH" value="<?=$super->Slider_MaxImageWidth;?>" />
			</p>
			<p>
				<label for="SLIDER_MAX_IMAGE_HEIGHT">Max Image Height:</label> <input type="text" id="SLIDER_MAX_IMAGE_HEIGHT" name="SLIDER_MAX_IMAGE_HEIGHT" value="<?=$super->Slider_MaxImageHeight;?>" />
			</p>
		</fieldset>
		
	</fieldset>
	</div>
	<?}?>
	
	<input style="margin-top: 18px;" type="submit" value="Save Settings" />
	
</form>
