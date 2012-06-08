<style>
	#photos {
	}
	
	
	#photos li, #newButton {
		list-style: none;
		float: left;
	}
	
	#photos img, #newButton img {
		width: 150px;
		height: 150px;
	}
	
	
</style>
<script>

	$(function() {
		$(".launcher").colorbox({inline:"true"});
		$("#photos").sortable({
			update: function(event, ui) {
				var results = $(this).sortable('toArray');
				$.post("/admin/gallery/edit/<?=$gallery->getSlug();?>/photo/save-sort", {
					order: results
				}, function(data) {
					if(data.status != "true") {
						alert(data.msg);
					} else Success();
				}, "json");
			}
		});
		$("#photos").disableSelection();
	});
	

	function deletePhoto(id) {
		if(confirm("Are you sure you wish to delete this Photo? This is irreversable")) window.location = "/admin/gallery/edit/<?=$gallery->getSlug();?>/photo/delete/"+id;
	}

	function Success() {
		$("#photos li").each(function(e) {
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
<div class="edit-gallery-title">
	<div><span class="title">Edit Gallery: <em><?=$gallery->getTitle();?></em></span></div>
	<a class="edit-settings-btn launcher" href="#multiple">Batch Upload</a>
</div>
<?=Session::flash("sysmsg");?>
<?=(string)new Tip("You can reorder photos by clicking and dragging");?>
<ul id="photos">

	<?foreach($photos as $photo){?>
		<script>
			$(function() {
				$(".img-<?=$photo->getID();?>").colorbox();
			});
		</script>
		<li id="<?=$photo->getID();?>">
			
			<a class="img-<?=$photo->getID();?>" href="/images/gallery/<?=$gallery->getSlug();?>/<?=$photo->getImage();?>">
				<?/*<img src="/images/gallery/<?=$gallery->getSlug();?>/<?=$photo->getImage();?>" width="150" height="150" />*/?>
				<img src="<?=$photo->getThumbnail(150, 150);?>" />
				
			</a>
			
			<div class="slide-settings">
				<a href="#edit_<?=$photo->getID();?>" class="settingsButton launcher">Edit Photo</a>
				<div onclick="deletePhoto(<?=$photo->getID();?>);" class="deleteButton">Delete</div>
			</div>
		</li>
	<?}?>
		

</ul>
<ul id="newButtonPhoto">
	<li>
		<a class="launcher" href="#new"></a>
	</li>
</ul>


<div style="display: none;">

	<?foreach($photos as $photo) {?>
	
		<div class="editWindow" id="edit_<?=$photo->getID();?>">
			<h2>Edit Photo</h2>
				
			<form action="/admin/gallery/edit/<?=$gallery->getSlug();?>/photo/update/<?=$photo->getID();?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<input type="checkbox" name="published" value="1" <?=(($photo->isPublished())?"checked":null);?> /> Published
				</div>
				<div class="row">
					<span>Caption:</span> <input type="text" name="caption" value="<?=$photo->getCaption();?>" />
				</div>
				<div class="row">
					<input type="submit" value="Update" />
				</div>
			</form>
			
		</div>
	
	<?}?>
	
		<div class="editWindow" id="new">
			<h2>Add New Image</h2>
			<form action="/admin/gallery/edit/<?=$gallery->getSlug();?>/photo/save" method="post" enctype="multipart/form-data">
			
				<div class="row">
					<input type="checkbox" name="published"  value="1" /> Published
				</div>
				<div class="row">
					<span>Browse:</span>
					<input type="file" name="photo" />
				</div>
				<div class="row">
					<span>Caption:</span> <input type="text" name="caption" />
				</div>
				<div class="row">
					<input type="hidden" name="gallery" value="<?=$gallery->getID();?>" />
				</div>
				<div class="row">
					<input type="submit" value="Add Image" />
				</div>
			
			</form>
		
		</div>
		
		<div class="editWindow" id="multiple">
			<h2>Batch Upload</h2>
			<form action="/admin/gallery/edit/<?=$gallery->getSlug();?>/photo/batch" method="post" enctype="multipart/form-data">
				<div class="row">
					<input type="checkbox" name="published"  value="1" /> All Published?
				</div>
				<div class="row">
					<span>Select Files:</span> <input type="file" name="batch[]" multiple />
				</div>
				<div class="row">
					<input type="hidden" name="gallery" value="<?=$gallery->getID();?>" />
				</div>
				<div class="row">
					<input type="submit" value="Add Images" />
				</div>
			</form>
		</div>
</div>