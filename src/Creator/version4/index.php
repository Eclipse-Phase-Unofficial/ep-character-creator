<?php
    require_once '../../php/EPListProvider.php';
    error_reporting(0);
    $provider = new EPListProvider('../../php/config.ini');
    function createDataURI($image,$image_type){
        return "data:image/".$image_type.";base64,".base64_encode(file_get_contents($image));
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

        <style><?php include "scripts/jquery/jquery-ui.min.css"; ?></style>
        <style><?php include "scripts/vegas/vegas.min.css"; ?></style>
        <style><?php include "css/normalize/normalize.min.css"; ?></style>
        <style><?php include "css/lato.css"; ?></style>
        <style><?php include "css/icomoon.css"; ?></style>
        <style><?php include "css/popup.css"; ?></style>
        <style><?php include "css/main7.css"; ?></style>

        <script><?php include "scripts/jquery/jquery.min.js"; ?></script>
        <script><?php include "scripts/jquery/jquery-ui.min.js"; ?></script>
        <script><?php include "scripts/vegas/vegas.min.js"; ?></script>
        <script><?php include "scripts/ajax_helper.js"; ?></script>
    </head>
    <body>
        <div id="container">

        	<!-- MAIN MENU - STATIC CONTENT-->       	
        	<section id="primary" class="panel">
        		<nav id="main-nav">
    			<ul>
                    <?php
                    $main_menu = array(
                        array("background","&#x2f;","Background","backgrounds"),
                        array("faction","&#x30;","Faction","faction"),
                        array("motivations","&#x28;","Motivations","motivations"),
                        array("aptitudes","&#x21;","Aptitudes","aptitudes"),
                        array("rep","&#x24;","Rep","rep"),
                        array("positive-traits","&#x35;","ego pos. traits","posTrait"),
//                         array("neutral-traits","&#x34;","ego neutral traits","neuTrait"),
                        array("negative-traits","&#x36;","ego neg. traits","negTrait"),
                        array("active-skills","&#x2d;","Active Skills","activeSkills"),
                        array("knowledge-skills","&#x2e;","Knowledge Skills","knowledgeSkills"),
                        array("morph","&#x32;","Morph","morph"),
                        array("soft","&#x33;","Ai's & Soft Gear","soft"),
                        array("psy","&#x34;","Psi Sleights","psy"),
                        array("credit","&#x38;","Credit","credit"),
                        array("stat","&#x37;","Stat","moxie"),
                        array("lastdetails","&#x27;","Last Details","lastdetails")
                        );
                    function createElement($item_class,$icon,$display_name,$help_id){
                        global $provider;
                        echo '<li>';
                        echo '  <a class="'.$item_class.'" href="#">';
                        echo '      <span class="icone" data-icon="'.$icon.'"></span>';
                        echo        $display_name;
                        echo '      <span class="btnhelp slowTransition" data-icon="&#x2a;" title="'.$provider->getInfosById($help_id).'"></span>';
                        echo '  </a>';
                        echo '</li>';
                    };
                    foreach ($main_menu as $i){
                        createElement($i[0],$i[1],$i[2],$i[3]);
                    }
                    ?>
    			</ul>
    			</nav>
        	</section>
        	
        	<!-- SECONDARY PANNEL - DYNAMIC CONTENT -->
        	<section id="secondary" class="panel"></section>	
        	
        	<!-- TERTIARY PANNEL - DYNAMIC CONTENT-->
        	<section id="tertiary" class="panel"></section>	
        	
        	<!-- QUATERNARY PANNEL - DYNAMIC CONTENT-->
        	<section id="quaternary" class="panel"></section>	
        	
        	<!-- REMAINAING POINTS - DYNAMIC CONTENT-->
        	<section class="points">
            		CP <span id="creation_remain" class="rest"></span><br>  
            		AP <span id="aptitude_remain" class="rest"></span><br>
            		RP <span id="reputation_remain" class="rest"></span><br>
            		CR <span id="credit_remain" class="rest"></span><br>
                    ASR <span id="asr_remain" class="rest"></span><br>
                    KSR <span id="ksr_remain" class="rest"></span><span class="btnhelp slowTransition" data-icon="&#x2a;" title="<?php echo $provider->getInfosById('points'); ?>"></span><br>
                    RZ <span id="rez_remain" class="rest"></span><br>
        	</section>
        	<!-- MESSAGES FOR THE USER - DYNAMIC CONTENT-->
        	<section id="messages"></section>
        	<!-- SAVE BUTTON-->
			<button class="popupButton" id="saveButton">
	       			save<!-- <span class="button_icone" data-icon="&#x2d;"></span> -->
        	</button>
        	<!-- LOAD BUTTON-->
			<button class="popupButton" id="loadButton">
	       			load<!-- <span class="button_icone" data-icon="&#x30;"></span> -->
        	</button>
        	<!-- VALIDATE BUTTON-->
        	<button class="popupButton" id="validateButton">
	       		check<!-- <span class="button_icone" data-icon="&#x2b;"></span> -->
        	</button>
        	<!-- EXPORT TXT BUTTON-->
        	<button class="popupButton" id="exportTxtButton">
	       		TXT<!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
        	</button>
        	<!-- EXPORT PDF BUTTON-->
        	<button class="popupButton" id="exportButton">
	       		PDF<!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
        	</button>
        	<!-- RESET BUTTON-->
        	<button class="popupButton" id="settingsButton">
	       		reset<!-- <span class="button_icone" data-icon="&#x21;"></span> -->
        	</button>
            <!-- ABOUT BUTTON-->
            <button class="popupButton" id="aboutButton">
                about<!-- <span class="button_icone" data-icon="&#x21;"></span> -->
            </button>
        </div>

		<!-- POPUP  -- DYNAMIC CONTENT -->
        <div id="popup" data-name=""></div>
        <div class="loadingPopup" id="loading_popup"><center><img src="<?php echo createDataURI("img/ajax-loader.gif","gif"); ?>"></center></div>

        <script><?php include "scripts/ajaxManager.js"; ?></script>
        <script><?php include "scripts/popup.js"; ?></script>
        <script>
            $( function() {
                //NAVIGATION JQUERRY
                //class active au menu principal
                $("#main-nav li a").click(function(){
                    $(".active").removeClass("active");
                    $(this).toggleClass("active");
                    $("#tertiary_infos").css('visibility','hidden');
                    return false;
                });

                var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));

                //background slideshow
                //See here for more options:  http://vegas.jaysalvat.com/documentation/settings/
                var desktopSlides = [
                        { src: 'img/bg/bg1.jpg'},
                        { src: 'img/bg/bg2.jpg'},
                        { src: 'img/bg/bg3.jpg'},
                        //These are all free (at least non-commercial) use images or images in the public domain
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/3/34/SFO_at_night.jpg'},     //Andrew Choy from Santa Clara, California (Creative Commons Attribution-Share Alike 2.0 Generic)
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/6/62/Starsinthesky.jpg'},    //Credit ESA (This is me giving credit, per the license)
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/0/00/Crab_Nebula.jpg'},                  //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Ngc1999.jpg'},                      //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Pleiades_large.jpg'},               //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/c/c9/Sirius_A_and_B_artwork.jpg'},       //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/5/57/Witness_the_Birth_of_a_Star.jpg'},  //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/4/44/Ngc6397_hst_blue_straggler.jpg'},   //Credit NASA
                        { src: 'https://upload.wikimedia.org/wikipedia/commons/b/b4/The_Sun_by_the_Atmospheric_Imaging_Assembly_of_NASA%27s_Solar_Dynamics_Observatory_-_20100819.jpg'}  //Credit NASA
                    ];
                //Do not show the (data heavy) background images if on mobile
                if(isMobile){
                    desktopSlides=[{}]
                };
                $('body').vegas({
                    timer: false,
                    shuffle: true,
                    delay: 60000,
                    overlay: '<?php echo createDataURI("scripts/vegas/overlays/08.png","png"); ?>',
                    slides: desktopSlides
                });
            });
        </script>
    </body>
</html>
