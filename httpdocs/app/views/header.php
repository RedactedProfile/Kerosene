<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html> <!--<![endif]-->
<head>
<meta charset="UTF-8">
<title>Title</title>
<meta name="title" content="meta>" />
<meta name="keywords" content="keywords" />
<meta name="description" content="description" />
<meta name="revisit-after" content=" 7 days "/>
<meta name="author" content="Navigator Multimedia Inc"/>
<meta name="canonical" content="<?="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="shortcut icon" href="" />
<link rel="apple-touch-icon" href="" />
<link rel="apple-touch-icon" sizes="72x72" href="" />
<link rel="apple-touch-icon" sizes="114x114" href="" />

<!-- disable iPhone inital scale -->
<meta name="viewport" content="width=device-width; initial-scale=1.0">

<!-- css -->
<?inc("css", "styles");?>
<?inc("css", "orbit-1.2.3");?>

<!--jquery-->
<?inc("jq");?>

<!--Make HTML 5 work in older browsers-->
<?inc("js", "libs/modernizr-2.0.6.min");?>

<?inc("js", "colorbox/jquery.colorbox-min");?>
<?inc("css", "colorbox/colorbox", "/scripts/");?>
<?inc("js", "libs/swfobject");?>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18081365-25']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<!-- facebook / pinterest javascript  ===================-->
<div id="fb-root"></div>
<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<div id="page-wrapper">
    <div id="header-bg">
        <div id="header-wrapper">
            <div id="navigation-bg">
                <header id="header-main" class="page-width">
                    <nav>
                        <ul id="navigation-main">
                            <li>
                            	<a href="/">Home</a>
							</li>
                        </ul>
                    </nav>
                    <!-- /#navigation-main -->  
                </header>
            </div>
            <!-- /#header -->    
        </div>
    </div>
    <!-- /#headerwrap -->
    
	<div id="body" class="clearfix">