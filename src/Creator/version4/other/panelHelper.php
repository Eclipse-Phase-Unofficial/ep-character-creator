<?php
/**
 *  Panel helper functions
 *
 *  Instead of directly writing HTML, we use these helper functions whenever possible
 */

require_once('../other/bookPageLayer.php');
require_once('../other/iconHelper.php');

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
        $output .= getBPHtml($atomName);
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
     */
    function addBuySell($atom,$type){
        $output = "";
        if(!$atom->unique){
            $output .= "<li class='listSection'>";
            $output .= "Buy more of this";
            $output .= "</li>";
            $output .= "<li>";
            $output .= "	<span class='bmGranted'>Number</span>";
            $output .= "	<span class='iconPlusMinus slowTransition' id='removeOccurence_".$type."' data-icon='&#x3b;'></span>";
            $output .= "	<span class='betweenPlusMinus'>[".$atom->occurence."]</span>";
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
    function addAi($ai){
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
}

/**
 * End a panel
 */
function endPanel(){
    return "</ul>";
}

/**
 * Used to generate an li element.
 *
 * This element can have plenty of fun extras.  Like a cost, book logo, and add/remove button.
 */
class li{
    private $html;
    private $id;            //Translates to html 'id ='
    private $class;         //Translates to html 'class ='
    private $cost;          //Translates to html 'data-cost ='
    private $cost_isDefault;//Translates to html 'data-cost_isDefault ='
    private $cost_units;    //Translates to html 'data-cost_units ='

    /**
     * Creat an li element.
     *
     * @param $id    - The li's id. (Also displays this to the user)
     * @param $class - The li's class. (defaults to "")
     */
    function __construct($id,$class = ""){
        $this->id = $id;
        $this->class = $class;

        $this->cost = "";
        $this->cost_isDefault = "";
        $this->cost_units = "";
    }

    /**
     * Tell the user how much the li costs.
     *
     * @param $cost      - The item's cost. (if 0, nothing is displayed)
     * @param $isDefault - If the item is automatically given to the player. (if true, "(Granted)" is displayed; takes precidence over $isDefault)
     * @param $units     - What is being charged
     *
     * @example $item->addCost(1): Gives "(1 cp)"
    */
    function addCost($cost,$isDefault = False, $units = "cp"){
        $this->cost = $cost;
        $this->cost_isDefault = $isDefault;
        $this->cost_units = $units;


        $costDisplay = "(".$cost." ".$units.")";
        if($cost == 0){
            $costDisplay = "";
        }
        if($isDefault){
            $costDisplay = "(Granted)";
        }
        $this->html .=  "<span class='costInfo'>".$costDisplay."</span>";
    }

    /**
     * Add a book icon to the li.
     *
     * @param $id - The id used to look up what icon to use.
     */
    function addBookIcon($id){
        $this->html .= getListStampHtml($id);
    }

    /**
     * TODO: inline in a proper helper?
     */
    function getBookStamp($id){
        return getBookAbbreviation($id);
    }

    /**
     * Add a plus or checked icon.
     *
     * WARNING:  The icon will have the same id as the main li. (To fix this other code must be changed...)
     *
     * @param $iconClass - The class of the icon itself. (Used by javascript for ajax calls.)
     * @param $isPlus    - Display the plus icon, or the checked icon.
     */
     function addPlusChecked($iconClass,$isChecked = False){
        $icon = Icon::$checked;
        if(!$isChecked){
            $icon = Icon::$plus;
        }
        $this->html .= Icon::getHtml('addOrSelectedIcon '.$iconClass,$this->id,$icon);
     }

    /**
     * Add a plus or minus icon.
     *
     * WARNING:  The icon will have the same id as the main li. (To fix this other code must be changed...)
     *
     * @param $iconClass - The class of the icon itself. (Used by javascript for ajax calls.)
     * @param $isPlus    - Display the plus icon, or the minus icon.
     */
    function addPlusMinus($iconClass,$isPlus = True){
        $icon = Icon::$plus;
        if(!$isPlus){
            $icon = Icon::$minus;
        }
        $this->html .= Icon::getHtml('addOrSelectedIcon '.$iconClass,$this->id,$icon);
     }

    /**
     * Add a plus or 'X' icon.
     *
     * WARNING:  The icon will have the same id as the main li. (To fix this other code must be changed...)
     *
     * @param $iconClass - The class of the icon itself. (Used by javascript for ajax calls.)
     * @param $isPlus    - Display the plus icon, or the 'X' icon.
     */
    function addPlusX($iconClass,$isPlus = True){
        $icon = Icon::$plus;
        if(!$isPlus){
            $icon = Icon::$X;
        }
        $this->html .= Icon::getHtml('addOrSelectedIcon '.$iconClass,$this->id,$icon);
     }

     /**
     * Add a checked icon, or blank space.
     *
     * WARNING:  The icon will have the same id as the main li. (To fix this other code must be changed...)
     *
     * @param $iconClass - The class of the icon itself. (Used by javascript for ajax calls.)
     * @param $isChecked - Display the checked icon, or a blank space.
     */
     function addCheckedBlank($iconClass,$isChecked = False){
        $icon = Icon::$checked;
        if(!$isChecked){
            $icon = '';
        }
        $this->html .= Icon::getHtml('addOrSelectedIcon '.$iconClass,$this->id,$icon);
     }

    /**
     * Get the final html of the li.
     */
    function getHtml(){
        $output  = "<li class='".$this->class."' id='".$this->id."' data-cost='".$this->cost."' data-cost_isDefault='".$this->cost_isDefault."' data-cost_units='".$this->cost_units."' >";
        $output .= $this->id;
        $output .= $this->html;
        $output .= "</li>";
        return  $output;
    }
}


?>
