//POPUP MANAGEMENT =================
//Requires: ajax_helper.js

//**************************************************
//**********Loading Popup**********

function loaddingReset(){
	setTimeout(function(){
		closeAllPopup();
		location.reload();
	},1000);
}

function loaddingLoad(){
	setTimeout(function(){
		closeAllPopup();
		endLoading();
	},3000);
}


function startLoading(){
	$("#loading_popup").show();
}

function endLoading(){
	$("#loading_popup").hide();
}

//**************************************************
//**********Popup Helpers**********

function closeAllPopup(){
    if($('#popup').data('name') == 'reload_popup' ||
        $('#popup').data('name') == 'error_popup'){
        location.reload();
    }
    $('#popup').data('name','');
    $("#popup").css('opacity',0);
    $("#popup").css('visibility','hidden');
}

// Load a url into #popup
//
// If attempting to reload the same popup instead close it.
// Note: A popup name of "reload_popup" or "error_popup" is treated in a special manner.
//       If this popup is ever closed the page will reload.
function loadPopup(popup_name,url){
    //Let an already loaded popup close instead of reloading it
    if($('#popup').data('name') != popup_name){
        closeAllPopup();
        $('#popup').data('name',popup_name)
        $('#popup').load(url);
        $('#popup').css('opacity',1);
        $('#popup').css('visibility','visible');
    }else{
        closeAllPopup();
    }
}

//**************************************************
//**********Popup Buttons (on main page)**********

//click on button load on main page
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
        loadPopup("#validation_popup", "popup-contents/validation.php");
});

// Txt export button
$("#exportTxtButton").click(function() {
        window.open("./exporter/txtExporter.php");
});

// Pdf export button
$("#exportButton").click(function() {
        window.open("./exporter/pdfExporterV2_fpdf.php");
});

// Reset button
$("#settingsButton").click(function() {
        loadPopup("reload_popup","popup-contents/reset.php");
});

// About button
$("#aboutButton").click(function() {
        loadPopup("#about_popup", "popup-contents/about.php");
});

//**************************************************

// Close about, check, and error popups by clicking on them
$("#popup").click(function() {
    var popup_name = $('#popup').data('name');
    if(popup_name == '#about_popup' ||
       popup_name == '#validation_popup' ||
       popup_name == 'error_popup'){
        closeAllPopup();
    }
});

//**************************************************
//**********Buttons inside popup windows**********

//Click on the cancel button
$(document).on("click",".closeButton",function() {
    closeAllPopup();
});

//Click on the begin button
$(document).on("click",".startButton",function() {
    startLoading();
    ajax_helper({
            setCP :  $("#startCP").val(),
            getCrePoint : 'get'
        },
        function(response){
            if(response.error){
                endLoading();
                displayError(response.msg);
            }
            else{
                loaddingReset();
                setRemainingPoint(response);

            }
        }
    );
    return false;
});

//Click on the save save button
$(document).on("click",".saveSaveButton",function() {
    $('#saveForm').submit();
     closeAllPopup();
});

//Load file
$(document).on('submit','#loadForm', function () {
    startLoading();
    ajax_helper({
            load_char :     true,
            creationMode:   $('#creationMode').prop("checked"),
            rezPoints:      $('#rezPoints').val(),
            repPoints:      $('#repPoints').val(),
            credPoints:     $('#credPoints').val()
        },
        function(response){
            if(response.error) {
                endLoading();
                treatMessageError(response);
            }else{
                location.reload();
                return false;
            }
        });
    return false;
});
