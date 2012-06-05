<nav>
	<ul id="menu-navigation">
		<li <?=(($root_page == "index")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/index">Home</a></h3> <a href="/admin/pages/edit/index" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/index" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(1)){
					foreach(CMS::getChildren(1) as $child){?>
					<li>
						<a href="/admin/pages/edit/index/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<div class="on-hover">
							<a href="/admin/pages/edit/index/<?=$child->getSlug();?>" class="edit">Edit</a>
							<span class="delete" onclick="deleteMenu('index', '<?=$child->getID();?>');">Delete</span>
						</div>
					<?}
				}?>
				</ul>
			</div>								
		</li>
		<li <?=(($root_page == "location")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/location">Location &amp; Area</a></h3> <a href="/admin/pages/edit/location" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/location" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(2)){
						foreach(CMS::getChildren(2) as $child){?>
					<li>
						<a href="/admin/pages/edit/location/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<div class="on-hover">
							<a href="/admin/pages/edit/location/<?=$child->getSlug();?>" class="edit">Edit</a>
							<span class="delete" onclick="deleteMenu('location', '<?=$child->getID();?>');">Delete</span>
						</div>
					</li>
						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "getting-here")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/getting-here">Getting Here</a></h3> <a href="/admin/pages/edit/getting-here" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/getting-here" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(3)){
						foreach(CMS::getChildren(3) as $child){?>
					<li>
						<a href="/admin/pages/edit/getting-here/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/getting-here/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('getting-here', '<?=$child->getID();?>');">Delete</span>
					</li>
						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "gallery")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/gallery">Galleries</a></h3> <a href="/admin/pages/edit/gallery" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/gallery" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(4)){
						foreach(CMS::getChildren(4) as $child){?>
					<li>
						<a href="/admin/pages/edit/gallery/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/gallery/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('gallery', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "financing")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/financing">Financing</a></h3> <a href="/admin/pages/edit/financing" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/financing" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(5)){
						foreach(CMS::getChildren(5) as $child){?>
					<li>
						<a href="/admin/pages/edit/financing/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/financing/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('financing', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "site-plan")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/site-plan">Site Plan</a></h3> <a href="/admin/pages/edit/site-plan" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/site-plan" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(6)){
						foreach(CMS::getChildren(6) as $child){?>
					<li>
						<a href="/admin/pages/edit/site-plan/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/site-plan/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('site-plan', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "faq")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/faq">FAQ</a></h3> <a href="/admin/pages/edit/faq" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/faq" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(7)){
						foreach(CMS::getChildren(7) as $child){?>
					<li>
						<a href="/admin/pages/edit/faq/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/faq/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('faq', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "news")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/news">News</a></h3> <a href="/admin/pages/edit/news" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/news" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(8)){
						foreach(CMS::getChildren(8) as $child){?>
					<li>
						<a href="/admin/pages/edit/news/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/news/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('news', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "links")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/links">Links</a></h3> <a href="/admin/pages/edit/links" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/links" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(9)){
						foreach(CMS::getChildren(9) as $child){?>
					<li>
						<a href="/admin/pages/edit/links/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/links/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('links', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
		<li <?=(($root_page == "contact")?'class="current_page_item"':'');?>>
			<div class="inner-list">
				<div class="main-link">
					<h3><a href="/admin/pages/edit/contact">Contact</a></h3> <a href="/admin/pages/edit/contact" class="btn-edit">Edit</a>
					<a href="/admin/pages/new/contact" class="btn-new-page">New Page</a>
				</div>
				<ul class="sub-navigation-main">
				<?if(CMS::HasChildren(9)){
						foreach(CMS::getChildren(9) as $child){?>
					<li>
						<a href="/admin/pages/edit/contact/<?=$child->getSlug();?>" class="sub-page"><?=$child->getTitle();?></a>
						<em>Last Updated: <?=$child->getDateUpdated();?></em>
						<?php if($child->getDisplay()) { ?>
							<span class="publish">Published</span>
						<? } else { ?>
							<span class="not-published">lulz Published</span>
						<? } ?>
						<a href="/admin/pages/edit/contact/<?=$child->getSlug();?>" class="edit">Edit</a>
						<span class="delete" onclick="deleteMenu('contact', '<?=$child->getID();?>');">Delete</span>
					</li>						<?}
					}?>
				</ul>
			</div>
		</li>
	</ul>
</nav>