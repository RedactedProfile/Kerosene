<!DOCTYPE html>

<!--[if lt IE 7 ]> <html dir="ltr" lang="en-US" class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html dir="ltr" lang="en-US" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html dir="ltr" lang="en-US" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html dir="ltr" lang="en-US" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
    <meta charset="UTF-8" />
    <title><?=$cms->getMetaTitle();?></title>
    <meta name="keywords" content="<?=$cms->getMetaKeys();?>" />
    <meta name="description" content="<?=$cms->getMetaDescription();?>" />
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="touch-icon-iphone.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="touch-icon-iphone4.png" />
    <!-- main css -->
	<?inc("css", "style");?>
    <!--Make HTML 5 work in older browsers-->
	<?inc("js", "libs/modernizr-2.0.6.min");?>
</head>
<body>
<div id="page-wrapper">
    <div id="header-wrapper">
        <header id="header-main" class="page-width">
            <hgroup>
                <h1 id="site-logo"><a href="" title=""><?=Settings::GetSetting("SITE_TITLE");?></a></h1>
            </hgroup>
            <nav>
                
                	<?=CMS::GetNavigation($pages, CMS::RENDER_HTML);?>
                	<!--
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/blog">Blog</a></li>
                    <li><a href="/contact">Contact</a></li>
                     -->
                
            </nav>
            <!-- /#navigation-main -->          
            <nav class="social-media">
                <ul class=" clearfix">
                    <li><a href="#" target="_blank"><img src="images/icons/facebook.png" width="24" height="24" alt="Facebook"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/linkedin.png" width="24" height="24" alt="Linkedin"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/google.png" width="24" height="24" alt="Google +"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/youtube.png" width="24" height="24" alt="Youtube"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/pinterest.png" width="24" height="24" alt="pinterest"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/flickr.png" width="24" height="24" alt="Flicker"></a></li>
                    <li><a href="#" target="_blank"><img src="images/icons/rss.png" width="24" height="24" alt="RSS Feed"></a></li>
                </ul>
            </nav>
            <!-- /.social-media -->       
        </header>       
        <!-- /#header -->
    </div>
    <!-- /#headerwrap -->
    <div id="body" class="clearfix">   