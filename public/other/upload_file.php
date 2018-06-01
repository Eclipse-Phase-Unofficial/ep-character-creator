<?php
    error_reporting(0);
    session_start();

//     error_log(print_r($_POST,true));
//     error_log(print_r($_FILES,true));
//     error_log(print_r($_SESSION,true));

    if(isset($_POST['clear_file'])){
        unset($_SESSION['fileName']);
        unset($_SESSION['fileToLoad']);
    }
    else if(isset($_FILES['uploadedfile'])){
        $_SESSION['fileName']=$_FILES['uploadedfile']['name'];
        $handle = fopen($_FILES['uploadedfile']['tmp_name'],'r');
        $_SESSION['fileToLoad'] = fread($handle, filesize($_FILES['uploadedfile']['tmp_name']));
    }
?>
<html>
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body style="margin:0;">
    <form id="file_upload_form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="8388608" /><!--Max size of 8MB.  If we're hitting this limit there's a problem.-->
        <?php
            // Offer to set the file name, or clear the uploaded file
            if(isset($_SESSION['fileName'])){
                echo "<input type='hidden' name='clear_file' value='true' />";
                echo "<button id='fileName' name='fileName' value='".$_SESSION['fileName']."'>".$_SESSION['fileName']."</button>";
            }
            else {
                echo "<input id='uploadedfile' name='uploadedfile' type='file'>";
            }
        ?>
    </form>
    <script>
        $('#uploadedfile').on('change', function(){ $('#file_upload_form').submit(); });
        $('#fileName').on('click', function(){ $('#file_upload_form').submit(); });
    </script>
</body>

