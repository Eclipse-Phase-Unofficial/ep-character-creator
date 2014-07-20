<?php 
 header('Content-Type: application/json'); 
 
 $filename = $_POST["saveName"];
 
// $filename = "EPCreatorSave-".date("d\_m\_Y");
 header('Content-Disposition: attachment; filename="'.$filename.'.json"');
 
 require_once '../../../php/EPCharacterCreator.php';
 session_start();
 /*
require_once '../../../php/EPCharacterCreator.php';
 session_start();
 
if (empty($_SESSION['cc']->character->charName)){
    $filename = "UNKNOW";
}else{
    $filename = $_SESSION['cc']->character->charName;        
}

$filename = str_replace(' ', '_', $filename);

if($_SESSION['cc']->creationMode){
    $filename .= "_(Creation_Mode)_";
}else{
    $filename .= "_(Evolution_Mode)_";
} 
$filename .= date("Y\_m\_d\_H\_i\_s");
    
 //$filename = "EPCreatorSave-".date("d\_m\_Y");

         
 //header('Content-Disposition: attachment; filename="'.$filename.'.json"');
*/

 echo json_encode($_SESSION['cc']->getSavePack());
?>