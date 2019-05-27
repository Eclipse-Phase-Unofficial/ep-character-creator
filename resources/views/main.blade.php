<?php
declare(strict_types=1);

    error_reporting(0);
    function createDataURI($image,$image_type){
        $fileContents = file_get_contents($image);
        if (!$fileContents) {
            throw new \InvalidArgumentException("File does not exist: $image");
        }
        return "data:image/".$image_type.";base64,".base64_encode($fileContents);
    }
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Eclipse Phase Character Creator</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>

        <!-- POPUP  -- DYNAMIC CONTENT -->
        <div id="popup" data-name=""></div>
        <div id="loading_popup"><center><img src="<?php echo createDataURI(public_path("img/ajax-loader.gif"),"gif"); ?>"></center></div>

        <div id="container">

            <!-- Ego/Morph MENU - STATIC CONTENT-->
        	<section id="primary" class="panel">
        		<nav id="main-nav">
                    <panel-one></panel-one>
    			</nav>
        	</section>

            <!-- AJAX Panels-->
            <section id="secondary" class="panel"></section>
            <section id="tertiary" class="panel"></section>
            <section id="quaternary" class="panel"></section>

        	<!-- REMAINAING POINTS - DYNAMIC CONTENT-->
            <points-tracker></points-tracker>
        	<!-- MESSAGES FOR THE USER - DYNAMIC CONTENT-->
        	<section id="messages"></section>
            <div id="mainMenu">
                <button class="popupButton" id="saveButton">
                    <!-- <span class="button_icone" data-icon="&#x2d;"></span> -->
                    Save
                </button>
                <button class="popupButton" href="#load-modal" uk-toggle>
                    Load
                </button>
                <load-dialog id="load-modal"></load-dialog>
                <button class="popupButton" href="#validation-modal" uk-toggle>
                    Check
                </button>
                <validation id="validation-modal"></validation>
                <div class='dropdown'>
                    <button class="popupButton" id="exportButton">
                        <!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
                        Export
                        &#x25BC;
                    </button>
                    <div class='dropdown-content'>
                        <ul>
                            <li><a id="exportPdfButton" href="/export/pdf" target="_blank">PDF</a></li>
                            <li><a id="exportTxtButton" href="/export/txt" target="_blank">TXT</a></li>
                        </ul>
                    </div>
                </div>
                <button class="popupButton" id="settingsButton">
                    <!-- <span class="button_icone" data-icon="&#x21;"></span> -->
                    Reset
                </button>
                <button class="popupButton" href="#about-modal" uk-toggle>
                    About
                </button>
                <about id="about-modal"></about>
            </div>
        </div>

{{--        <link rel="stylesheet" href="{{mix('css/vendor.css')}}">--}}
{{--        <link rel="stylesheet" href="{{mix('css/app.css')}}">--}}
        <style>
            <?php
            include public_path('css/vendor.css');
            include public_path('css/app.css');
            ?>
        </style>

        {{--<script src="{{mix('js/manifest.js')}}" type="text/javascript"></script>--}}
        {{--<script src="{{mix('js/vendor.js')}}" type="text/javascript"></script>--}}
        {{--<script src="{{mix('js/app.js')}}" type="text/javascript"></script>--}}
        {{--<script src="{{mix('js/legacy.js')}}" type="text/javascript"></script>--}}
        <script>
            var process = {};
            process.env = {};
            process.env.MIX_GOOGLE_ANALYTICS_ID = "{{config('epcc.googleAnalyticsId')}}";
            <?php
            //Load order is important here
            include public_path('js/manifest.js');
            include public_path('js/vendor.js');
            include public_path('js/app.js');
            include public_path('js/legacy.js');
            ?>

            $( function() {
                //NAVIGATION JQUERRY
                //class active au menu principal
                $("#main-nav li a").click(function(){
                    $(".active").removeClass("active");
                    $(this).toggleClass("active");
                    $("#tertiary_infos").css('visibility','hidden');
                    return false;
                });
            });
        </script>
    </body>
</html>
