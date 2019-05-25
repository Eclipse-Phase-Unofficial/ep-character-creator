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
                <ul class="mainlist">
                    <?php
                    $main_menu = [
                        [
                            'id'   => "backgrounds",
                            'icon' => "icon-globe",
                            'name' => "Background",
                            'description' => "
                                <p>Was your character born on Earth before the Fall? Were they raised on a habitat commune? Or did they start existence as a disembodied AI?</p>
                                <p>You must choose one of the backgrounds for your character from the list. Choose wisely, as each background may provide your character with certain skills, traits, limitations, or other characteristics to start with. </p>
                                <p>Keep in mind that your background is where you came from, not who you are now. It is the past, whereas your faction represents whom your character is currently aligned with. Your future, of course, is yours to make.</p>
                            "
                        ],
                        [
                            'id'   => "faction",
                            'icon' => "icon-users",
                            'name' => "Faction",
                            'description' => "
                                <p>Faction most likely represents the grouping that controls your character current home habitat/station, and to which your character holds allegiance, but this need not be the case. </p>
                                <p>You may be a dissident member of your faction, living among them but opposing some (or all) of their core memes and perhaps agitating for change. Whatever the case, your faction defines how your character represents themself in the struggle between ideologies post-Fall.</p>
                                <p>You must choose one of the factions in the list. Like your character?s background, it will provide your character with certain skills, traits, limitations, or other characteristics.</p>
                            "
                        ],
                        [
                            'id'   => "motivations",
                            'icon' => "icon-heart",
                            'name' => "Motivations",
                            'description' => "
                                <p>Motivation are personal memes that dominate the characters interests and pursuits. </p>
                                <p>These memes may be as abstract as ideologies the character adheres to or supports for example, social anarchism, Islamic jihad, or bioconservatism?or they may be as concrete as specific outcomes the character desires, such as revealing a certain hypercorps corruption, obtaining massive personal wealth, or winning victories for uplifted rights. </p>
                                <p>A motivation may also be framed in opposition to something; for example, anti-capitalism or anti-pod-citizenship, or staying out of jail. In essence, these are ideas that moti- vate the character to do the things they do.</p>
                            "
                        ],
                        [
                            'id'   => "aptitudes",
                            'icon' => "icon-cog",
                            'name' => "Aptitudes",
                            'description' => "
                                <p>Aptitudes are the core skills that every character has by default. They are the foundation on which learned skills are built.</p>
                                <p> Aptitudes are purchased during character creation and rate between 1 and 30, with 10 being average for a baseline unmodified human.</p>
                                <p> They represent the ingrained characteristics and talents that your character has developed from birth and stick with you even when you change morphs?though some morphs may modify your aptitude ratings.</p>
                            "
                        ],
                        [
                            'id'   => "rep",
                            'icon' => "icon-star-2",
                            'name' => "Rep",
                            'description' => "
                                <p>Capitalism is no longer the only economy in town. The development of nanofabricators allowed for the existence of post-scarcity economies, a fact eagerly taken advantage of by anarchist factions and others. </p>
                                <p>When anyone can make anything, concepts like property and wealth become irrelevant. The advent of functional gift and communist economies, among other alternative economic models, means that in such systems you can acquire any goods or services you need via free exchange, reciprocity, or barter? presuming you are a contributing member of such a system and respected by your peers. </p>
                            "
                        ],
                        [
                            //TODO:  This is duplicated in negative traits, and on the morph page
                            'id'   => "posTrait",
                            'icon' => "icon-circle",
                            'name' => "ego pos. traits",
                            'description' => "
                                <p>Traits include a range of inherent qualities and features that help define your character.</p>
                                <p> Some traits are positive, in that they give your character a bonus to certain stats, skills, or tests, or otherwise give them an edge in certain situations.</p>
                                <p> Others are negative, in that they impair your abilities or occasionally create a glitch in your plans.</p>
                                <p> Some traits apply to a characters ego, staying with them from body to body, while others only apply to a character?s morph.</p>
                            "
                        ],
//                        [
//                            //TODO: This is duplicated on the morph page
//                            'id'   => "neuTrait",
//                            'icon' => "icon-contrast",
//                            'name' => "ego neutral traits",
//                            'description' => "
//                                <p>Some traits are a mixed bag, providing neither a positive benefit nor negative penalty-or applying both. Characters may take these at a Cost/Bonus of 0 CP. Others are traits that define an inherent characteristic of the morph design, these only apply to morphs of a certain type as noted.</p>
//                            "
//                        ],
                        [
                            //TODO:  This is duplicated in positive traits, and on the morph page
                            'id'   => "negTrait",
                            'icon' => "icon-radio-unchecked",
                            'name' => "ego neg. traits",
                            'description' => "
                                <p>Traits include a range of inherent qualities and features that help define your character.</p>
                                <p> Some traits are positive, in that they give your character a bonus to certain stats, skills, or tests, or otherwise give them an edge in certain situations.</p>
                                <p> Others are negative, in that they impair your abilities or occasionally create a glitch in your plans.</p>
                                <p> Some traits apply to a characters ego, staying with them from body to body, while others only apply to a character?s morph.</p>
                            "
                        ],
                        [
                            'id'   => "activeSkills",
                            'icon' => "icon-tools",
                            'name' => "Active Skills",
                            'description' => "<p>Active skills typically require physical actions and are used in action scenes within game play.</p>"
                        ],
                        [
                            'id'   => "knowledgeSkills",
                            'icon' => "icon-brain",
                            'name' => "Knowledge Skills",
                            'description' => "
                                <p>Knowledge skills are more knowledge- based and intellectual, representing ideas and facts.</p>
                                <p> Knowledge skills may play a less dramatic role in certain action-oriented game play moments, but they flesh out the characters background and interests and are integral to roleplaying interactions. </p>
                            "
                        ],
                        [
                            'id'   => "morph",
                            'icon' => "icon-user-2",
                            'name' => "Morph",
                            'description' => "<p>The term morph is used to describe any type of form your mind inhabits, whether a vat- grown clone sleeve, a synthetic robotic shell, a part- bio/part-synthetic ?pod,? or even the purely electronic software state of an infomorph.</p>"
                        ],
                        [
                            'id'   => "soft",
                            'icon' => "icon-cart",
                            'name' => "Ai's & Soft Gear",
                            'description' => "<p>A wide range of software is available for mesh users, from firewalls and AIs to hacking and encryption tools or tacnets and skillsofts. Like other gear, software may enable a character to perform a task they could not otherwise. </p>"
                        ],
                        [
                            'id'   => "psy",
                            'icon' => "icon-radio-unchecked",
                            'name' => "Psi Sleights",
                            'description' => "<p>In Eclipse Phase, psi is considered a special cognitive condition resulting from infection by the mutant and hopefully otherwise benign Watts-MacLeod strain of the exsurgent virus. This plague modifies the victim mind, conferring special abilities. These abilities are inherent to the brain architecture and are copied when the mind is uploaded, allowing the character to retain their psi abilities when changing from morph to morph.</p>"
                        ],
                        [
                            'id'   => "credit",
                            'icon' => "icon-credit",
                            'name' => "Credit",
                            'description' => "
                                <p>This currency is backed by all of the large capitalist-oriented factions and is used to trade for goods and services as well for other financial transactions.</p>
                                <p> Credit is mainly transferred electronically, though certified credit chips are also common (and favored for their anonymity). Hardcopy bills are even used in some habitats.</p>
                            "
                        ],
                        [
                            'id'   => "moxie",
                            'icon' => "icon-moxie",
                            'name' => "Stat",
                            'description' => "
                                <p>Moxie represents your characters inherent talent at facing down challenges and overcoming obstacles with spirited fervor. More than just luck, Moxie is your character?s ability to run the edge and do what it takes, no matter the odds.</p>
                                <p> Some people consider it the evolutionary trait that spurred humankind to pick up tools, expand our brains, and face the future head on, leaving other mammals in the dust. When the sky is falling, death is imminent, and no one can help you, Moxie is what saves the day.</p>
                            "
                        ],
                        [
                            'id'   => "lastdetails",
                            'icon' => "icon-equalizer",
                            'name' => "Last Details",
                            'description' => "<p>The final step in character creation is filling in the details and figuring out what your character is like and what they are all about. </p>"
                        ]
                    ];
                    function createElement($item_class, $icon, $display_name, $description){
                        echo '<li>';
                        echo '  <a class="'.$item_class.'" href="#">';
                        echo '      <span class="icone '.$icon.'"></span>';
                        echo        $display_name;
                        echo '      <span class="btnhelp slowTransition icon-question" title="'.$description.'"></span>';
                        echo '  </a>';
                        echo '</li>';
                    };
                    foreach ($main_menu as $i){
                        createElement($i['id'],$i['icon'],$i['name'], $i['description']);
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
