<?php
declare(strict_types=1);

require_once (__DIR__ . '/../../../../vendor/autoload.php');

use EclipsePhaseCharacterCreator\Backend\EPValidation;

session_start();

$isValid = $_SESSION['cc']->checkValidation();

$aptPoint = $_SESSION['cc']->validation->items[EPValidation::$APTITUDE_POINT_USE];
$repPoint = $_SESSION['cc']->validation->items[EPValidation::$REPUTATION_POINT_USE];
$bck 	  = $_SESSION['cc']->validation->items[EPValidation::$BACKGROUND_CHOICE];
$fac      = $_SESSION['cc']->validation->items[EPValidation::$FACTION_CHOICE];
$charName = $_SESSION['cc']->validation->items[EPValidation::$CHARACTER_NAME_CHOICE];
$morph    = $_SESSION['cc']->validation->items[EPValidation::$MORPH_CHOICE];
$mot      = $_SESSION['cc']->validation->items[EPValidation::$MOTIVATION_THREE_CHOICE];
$acSkill  = $_SESSION['cc']->validation->items[EPValidation::$ACTIVE_SKILLS_MIN];
$knSkill  = $_SESSION['cc']->validation->items[EPValidation::$KNOWLEDGE_SKILLS_MIN];

$AP = $_SESSION['cc']->getAptitudePoint();
$CP = $_SESSION['cc']->getCreationPoint();
$RP = $_SESSION['cc']->getReputationPoints();
$cpActivRestNeed = $_SESSION['cc']->getActiveRestNeed();
$cpKnowRestNeed = $_SESSION['cc']->getKnowledgeRestNeed();

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
