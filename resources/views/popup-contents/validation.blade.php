<?php
declare(strict_types=1);

use App\Creator\EPValidation;

session_start();

$isValid = creator()->checkValidation();

$aptPoint = creator()->validation->items[EPValidation::$APTITUDE_POINT_USE];
$repPoint = creator()->validation->items[EPValidation::$REPUTATION_POINT_USE];
$bck 	  = creator()->validation->items[EPValidation::$BACKGROUND_CHOICE];
$fac      = creator()->validation->items[EPValidation::$FACTION_CHOICE];
$charName = creator()->validation->items[EPValidation::$CHARACTER_NAME_CHOICE];
$morph    = creator()->validation->items[EPValidation::$MORPH_CHOICE];
$mot      = creator()->validation->items[EPValidation::$MOTIVATION_THREE_CHOICE];
$acSkill  = creator()->validation->items[EPValidation::$ACTIVE_SKILLS_MIN];
$knSkill  = creator()->validation->items[EPValidation::$KNOWLEDGE_SKILLS_MIN];

$AP = creator()->getAptitudePoint();
$CP = creator()->getCreationPoint();
$RP = creator()->getReputationPoints();
$cpActivRestNeed = creator()->getActiveRestNeed();
$cpKnowRestNeed = creator()->getKnowledgeRestNeed();

function getIsValidHtml($itemName,$isValid,$failureMessage){
    $output = "<tr><td>";
    if($isValid){
        $output .= "<span class='valideIcone' data-icon='&#x2b;'></span>";
    }else{
        $output .= "<span class='invalidIcone' remMotiv' data-icon='&#x39;'></span>";
    }
    $output .= "<b>".$itemName."</b></td> <td>";
    if(!$isValid){
        $output .= $failureMessage;
    }
    $output .= "</td></tr>";
    return $output;
}

?>
<table class="popup_table" id="table_validation" align="center">
    <tr align="center">
        <td colspan='2'>
            <h1><b><u> Character Validation </u></b></h1>
        </td>
    </tr>
    <?php
        echo getIsValidHtml("Background",$bck,"You have to choose a background.");
        echo getIsValidHtml("Faction",$fac,"You have to choose a faction.");
        echo getIsValidHtml("Motivations",$mot,"You have to choose at least three motivations.");
        echo getIsValidHtml("Active Skills",$acSkill,"<b>(Need: ".$cpActivRestNeed."CP)</b> You have to spend more points on your Active Skills.");
        echo getIsValidHtml("Knowlege Skills",$knSkill,"<b>(Need: ".$cpKnowRestNeed."CP)</b> You have to spend more points on your Knowlege Skills.");
        echo getIsValidHtml("Creation Points",($CP==0),"<b>(Unspent: ".$CP."AP)</b> You have unspent Creation Points.");
        echo getIsValidHtml("Aptitude Points",$aptPoint,"<b>(Unspent: ".$AP."AP)</b> You have unspent Aptitude Points.");
        echo getIsValidHtml("Reputation Points",$repPoint,"<b>(Unspent: ".$RP."RP)</b> You have unspent Reputation Points.");
        echo getIsValidHtml("Morph(s)",$morph,"You have to choose at least one Morph.");
        echo getIsValidHtml("Character name",$charName,"Your Character must have a name.");

        //Put a big banner on the bottom if the Character is valid or not
        echo "<tr align='center'><td colspan='2'><h1><b>";
        if($isValid){
            echo "<span class='valideIcone' data-icon='&#x2b;'></span>";
            echo "Character is valid";
        }else{
            echo "<span class='invalidIcone' remMotiv' data-icon='&#x39;'></span>";
            echo "Character is NOT valid";
        }
        echo "</b></h1></tr></td>";
    ?>
    <tr align='center'>
        <td colspan='2'>
			<button class="closeButton popupInnerButton">
				Close
			</button>
		</td>
	</tr>
</table>
