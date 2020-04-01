<?php
//declare(strict_types=1);

use App\Creator\EPFileUtility;
use App\Creator\EPBook;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPMorph;
use App\Creator\Atoms\EPPsySleight;
use App\Creator\Atoms\EPTrait;

if(null !== creator()) {
    $file_util = new EPFileUtility(creator()->character);
    $filename = $file_util->buildExportFilename('EPCharacter', 'txt');

    header("Content-type: text/plain");
//    header('Content-Disposition: attachment; filename="'.$filename.'"');

    //TXT EXPORT ================================================================
    //TODO:  Convert this to a blade template
    //formatResult(...) is equivalent to <span style='min-width: 25ch;'></span>
    //So all of those formatX functions can be replaced with <span class='result'> tags and some css
    //TODO:  Figure out what to do about tabs
    //Multiple spaces are just more span tags

    //EGO ================================================================

    $character = creator()->character;
    $characterName = $character->charName;
    $playerName = $character->playerName;
    try{$backgroundName = creator()->getCurrentBackground()->getName();} catch (Error $e){$backgroundName = '';};
    try{$factionName = creator()->getCurrentFaction()->getName();} catch (Error $e){$factionName = '';};
    $birthGender =  toUpper($character->birthGender);
    $realAge =  toUpper($character->realAge);
    $credits = toUpper(creator()->getCredit());
    $aptitudes = creator()->getAptitudes();
    $reputations = creator()->getReputations();
    $motivations = creator()->getMotivations();
    $skillList = creator()->getSkills();
    $egoNegTraits = EPTrait::getNegativeTraits(creator()->character->ego->getTraits());
    $egoPosTraits = EPTrait::getPositiveTraits(creator()->character->ego->getTraits());
    $psySleights = creator()->getCurrentPsySleights();
    $softGears = creator()->getEgoSoftGears();
    $ais = creator()->getEgoAi();
    $egoBonusMalus = creator()->getBonusMalusEgo();

    //EGO TITLE
    output("<br>====================================== EGO ==========================================<br>"
    //NAMES
    ."Name<tab>: "
    .formatResult($characterName)
    ."<br>Player<tab>: "
    .formatResult($playerName)
    ."<br><hr><br>"

    //ORIGINS
    ."Background<tab>: "
    .formatResult($backgroundName)
    ."<tab>"
    .setBookLink($backgroundName)
    ."<br>Faction<tab><tab>: "
    .formatResult($factionName)
    ."<tab>"
    .setBookLink($factionName)
    ."<br><hr><br>"

    //AGE - SEX
    ."Birth Gender<tab>: "
    .formatResult($birthGender)
    ."<br>Real Age<tab>: "
    .formatResult($realAge)
    ."<br><hr><br>"

    //CREDIT
    ."Credit<tab>: "
    .formatResult($credits)
    ."<tab>   (EP p.137)"
    ."<br><hr><br>"

    //EGO APTITUDES
    ."Aptitudes<tab>   (EP p.122)<br><br>");

    foreach($aptitudes as $apt){
        output(formatTitle($apt->getName())
        ."<tab>"
        .formatResult($apt->value)//Apt Value Ego
        ."<br>");
    }

    output("<hr><br>"

    //REPUTATION
    ."Reputations<tab>   (EP p.285)<br><br>");

    foreach($reputations as $rep){
        output(formatTitle($rep->getName())
        ."<tab>"
        .formatResult($rep->value)//Rep Value
        ."<br>");
    }

    output("<hr><br>"

    //MOTIVATION
    ."Motivations<tab>   (EP p.120)<br><br>");

    foreach($motivations as $mot){
        output(formatResult($mot)
        ."<br>");
    }

    output("<hr><br>"

    //NOTES
    ."Notes<br><br>"
    .formatResult($character->note)
    ."<br><hr><br>"

    //EGO SKILLS
    ."Ego Skills<tab>   (EP p.176)<br><br>");

    foreach($skillList as $skill){

        $skillCompleteName = $skill->getPrintableName();
        $skillType = "A";
        if($skill->isKnowledge()) {
            $skillType = "K";

            if($skill->getValue() == 0)
                continue;
        }

        output(formatResult($skillType." ".$skillCompleteName)
        ."<tab>"
        .$skill->linkedAptitude->getAbbreviation()
        ."<tab><tab>");

        if(!empty($skill->specialization)){
            echo formatResult($skill->getEgoValue()."  SPE[".$skill->specialization."]");//Skill speci
        }
        else
        {
            echo formatResult($skill->getEgoValue());
        }

        output("<br>");
    }

    output("<hr><br>"

    //EGO NEG TRAIT
    ."Ego Negative traits<br><br>");

    foreach($egoNegTraits as $trait){
        output(formatResult($trait->getName())
        ."<tab>"
        .setBookLink($trait->getName())
        ."<br>");
    }

    output("<hr><br>"

    //EGO POS TRAIT
    ."Ego Positive traits<br><br>");

    foreach($egoPosTraits as $trait){
         output(formatResult($trait->getName())
        ."<tab>"
        .setBookLink($trait->getName())
        ."<br>");
    }

    output("<hr><br>"

    //PSI SLEIGHTS
    ."Psi Sleights<br><br>");
    /**
     * @var EPPsySleight $sleight
     */
    foreach($psySleights as $sleight){
         $type = "(P)";
         if($sleight->isActive()) {
             $type = "(A)";
         }
         output(formatResult($type." ".$sleight->getName())
         ."<tab>"
        .setBookLink($sleight->getName())
        ."<br>");
    }

    output("<hr><br>"

    //SOFT GEAR
    ."Soft Gear<br><br>");

    foreach($softGears as $gear){
        $occ = "";
        if($gear->getOccurrence() > 1) {
            $occ = "(".$gear->getOccurrence().") ";
        }
        output(formatResult($occ." ".$gear->getName())
        ."<tab>"
        .setBookLink($gear->getName())
        ."<br>");
    }

    output("<hr><br>"


    //AI
    ."Ai<br><br>");

    foreach($ais as $ai){
        output(formatResult($ai->getName())
        ."<tab>"
        .setBookLink($ai->getName())
        ."<br><br>");

        $skillAptNonformated = "";
        foreach($ai->aptitudes as $aiApt){
            $skillAptNonformated .= $aiApt->getAbbreviation()."[";
            $skillAptNonformated .= $aiApt->value."]   <br>";
        }
        foreach($ai->skills as $aiSkill){
            $skillAptNonformated .= $aiSkill->getPrintableName() . "(";
            $skillAptNonformated .= $aiSkill->baseValue.")  <br>";
        }

        output(formatResult($skillAptNonformated)
        ."<br>");

    }

    output("<hr><br>"


    //All ego bonus malus
    ."Ego Memo<br><br>");

    foreach($egoBonusMalus as $bm) {
        output(formatResult($bm->getName())
        ."<br>"
        .formatResult($bm->getDescription())
        ."<br><br>");
    }

    output("<hr><br>");

    //MORPHS ============================================================

    $morphs = creator()->getCurrentMorphs();
    foreach($morphs as $morph){
        creator()->activateMorph($morph); //ACTIVATE THE MORPH
        //Morph Type
        if($morph->morphType == EPMorph::$BIOMORPH) $type = "[bio]";
        else if($morph->morphType == EPMorph::$SYNTHMORPH) $type = "[synth]";
        else if($morph->morphType == EPMorph::$INFOMORPH) $type = "[info]";
        else if($morph->morphType == EPMorph::$PODMORPH) $type = "[pod]";
        $morphName = $morph->getName();
        $morphNickname = toUpper($morph->nickname);
        $morphAge = toUpper($morph->age); //Apparent age of the morph
        $morphLocation = toUpper($morph->location);
        $morphGender = toUpper($morph->gender);
        $morphNegTraits = EPTrait::getNegativeTraits(creator()->getCurrentTraits($morph));
        $morphPosTraits = EPTrait::getPositiveTraits(creator()->getCurrentTraits($morph));

        //These change based on the currently selected morph
        //TODO: These (or a modifier) should be on the morph object itself.
        $stats = creator()->getStats();
        $aptitudes = creator()->getAptitudes();
        $skillList = creator()->getSkills();
        $morphGear = creator()->getGearForCurrentMorph();

        $morphBonusMalus = creator()->getBonusMalusForMorph($morph);
        $weapons = filterWeaponOnly($morphGear); //TODO: This filter should be on the EPAtom
        $armor = filterArmorOnly($morphGear); //TODO: This filter should be on the EPAtom
        $gear = filterGeneralOnly($morphGear); //TODO: This filter should be on the EPAtom
        $implants = filterImplantOnly($morphGear); //TODO: This filter should be on the EPAtom

        output("<br>====================================== MORPH ========================================<br>"

        //NAMES
        ."Morph Name<tab>: "
        .formatResult($morphName." ".$type)
        .setBookLink($morphName)
        ."<br>Nickname<tab>: "
        .formatResult($morphNickname)
        ."<br>Apparent Age<tab>: "
        .formatResult($morphAge)
        ."<br>Morph Gender<tab>: "
        .formatResult($morphGender)
        ."<br>Location<tab>: "
        .formatResult($morphLocation)
        ."<br>Player<tab>: "
        .formatResult($playerName)
        ."<br><hr><br>");

        //MORPH NEG TRAIT
        output("Morph Negative traits<br><br>");

        foreach($morphNegTraits as $trait){
            output(formatResult($trait->getName())
            ."<tab>"
            .setBookLink($trait->getName())
            ."<br>");
        }

        output("<hr><br>"

        //MORPH POS TRAIT
        ."Morph Positive traits<br><br>");

        foreach($morphPosTraits as $trait){
            output(formatResult($trait->getName())
            ."<tab>"
            .setBookLink($trait->getName())
            ."<br>");
        }

        output("<hr><br>"

        //APTITUDES
        ."Morph Aptitudes<tab>   (EP p.122)<br><br>"
        .formatResult("")
        ."<tab>BASE<tab>MORPH<tab>TOTAL<br>");

        foreach($aptitudes as $apt){
            output(formatTitle($apt->getName())
            ."<tab>"
            .$apt->value # base
            ."<tab>"
            .$apt->morphMod
            ."<tab>"
            .formatResult($apt->getValue())//Apt Value Morph
            ."<br>");
        }

        output("<hr><br>"

        //MORPH STATS
        ."Morp Stats<tab>   (EP p.121)<br><br>");

        foreach($stats as $stat) {
            output(formatResult($stat->getName())
            ."<tab>"
            .$stat->getValue()
            ."<br>");
        }

        output("<hr><br>"

        //SKILLS
        ."Morph Skills<tab>   (EP p.176)<br><br>"
        .formatResult("")
        ."<tab>APT<tab>EGO<tab>MORPH<br>");

        foreach($skillList as $skill){

            $skillCompleteName = $skill->getPrintableName();
            $skillType = "A";

            # ignore knowledge skills with an empty value as the player didn't pick the skill
            if($skill->isKnowledge()) {
                $skillType = "K";

                if($skill->getValue() == 0)
                    continue;
            }

            output(formatResult($skillType." ".$skillCompleteName)
            ."<tab>"
            .$skill->linkedAptitude->getAbbreviation()
            ."<tab>"
            .$skill->getEgoValue()
            ."<tab>");

            if(!empty($skill->specialization)){
                echo formatResult($skill->getValue()."  SPE[".$skill->specialization."]");//Skill speci
            }
            else
            {
                echo formatResult($skill->getValue());
            }

            output("<br>");
        }

        output("<hr><br>"

        //WEAPONS

        ."Weapons<br><br>");

        foreach($weapons as $w){
            $type = "kinetic";
            if($w->gearType == EPGear::$WEAPON_ENERGY_GEAR) $type="energy";
            if($w->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR) $type="explos.";
            if($w->gearType == EPGear::$WEAPON_SPRAY_GEAR) $type="spray";
            if($w->gearType == EPGear::$WEAPON_SEEKER_GEAR) $type="seeker";
            if($w->gearType == EPGear::$WEAPON_AMMUNITION) $type="ammo";
            if($w->gearType == EPGear::$WEAPON_MELEE_GEAR) $type="melee";
            if($w->getOccurrence() > 1) $occ = "(".$w->getOccurrence().") ";
            else $occ = "";

            $damage = $w->damage?? "0";
            $ap = $w->armorPenetration?? "0";
            output(formatResultXL("[".$type."] ".$occ.$w->getName()."  "."DV: ". $damage ."  "."AP : ". $ap)//Weapon type
            ."<tab>"
            .setBookLink($w->getName())
            ."<br>");
        }

        output("<hr><br>"

        //ARMORS

        ."Armor<br><br>");

        $protectionKinetic = 0;
        $protectionEnergy = 0;

        foreach($armor as $a){
            if($a->getOccurrence() > 1) $occ = "(".$a->getOccurrence().") ";
            else $occ = "";
            $protec = "";
            if($a->armorKinetic == 0 && $a->armorEnergy==0){
                $protec = "see memo";//No protec, see memeo
            }
            else{
                $protec = "Kin: ". formatNumber($a->armorKinetic) . "  Ene: " . formatNumber($a->armorEnergy);

                $protectionKinetic += $a->armorKinetic;
                $protectionEnergy += $a->armorEnergy;
            }
            output(formatResult($occ.$a->getName() . ($a->isImplant() ? " (Implant)" : ""))//armor
            ."<tab>"
            .$protec
            ."<tab>"
            .setBookLink($a->getName())
            ."<br>");
        }

        # total protection
        if($protectionKinetic > 0 || $protectionEnergy > 0) {
            output(formatResult("")
            ."<tab>"
            ."Kin: " . formatNumber($protectionKinetic) . "  Ene: " . formatNumber($protectionEnergy)
            ."<br>");
        }

        output("<hr><br>"

        //GEAR
        ."Gears<br><br>");

        foreach($gear as $g){
            if($g->getOccurrence() > 1) $occ = "(".$g->getOccurrence().") ";
            else $occ = "";

            output(formatResult($occ." ".$g->getName())
            ."<tab>"
            .setBookLink($g->getName())
            ."<br>");
        }

        output("<hr><br>"

        //IMPLANTS
        ."Implants<br><br>");

        foreach($implants as $i){
            if($i->getOccurrence() > 1) $occ = "(".$i->getOccurrence().") ";
            else $occ = "";

            output(formatResult($occ.$i->getName())
                ."<tab>"
            .setBookLink($i->getName())
            ."<br>");
        }

        output("<hr><br>"



        //MEMO (all ego bonus malus descriptive only)
        ."Morph Memo<br><br>");

        foreach($morphBonusMalus as $bm){

            output(formatResult($bm->getName())
            ."<br>"
            .formatResult($bm->getDescription())
            ."<br><br>");
        }

        output("<hr><br>");
}


	}
	//NO CHARACTER CREATOR ! ================================================
	else{
        header("Status: 500 Internal Server Error", true, 500);
        echo "Bad news, something went wrong, we can not print your character, verify your character and try again.";
        die;
	}



	//HELPERS ===============================================================

	function formatTitle($string){
		return padString($string, 12);
	}

	function formatResult($string){
		return padString($string, 25);
	}

	function formatResultLong($string){
		return padString($string, 40);
	}

	function formatResultXL($string){
		return padString($string, 60);
	}

	function formatNumber($string) {
		return padString($string, 3);
	}

	function padString($string, $pad) {
		$res = $string;
		if($res == null) $res = " ";
		$res = str_pad($res, $pad);
		return $res;
	}

	function setBookLink($atomeName){
        $book = new EPBook($atomeName);
		return $book->getPrintableName();
	}

/**
 * Basically print with a little pre-processing to let me use some basic html tags
 *
 * There are a few extra tags which don't exist, but make string formating so much easier, so were added.
 * @param string $outString
 */
function output(string $outString) {
        $line = "-------------------------------------------------------------------------------------";

        $outString = str_replace('<br>', "\r\n", $outString);
        $outString = str_replace('<hr>', $line, $outString);
        //These aren't real html tags, but make life easier
        $outString = str_replace('<tab>', "\t", $outString);
        print($outString);
    }
