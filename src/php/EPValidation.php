<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPValidation
 *
 * @author Jige_SSD
 */
class EPValidation {
    //put your code here
    static $APTITUDE_POINT_USE = 'Apt_Pts_Use';
    static $REPUTATION_POINT_USE = 'Rep_Pts_Use';
    static $BACKGROUND_CHOICE = 'Bck_Chc';
    static $FACTION_CHOICE = 'Fac_Chc';
    static $CHARACTER_NAME_CHOICE = 'Cha_Nme_Chc';
    static $MORPH_CHOICE = 'Mrp_Chc';
    static $MOTIVATION_THREE_CHOICE = 'Mot_3_Chc';
    static $ACTIVE_SKILLS_MIN = 'Akt_Skl_Min';
    static $KNOWLEDGE_SKILLS_MIN = 'Knw_Skl_Min';
    static $CREDIT_AMOUNT_ENOUGH = 'Cre_Amo_Eno';
    
    
    public $items;
    
    function __construct() {
        $this->items = array();

        $this->items[EPValidation::$APTITUDE_POINT_USE] = false;
        $this->items[EPValidation::$REPUTATION_POINT_USE] = false;
        $this->items[EPValidation::$BACKGROUND_CHOICE] = false;
        $this->items[EPValidation::$FACTION_CHOICE] = false;
        $this->items[EPValidation::$CHARACTER_NAME_CHOICE] = false;
        $this->items[EPValidation::$MORPH_CHOICE] = false;
        $this->items[EPValidation::$MOTIVATION_THREE_CHOICE] = false;
        $this->items[EPValidation::$ACTIVE_SKILLS_MIN] = false;
        $this->items[EPValidation::$KNOWLEDGE_SKILLS_MIN] = false;
        $this->items[EPValidation::$CREDIT_AMOUNT_ENOUGH] = false;
    }
}

?>
