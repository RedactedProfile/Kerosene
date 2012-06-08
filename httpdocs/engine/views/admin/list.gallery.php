<style>
	#galleries {
	}
	
	
	#galleries li, #newBGallery {
		list-style: none;
		float: left;
	}
	
	#galleries img, #newGallery img {
		width: 150px;
		height: 150px;
	}
	
	
</style>
<script>

	$(function() {
		$(".launcher").colorbox({inline:"true"});
		$("#galleries").sortable({
			update: function(event, ui) {
				var results = $(this).sortable('toArray');
				$.post("/admin/gallery/save-sort", {
					order: results
				}, function(data) {
					if(data.status != "true") {
						alert(data.msg);
					} else Success();
				}, "json");
			}
		});
		$("#galleries").disableSelection();
	});
	

	function deleteGallery(id) {
		if(confirm("Are you sure you wish to delete this Gallery? This is irreversable")) window.location = "/admin/gallery/delete/"+id;
	}

	function Success() {
		$("#galleries li").each(function(e) {
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
	<div><span class="title">Edit Galleries</span></div>
</div>
<?=Session::flash("sysmsg");?>
<?=(string)new Tip("You can reorder albums by clicking and dragging");?>
<ul id="galleries">

	<?foreach($galleries as $gallery){?>
		
		<li id="<?=$gallery->getID();?>">
			<span class="title"><?=$gallery->getTitle();?></span>
			<a class="launcher" href="#edit_<?=$gallery->getID();?>">
				<!--<img src="/images/gallery/<?=$gallery->getSlug();?>/<?=$gallery->getTopImage()->getThumbnail();?>" width="150" height="150" />-->
				<img src="<?=$gallery->getTopImage()->getThumbnail(150, 150);?>" />
			</a>
			<div class="slide-settings">
				<div onclick="deleteGallery(<?=$gallery->getID();?>);" class="deleteButton"></div>
				<a href="#edit_<?=$gallery->getID();?>" class="launcher settingsButton">Edit</a>
				<div onclick="window.location='/admin/gallery/edit/<?=$gallery->getSlug();?>'" class="photosButton">Photos</div>
			</div>
		</li>
	<?}?>
		

</ul>
<ul id="newGallery">
	<li>
		<a class="launcher" href="#new"></a>
	</li>
</ul>


<div style="display: none;">

	<?foreach($galleries as $gallery) {?>
	
		<div class="editWindow" id="edit_<?=$gallery->getID();?>">
			<h2>Edit Gallery: <em><?=$gallery->getTitle();?></em></h2>
			<form action="/admin/gallery/save/<?=$gallery->getID();?>" method="post">
				<div class="row"><a href="/admin/gallery/edit/<?=$gallery->getSlug();?>">Manage Photos</a></div>
				<div class="row">
					<input type="checkbox" name="published" value="1" <?=(($gallery->isPublished())?"checked":null);?> /> Published
				</div>
				<div class="row">
					<span>Title:</span> <input type="text" name="title" value="<?=$gallery->getTitle();?>" />
				</div>
				<div class="row">
					<input type="submit" value="Update" />
				</div>
			</form>
			
		</div>
	
	<?}?>
	
		<div class="editWindow" id="new">
		
			<form action="/admin/gallery/save" method="post">
				
				<div class="row">
					<input type="checkbox" name="published"  value="1" /> Published
				</div>
				<div class="row">
					<span>Title:</span> <input type="text" name="title" />
				</div>
				<div class="row">
					<input type="submit" value="Update" />
				</div>
				
			</form>
		
		</div>
</div>