//Helper library for ajax and error messages
// This helps reduce boilerplate code, and increases consistency

var dispatcherURL = 'scripts/dispatcher.php';

// Handle the boilerplate ajax code
//
// Do not use except in special circumstances.
// In 90% of the cases, do_ajax(...) provides all the functionality, and handles displaying errors.
function ajax_helper(data,success_function) {
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

//Should be in popup.js, but preventing circular dependencies
function displayError(error_message){
    $("#popup").html(error_message);
    $('#popup').data('name','error_popup');
    $('#popup').data('clickToClose',true);
    $('#popup').data('reloadOnClose',true);
    $("#popup").css('opacity',1);
    $("#popup").css('visibility','visible');
}
