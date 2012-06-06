<!DOCTYPE html>
<html>
	<head>
		<title><?=BaseAdmin::parseTitle($title);?></title>
		<?inc("jq");?>
		<?inc("js", "jscolor/jscolor");?>
		<?inc("js", "colorbox/jquery.colorbox-min");?>
		<?inc("css", "colorbox/colorbox", "/scripts/");?>
		<?inc("js", "ckeditor", "/ckeditor/");?>
		<?inc("js", "ckfinder", "/ckfinder/");?>
		<?inc("css", "admin.styles");?>
		<?inc("js", "jquery-ui-1.8.20.custom.min");?>
		<?inc("css", "ui-lightness/jquery-ui-1.8.20.custom");?>
		<?inc("js", "jquery.color");?>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				$(".iframe").colorbox({iframe:true, width:"86%", height:"97%"});

				setTimeout(function() {
					$(".success,.error").slideUp();
				}, 3000);
				
			});

			
		</script>
		<link rel="shortcut icon" href="/images/system/favicon.ico" type="image/vnd.microsoft.icon" />
		<link rel="icon" href="/images/system/favicon.ico" type="image/vnd.microsoft.icon" /> 
	</head>
	<body>
	<div id="page-wrapper">
		<div id="header">
			<a href="http://www.navigatormm.com" title="Navigator Multimedia Inc" target="_blank" class="logo">Navigator Multimedia Inc</a>
			<div class="tool-bar">
				<a href="/admin/" class="site-title">Applewood Site Management</a>
				<ul>
					<li class="settings"><a href="/admin/settings/site" title="Site Settings">Settings</a></li>
					<li class="stats"><a href="/admin/" title="Site Stats">Stats</a></li>
				</ul>
			</div>
			<?
			$user_image = "";
			switch(Account::GetData("level")){
				case Account::SUPER_ADMINISTRATOR:
					$user_image = "user-1.png";
					break;
				case Account::ADMINISTRATOR:
					$user_image = "user-2.png";
					break;
				default:
				case Account::USER:
					$user_image = "user-3.png"; // wtf are YOU doing here?!
					break;
			}?>
			
			<div id="banner" style="background: url(/images/admin/<?=$user_image;?>) #FF0000 no-repeat 0 0 !important;">
				<div id="account">
					Hello, <?=Account::GetData("display");?> | <a href="/admin/logout">Logout</a><br />
					
					
				</div>
			</div>			
		</div>
		<div id="nav">
			<div id="preview">
				<a class="iframe" href="http://www.applewood.nav"></a>
				<img src="/images/admin/screenshot.png" alt="" />
			</div>
			<ul>
				<li class="dashboard <?=(($uri->method == "")?"active":null);?>">
					<a href="/admin"><span></span> Statistics</a>
				</li>
				<?if(Account::GetData("level") == Account::SUPER_ADMINISTRATOR){?>
				<li class="pages <?=(($uri->method == "domains")?"active":null);?>">
					<a href="/admin/domains"><span></span> Manage Domains</a>
				</li>
				<li class="pages" <?=(($uri->method == "modules")?"active":null);?>">
					<a href="/admin/modules"><span></span> Modules</a>
				</li>
				<?}?>
				<li class="pages <?=(($uri->method == "pages")?"active":null);?>">
					<a href="/admin/pages"><span></span> Pages</a>
				</li>
				<li class="slideshow <?=(($uri->method == "slideshow")?"active":null);?>">
					<a href="/admin/slideshow"><span></span> Slideshow</a>
				</li>
				<li class="gallery <?=(($uri->method == "gallery")?"active":null);?>">
					<a href="/admin/gallery"><span></span> Galleries</a>
				</li>
				<li class="blog <?=(($uri->method == "blog")?"active":null);?>">
					<a href="/admin/blog"><span></span> Blog</a>
				</li>
				<li class="site <?=(($uri->method == "settings" && $uri->arguments[0] == "site")?"active":null);?>">
					<a href="/admin/settings/site"><span></span> Site Settings</a>
				</li>
				
			</ul>
		</div>
			<div id="body">
				<div class="inner-tube">
			
		