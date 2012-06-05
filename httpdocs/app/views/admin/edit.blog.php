<script>
	var edits = 0;
	var bypass = 0;
	$(function() {
		CKEDITOR.replace("content", {
			width: "99%",
			height: "545"
		});
	


		window.onbeforeunload = function() {
			if(CKEDITOR.instances.content.checkDirty()) edits = 1;
			if(edits == 1 && bypass == 0) return "You have unsaved changes";
		}

		$("#title").keyup(function() { edits = 1; });
		$("#meta_title").keyup(function() { edits = 1; });
		$("#meta_keys").keyup(function() { edits = 1; });
		$("#meta_description").keyup(function() { edits = 1; });
		
	});

	function Save() {
		bypass = 1;
		return true;
	}

	function editPublishTime() {
		if($("#edit_publish_time").is(":visible")) {
			//
		} else {
			$("#edit_publish_time").slideDown();
		}
	}

	
</script>
<div id="bg-white">

<form action="/admin/blog/save/<?=$post->getSlug();?>" method="post" onsubmit="return Save();" enctype="multipart/form-data">

	<div class="edit-page-title">
		<div><span class="title-blog">Post Title:</span> <input type="text" name="title" id="title" value="<?=$post->getTitle();?>" /></div>
		<input class="save-btn" type="submit" value="Save Post" />
	</div>

	<div class="publish-settings">
		<div>
			<h3>Publish</h3>
			<div style="padding: 8px; margin: 4px 0px 12px 0px; background: #fff;">
			<span class="tipleft" tip="Marks whether the post is visible on the front end or not">
				Published: <input type="checkbox" name="post_visible" id="post_visible" <?=(($post->getPublished())?"checked":null)?> /><br />
			</span>
			<span class="tipleft" tip="Specifies the time when the post will be visible to the front end (if published of course)">
				Publish <span id="publish_time"><strong><?=(($post->getID())?$post->getDatePublished(Tools::DATEFORMAT_MEDIUM_TIME):"Right Now");?></strong></span> <span id="publish_edit"><a href="#" onClick="editPublishTime()">Edit</a> </span>
			</span>
			<div id="edit_publish_time" style="display: none;">
				Date:
				<select name="edit_publish_time_month" id="edit_publish_time_month">
					<?for($i = 01; $i <= 12; $i++){
						echo "<option value='".$i."' ".(($i == date("n", strtotime( ((strtotime($post->getDatePublished(Tools::DATEFORMAT_RAW)))?$post->getDatePublished(Tools::DATEFORMAT_RAW):"now") )))?"selected":null).">".date("M", strtotime("01-".$i."-1999"))."</option>
						";
					}?>
				</select>
				<select name="edit_publish_time_day" id="edit_publish_time_day">
					<?for($i = 1; $i <= 31; $i++){
						echo "<option value='".$i."' ".(($i == date("j", strtotime( ((strtotime($post->getDatePublished(Tools::DATEFORMAT_RAW)))?$post->getDatePublished(Tools::DATEFORMAT_RAW):"now") )))?"selected":null).">$i</option>";
					}?>
				</select>,
				<input type="text" name="edit_publish_time_year" id="edit_publish_time_year" value="<?=date("Y", strtotime(  (( strtotime($post->getDatePublished(Tools::DATEFORMAT_RAW)) )? $post->getDatePublished(Tools::DATEFORMAT_RAW) : "now")));?>" maxlength="4" style="width: 55px;" />
				<br />
				Time (24h): 
				<input type="text" name="edit_publish_time_hour" id="edit_publish_time_hour" value="<?=date("H", strtotime(  (( strtotime($post->getDatePublished(Tools::DATEFORMAT_RAW)) )? $post->getDatePublished(Tools::DATEFORMAT_RAW) : "now")));?>" maxlength="2" style="width: 45px;" />
				:
				<input type="text" name="edit_publish_time_min" id="edit_publish_time_min" value="<?=date("i",   strtotime(  (( strtotime($post->getDatePublished(Tools::DATEFORMAT_RAW)) )? $post->getDatePublished(Tools::DATEFORMAT_RAW) : "now")));?>" maxlength="2" style="width: 45px;" />
				<br />
				
			</div>
			<input type="hidden" id="publish_time_date" value="now" />
			</div>
		</div>
		<div class="category">
		
			<h3>Category</h3>
			<div style="width: 100%; background: #fff; height: 150px; overflow-y: scroll;">
				<?foreach($categories as $category){
					echo '<input type="radio" name="category" value="'.$category->getID().'" '.(($post->getCategoryID() == $category->getID())?"checked":null).' />'.$category->getTitle()."<br />";
				}?>
			</div>
			<div style="width: 100%; padding: 10px 0px;">
				<strong>New Category:</strong><br />
				<input class="tipleft" style="background: #fff;" tip="To create a new category, simply type the title here. It will be created and assigned when the blog post is saved" type="text" name="new_category" id="new_category" />
			</div>
		</div>
		<div tip="This is the main image associated with the post, shown on the front end beside the title and in addition this will also be carried over to social networks where applicable" class="featured tipleft">
			<h3>Featured Image</h3>
			<?if($post->getFeaturedImage() != null){?>
			<img src="<?=$post->getFeaturedThumbnail(290, 175);?>" />
			<?}else{?>
			<img src="/images/blog/no-feature.png" width="175" height="175" />
			<?}?>
			<input type="file" name="new_featured_image" />
			<input type="hidden" name="featured_image" value="<?=$post->getFeaturedImage();?>" />
		</div>
	</div>

	<div class="post-content">
		<textarea name="content" id="content"><?=$post->getContent();?></textarea>
	</div>
	
	<div style="clear: both;"></div>

	<div class="pane">
		<h2>
			<span class="meta">Search Engine Optimization</span>
		</h2>
		<div class="pane-detail">
			<p>
				<label for="meta_title">Meta Title:</label>
				<input type="text" name="meta_title" id="meta_title" value="<?=$post->getMetaTitle();?>" />
			</p>
			<p>
				<label for="meta_keys">Meta Keys:</label>
				<textarea name="meta_keys" id="meta_keys"><?=$post->getMetaKeys();?></textarea><br />
			</p>
			<p>
				<label for="meta_description">Meta Description:</label>
				<textarea name="meta_description" id="meta_description"><?=$post->getMetaDescription();?></textarea>
			</p>
		</div>
	</div>
	<div style="clear: both;"></div>

</form>
</div>