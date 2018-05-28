<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Site\other;

use EclipsePhaseCharacterCreator\Backend\EPAtom;
use EclipsePhaseCharacterCreator\Backend\EPBook;
use EclipsePhaseCharacterCreator\Backend\EPCreditCost;
use EclipsePhaseCharacterCreator\Backend\EPGear;
use EclipsePhaseCharacterCreator\Backend\EPMorph;
use EclipsePhaseCharacterCreator\Backend\EPTrait;
use EclipsePhaseCharacterCreator\Backend\EPBonusMalus;

class Helpers
{
    /**
     * Get the Gear list a morph can use.
     *
     * Filters out gear morphs can't use.
     */
    static function getFormatedMorphGearList($listFiltered, $morph, $iconClass)
    {
        $htmlResult = "";
        foreach ($listFiltered as $m) {
            if (static::isGearLegal($morph, $m)) {
                $li = new Li($m->name, 'morphGear');
                $li->addCost($m->getCost(), $m->isInArray($_SESSION['cc']->getCurrentDefaultMorphGear($morph)), 'cr');
                $li->addBookIcon($m->name);
                $li->addPlusChecked($iconClass, $_SESSION['cc']->haveGearOnMorph($m, $morph));
                $htmlResult .= $li->getHtml();
            }
        }
        return $htmlResult;
    }

    /**
     * Add/Display/Remove free gear for both morph and Ego.
     */
    static function getFreeGear($currentGear, $isEgo = true)
    {
        $output       = "";
        $ego_or_morph = "Ego";
        if (!$isEgo) {
            $ego_or_morph = "Morph";
        }

        $output .= "<li class='foldingListSection' id='free'>";
        $output .= "Free Gear";
        $output .= "</li>";
        $output .= "<ul class='mainlist foldingList free' id='freeGear'>";
        $output .= "    <li>";
        $output .= "            <input type='text' id='free" . $ego_or_morph . "GearToAdd' placeholder='Gear Name'/>";
        $output .= "            <select id='free" . $ego_or_morph . "GearPrice'>";
        $output .= "                    <option value=" . EPCreditCost::$LOW . ">" . EPCreditCost::$LOW . "</option>";
        $output .= "                    <option value=" . EPCreditCost::$MODERATE . ">" . EPCreditCost::$MODERATE . "</option>";
        $output .= "                    <option value=" . EPCreditCost::$HIGH . ">" . EPCreditCost::$HIGH . "</option>";
        $output .= "                    <option value=" . EPCreditCost::$EXPENSIVE . ">" . EPCreditCost::$EXPENSIVE . "</option>";
        $output .= "                    <option value=" . EPCreditCost::$VERY_EXPENSIVE . ">" . EPCreditCost::$VERY_EXPENSIVE . "</option>";
        $output .= "                    <option value=" . EPCreditCost::$EXTREMELY_EXPENSIVE . ">" . EPCreditCost::$EXTREMELY_EXPENSIVE . "</option>";
        $output .= "            </select>";
        $output .= "            <span class='addOrSelectedIcon' id='addFree" . $ego_or_morph . "Gear' data-icon='&#x3a;'></span>";
        $output .= "    </li>";
        foreach ($currentGear as $m) {
            if ($m->gearType == EPGear::$FREE_GEAR) {
                $li = new Li($m->name);
                $li->addCost($m->getCost(), false, 'Credits');
                $li->addPlusX("remFree" . $ego_or_morph . "Gear", false);
                $output .= $li->getHtml();
            }
        }
        $output .= "</ul>";
        return $output;
    }

    /**
     * Outputs a 'foldingListSection' for gear of a certain type.
     */
    static function getGearSection($gears, $morph, $gearType, $sectionName)
    {
        //Generate a HTML valid Id from the section name
        $id = preg_replace("/[^A-z]/", "", $sectionName);

        $listFiltered = array();
        foreach ($gears as $m) {
            if ($m->gearType == $gearType) {
                array_push($listFiltered, $m);
            }
        }
        $formatedHtml = static::getFormatedMorphGearList($listFiltered, $morph, 'addSelMorphGearIcon');

        $output = "";
        $output .= "<li class='foldingListSection' id='" . $id . "'>";
        $output .= $sectionName;
        $output .= "</li>";
        $output .= "<ul class='mainlist foldingList " . $id . "''>";
        $output .= $formatedHtml;
        $output .= "</ul>";
        return $output;
    }

    static function isGearLegal($morph, $gear)
    {
        //Removed so infomorphs can buy gear
        //if($morph->morphType == EPMorph::$INFOMORPH)
        //    return false;
        if ($gear->gearRestriction == EPGear::$CAN_USE_EVERYBODY) {
            return true;
        } //this check hides gear that you want to exist, but not render on the list
        else {
            if ($gear->gearRestriction == EPGear::$CAN_USE_CREATE_ONLY) {
                return false;
            } else {
                if ($gear->gearRestriction == EPGear::$CAN_USE_BIO) {
                    if ($morph->morphType == EPMorph::$BIOMORPH) {
                        return true;
                    }
                } else {
                    if ($gear->gearRestriction == EPGear::$CAN_USE_SYNTH) {
                        if ($morph->morphType == EPMorph::$SYNTHMORPH) {
                            return true;
                        }
                    } else {
                        if ($gear->gearRestriction == EPGear::$CAN_USE_POD) {
                            if ($morph->morphType == EPMorph::$PODMORPH) {
                                return true;
                            }
                        } else {
                            if ($gear->gearRestriction == EPGear::$CAN_USE_BIO_POD) {
                                if ($morph->morphType == EPMorph::$BIOMORPH || $morph->morphType == EPMorph::$PODMORPH) {
                                    return true;
                                }
                            } else {
                                if ($gear->gearRestriction == EPGear::$CAN_USE_SYNTH_POD) {
                                    if ($morph->morphType == EPMorph::$SYNTHMORPH || $morph->morphType == EPMorph::$PODMORPH) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    static function getDynamicTraitLi($trait, $currentTraits, $defaultTraits, $traitClass, $iconClass)
    {
        if ($currentTraits == null) {
            $currentTraits = array();
        }
        if ($defaultTraits == null) {
            $defaultTraits = array();
        }

        $li = new Li($trait->name, $traitClass);
        $li->addCost($trait->cpCost, $trait->isInArray($defaultTraits));
        $li->addBookIcon($trait->name);
        $li->addPlusChecked($iconClass, $trait->isInArray($currentTraits));
        return $li->getHtml();
    }

    static function isTraitLegal($morph, $trait)
    {
        if ($morph->morphType == EPMorph::$INFOMORPH) {
            return false;
        }

        if ($trait->canUse == EPTrait::$CAN_USE_EVERYBODY) {
            return true;
        } else {
            if ($trait->canUse == EPTrait::$CAN_USE_BIO) {
                if ($morph->morphType == EPMorph::$BIOMORPH) {
                    return true;
                }
            } else {
                if ($trait->canUse == EPTrait::$CAN_USE_SYNTH) {
                    if ($morph->morphType == EPMorph::$SYNTHMORPH) {
                        return true;
                    }
                } else {
                    if ($trait->canUse == EPTrait::$CAN_USE_POD) {
                        if ($morph->morphType == EPMorph::$PODMORPH) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }


    /**
     * Get the section handling Bonuses / Detriments.
     *
     * @param $parentType - Good values are (origine, faction, trait, morph, morphTrait)
     * @return string The HTML of multiple \<li>s
     */
    static function getBMHtml($bonusMalusArray, $parentName, $parentType)
    {
        $output = "";

        //GRANTED BM
        if (static::grantedExist($bonusMalusArray)) {
            $output .= "<li class='listSection'>";
            $output .= "Bonuses / Detriments Granted";
            $output .= "</li>";
            foreach ($bonusMalusArray as $bm) {
                if ($bm->isGranted()) {
                    $output .= "<li class='bmDesc'>";
                    $output .= $bm->name;
                    if ($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY) {
                        $output .= "<label class='bmGrantedDesc'>" . $bm->description . "</label>";
                    }
                    $output .= "</li>";
                }
            }
        }
        //CHOICE BM
        if (static::choiceExist($bonusMalusArray)) {
            $output .= "<li class='listSection'>";
            $output .= "Bonuses / Detriments Requiring Selection";
            $output .= "</li>";
            foreach ($bonusMalusArray as $bm) {
                if ($bm->isChoice()) {
                    $output .= "<li><label class='bmChoiceInput'>";
                    $output .= static::choosePrintOption($bm, $parentName, $parentType);
                    $output .= "<input id='" . $bm->getUid() . "Parent' type='hidden' value='" . $parentName . "'>";
                    $output .= "<input id='" . $bm->getUid() . "Type' type='hidden' value='" . $parentType . "'>";
                    $output .= "<input id='" . $bm->getUid() . "BmName' type='hidden' value='" . $bm->name . "'>";
                    $output .= "</label></li>";
                }
            }
        }
        //Multiple Choice BM
        foreach ($bonusMalusArray as $bm) {
            if ($bm->isMultipleChoice()) {
                $output .= "<li class='listSection'>";
                $output .= "Choose <span class='betweenPlusMinus'>" . static::getSelectedOnMulti($bm) . " / " . $bm->multi_occurence . "</span>";
                $output .= "</li>";
                // If all the selections are made, only print out the selected BMs
                if (static::getSelectedOnMulti($bm) == $bm->multi_occurence) {
                    foreach ($bm->bonusMalusTypes as $bmMulti) {
                        if ($bmMulti->selected) {
                            $output .= "<li><label class='bmChoiceInput'>";
                            if ($bmMulti->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX) {
                                $activeSkills    = $_SESSION['cc']->character->ego->getActiveSkills();
                                $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
                                $skill           = EPAtom::getAtomByUid(array_merge($activeSkills, $knowledgeSkills),
                                    $bmMulti->forTargetNamed);
                                $output          .= "+" . $bmMulti->value . " " . $skill->getPrintableName();
                            } else {
                                if ($bmMulti->targetForChoice == EPBonusMalus::$ON_APTITUDE) {
                                    $output .= "+" . $bmMulti->value . " on " . $bmMulti->forTargetNamed;
                                } else {
                                    if ($bmMulti->targetForChoice == EPBonusMalus::$ON_REPUTATION) {
                                        $output .= "+" . $bmMulti->value . " on " . $bmMulti->forTargetNamed;
                                    } else {
                                        $output .= $bmMulti->name;
                                    }
                                }
                            }
                            $output .= "<span class='iconPlusMinus iconebmRemChoice' id='" . $bmMulti->getUid() . "' data-icon='&#x39;'></span>";
                            $output .= "</label>";
                            $output .= "<input id='" . $bmMulti->getUid() . "MultiName' type='hidden' value='" . $bmMulti->name . "'>";
                            $output .= "<input id='" . $bmMulti->getUid() . "ParentId' type='hidden' value='" . $bm->getUid() . "'>";
                            $output .= "</li>";
                        }
                    }
                } //If there are still selections remaining
                else {
                    foreach ($bm->bonusMalusTypes as $bmMulti) {
                        $output .= "<li>";
                        if (!$bmMulti->isChoice()) {
                            $output .= "<label class='bmGranted'>" . $bmMulti->name . "</label>";
                            if ($bmMulti->selected) {
                                $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bmMulti->getUid() . "' data-icon='&#x39;'></span>";
                            } else {
                                $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bmMulti->getUid() . "' data-icon='&#x3a;'></span>";
                            }
                            $output .= "<input id='" . $bmMulti->getUid() . "Sel' type='hidden' value='" . $bmMulti->forTargetNamed . "'>";
                        } else {
                            $output .= "<label class='bmChoiceInput'>";
                            $output .= static::choosePrintOption($bmMulti, $parentName, $parentType);
                            $output .= "</label>";
                        }
                        $output .= "<input id='" . $bmMulti->getUid() . "MultiName' type='hidden' value='" . $bmMulti->name . "'>";
                        $output .= "<input id='" . $bmMulti->getUid() . "ParentId' type='hidden' value='" . $bm->getUid() . "'>";
                        $output .= "</li>";
                    }
                }
                $output .= "<li>";
                $output .= "		<label class='listSectionClose'>-</label>";
                $output .= "</li>";
                $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$MULTIPLE . "'>";
                $output .= "<input id='" . $bm->getUid() . "Parent' type='hidden' value='" . $parentName . "'>";
                $output .= "<input id='" . $bm->getUid() . "Type' type='hidden' value='" . $parentType . "'>";
                $output .= "<input id='" . $bm->getUid() . "BmName' type='hidden' value='" . $bm->name . "'>";
            }
        }
        return $output;
    }

    /**
     * Choose which item to print based on the BM type.
     */
    static function choosePrintOption($bm, $parentName, $parentType)
    {
        $activeSkills    = $_SESSION['cc']->character->ego->getActiveSkills();
        $knowledgeSkills = $_SESSION['cc']->character->ego->getKnowledgeSkills();
        if ($bm->targetForChoice == EPBonusMalus::$ON_SKILL_WITH_PREFIX) {
            return static::getSkillOptions($bm, array_merge($activeSkills, $knowledgeSkills), true);
        } else {
            if ($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE) {
                return static::getSkillOptions($bm, $activeSkills);
            } else {
                if ($bm->targetForChoice == EPBonusMalus::$ON_SKILL_KNOWLEDGE) {
                    return static::getSkillOptions($bm, $knowledgeSkills);
                } else {
                    if ($bm->targetForChoice == EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE) {
                        return static::getSkillOptions($bm, array_merge($activeSkills, $knowledgeSkills));
                    } else {
                        if ($bm->targetForChoice == EPBonusMalus::$ON_APTITUDE) {
                            return static::getAptitudeOptions($bm, $parentName, $parentType);
                        } else {
                            if ($bm->targetForChoice == EPBonusMalus::$ON_REPUTATION) {
                                return static::getReputationOptions($bm);
                            }
                        }
                    }
                }
            }
        }
        error_log("choosePrintOption:  $bm->targetForChoice (" . $bm->targetForChoice . ") is unkown!");
        return "choosePrintOption:  $bm->targetForChoice (" . $bm->targetForChoice . ") is unkown!";
    }

    /**
     * Get the options to select/deselect a skill
     */
    static function getSkillOptions($bm, $skill_list, $prefix_skill = false)
    {
        //Handle Prefix only skill selection
        if ($prefix_skill == true && !empty($bm->typeTarget)) {
            $skill_list = static::skillsWithPrefix($skill_list, $bm->typeTarget);
        }

        $output = "";

        if ($bm->forTargetNamed == null || $bm->forTargetNamed == "") {
            $output .= $bm->name;
            if (!empty($skill_list)) {
                $output .= "<select class='bmChoiceSelect' id='" . $bm->getUid() . "Sel'>";
                foreach ($skill_list as $skill) {
                    $output .= "<option value='" . $skill->getUid() . "'>" . $skill->getPrintableName() . "</option>";
                }
                $output .= "</select>";
                $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bm->getUid() . "' data-icon='&#x3a;'></span>";
            } else {
                $output .= "Please create an appropriate skill.";
            }
        } else {
            //If a skill has already been selected, display the deselect option
            $skill  = EPAtom::getAtomByUid($skill_list, $bm->forTargetNamed);
            $output .= "+" . $bm->value . " " . $skill->getPrintableName();
            $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";

        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_SKILL . "'>";
        return $output;
    }

    /**
     * Get the options to select/deselect an aptitude
     */
    static function getAptitudeOptions($bm, $parentName, $parentType)
    {
        $output = "";

        if ($bm->forTargetNamed == null || $bm->forTargetNamed == "") {
            $output .= $bm->name;
            $output .= "<select id='" . $bm->getUid() . "Sel'>";
            if ($parentType == 'morph') {
                $morph = $_SESSION['cc']->getMorphByName($parentName);
                if (!empty($morph)) {
                    $banedAptNameList = $_SESSION['cc']->getMorphGrantedBMApptitudesNameList($morph);
                    foreach ($_SESSION['cc']->getAptitudes() as $apt) {
                        if (!static::isNameOnList($apt->name, $banedAptNameList)) {
                            $output .= "<option value='" . $apt->name . "'>" . $apt->name . "</option>";
                        }
                    }
                }
            } else {
                foreach ($_SESSION['cc']->getAptitudes() as $apt) {
                    $output .= "<option value='" . $apt->name . "'>" . $apt->name . "</option>";
                }
            }
            $output .= "</select>";
            $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bm->getUid() . "' data-icon='&#x3a;'></span>";
        } else {
            //If an aptitude has already been selected, display the deselect option
            $output .= "+" . $bm->value . " on " . $bm->forTargetNamed;
            $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";

        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_APTITUDE . "'>";
        return $output;
    }

    /**
     * Get the options to select/deselect a reputation
     */
    static function getReputationOptions($bm)
    {
        $output = "";

        if ($bm->forTargetNamed == null || $bm->forTargetNamed == "") {
            $output .= $bm->name;
            $output .= "<select id='" . $bm->getUid() . "Sel'>";
            foreach ($_SESSION['cc']->getReputations() as $apt) {
                $output .= "<option value='" . $apt->name . "'>" . $apt->name . "</option>";
            }
            $output .= "</select>";
            $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bm->getUid() . "' data-icon='&#x3a;'></span>";
        } else {
            //If a reputation has already been selected, display the deselect option
            $output .= "+" . $bm->value . " on " . $bm->forTargetNamed;
            $output .= "<span class='iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";
        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_REPUTATION . "'>";
        return $output;
    }

    static function grantedExist($bmArray)
    {
        foreach ($bmArray as $bm) {
            if ($bm->isGranted()) {
                return true;
            }
        }
        return false;
    }

    static function choiceExist($bmArray)
    {
        foreach ($bmArray as $bm) {
            if ($bm->isChoice()) {
                return true;
            }
        }
        return false;
    }

//All the skills in an array that have a certain prefix
    static function skillsWithPrefix($skillArray, $prefix)
    {
        $outArray = array();
        foreach ($skillArray as $skill) {
            if ($skill->prefix == $prefix) {
                array_push($outArray, $skill);
            }
        }
        return $outArray;
    }

    static function isNameOnList($name, $list)
    {
        foreach ($list as $s) {
            if ($name == $s) {
                return true;
            }
        }
        return false;
    }

    static function getSelectedOnMulti($bmMulti)
    {
        $count = 0;
        foreach ($bmMulti->bonusMalusTypes as $bm) {
            if ($bm->selected) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Print out the html for a book reference
     *
     * This is designed to be slotted in with other elements in an unordered list.
     */
    static function getBPHtml($atomName){
        $book = new EPBook($atomName);
        $output = "<li class='listSection'>";
        $output .= "Find more at";
        $output .= "</li>";
        $output .= "<li class='bmDesc'>";
        $output .= $book->getPrintableNameL();
        $output .= "</li>";
        return $output;
    }

    /**
     * Get a book's icon
     */
    static function getListStampHtml($atomName){
        $book = new EPBook($atomName);
        return "<span class='bookIcon ".$book->getAbbreviation()."'></span>";
    }
}