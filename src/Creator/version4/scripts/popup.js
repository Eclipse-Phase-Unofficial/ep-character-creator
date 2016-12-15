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
    if($("#reset_popup").css('visibility') == 'visible'||
       $("#error_popup").css('visibility') == 'visible'){
        location.reload();
    }
    $(".popup").css('opacity',0);
    $(".popup").css('visibility','hidden');
}

function loadPopup(popup_name,url){
    if($(popup_name).css('visibility') == 'visible'){
        closeAllPopup();
    }
    else{
        closeAllPopup();
        $(popup_name).load(url);
        $(popup_name).css('opacity',1);
        $(popup_name).css('visibility','visible');
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
        loadPopup("#reset_popup","popup-contents/reset.php");
});

// About button
$("#aboutButton").click(function() {
        loadPopup("#about_popup", "popup-contents/about.php");
});

//**************************************************

// Close about, check, and error popups by clicking on them
$("#validation_popup").click(function() {
    closeAllPopup();
});

$("#about_popup").click(function() {
    closeAllPopup();
});

$("#error_popup").click(function() {
    closeAllPopup();
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
                $("#reset_popup").html(response.msg);
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
