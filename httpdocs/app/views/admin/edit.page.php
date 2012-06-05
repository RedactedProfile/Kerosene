<script>
	var edits = 0;
	var bypass = 0;
	$(function() {
		
		CKEDITOR.replace("content", {
			width: "99%",
			height: "500"
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
	
</script>

<?=Session::flash("sysmsg");?>
<div id="bg-white">
<form action="/admin/pages/save/<?=$slugs;?>" method="post" onsubmit="return Save();">
	<div class="edit-page-title">
		<div><span class="title">Page Title:</span> <input type="text" name="title" id="title" value="<?=$cms->getTitle();?>" /></div>
		<input class="save-btn" type="submit" value="Save Page" />
	</div>
	<div class="edit-page-settings">
		<div class="display">
			<?if($cms->getCategory() != 0){?>
			<input type="checkbox" id="display" name="display" value="1" <?=(($cms->getDisplay())?"checked":null)?> /> <label for="display">Display?</label>
			<?} else {?>
			<input type="checkbox" id="display" name="display" checked disabled /> <label for="display">Display?</labe> <span style="color: red; font-size: 12px">(forced)</span>
			<?}?>
		</div>
	</div>
	<textarea name="content" id="content"><?=$cms->getContent();?></textarea>
	<div class="pane">
		<h2><span class="meta">Meta</span></h2>
		<div class="pane-detail">
			<p><label for="meta_title">Meta Title:</label> <input type="text" id="meta_title" name="meta_title" value="<?=$cms->getMetaTitle();?>" /></p>
			<p><label for="meta_keys">Keywords:</label> <input type="text" id="meta_keys" name="meta_keys" value="<?=$cms->getMetaKeys();?>" /></p>
			<p style="margin-bottom: 2px;"><label for="meta_description">Description:</label> <input type="text" id="meta_description" name="meta_description" value="<?=$cms->getMetaDescription();?>" /></p>
		</div>
	</div>
	<div class="submit-area">
		<input type="submit" class="save-btn" value="Save Page" />
	</div>
	<div style="clear: both;"></div>
</form>
</div>
