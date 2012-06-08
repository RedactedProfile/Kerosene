<script>
	function deleteMenu(parent, slug) {
		if(confirm("Are you sure you wish to delete this page? This is irreversable.")) {
			window.location = "/admin/pages/delete/"+parent+"/"+slug;
		}
	}

	$(function() {
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

<?//die(Session::data("currentDomain"));?>
<div class="edit-slideshow-title">
	<div><span class="title" style="background: url(/images/admin/icon-page.png) no-repeat left center;">Edit Pages for </span> 
	<select id="chooser_domain">
		<?foreach($domains as $domain){?>
		<option value="<?=$domain->getID();?>" <?=((Session::data("currentDomain") == $domain->getID())?"selected":null);?>><?=$domain->getName().".".$domain->getDomain();?></option>
		<?}?>
	</select>
	
	</div>
	<a class="edit-settings-btn launcher" href="/admin/pages/new">New Page</a>
</div>
<?=Session::flash("sysmsg");?>
<?=(string)new Tip("You can reorder main pages and subpages by dragging them around. Changes are saved immediately");?>
<nav>
	<ul id="menu-navigation">
		<?foreach($pages as $page){?>
			<li id="<?=$page->getID();?>">
				<?if(!$page->isHomepage()){?><input type="button" value="Make Homepage" onclick="window.location= '/admin/pages/make-homepage/<?=$page->getID();?>'" /><?}?>
				<div class="inner-list">
					
					<div class="main-link">
						<h3><a href="/admin/pages/edit/<?=$page->getID();?>"><?=$page->getTitle();?></a></h3> <a href="/admin/pages/edit/<?=$page->getID();?>" class="btn-edit">Edit</a>
						<a href="/admin/pages/new-sub/<?=$page->getID();?>" class="btn-new-page">New Page</a>
					</div>
					
					<?if(CMS::HasChildren($page->getID())){?>
					<ul class="sub-navigation-main">
					<?foreach(CMS::getChildren($page->getID()) as $child){?>
						<li id="<?=$child->getID();?>">
							<a href="/admin/pages/edit/<?=$page->getID();?>/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
							<em>Last Updated: <?=$child->getDateUpdated();?></em>
							<?php if($child->isComplete()) { ?>
								<span class="complete">Completed</span>
							<? } else { ?>
								<span class="not-complete">Incomplete</span>
							<? } ?>
							<?php if($child->getDisplay()) { ?>
								<span class="publish">Published</span>
							<? } else { ?>
								<span class="not-published">Published</span>
							<? } ?>
							<div class="on-hover">
								<a href="/admin/pages/edit/<?=$page->getID();?>/<?=$child->getSlug();?>" class="edit">Edit</a>
								<span class="delete" onclick="deleteMenu('index', '<?=$child->getID();?>');">Delete</span>
								<a href="http://www.applewood.nav/main/<?=$child->getSlug();?>" class="iframe view-btn">View</a>
							</div>
						<?}?>
					</ul>
					<?}?>
					
				</div>								
			</li>
		<?}?>
	</ul>
</nav>