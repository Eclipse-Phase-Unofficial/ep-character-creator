//POPUP MANAGEMENT =================
//Requires: ajax_helper.js

//**************************************************
//**********Loading Popup**********

function startLoading(){
	$("#loading_popup").show();
}

function endLoading(){
	$("#loading_popup").hide();
}

//**************************************************
//**********Popup Helpers**********

function closeAllPopup(){
    if($('#popup').data('reloadOnClose')){
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
function loadPopup(popup_name,url,clickToClose=false,reloadOnClose=false){
    //Let an already loaded popup close instead of reloading it
    if($('#popup').data('name') != popup_name){
        closeAllPopup();
        $('#popup').data('name',popup_name);
        $('#popup').data('clickToClose',clickToClose);
        $('#popup').data('reloadOnClose',reloadOnClose);
        $('#popup').load(url);
        $('#popup').css('opacity',1);
        $('#popup').css('visibility','visible');
    }else{
        closeAllPopup();
    }
}

//**************************************************

// Close some popups by clicking on them
$("#popup").click(function() {
    var popup_name = $('#popup').data('name');
    if($('#popup').data('clickToClose')){
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
    // TODO: Change setCP to reset character.
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
                location.reload();
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
            }
        });
    return false;
});
