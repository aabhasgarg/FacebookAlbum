<?php
ob_start();
session_start();
require_once("fbAppData.php");
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="stylesheet" href="css/forkit.css">

    </head>
    <body>
<!--        Project Title Nav Bar-->
        <div class="navbar" id="navbar">
            <div class="navbar-inner">
                <div class="container" style="width: auto;">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Facebook Album Slideshow & Download</a>
                    <div class="nav-collapse">

                    </div><!-- /.nav-collapse -->
                </div>
            </div><!-- /navbar-inner -->
        </div>
        <div class="container">
            <!--            SuprizedMe full screen slider -->
            <div id="slider" style="display:none">
                <div id="backalbum">
                    <button id="backtoalbum" class="btn btn-inverse">Back to Albums</button>
                    <button id="btnDownload" class="btn btn-success">Download Album</button>
                </div>
                <!--Thumbnail Navigation-->
                <div id="prevthumb"></div> 
                <div id="nextthumb"></div>

                <!--Arrow Navigation-->
                <a id="prevslide" class="load-item"></a>
                <a id="nextslide" class="load-item"></a>

                <div id="thumb-tray" class="load-item">
                    <div id="thumb-back"></div>
                    <div id="thumb-forward"></div>
                </div>

                <!--Time Bar-->
                <div id="progress-back" class="load-item">
                    <div id="progress-bar"></div>
                </div>

                <!--Control Bar-->
                <div id="controls-wrapper" class="load-item">
                    <div id="controls">

                        <a id="play-button"><img id="pauseplay" src="img/pause.png"/></a>

                        <!--Slide counter-->
                        <div id="slidecounter">
                            <span class="slidenumber"></span> / <span class="totalslides"></span>
                        </div>

                        <!--Slide captions displayed here-->
                        <div id="slidecaption"></div>

                        <!--Thumb Tray button-->
                        <a id="tray-button"><img id="tray-arrow" src="img/button-tray-up.png"/></a>

                        <!--Navigation-->
                        <ul id="slide-list"></ul>

                    </div>
                </div>		
            </div>
            <div id="connect">
<!--Album View -->
                <div id="container" style="display:none">
                    <h1 id="title"></h1>
                    <hr/>
                    <img id="galleryLoading" src="/img/ajax-loader.gif" />
                    <div id="albums" class="row"></div>   
                </div>

<!--Facebook Connect -->
                
                <Div>
                    <div class="span5">&nbsp;</div>
                    <div class="span2">

                        <button class="btn btn-primary large" id='fblogin'> Connect Facebook </button>
                    </div>
                    <div class="span5">&nbsp;</div>
                </Div>

            </div>
        </div>

        <!--Model window for Download -->
        <a href="#myModal" role="button" id="openmodel" class="btn" data-toggle="modal" style="display:none"> </a>
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Please wait while we prepare your files for download</h3>
            </div>
            <div class="modal-body">
                <!--                 Progress bar    -->
                <div class="progress progress-striped active" id="downloadprogress">
                    <div class="bar" style="width: 100%;"></div>
                </div>
            </div>
            <div class="modal-footer" id="downloadlink" style="display:none">
                <button class="btn" data-dismiss="modal" aria-hidden="true" id='modelclose'>Close</button>
                <!--Download Button -->
                <a href="" id="hrefDownload" class="btn btn-primary" onclick="$('#modelclose').click();">Click Here to Download</a>
                <div>
                </div>
            </div>
        </div>
<!--        Load External js Lib-->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>

        <script src="js/bootstrap.min.js"></script>
        <div id="fb-root"></div>
        <script> var appId='<?php echo $fbAppId; ?>' </script>
        <link rel="stylesheet" href="css/supersized.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/supersized.shutter.css" type="text/css" media="screen" />
        <script type="text/javascript" src="js/jquery.easing.min.js"></script>
        <script type="text/javascript" src="js/supersized.3.2.7.min.js"></script>
        <script type="text/javascript" src="theme/supersized.shutter.min.js"></script>
        <script src="js/scrips.js"></script>
<!-- Fork it Image-->
        <a class="forkit" data-text="Fork me" data-text-detached="Drag down ;-) >" href="https://github.com/hakimel/forkit.js"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/camo.github.com/e6bef7a091f5f3138b8cd40bc3e114258dd68ddf/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub"></a>

        <script src="js/forkit.js"></script>	
    </body>
</html> 
