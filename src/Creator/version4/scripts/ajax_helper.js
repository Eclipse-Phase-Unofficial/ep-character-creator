//Handle the boilerplate ajax code
function ajax_helper(data,success_function,error_function) {
    $.ajax({
        type : 'POST',
        contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
        url : dispatcherURL,
        dataType : 'json',
        data: data,
        success: success_function,
        error: error_function
    });
}

//Send an ajax request, and process the result
function do_ajax(data,success_function) {
    ajax_helper(data,
        function(response){
            if(response.error) {
                treatMessageError(response,DISPLAY_ON_TOP);
            }
            else {
                success_function(response)
            }
        },
        function(XMLHttpRequest, textStatus, errorThrown) {
            treatMessageError('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>',DISPLAY_ON_TOP);
        });
}
