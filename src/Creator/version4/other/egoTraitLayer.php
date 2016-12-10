<?php
function getTraitHtml(){
        $traits = $_SESSION['cc']->character->ego->faction->traits;
        echo "<li>";
        echo "		<label class='listSection'>Traits</label>";
        echo "</li>";
        foreach($traits as $et){
            echo "<li>";
            echo "		<label class='bmGranted'>".$et->name."</label>";
            echo "</li>";
        }
}
?>
