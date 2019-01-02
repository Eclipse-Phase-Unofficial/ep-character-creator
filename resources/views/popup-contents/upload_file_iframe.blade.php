<?php
    error_reporting(0);

//     error_log(print_r($_POST,true));
//     error_log(print_r($_FILES,true));
//     error_log(print_r($_SESSION,true));

    if(isset($_POST['clear_file'])){
        session()->put('fileName', null);
        session()->put('fileToLoad', null);
    }
    else if(isset($_FILES['uploadedfile'])){
        session()->put('fileName', $_FILES['uploadedfile']['name']);
        session()->put('fileToLoad', file_get_contents($_FILES['uploadedfile']['tmp_name']));
    }
?>
<html>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body style="margin:0;">
    <form id="file_upload_form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="8388608" /><!--Max size of 8MB.  If we're hitting this limit there's a problem.-->
        {{--Offer to set the file name, or clear the uploaded file--}}
        @if(empty(session('fileName')))
            <input id='uploadedfile' name='uploadedfile' type='file'>
        @else
            <input type='hidden' name='clear_file' value='true' />
            <button id='fileName' name='fileName' value='{{session('fileName')}}'>{{session('fileName')}}</button>
        @endif
    </form>
    <script>
        $('#uploadedfile').on('change', function(){ $('#file_upload_form').submit(); });
        $('#fileName').on('click', function(){ $('#file_upload_form').submit(); });
    </script>
</body>

