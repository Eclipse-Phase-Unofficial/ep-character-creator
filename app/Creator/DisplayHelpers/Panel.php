<?php
declare(strict_types=1);

namespace App\Creator\DisplayHelpers;

use App\Creator\Atoms\EPAi;
use App\Creator\Atoms\EPGear;


/**
 * A class to help in panel creation.
 *
 * Use this instead of writing raw HTML.
 */
class Panel{
    private $html;
    function __construct(){
        $this->html = "";
    }

    /**
     * Start an empty panel.
     *
     * If a panel previously existed, this resets the panel.
     *
     * @param $id - The panel's id.
     */
    function startPanel($id){
        $this->html = "<ul class='mainlist' id='".$id."'>";
    }

    /**
     *  Start a panel (with a title, and a book link).
     *
     * If a panel previously existed, this resets the panel.
     *
     *  @param $atomName - The name of the item being described (Both printed, and used to add the book page.
     */
    function startDescriptivePanel($atomName){
        $output  = "<span class='descriptionTitle'>".$atomName."</span>";
        $output .= "<ul class='mainlist' id='bmdList'>";
        $output .= Helpers::getBPHtml($atomName);
        $this->html = $output;
    }

    /**
     * Add a description section.
     */
    function addDescription($description){
        $output  = "<li class='listSection'>";
        $output .= "Description";
        $output .= "</li>";
        $output .= "<li class='bmDesc'>";
        $output .= $description;
        $output .= "</li>";
        $this->html .= $output;
    }

    /**
     * Add an option to buy more/less of an item.
     * @param EPGear $gear
     * @param string $type
     */
    function addBuySell(EPGear $gear,string $type){
        $output = "";
        if(!$gear->isUnique()){
            $output .= "<li class='listSection'>";
            $output .= "Buy more of this";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "	<span class='bmGranted'>Number</span>";
            $output .= "	<span class='iconPlusMinus slowTransition' id='removeOccurence_".$type."' data-icon='&#x3b;'></span>";
            $output .= "	<span class='betweenPlusMinus'>[".$gear->getOccurrence()."]</span>";
            $output .= "	<span class='iconPlusMinus slowTransition' id='addOccurence_".$type."' data-icon='&#x3a;'></span>";
            $output .= "</li>";
        }
        $this->html .= $output;
    }

    /**
     * Add a Traits section if it exists.
     */
    function addTraits($traits){
        $output = "";
        if(!empty($traits)){
            $output .= "<li class='listSection'>";
            $output .= "Traits";
            $output .= "</li>";
            foreach($traits as $t){
                $output .= "<li>";
                $output .= "<span class='bmGranted'>".$t->name."</span>";
                $output .= "</li>";
            }
        }
        $this->html .= $output;
    }

    /**
     * Add AI Skills and Aptitudes if they exist.
     */
    function addAi(EPAi $ai){
        $output = "";
        if(!empty($ai->aptitudes)){
            $output .= "<li class='listSection'>";
            $output .= "Aptitudes";
            $output .= "</li>";
        }
        foreach($ai->aptitudes as $apt){
            $output .= "<li>";
            $output .= "<span class='bmGranted'>".$apt->abbreviation." <b>".$apt->value."</b></span>";
            $output .= "</li>";
        }

        if(!empty($ai->skills)){
            $output .= "<li class='listSection'>";
            $output .= "Skills";
            $output .= "</li>";
        }

        foreach($ai->skills as $apt){
            $output .= "<li>";
            $output .= "<span class='bmGranted'>".$apt->getPrintableName()." <b>".$apt->baseValue."</b></span>";
            $output .= "</li>";
        }
        $this->html .= $output;
    }

    /**
     * Add Armor and Offensive sections if they exist.
     */
    function addArmor($gear){
        $output = "";
        if($gear->armorEnergy != 0 || $gear->armorKinetic != 0){
            $output .= "<li class='listSection'>";
            $output .= "Provided armor";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "<span class='bmGranted'>Kinetic <b>".$gear->armorKinetic."</b></span>";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "<span class='bmGranted'>Energy <b>".$gear->armorEnergy."</b></span>";
            $output .= "</li>";
        }
        if($gear->degat != 0 || $gear->armorPenetration != 0){
            $output .= "<li class='listSection'>";
            $output .= "Offensive capacity";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "<span class='bmGranted'>Damage: <b>".$gear->degat."</b></span>";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "<span class='bmGranted'>Armor penetration: <b>".$gear->armorPenetration."</b></span>";
            $output .= "</li>";
        }
        $this->html .= $output;
    }

    /**
     * Add raw HTML.
     */
    function addRawHtml($input){
        $this->html .= $input;
    }

    /**
     * Get the final html of the panel
     */
    function getHtml(){
        return $this->html;
    }

    /**
     * End a panel
     */
    static function endPanel(){
        return "</ul>";
    }
}
