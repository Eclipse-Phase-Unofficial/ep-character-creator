// ================= Static UI elements =================
// This is for elements that the user should see regardless of where they are on the site.
// For example, the menu buttons, and Points remaining display
// Requires: popup.js
//**************************************************
//**********Menu Buttons**********

// Load button
$("#loadButton").click(function() {
    loadPopup("#load_popup","popup-contents/load");
});

// Save button
$("#saveButton").click(function() {
    loadPopup("#save_popup","popup-contents/save_popup");
});

// Check button
$("#validateButton").click(function() {
        loadPopup("#validation_popup", "popup-contents/validation",true);
});

// Txt export button
$("#exportTxtButton").click(function() {
        window.open("./export/txt");
});

// Pdf export button
$("#exportPdfButton").click(function() {
        window.open("./export/pdf");
});

// Reset button
$("#settingsButton").click(function() {
        loadPopup("reload_popup","popup-contents/reset",false,true);
});

// About button
$("#aboutButton").click(function() {
        loadPopup("#about_popup", "popup-contents/about",true);
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
