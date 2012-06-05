		<div id="slides-wrapper" class="page-width">           
            <div id="slides">
                <img src="" width="960" height="400" alt=""/>
                <img src="" width="960" height="400" alt=""/>
                <img src="" width="960" height="400" alt=""/>
                <img src="" width="960" height="400" alt=""/>
                <img src="" width="960" height="400" alt=""/>
                <img src="" width="960" height="400" alt=""/>
            </div>            
            <!-- Captions for Orbit -->
            <span class="orbit-caption" id="htmlCaption01">This is a test caption.</span>
        </div>
        <!-- /#slide-wrapper-->
        <div id="layout" class="page-width sidebar-none clearfix">          
            <div id="content">
                <h1 class="page-title"><?=$cms->getTitle();?></h1>
                <div class="page-content">
                
                <?=$cms->getContent();?>
                </div>
                <!-- /.page-content -->
            </div>
            <!-- /#content -->
        </div>
        <!-- /#layout -->