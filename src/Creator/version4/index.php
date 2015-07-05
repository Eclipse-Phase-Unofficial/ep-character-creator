<?php 
	error_reporting(0);
	session_start();
	if(isset($_FILES['uploadedfile'])){
		$handle = fopen($_FILES['uploadedfile']['tmp_name'],'r');
		$_SESSION['fileToLoad'] = fread($handle, filesize($_FILES['uploadedfile']['tmp_name']));
		fclose($handle);
		$_SESSION['creationMode'] = isset($_POST['creationMode']);
		$_SESSION['rezPoints'] = $_POST['rezPoints'];
	    $_SESSION['repPoints'] = $_POST['repPoints'];
	    $_SESSION['credPoints'] = $_POST['credPoints'];
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

        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-lightness/jquery-ui.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/1.1.0/normalize.min.css"/>
        <link rel='stylesheet' href='//fonts.googleapis.com/css?family=Lato:400,700,400italic'>
        <link rel="stylesheet" href="scripts/vegas/jquery.vegas.css">
        <link rel="stylesheet" href="css/icomoon.css" />
        <link rel="stylesheet" href="css/main7.css">
    </head>
    <body>

        <div id="container">
        
        	<!-- HELP SLIDING WINDOW - DYNAMIC CONTENT-->
        	<section class="help">
        		<div id="base-infos"></div>
        	</section>
        	
        	<!-- MAIN MENU - STATIC CONTENT-->       	
        	<section id="primary">
        		<nav id="main-nav">
    			<ul>
    				<li>
    					<a class="background" href="#">
    						<span class="icone" data-icon="&#x2f;"></span>
							background
							<span class="btnhelp" id="backgrounds" data-icon="&#x2a;"></span>
						</a>
    				</li>
    				<li>
    					<a class="faction" href="#">
    						<span class="icone" data-icon="&#x30;"></span>
							faction
							<span class="btnhelp" id="faction" data-icon="&#x2a;"></span>
						</a>
					</li>
    				<li>
    					<a class="motivations" href="#">
    						<span class="icone" data-icon="&#x28;"></span>
    						motivations
    						<span class="btnhelp" id="motivations" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="aptitudes" href="#">
    						<span class="icone" data-icon="&#x21;"></span>
    						aptitudes
    						<span class="btnhelp" id="aptitudes" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="rep" href="#">
    						<span class="icone" data-icon="&#x24;"></span>
    						rep
    						<span class="btnhelp" id="rep" data-icon="&#x2a;"></span>
    					</a>
    				</li>
					<li>
						<a class="positive-traits" href="#">
							<span class="icone" data-icon="&#x35;"></span>
							ego pos. traits
							<span class="btnhelp" id="posTrait" data-icon="&#x2a;"></span>
						</a>
					</li>
					<!--
					<li>
						<a class="neutral-traits" href="#">
							<span class="icone" data-icon="&#x34;"></span>
							ego neutral traits
							<span class="btnhelp" id="neuTrait" data-icon="&#x2a;"></span>
						</a>
					</li>
					-->
    				<li>
    					<a class="negative-traits" href="#">
    						<span class="icone" data-icon="&#x36;"></span>
    						ego neg. traits
    						<span class="btnhelp" id="negTrait" data-icon="&#x2a;"></span>
    					</a>
    				</li>
					<li>
						<a class="active-skills" href="#">
							<span class="icone" data-icon="&#x2d;"></span>
							active skills
							<span class="btnhelp" id="activeSkills" data-icon="&#x2a;"></span>
						</a>
					</li>
    				<li>
    					<a class="knowledge-skills" href="#">
    						<span class="icone" data-icon="&#x2e;"></span>
    						knowledge skills
    						<span class="btnhelp" id="knowledgeSkills" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="morph" href="#">
    						<span class="icone" data-icon="&#x32;"></span>
    						morph
    						<span class="btnhelp" id="morph" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="soft" href="#">
    						<span class="icone" data-icon="&#x33;"></span>
    						ai's & soft gear
    						<span class="btnhelp" id="soft" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="psy" href="#">
    						<span class="icone" data-icon="&#x34;"></span>
    						psi sleights
    						<span class="btnhelp" id="psy" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="credit" href="#">
    						<span class="icone" data-icon="&#x38;"></span>
    						credit
    						<span class="btnhelp" id="credit" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="stat" href="#">
    						<span class="icone" data-icon="&#x37;"></span>
    						stat
    						<span class="btnhelp" id="moxie" data-icon="&#x2a;"></span>
    					</a>
    				</li>
    				<li>
    					<a class="lastdetails" href="#">
    						<span class="icone" data-icon="&#x27;"></span>
    						last details
    						<span class="btnhelp" id="lastdetails" data-icon="&#x2a;"></span>
    					</a>
    				</li>
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
                    KSR <span id="ksr_remain" class="rest"></span><span class="btnhelpPoint" data-icon="&#x2a;"></span><br>
                    RZ <span id="rez_remain" class="rest"></span><br>
        	</section>
        	<!-- MESSAGES FOR THE USER - DYNAMIC CONTENT-->
        	<section id="messages"></section>
        	<!-- SAVE BUTTON-->
			<button class="saveButton">
	       			save<!-- <span class="button_icone" data-icon="&#x2d;"></span> -->
        	</button>
        	<!-- LOAD BUTTON-->
			<button class="loadButton">
	       			load<!-- <span class="button_icone" data-icon="&#x30;"></span> -->
        	</button>
        	<!-- VALIDATE BUTTON-->
        	<button class="validateButton">
	       		check<!-- <span class="button_icone" data-icon="&#x2b;"></span> -->
        	</button>
        	<!-- EXPORT TXT BUTTON-->
        	<button class="exportTxtButton">
	       		TXT<!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
        	</button>
        	<!-- EXPORT PDF BUTTON-->
        	<button class="exportButton">
	       		PDF<!-- <span class="button_icone" data-icon="&#x2c;"></span> -->
        	</button>
        	<!-- RESET BUTTON-->
        	<button class="settingsButton">
	       		reset<!-- <span class="button_icone" data-icon="&#x21;"></span> -->
        	</button>
            <!-- ABOUT BUTTON-->
            <button class="aboutButton">
                about<!-- <span class="button_icone" data-icon="&#x21;"></span> -->
            </button>
        </div>

		<!-- POPUP  -- DYNAMIC CONTENT -->
        <div class="popup" id="validation_popup"></div>
        <div class="popup" id="reset_popup"></div>
        <div class="popup" id="load_popup"></div>
        <div class="popup" id="save_popup"></div>
        <div class="popup" id="about_popup"></div>
        <div class="loadingPopup" id="loading_popup"><center><img src="img/ajax-loader.gif"></center></div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
        <script src="scripts/jquery.mCustomScrollbar.js"></script>
        <script src="scripts/vegas/jquery.vegas.js"></script>
        <script src="scripts/ajaxManager.js"></script>
        <script>

            $( function() {
                //NAVIGATION JQUERRY
                //class active au menu principal
                $("#main-nav li a").click(function(){
                    $(".active").removeClass("active");
                    $(this).toggleClass("active");
                    if($('.help').is(':visible')) {
                        $(".help").animate({height: "toggle"}, 350, 'easeInOutQuint');
                    }
                    $("#tertiary_infos").css('visibility','hidden');
                    return false;
                });

                $("a.help-btn").hide();
                $(".mainlist li").hover(function(){
                    $(this).find('.help-btn').toggle();
                });


                //background fixe
                $.vegas({
                    src:'img/bg/bg1.jpg'
                })('overlay', {
                    src:'scripts/vegas/overlays/13.png'
                });
            });

        </script>
    </body>
</html>
