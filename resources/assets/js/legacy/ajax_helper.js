//Helper library for ajax and error messages
// This helps reduce boilerplate code, and increases consistency

var dispatcherURL = 'scripts/dispatcher.php';

// Handle the boilerplate ajax code
//
// Do not use except in special circumstances.
// In 90% of the cases, do_ajax(...) provides all the functionality, and handles displaying errors.
function ajax_helper(data,success_function) {
    data._token = document.head.querySelector('meta[name="csrf-token"]').content; //Append the CSRF token
    $.ajax({
        type : 'POST',
        contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
        url : dispatcherURL,
        dataType : 'json',
        data: data,
        success: success_function,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            displayError('An Error Occured!<br>'+textStatus+'<br>'+errorThrown+'<br>The Server May Be Down!<br>Please Try Again Later!');
        }
    });
}

// Send an ajax request, and process the result
//
// Handles error responses.
// Use this one most of the time
function do_ajax(data,success_function) {
    ajax_helper(data,
        function(response){
            if(response.error) {
                treatMessageError(response);
            }
            else {
                success_function(response)
            }
        });
}

//**********Error display functions**********//
var USER_MSG_HTML = "<div id='user-messages'></div>";

function treatMessageError(response){
	if(response.erType == "rules" || response.erType == "system"){
		displayRulesMessage(response.msg);
	}
	else if(response.msg == ''){
		displayError('An Error Occured!<br>No Error Message Recieved!');
	}
	else{
		displayError(response.msg);
	}
}

// Puts a temporary message on screen that slowly fades out
// This is a fairly unobtrusive way to tell the user when they are attempting an invalid action
function displayRulesMessage(msg){
	$("#messages").stop( true, true ).fadeOut();
	$("#user-messages").stop( true, true ).fadeOut();
	$("#messages").html(USER_MSG_HTML);
	$("#user-messages").html(msg);
	$("#messages").fadeIn();
	$("#user-messages").fadeIn();
    $("#messages").fadeOut(15000);
}

function hideRulesMessage(){
	$("#messages").html(USER_MSG_HTML);
    $("#user-messages").fadeOut();
}

// Should be in popup.js, but preventing circular dependencies
function displayError(error_message){
    $("#popup").html(error_message);
    $('#popup').data('name','error_popup');
    $('#popup').data('clickToClose',true);
    $('#popup').data('reloadOnClose',true);
    $("#popup").css('opacity',1);
    $("#popup").css('visibility','visible');
}

//*** Display items on the panels ***//
function displayMessageOnTertiary(msg,title){
    var titleHtml = '';

    if (title) {
        titleHtml = '<label class="descriptionTitle">' + title + '</label>';
    }

	$("#tertiary").html(titleHtml + TERTIARY_INFO_HTML);
	$("#tertiary_infos").html(msg);
    $("#tertiary_infos").css('visibility','visible');
}

function displayMessageOnQuaternary(msg){
	$("#quaternary").html(QUATERNARY_INFO_HTML);
	$("#quaternary_infos").html(msg);
    $("#quaternary_infos").css('visibility','visible');
}

/**
 * Reload the current panel an element is under.
 *
 * Does NOT close out any elements to the right of the current panel!
 */
function reloadPanel(element){
    var panel = $(element).parents('.panel');
    loadPanel(panel,panel.attr('src'));
}

/**
 * Load the contents of a url into a specific panel.
 */
function loadPanel(id,url){
    $(id).load(url, function(){
        setupFoldingList();
    });
    $(id).attr('src',url);
}

/**
 * Hide all panels to the right of Secondary, then load a url into it.
 */
function loadSecondary(url){
    hideTertiary();
    hideQuaternary();
    loadPanel("#secondary",url);
}

/**
 * Hide all panels to the right of Tertiary, then load a url into it.
 */
function loadTertiary(url){
    hideQuaternary();
    loadPanel("#tertiary",url);
}

/**
 * Load a url in Quaternary.
 *
 * There are no panels to the right, so there's nothing to hide.
 */
function loadQuaternary(url){
    loadPanel("#quaternary",url);
}

function hideSecondary(){
    $("#secondary").html(TERTIARY_INFO_HTML);
    $("#secondary_infos").css('visibility','hidden');
}

function hideTertiary(){
    $("#tertiary").html(TERTIARY_INFO_HTML);
    $("#tertiary_infos").css('visibility','hidden');
}

function hideQuaternary(){
    $("#quaternary").html(QUATERNARY_INFO_HTML);
    $("#quaternary_infos").css('visibility','hidden');
}

function hideErrorsMsg(){
	hideRulesMessage();
	hideQuaternary();
	hideTertiary();
}
