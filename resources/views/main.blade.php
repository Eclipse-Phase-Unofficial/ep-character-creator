<?php
declare(strict_types=1);

use App\Creator\EPListProvider;

    error_reporting(0);
    $provider = new EPListProvider();
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
                <ul class="mainlist">
                    <?php
                    $main_menu = array(
                        array("backgrounds","&#x2f;","Background"),
                        array("faction","&#x30;","Faction"),
                        array("motivations","&#x28;","Motivations"),
                        array("aptitudes","&#x21;","Aptitudes"),
                        array("rep","&#x24;","Rep","rep"),
                        array("posTrait","&#x35;","ego pos. traits"),
//                         array("neuTrait","&#x34;","ego neutral traits"),
                        array("negTrait","&#x36;","ego neg. traits"),
                        array("activeSkills","&#x2d;","Active Skills"),
                        array("knowledgeSkills","&#x2e;","Knowledge Skills"),
                        array("morph","&#x32;","Morph","morph"),
                        array("soft","&#x33;","Ai's & Soft Gear"),
                        array("psy","&#x34;","Psi Sleights"),
                        array("credit","&#x38;","Credit"),
                        array("moxie","&#x37;","Stat"),
                        array("lastdetails","&#x27;","Last Details")
                        );
                    function createElement($item_class,$icon,$display_name, EPListProvider $provider){
                        echo '<li>';
                        echo '  <a class="'.$item_class.'" href="#">';
                        echo '      <span class="icone" data-icon="'.$icon.'"></span>';
                        echo        $display_name;
                        echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$provider->getInfosById($item_class).'"></span>';
                        echo '  </a>';
                        echo '</li>';
                    };
                    foreach ($main_menu as $i){
                        createElement($i[0],$i[1],$i[2], $provider);
                    }
                    ?>
    			</ul>
    			</nav>
        	</section>

            <!-- AJAX Panels-->
            <section id="secondary" class="panel"></section>
            <section id="tertiary" class="panel"></section>
            <section id="quaternary" class="panel"></section>

        	<!-- REMAINAING POINTS - DYNAMIC CONTENT-->
        	<section class="points">
                <div id='RZ'>RZ <span id="rez_remain" class="rest"></span>
                    <span class="btnhelp slowTransition" data-icon="&#x2a;" title="<?php echo $provider->getInfosById('points'); ?>"></span></span>
                </div>
                <div id='CP'>CP <span id="creation_remain" class="rest"></span>
                    <span class="btnhelp slowTransition" data-icon="&#x2a;" title="<?php echo $provider->getInfosById('points'); ?>"></span></span>
                </div>
                <div id='AP'>AP <span id="aptitude_remain" class="rest"></span></div>
                <div id='ASR'>ASR <span id="asr_remain" class="rest"></span></div>
                <div id='KSR'>KSR <span id="ksr_remain" class="rest"></span></div>
                <div id='RP'>RP <span id="reputation_remain" class="rest"></span></div>
                <div id='CR'>CR <span id="credit_remain" class="rest"></span></div>
        	</section>
        	<!-- MESSAGES FOR THE USER - DYNAMIC CONTENT-->
        	<section id="messages"></section>
            <div id="menu">
                <button class="popupButton" id="saveButton">
                    <!-- <span class="button_icone" data-icon="&#x2d;"></span> -->
                    Save
                </button>
                <button class="popupButton" id="loadButton">
                    <!-- <span class="button_icone" data-icon="&#x30;"></span> -->
                    Load
                </button>
                <button class="popupButton" id="validateButton">
                    <!-- <span class="button_icone" data-icon="&#x2b;"></span> -->
                    Check
                </button>
                <div class='dropdown'>
                    <button class="popupButton" id="exportButton">
                        <!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
                        Export
                        &#x25BC;
                    </button>
                    <div class='dropdown-content'>
                        <ul>
                            <li><a id="exportPdfButton" href="#">PDF</a></li>
                            <li><a id="exportTxtButton" href="#">TXT</a></li>
                        </ul>
                    </div>
                </div>
                <button class="popupButton" id="settingsButton">
                    <!-- <span class="button_icone" data-icon="&#x21;"></span> -->
                    Reset
                </button>
                <button class="popupButton" id="aboutButton">
                    <!-- <span class="button_icone" data-icon="&#x21;"></span> -->
                    About
                </button>
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
