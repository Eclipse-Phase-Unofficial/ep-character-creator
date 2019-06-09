<?php
declare(strict_types=1);

    error_reporting(0);
    function createDataURI(string $file, string $mimeType = null){
        $fileContents = file_get_contents($file);
        if (!$fileContents) {
            throw new \InvalidArgumentException("File does not exist: $file");
        }
        if(!$mimeType) {
            $mimeType = mime_content_type($file);
        }
        return "data:".$mimeType.";base64,".base64_encode($fileContents);
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

        {{--        <link rel="stylesheet" href="{{mix('css/vendor.css')}}">--}}
        {{--        <link rel="stylesheet" href="{{mix('css/app.css')}}">--}}
        <style>
            <?php
            include public_path('css/vendor.css');
            include public_path('css/app.css');
            ?>
        </style>
    </head>
    <body>

        <!-- POPUP  -- DYNAMIC CONTENT -->
        <div id="loading_popup" class="loading-indicator"></div>
        <!-- MESSAGES FOR THE USER - DYNAMIC CONTENT-->
        <section id="messages"></section>
        <div id="container">
            <router-view></router-view>
        </div>

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
