// ================= Static UI elements =================
// This is for elements that the user should see regardless of where they are on the site.
// For example, the menu buttons, and Points remaining display
// Requires: popup.js
// Requires: scripts/vegas/vegas.min.js

//Note:  This script is optimized to be included via a PHP include statement.  It uses PHP, and references relative paths!

//**************************************************
//**********Menu Buttons**********

// Load button
$("#loadButton").click(function() {
    loadPopup("#load_popup","popup-contents/load.php");
});

// Save button
$("#saveButton").click(function() {
    // window.open("./other/save.php");
    loadPopup("#save_popup","popup-contents/save_popup.php");
});

// Check button
$("#validateButton").click(function() {
        loadPopup("#validation_popup", "popup-contents/validation.php",true);
});

// Txt export button
$("#exportTxtButton").click(function() {
        window.open("./exporter/txtExporter.php");
});

// Pdf export button
$("#exportPdfButton").click(function() {
        window.open("./exporter/pdfExporterV2_fpdf.php");
});

// Reset button
$("#settingsButton").click(function() {
        loadPopup("reload_popup","popup-contents/reset.php",false,true);
});

// About button
$("#aboutButton").click(function() {
        loadPopup("#about_popup", "popup-contents/about.php",true);
});

//**************************************************

// Adjust the points remaining display
function setRemainingPoint(ajaxData){
    // Hide creation data if not in creation mode, and Rez points if not in rez mode
    if(ajaxData.creation_remain == "N/A")
    {
        $("#CP").css('display','none');
        $("#AP").css('display','none');
        $("#ASR").css('display','none');
        $("#KSR").css('display','none');
    }else{
        $("#RZ").css('display','none');
    }
     $("#creation_remain").html(ajaxData.creation_remain);
     $("#credit_remain").html(ajaxData.credit_remain);
     $("#aptitude_remain").html(ajaxData.aptitude_remain);
     $("#reputation_remain").html(ajaxData.reputation_remain);
     $("#rez_remain").html(ajaxData.rez_remain);
     $("#asr_remain").html(ajaxData.asr_remain);
     $("#ksr_remain").html(ajaxData.ksr_remain);
}

//**************************************************
//**********Background Slideshow**********
var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));

// Deprecated the 80's background sliders
//See here for more options:  http://vegas.jaysalvat.com/documentation/settings/
// var desktopSlides = [
//         { src: 'img/bg/bg1.jpg'},
//         { src: 'img/bg/bg2.jpg'},
//         { src: 'img/bg/bg3.jpg'},
//         //These are all free (at least non-commercial) use images or images in the public domain
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/d/d6/San_Francisco_International_Airport_at_night.jpg'},     //Andrew Choy from Santa Clara, California (Creative Commons Attribution-Share Alike 2.0 Generic)
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/6/62/Starsinthesky.jpg'},    //Credit ESA (This is me giving credit, per the license)
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/0/00/Crab_Nebula.jpg'},                  //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/7/7f/Ngc1999.jpg'},                      //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Pleiades_large.jpg'},               //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/c/c9/Sirius_A_and_B_artwork.jpg'},       //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/5/57/Witness_the_Birth_of_a_Star.jpg'},  //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/4/44/Ngc6397_hst_blue_straggler.jpg'},   //Credit NASA
//         { src: 'https://upload.wikimedia.org/wikipedia/commons/b/b4/The_Sun_by_the_Atmospheric_Imaging_Assembly_of_NASA%27s_Solar_Dynamics_Observatory_-_20100819.jpg'}  //Credit NASA
//     ];
// Do not show the (data heavy) background images if on mobile
// if(isMobile){
//     desktopSlides=[{}]
// };
// $('body').vegas({
//     timer: false,
//     shuffle: true,
//     delay: 60000,
//     overlay: '<?php echo createDataURI("scripts/vegas/overlays/08.png","png"); ?>',
//     slides: [{}]
// });
