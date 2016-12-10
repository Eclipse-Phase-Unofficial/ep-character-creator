<?php
function getTraitHtml($traits){
//     if(!empty($traits)){
        echo "<li>";
        echo "		<label class='listSection'>Traits</label>";
        echo "</li>";
        foreach($traits as $t){
            echo "<li>";
            echo "		<label class='bmGranted'>".$t->name."</label>";
            echo "</li>";
        }
//         }
}
?>
