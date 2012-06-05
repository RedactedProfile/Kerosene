<style>
	#slides li, #newButton {
		list-style: none;
		float: left;
	}
	
	#slides img, #newButton img {
		width: 250px;
		height: 100px;
	}
	
	
</style>
<script>

	$(function() {
		$(".launcher").colorbox({inline:"true"});
		$("#slides").sortable({
			update: function(event, ui) {
				var results = $(this).sortable('toArray');
				$.post("/admin/slideshow/save-sort", {
					order: results
				}, function(data) {
					if(data.status != "true") {
						alert(data.msg);
					} else {
						Success();
					}
				}, "json");
			}
		});
		$("#slides").disableSelection();
	});
	

	function deleteSlide(id) {
		if(confirm("Are you sure you wish to delete this slide? This is irreversable")) window.location = "/admin/slideshow/delete/"+id;
	}

	function Success() {
		$("#slides li").each(function(e) {
			var ogColor = $(this).css("background-color");
			var ogBorderColor = $(this).css("border-color");


			//$(this).css("background-color", "#00FF00");

			$(this).animate({
				"background-color": "#bbe5ac",
				"border-color": "#67ca44"
					
			},500, function() {

				$(this).animate({
					"background-color": ogColor,
					"border-color": "#CCC"
				}, 1500, function(){
				});
				
			});
			
			
			
		});
	}
	
</script>
<div class="edit-slideshow-title">
	<div><span class="title">Edit Front Page Slideshow</span></div>
	<a class="edit-settings-btn launcher" href="#settings">Edit Settings</a>
</div>
<?=Session::flash("sysmsg");?>
<?=(string)new Tip("You can reorder slides by clicking and dragging");?>
<ul id="slides">

	<?foreach($slides as $slide){?>
		<li id="<?=$slide->getID();?>">
			<a class="launcher" href="#edit_<?=$slide->getID();?>">
				<?/*<img src="/images/slider/<?=$slide->getImage();?>" class="imgPreview" />*/?>
				<img src="<?=$slide->getThumbnail(254, 104);?>" class="imgPreview" />
			</a>
			<div class="slide-settings">
				<div onclick="deleteSlide(<?=$slide->getID();?>);" class="deleteButton">Remove</div>
				<a href="#edit_<?=$slide->getID();?>" class="settingsButton launcher">Manage</a>
			</div>
		</li>
	<?}?>
</ul>
<ul id="newButton">
	<li>
		<a class="launcher" href="#new"></a>
	</li>
</ul>

<div style="display: none;">

	<div class="editWindow settingsWide" id="settings">
		<h2>Edit Slider Settings</h2>
		<form action="/admin/slideshow/save-settings" method="post">
		
			<div class="row">
				<span>Display Slideshow:</span> <input type="checkbox" value="1" name="is_on" <?=(($settings->isOn())?"checked":null);?> /> On
			</div>
			<div class="row">
				<span>Transition:</span>
				<select name="transition">
					<option value="fade" <?=(($settings->getTransition() == "fade")?"selected":null);?>>Fade</option>
					<option value="horizontal-slide" <?=(($settings->getTransition() == "horizontal-slide")?"selected":null);?>>Slide Horizontally</option>
					<option value="vertical-slide" <?=(($settings->getTransition() == "vertical-slide")?"selected":null);?>>Slide Vertically</option>
					<option value="horizontal-push" <?=(($settings->getTransition() == "horizontal-push")?"selected":null);?>>Push Horizontally</option>
				</select>
			</div>
			<div class="row">
				<span>Animation Speed:</span> <input type="text" name="animation_speed" value="<?=$settings->getAnimationSpeed();?>" />
			</div>
			<div class="row">
				<span>Transition Speed:</span> <input type="text" name="transition_speed" value="<?=$settings->getTransitionSpeed();?>" />
			</div>
			<div class="row">
				<span>Slide Navigation:</span> <input type="checkbox" value="1" name="is_slide_nav_on" <?=(($settings->isSlideNavOn())?"checked":null);?> /> On
			</div>
			<div class="row">
				<span>Caption Transition:</span>
				<select name="caption_transition">
					<option value="fade" <?=(($settings->getCaptionTransition() == "fade")?"selected":null);?>>Fade</option>
					<option value="slideOpen" <?=(($settings->getCaptionTransition() == "slideOpen")?"selected":null);?>>Slide Open</option>
					<option value="none" <?=(($settings->getCaptionTransition() == "none")?"selected":null);?>>None</option>
				</select>
			</div>
			<div class="row">
				<span>Caption Animation Speed:</span>
				<input type="text" name="caption_animation_speed" value="<?=$settings->getCaptionAnimationSpeed();?>" />
			</div>
			<div class="row">
				<span>Slide Navigation Bullets:</span>
				<input type="checkbox" value="1" name="is_slide_nav_bullet_on" <?=(($settings->isSlideNavBulletOn())?"checked":null);?>> On
			</div>
			<div class="row">
				<span>Width:</span>
				<input type="text" name="width" value="<?=$settings->getWidth();?>" />
			</div>
			<div class="row">
				<span>Height:</span>
				<input type="text" name="height" value="<?=$settings->getHeight();?>" />
			</div>
			<div class="row">
				<input type="submit" value="Save Changes" />
			</div>
		</form>
		
	</div>

	<?foreach($slides as $slide) {?>
	
		<div class="editWindow" id="edit_<?=$slide->getID();?>">
			<h2>Edit Slide</h2>
			<form action="/admin/slideshow/save/<?=$slide->getID();?>" method="post" enctype="multipart/form-data">
				<div class="row"><input type="checkbox" name="published" value="1" <?=(($slide->getPublished())?"checked":null);?> /> Published</div>
				<div class="row"><img src="/images/slider/<?=$slide->getImage();?>" /></div>
				<div class="row"><span>Replace:</span> <input type="file" name="new_image" /></div>
				<div class="row"><span>Caption:</span> <input type="text" name="caption" value="<?=$slide->getCaption();?>" /></div>
				<div class="row"><span>Link:</span> <input type="text" name="url" value="<?=$slide->getURI();?>" /></div>
				<div class="row">
					<input type="submit" value="Update" />
				</div>
			</form>
			
		</div>
	
	<?}?>
	
		<div class="editWindow" id="new">
		
			<form action="/admin/slideshow/save" method="post" enctype="multipart/form-data">
			
				<div class="row"><input type="checkbox" name="published"  value="1" /> Published</div>
				<div class="row"><span>Image:</span> <input type="file" name="new_image" /></div>
				<div class="row"><span>Caption:</span> <input type="text" name="caption" /></div>
				<div class="row"><span>Link:</span> <input type="text" name="url" value="http://" /></div>
				<div class="row">
					<input type="submit" value="Update" />
				</div>
			
			</form>
		
		</div>
</div>