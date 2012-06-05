</div>
    <!-- /body -->
</div>
<!-- /#page-wrapper -->
<div id="footer-wrapper">
    <footer id="footer-main" class="page-width clearfix">
        <div class="footer-text clearfix">
            <div class="one">
                <a href="http://wordpress.nav">&copy; 2012 Wordpress Testing. All Rights Reserved.</a>
            </div>
            <div class="two">
                <a href="http://www.navigatormm.com" target="_blank" title="Web Design and Hosting By Navigator Multimedia Inc">Kelowna Web Design and Hosting by Navigator Multimedia Inc.</a> | <a href="" target="_blank" title="KeroseneVersion 1.0">Kerosene Powered </a>
            </div>
        </div>
    </footer>
    <!-- /#footer --> 
</div>
<!-- /#footerwraper -->
<!-- Load in jQuery from Google-->
<?
inc("js", "libs/jquery");
inc("js", "libs/jquery.orbit-1.2.3.min");
inc("js", "scripts");
?>

<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type='text/javascript' src='js/libs/jquery.orbit-1.2.3.min.js'></script>
<script type='text/javascript' src='js/scripts.js'></script>
-->
<!--Ordit (Slide Show) Script-->
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#slides').orbit({
            animation: 'horizontal-slide',
            animationSpeed: 800,
            advanceSpeed: 4000,
            directionalNav: true,
            captionAnimation: 'none',
            captionAnimationSpeed: 1000,
            bullets: true,
            afterSlideChange: function(){}
        });
    });
</script>   
</body>
</html>