//POPUP MANAGEMENT =================

function startLoading(){
	$("#loading_popup").show();
}

function endLoading(){
	$("#loading_popup").hide();
}

function closeAllPopup(){
    $(".popup").css('opacity',0);
    $(".popup").css('visibility','hidden');
}

function loadPopup(popup_name,url){
    $(popup_name).load(url);
    $(popup_name).addClass("popup");
    $(popup_name).css('opacity',1);
    $(popup_name).css('visibility','visible');
}

//click on button validation
$(".validateButton").click(function() {
    if($("#validation_popup").css('visibility') == 'visible'){
        closeAllPopup();
    }
    else{
        closeAllPopup();
        loadPopup("#validation_popup", "popup-contents/validation.php");
    }
});
//Click on validation popup for close
$("#validation_popup").click(function() {
    closeAllPopup();
});

//click on button about
$(".aboutButton").click(function() {
    if($("#about_popup").css('visibility') == 'visible'){
        closeAllPopup();
    }
    else{
        closeAllPopup();
        loadPopup("#about_popup", "popup-contents/about.php");
    }
});
//Click on about popup for close
$("#about_popup").click(function() {
    closeAllPopup();
});

//click on button reset
$(".settingsButton").click(function() {
    closeAllPopup();
    $("#reset_popup").load("popup-contents/reset.php");
    $("#reset_popup").css('opacity',1);
    $("#reset_popup").css('visibility','visible');
});
//Click on the begin button
$(document).on("click",".startButton",function() {
    startLoading();
        $.ajax({
            type : 'POST',
            contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
            url : dispatcherURL,
            dataType : 'json',
            data: {
                    setCP :  $("#startCP").val(),
                    getCrePoint : 'get'
            },
            success : function(response){
                    if(response.error){
                        $("#reset_popup").html(response.msg);
                    }
                    else{
                        loaddingReset();
                        setRemainingPoint(response);

                    }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#reset_popup").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
            }
    });

    return false;
});

//Click on the cancel button
$(document).on("click",".cancelButton",function() {
    location.reload();
});

//Click on the load load button
$(document).on("click",".loadLoadButton",function() {
    $('#loadForm').submit();
});

    //Click on the save cancel button
$(document).on("click",".cancelSaveButton",function() {
    $("#save_popup").css('opacity',0);
    $("#save_popup").css('visibility','hidden');
});

//Click on the save save button
$(document).on("click",".saveSaveButton",function() {
    $('#saveForm').submit();
    $("#save_popup").css('opacity',0);
    $("#save_popup").css('visibility','hidden');
});

//click on button export txt
$(".exportTxtButton").click(function() {
        window.open("./exporter/txtExporter.php");
});


//click on button export pdf
$(".exportButton").click(function() {
        window.open("./exporter/pdfExporterV2_fpdf.php");
});

//click on button save
$(".saveButton").click(function() {
    // window.open("./other/save.php");
        closeAllPopup();
    $("#save_popup").load("popup-contents/save_popup.php");
    $("#save_popup").css('opacity',1);
    $("#save_popup").css('visibility','visible');
});
//click on button load on main page
$(".loadButton").click(function() {
    closeAllPopup();
    $("#load_popup").load("popup-contents/load.php");
    $("#load_popup").css('opacity',1);
    $("#load_popup").css('visibility','visible');
});

//Load file
$(document).on('submit','#loadForm', function () {
    var fileVal = $('#fileName').val();
    if(fileVal == ""){
        $('#errorLoadMsg').html("Choose a file !");
        return false;
    }
    else{
        $("#load_popup").css('opacity',0);
        $("#load_popup").css('visibility','hidden');
        closeAllPopup();
    }
});
