<?inc("js", "jquery.ui.nestedSortable");?>
<style>

	.placeholder {
			background-color: #cfcfcf;
		}
</style>
<script>
	function deleteMenu(parent, slug) {
		if(confirm("Are you sure you wish to delete this page? This is irreversable.")) {
			window.location = "/admin/pages/delete/"+parent+"/"+slug;
		}
	}


	$(function() {

		$(".launcher").colorbox({inline:"true"});
		
		$("#chooser_domain").change(function(){
			$.post("/admin/ajax/setNewDomainSession", {
				domain: $("#chooser_domain").val()
			}, function(data) {
				if(data.status == "true") window.location.reload(); 
				else alert(msg);
			}, "json");
		});

		/*
		$("#menu-navigation,.sub-navigation-main").sortable(
			{
				update: function(event, ui) {
					var results = $(this).sortable('toArray');
					$.post("/admin/ajax/save-sort", {
						type: "pages",
						order: results
					}, function(data) {
						if(data.status != "true") {
							alert(data.msg);
						} else {
							Success();
						}
					}, "json");
				}
			}
		);
		*/
		$("ol.sortable").nestedSortable({
			disableNesting: 'no-nest',
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			maxLevels: 2,
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div'
		});

		
		//$("#menu-nevigation").disableSelection();
	});	

</script>

<?//die(Session::data("currentDomain"));?>
<div class="edit-slideshow-title">
	<div><span class="title" style="background: url(/images/admin/icon-page.png) no-repeat left center;">Edit Menu for <em><?=$menu->getName();?></em></span></div>
	<a class="edit-settings-btn launcher" href="#new">New Link</a>
</div>
<?=Session::flash("sysmsg");?>
<?=(string)new Tip("You can reorder menus and submenus by dragging them around. Changes are saved immediately");?>
<nav>
	<ol class="sortable">
		<?foreach($links as $link){?>
			<li id="<?=$link->getID();?>">
				<div class="inner-list">
					<div class="main-link">
						<h3><a class="launcher" href="#link_<?=$link->getID();?>"><?=$link->getLabel();?></a></h3> 
					</div>
				</div>								
			</li>
		<?}?>
	</ol>
</nav>

<div style="display:none;">
	
	
	<div class="editWindow" id="new">
		<h2>New Link</h2>
		<form action="/admin/menus/links/save/new" method="post">
			<input type="hidden" name="menu" value="<?=$menu->getID();?>" />
			<div class="row"><span>Text:</span> <input type="text" name="label" /></div>
			<div class="row"><span>Link:</span> <input type="text" name="url" /></div>
			<div class="row"><span>Classes:</span> <input type="text" name="classes" /></div>
			<div class="row"><span>Target</span> <select name="target"><option value="<?=Target::_SAME;?>">Same Window</option><option value="<?=Target::_NEW;?>">New Window</option></select></div>
			<div class="row">
				<input type="submit" value="Create" /> 
			</div>
		</form>
		
	</div>
	
	<?foreach($links as $link){?>
	<div class="editWindow" id="link_<?=$link->getID();?>">
		<h2>Edit Link</h2>
		<form action="/admin/menus/links/save/<?=$link->getID();?>" method="post">
			<input type="hidden" name="menu" value="<?=$menu->getID();?>" />
			<div class="row"><span>Text:</span> <input type="text" name="label" value="<?=$link->getLabel();?>" /></div>
			<div class="row"><span>Link:</span> <input type="text" name="url" value="<?=$link->getURI();?>" /></div>
			<div class="row"><span>Classes:</span> <input type="text" name="classes" value="<?=$link->getClasses();?>" /></div>
			<div class="row"><span>Target</span> <select name="target"><option value="<?=Target::_SAME;?>" <?=(($link->getTarget() == Target::_SAME)?"selected":null);?>>Same Window</option><option value="<?=Target::_NEW;?>" <?=(($link->getTarget() == Target::_NEW)?"selected":null);?>>New Window</option></select></div>
			<div class="row">
				<input type="submit" value="Update" />
			</div>
		</form>
		
	</div>
	<?}?>
	
</div>