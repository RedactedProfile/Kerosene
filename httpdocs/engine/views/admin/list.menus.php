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

		
		$("#menu-nevigation").disableSelection();
	});	

</script>

<div class="edit-slideshow-title">
	<div><span class="title" style="background: url(/images/admin/icon-page.png) no-repeat left center;">Edit Menus for </span> 
	<select id="chooser_domain">
		<?foreach($domains as $domain){?>
		<option value="<?=$domain->getID();?>" <?=((Session::data("currentDomain") == $domain->getID())?"selected":null);?>><?=$domain->getName().".".$domain->getDomain();?></option>
		<?}?>
	</select>
	
	</div>
	<a class="edit-settings-btn launcher" href="#new">New Menu</a>
</div>
<?=Session::flash("sysmsg");?>
<nav>
	<ul id="menu-navigation">
		<?foreach($menus as $menu){?>
			<li id="<?=$menu->getID();?>">
				
				<div class="inner-list">
					
					<div class="main-link">
						<h3><a class="launcher" href="#menu_<?=$menu->getID();?>"><?=$menu->getName();?></a></h3>
						<a href="/admin/menus/links/list/<?=$menu->getID();?>" class="btn-new-page">Modify Links</a>
					</div>
					
				</div>								
			</li>
		<?}?>
	</ul>
</nav>

<div style="display:none;">
	
	
	<div class="editWindow" id="new">
		<h2>New Menu</h2>
		<form action="/admin/menus/save/new" method="post">
			<div class="row"><span>Name:</span> <input type="text" name="name" /></div>
			<div class="row">
				<input type="submit" value="Create" />
			</div>
		</form>
		
	</div>
	
	<?foreach($menus as $menu){?>
	<div class="editWindow" id="menu_<?=$menu->getID();?>">
		<h2>Edit Menu: <?=$menu->getName();?></h2>
		<form action="/admin/menus/save/<?=$menu->getID();?>" method="post">
			<div class="row"><span>Name:</span> <input type="text" name="name" value="<?=$menu->getName();?>" /></div>
			<div class="row">
				<input type="submit" value="Update" />
			</div>
		</form>
		
	</div>
	<?}?>
	
</div>