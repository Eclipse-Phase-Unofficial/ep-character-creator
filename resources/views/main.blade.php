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
                    $main_menu = [
                        [
                            'id'   => "backgrounds",
                            'icon' => "icon-globe",
                            'name' => "Background"
                        ],
                        [
                            'id'   => "faction",
                            'icon' => "icon-users",
                            'name' => "Faction"
                        ],
                        [
                            'id'   => "motivations",
                            'icon' => "icon-heart",
                            'name' => "Motivations"
                        ],
                        [
                            'id'   => "aptitudes",
                            'icon' => "icon-cog",
                            'name' => "Aptitudes"
                        ],
                        [
                            'id'   => "rep",
                            'icon' => "icon-star-2",
                            'name' => "Rep"
                        ],
                        [
                            'id'   => "posTrait",
                            'icon' => "icon-circle",
                            'name' => "ego pos. traits"
                        ],
//                        [
//                            'id'   => "neuTrait",
//                            'icon' => "icon-contrast",
//                            'name' => "ego neutral traits"
//                        ],
                        [
                            'id'   => "negTrait",
                            'icon' => "icon-radio-unchecked",
                            'name' => "ego neg. traits"
                        ],
                        [
                            'id'   => "activeSkills",
                            'icon' => "icon-tools",
                            'name' => "Active Skills"
                        ],
                        [
                            'id'   => "knowledgeSkills",
                            'icon' => "icon-brain",
                            'name' => "Knowledge Skills"
                        ],
                        [
                            'id'   => "morph",
                            'icon' => "icon-user-2",
                            'name' => "Morph"
                        ],
                        [
                            'id'   => "soft",
                            'icon' => "icon-cart",
                            'name' => "Ai's & Soft Gear"
                        ],
                        [
                            'id'   => "psy",
                            'icon' => "icon-radio-unchecked",
                            'name' => "Psi Sleights"
                        ],
                        [
                            'id'   => "credit",
                            'icon' => "icon-credit",
                            'name' => "Credit"
                        ],
                        [
                            'id'   => "moxie",
                            'icon' => "icon-moxie",
                            'name' => "Stat"
                        ],
                        [
                            'id'   => "lastdetails",
                            'icon' => "icon-equalizer",
                            'name' => "Last Details"
                        ]
                    ];
                    function createElement($item_class,$icon,$display_name, EPListProvider $provider){
                        echo '<li>';
                        echo '  <a class="'.$item_class.'" href="#">';
                        echo '      <span class="icone '.$icon.'"></span>';
                        echo        $display_name;
                        echo '      <span class="btnhelp slowTransition icon-question" title="'.$provider->getInfosById($item_class).'"></span>';
                        echo '  </a>';
                        echo '</li>';
                    };
                    foreach ($main_menu as $i){
                        createElement($i['id'],$i['icon'],$i['name'], $provider);
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
            <points-tracker></points-tracker>
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
