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
                                <?/*is a parent value exists, this is a new sub-page, if not then we are creating a new parent (blank value)*/?>
<form action="/admin/pages/save/new/<?=((isset($parent))? $parent : null );?>" method="post" onsubmit="return Save();">
	<div class="edit-page-title">
		<div><span class="title">Page Title:</span> <input type="text" name="title" value="" /></div>
		<input class="save-btn" type="submit" value="Create Page" />
	</div>
	<div class="edit-page-settings">
		<div class="display">
			<input type="checkbox" id="display" name="display" value="1" checked/> <label for="display">Display?</label>
		</div>
	</div>
	<textarea name="content" id="content"></textarea>
	<div class="pane">
		<h2><span class="meta">Meta</span></h2>
		<div class="pane-detail">
			<p><label for="meta_title">Meta Title:</label> <input type="text" id="meta_title" name="meta_title" value="" /></p>
			<p><label for="meta_keys">Keywords:</label> <input type="text" id="meta_keys" name="meta_keys" value="" /></p>
			<p style="margin-bottom: 2px;"><label for="meta_description">Description:</label> <input type="text" id="meta_description" name="meta_description" value="" /></p>
		</div>
	</div>
	<div class="submit-area">
		<input type="submit" class="save-btn" value="Create Page" />
	</div>
	<div style="clear: both;"></div>
</form>
</div>