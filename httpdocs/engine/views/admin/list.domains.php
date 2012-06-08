<script>

	$(function() {

		$(".launcher").colorbox({inline:"true"})
		
	});


	function rm(path) {
		if(confirm("Are you sure you wish to delete this domain from the system?")) window.location = path;
	}
</script>
<div class="edit-slideshow-title">
	<div><span class="title">Domain Editor</span></div>
	<a class="edit-settings-btn launcher" href="#new-domain">New Domain</a>
</div>
<?=Session::flash("sysmsg");?>
<nav>
	<ul id="menu-navigation">
		<?foreach($domains as $domain){?>
		<li>
			<div class="inner-list">
				<div class="main-link">
					<h3><?=$domain->getName();?>.<?=$domain->getDomain();?></h3>
				</div>
				<ul class="sub-navigation-main">
					<li>
						<em>Date Added: <?=$domain->getDateAdded(DateFormat::SHORT_TIME);?></em>
						<?php if($domain->isActive()) { ?>
							<span class="publish">Active</span>
						<? } else { ?>
							<span class="not-published">Inactive</span>
						<? } ?>
						<div class="on-hover">
							<span class="delete" onclick="rm('/admin/domains/delete/<?=$domain->getID();?>');">Delete</span>
							<a href="http://<?=$domain->getName().'.'.$domain->getDomain();?>" class="iframe view-btn">View</a>
						</div>
				</ul>
			</div>								
		</li>
		<?}?>
		
	</ul>
</nav>
<div style="display: none;">
	
	<div class="editWindow" id="new-domain">
		<h2>New Domain</h2>
		<form action="/admin/domains/save/" method="post">
			<div class="row"><input type="checkbox" name="activated" value="1" /> Activated</div>
			<div class="row"><span>Domain:</span> <input type="text" name="domain" /></div>
			<div class="row">
				<input type="submit" value="Update" />
			</div>
		</form>
	</div>
	
</div>