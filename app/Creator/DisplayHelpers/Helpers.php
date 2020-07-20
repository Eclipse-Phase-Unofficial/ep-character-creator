<?php
declare(strict_types=1);

namespace App\Creator\DisplayHelpers;

use App\Creator\Atoms\EPAtom;
use App\Creator\Atoms\EPSkill;
use App\Creator\EPBook;
use App\Creator\EPCharacterCreator;
use App\Creator\EPCreditCost;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPMorph;
use App\Creator\Atoms\EPTrait;
use App\Creator\Atoms\EPBonusMalus;

class Helpers
{
    /**
     * Get the Gear list a morph can use.
     *
     * Filters out gear morphs can't use.
     * @param EPCharacterCreator $creator
     * @param EPGear[]           $gears
     * @param EPMorph            $morph
     * @param string             $iconClass
     * @return string
     */
    static function getFormatedMorphGearList(EPCharacterCreator $creator, array $gears, EPMorph $morph, string $iconClass)
    {
        $htmlResult = "";
        foreach ($gears as $gear) {
            if (static::isGearLegal($morph, $gear)) {
                $li = new Li($gear->getName(), 'morphGear');
                $li->addCost($gear->getCost(), $gear->isInArray($creator->getCurrentDefaultMorphGear($morph)), 'cr');
                $li->addBookIcon($gear->getName());
                $li->addPlusChecked($iconClass, $creator->haveGearOnMorph($gear, $morph));
                $htmlResult .= $li->getHtml();
            }
        }
        return $htmlResult;
    }

    /**
     * Add/Display/Remove free gear for both morph and Ego.
     * @param EPGear[] $currentGear
     * @param bool     $isEgo
     * @return string
     */
    static function getFreeGear(array $currentGear, bool $isEgo = true)
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
            if ($m->getModel()->isUserCreated()) {
                $li = new Li($m->getName());
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
     * @param EPCharacterCreator $creator
     * @param EPGear[]           $gears
     * @param EPMorph            $morph
     * @param string             $gearType
     * @param string             $sectionName
     * @return string
     */
    static function getGearSection(EPCharacterCreator $creator, array $gears, EPMorph $morph, string $gearType, string $sectionName)
    {
        //Generate a HTML valid Id from the section name
        $id = preg_replace("/[^A-z]/", "", $sectionName);

        $listFiltered = array();
        foreach ($gears as $m) {
            if ($m->getModel()->type == $gearType) {
                array_push($listFiltered, $m);
            }
        }
        $formatedHtml = static::getFormatedMorphGearList($creator, $listFiltered, $morph, 'addSelMorphGearIcon');

        $output = "";
        $output .= "<li class='foldingListSection' id='" . $id . "'>";
        $output .= $sectionName;
        $output .= "</li>";
        $output .= "<ul class='mainlist foldingList " . $id . "''>";
        $output .= $formatedHtml;
        $output .= "</ul>";
        return $output;
    }

    /**
     * If the morph can purchase that piece of gear.
     *
     * NOTE:  As designed, Infomorphs can buy all unrestricted gear.  They obviously can't use it, but could store it or give it to someone!
     * @param EPMorph $morph
     * @param EPGear  $gear
     * @return bool
     */
    static function isGearLegal(EPMorph $morph,EPGear $gear)
    {
        //Removed so infomorphs can buy gear
        //if($morph->morphType == EPMorph::$INFOMORPH)
        //    return false;
        switch($morph->morphType) {
            case EPMorph::$BIOMORPH:
                return $gear->getModel()->isAllowedBiomorph();
            case EPMorph::$PODMORPH:
                return $gear->getModel()->isAllowedPodmorph();
            case EPMorph::$SYNTHMORPH:
                return $gear->getModel()->isAllowedSynthmorph();
        }
        return true;
    }

    /**
     * @param EPTrait   $trait
     * @param EPTrait[] $currentTraits
     * @param EPTrait[] $defaultTraits
     * @param string    $traitClass
     * @param string    $iconClass
     * @return string HTML for an Li element
     */
    static function getDynamicTraitLi(EPTrait $trait, array $currentTraits, array $defaultTraits, string $traitClass, string $iconClass)
    {
        if ($currentTraits == null) {
            $currentTraits = array();
        }
        if ($defaultTraits == null) {
            $defaultTraits = array();
        }

        $li = new Li($trait->getName(), $traitClass);
        $li->addCost($trait->getCpCost(), $trait->isInArray($defaultTraits));
        $li->addBookIcon($trait->getName());
        $li->addPlusChecked($iconClass, $trait->isInArray($currentTraits));
        return $li->getHtml();
    }

    /**
     * If the morph can use that trait
     *
     * @param EPMorph $morph
     * @param EPTrait $trait
     * @return bool
     */
    static function isTraitLegal(EPMorph $morph, EPTrait $trait)
    {
        switch ($morph->morphType) {
            case EPMorph::$BIOMORPH:
                return $trait->canUseBiomorph();
            case EPMorph::$SYNTHMORPH:
                return $trait->canUseSynthmorph();
            case EPMorph::$PODMORPH:
                return $trait->canUsePodmorph();
            case EPMorph::$INFOMORPH:
                return false;
        }
        return $trait->canUseAllMorphs();
    }


    /**
     * Get the section handling Bonuses / Detriments.
     *
     * @param EPCharacterCreator $creator
     * @param EPBonusMalus[]     $bonusMalusArray
     * @param string             $parentName
     * @param string             $parentType - Good values are (origine, faction, trait, morph, morphTrait)
     * @return string The HTML of multiple \<li>s
     */
    static function getBMHtml(EPCharacterCreator $creator, array $bonusMalusArray, string $parentName, string $parentType)
    {
        $output = "";
        $morph  = null;
        if ($parentType === 'morph') {
            $morph = EpDatabase()->getMorphByName($parentName);
        }

        //GRANTED BM
        if (static::grantedExist($bonusMalusArray)) {
            $output .= "<li class='listSection'>";
            $output .= "Bonuses / Detriments Granted";
            $output .= "</li>";
            foreach ($bonusMalusArray as $bm) {
                if ($bm->isGranted()) {
                    $output .= "<li class='bmDesc'>";
                    $output .= $bm->getName();
                    if ($bm->getBonusMalusType() == EPBonusMalus::$DESCRIPTIVE_ONLY) {
                        $output .= "<label class='bmGrantedDesc'>" . $bm->getDescription() . "</label>";
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
                    $output .= static::choosePrintOption($creator, $bm, $morph, $parentType);
                    $output .= "<input id='" . $bm->getUid() . "Parent' type='hidden' value='" . $parentName . "'>";
                    $output .= "<input id='" . $bm->getUid() . "Type' type='hidden' value='" . $parentType . "'>";
                    $output .= "<input id='" . $bm->getUid() . "BmName' type='hidden' value='" . $bm->getName() . "'>";
                    $output .= "</label></li>";
                }
            }
        }
        //Multiple Choice BM
        foreach ($bonusMalusArray as $bm) {
            if ($bm->isMultipleChoice()) {
                $output .= "<li class='listSection'>";
                $output .= "Choose <span class='betweenPlusMinus'>" . static::getSelectedOnMulti($bm) . " / " . $bm->getRequiredSelections() . "</span>";
                $output .= "</li>";
                // If all the selections are made, only print out the selected BMs
                if (static::getSelectedOnMulti($bm) >= $bm->getRequiredSelections()) {
                    foreach ($bm->bonusMalusTypes as $bmMulti) {
                        if ($bmMulti->isSelected()) {
                            $output .= "<li><label class='bmChoiceInput'>";
                            switch ($$bmMulti->getTargetForChoice()) {
                                case EPBonusMalus::$ON_SKILL_WITH_PREFIX:
                                    $activeSkills    = $creator->character->ego->getActiveSkills();
                                    $knowledgeSkills = $creator->character->ego->getKnowledgeSkills();
                                    $skill           = EPAtom::getAtomByUid(array_merge($activeSkills, $knowledgeSkills),
                                        $bmMulti->getTargetName());
                                    $output          .= "+" . $bmMulti->getValue() . " " . $skill->getPrintableName();
                                    break;
                                case EPBonusMalus::$ON_APTITUDE:
                                case EPBonusMalus::$ON_REPUTATION:
                                    $output .= "+" . $bmMulti->getValue() . " on " . $bmMulti->getTargetName();
                                    break;
                                default:
                                    $output .= $bmMulti->getName();
                            }
                            $output .= "<span class='iconPlusMinus iconebmRemChoice' id='" . $bmMulti->getUid() . "' data-icon='&#x39;'></span>";
                            $output .= "</label>";
                            $output .= "<input id='" . $bmMulti->getUid() . "MultiName' type='hidden' value='" . $bmMulti->getName() . "'>";
                            $output .= "<input id='" . $bmMulti->getUid() . "ParentId' type='hidden' value='" . $bm->getUid() . "'>";
                            $output .= "</li>";
                        }
                    }
                } //If there are still selections remaining
                else {
                    foreach ($bm->bonusMalusTypes as $bmMulti) {
                        $output .= "<li>";
                        if (!$bmMulti->isChoice()) {
                            $output .= "<label class='bmGranted'>" . $bmMulti->getName() . "</label>";
                            if ($bmMulti->isSelected()) {
                                $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bmMulti->getUid() . "' data-icon='&#x39;'></span>";
                            } else {
                                $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bmMulti->getUid() . "' data-icon='&#x3a;'></span>";
                            }
                            $output .= "<input id='" . $bmMulti->getUid() . "Sel' type='hidden' value='" . $bmMulti->getTargetName() . "'>";
                        } else {
                            $output .= "<label class='bmChoiceInput'>";
                            $output .= static::choosePrintOption($creator, $bmMulti, $morph, $parentType);
                            $output .= "</label>";
                        }
                        $output .= "<input id='" . $bmMulti->getUid() . "MultiName' type='hidden' value='" . $bmMulti->getName() . "'>";
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
                $output .= "<input id='" . $bm->getUid() . "BmName' type='hidden' value='" . $bm->getName() . "'>";
            }
        }
        return $output;
    }

    /**
     * Choose which item to print based on the BM type.
     * @param EPCharacterCreator $creator
     * @param EPBonusMalus       $bm
     * @param EPMorph|null       $morph
     * @param string             $parentType
     * @return string
     */
    static function choosePrintOption(EPCharacterCreator $creator, EPBonusMalus $bm, ?EPMorph $morph, string $parentType)
    {
        $activeSkills    = $creator->character->ego->getActiveSkills();
        $knowledgeSkills = $creator->character->ego->getKnowledgeSkills();
        switch ($bm->getTargetForChoice()) {
            case EPBonusMalus::$ON_SKILL_WITH_PREFIX:
                return static::getSkillOptions($bm, array_merge($activeSkills, $knowledgeSkills), true);
            case EPBonusMalus::$ON_SKILL_ACTIVE:
                return static::getSkillOptions($bm, $activeSkills);
            case EPBonusMalus::$ON_SKILL_KNOWLEDGE:
                return static::getSkillOptions($bm, $knowledgeSkills);
            case EPBonusMalus::$ON_SKILL_ACTIVE_AND_KNOWLEDGE:
                return static::getSkillOptions($bm, array_merge($activeSkills, $knowledgeSkills));
            case EPBonusMalus::$ON_APTITUDE:
                return static::getAptitudeOptions($creator, $bm, $morph, $parentType);
            case EPBonusMalus::$ON_REPUTATION:
                return static::getReputationOptions($creator, $bm);
        }
        error_log("choosePrintOption:  " .$bm->getTargetForChoice() ." (" . $bm->getTargetForChoice() . ") is unkown!");
        return "choosePrintOption:  " . $bm->getTargetForChoice() . " (" . $bm->getTargetForChoice() . ") is unkown!";
    }

    /**
     * Get the options to select/deselect a skill
     * @param EPBonusMalus $bm
     * @param EPSkill[]    $skill_list
     * @param bool         $prefix_skill
     * @return string HTML for a Select box, or an error message
     */
    static function getSkillOptions(EPBonusMalus $bm, array $skill_list, bool $prefix_skill = false)
    {
        //Handle Prefix only skill selection
        if ($prefix_skill == true && !empty($bm->getTargetSkillPrefix())) {
            $skill_list = static::skillsWithPrefix($skill_list, $bm->getTargetSkillPrefix());
        }

        $output = "";

        if (empty($bm->getTargetName())) {
            $output .= $bm->getName();
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
            $skill  = EPAtom::getAtomByUid($skill_list, $bm->getTargetName());
            $output .= "+" . $bm->getValue() . " " . $skill->getPrintableName();
            $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";

        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_SKILL . "'>";
        return $output;
    }

    /**
     * Get the options to select/deselect an aptitude
     * @param EPCharacterCreator $creator
     * @param EPBonusMalus       $bm
     * @param EPMorph|null       $morph
     * @param string             $parentType
     * @return string
     */
    static function getAptitudeOptions(EPCharacterCreator $creator, EPBonusMalus $bm, ?EPMorph $morph, string $parentType)
    {
        $output = "";

        if (empty($bm->getTargetName())) {
            $output .= $bm->getName();
            $output .= "<select id='" . $bm->getUid() . "Sel'>";
            if ($parentType == 'morph' && !empty($morph)) {
                $banedAptNameList = $creator->getMorphGrantedBMApptitudesNameList($morph);
                foreach ($creator->getAptitudes() as $apt) {
                    if (!static::isNameOnList($apt->getName(), $banedAptNameList)) {
                        $output .= "<option value='" . $apt->getName() . "'>" . $apt->getName() . "</option>";
                    }
                }
            } else {
                foreach ($creator->getAptitudes() as $apt) {
                    $output .= "<option value='" . $apt->getName() . "'>" . $apt->getName() . "</option>";
                }
            }
            $output .= "</select>";
            $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bm->getUid() . "' data-icon='&#x3a;'></span>";
        } else {
            //If an aptitude has already been selected, display the deselect option
            $output .= "+" . $bm->getValue() . " on " . $bm->getTargetName();
            $output .= "<span class='iconPlusMinus iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";

        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_APTITUDE . "'>";
        return $output;
    }

    /**
     * Get the options to select/deselect a reputation
     * @param EPCharacterCreator $creator
     * @param EPBonusMalus       $bm
     * @return string
     */
    static function getReputationOptions(EPCharacterCreator $creator, EPBonusMalus $bm)
    {
        $output = "";

        if (empty($bm->getTargetName())) {
            $output .= $bm->getName();
            $output .= "<select id='" . $bm->getUid() . "Sel'>";
            foreach ($creator->getReputations() as $apt) {
                $output .= "<option value='" . $apt->getName() . "'>" . $apt->getName() . "</option>";
            }
            $output .= "</select>";
            $output .= "<span class='iconPlusMinus iconebmChoice'  id='" . $bm->getUid() . "' data-icon='&#x3a;'></span>";
        } else {
            //If a reputation has already been selected, display the deselect option
            $output .= "+" . $bm->getValue() . " on " . $bm->getTargetName();
            $output .= "<span class='iconebmRemChoice'  id='" . $bm->getUid() . "' data-icon='&#x39;'></span>";
        }
        $output .= "<input id='" . $bm->getUid() . "Case' type='hidden' value='" . EPBonusMalus::$ON_REPUTATION . "'>";
        return $output;
    }

    /**
     * @param EPBonusMalus[] $bmArray
     * @return bool
     */
    static function grantedExist(array $bmArray)
    {
        foreach ($bmArray as $bm) {
            if ($bm->isGranted()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param EPBonusMalus[] $bmArray
     * @return bool
     */
    static function choiceExist(array $bmArray)
    {
        foreach ($bmArray as $bm) {
            if ($bm->isChoice()) {
                return true;
            }
        }
        return false;
    }

    /**
     * All the skills in an array that have a certain prefix
     * @param EPSkill[] $skillArray
     * @param string    $prefix
     * @return EPSkill[]
     */
    static function skillsWithPrefix(array $skillArray, string $prefix)
    {
        $outArray = array();
        foreach ($skillArray as $skill) {
            if ($skill->getPrefixName() == $prefix) {
                array_push($outArray, $skill);
            }
        }
        return $outArray;
    }

    /**
     * @param string   $name
     * @param string[] $list
     * @return bool
     */
    static function isNameOnList(string $name, array $list)
    {
        foreach ($list as $s) {
            if ($name == $s) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param EPBonusMalus $bmMulti
     * @return int
     */
    static function getSelectedOnMulti(EPBonusMalus $bmMulti)
    {
        $count = 0;
        foreach ($bmMulti->bonusMalusTypes as $bm) {
            if ($bm->isSelected()) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Print out the html for a book reference
     *
     * This is designed to be slotted in with other elements in an unordered list.
     * @param string $atomName
     * @return string
     */
    static function getBPHtml(string $atomName){
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
     * @param string $atomName
     * @return string
     */
    static function getListStampHtml(string $atomName){
        $book = new EPBook($atomName);
        return "<span class='bookIcon ".$book->getAbbreviation()."'></span>";
    }
}
