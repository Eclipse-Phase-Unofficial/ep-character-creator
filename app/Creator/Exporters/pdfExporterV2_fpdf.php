<?php
//declare(strict_types=1);

namespace App\Creator\Exporters;

use App\Creator\Atoms\EPBonusMalus;
use App\Creator\Atoms\EPStat;
use App\Creator\EPFileUtility;
use App\Creator\EPBook;
use App\Creator\Atoms\EPGear;
use App\Creator\Atoms\EPMorph;
use App\Creator\Atoms\EPPsySleight;
use App\Creator\Atoms\EPSkill;
use App\Creator\Atoms\EPTrait;
use App\Creator\DisplayHelpers\FpdfCustomFonts;
use Error;

/*
    Overview of the PDF export

    Ego - 1 page

    Morph(s) - 1 page each
*/
class pdfExporterV2_fpdf {
    public function export(){
        if(empty(creator())) {
            return false;
        }
		$pdf = new FpdfCustomFonts();
		$ovf = new Overflow($pdf);

		//Disable automatic page breaks
        $pdf->SetAutoPageBreak(false);

        //Set some formatting to be used later
        $traitFormat = PdfHelpers::setTwoColFormat(30,15,1,8,7);        //For traits, and psi
        $skillFormat = PdfHelpers::setTwoColFormat(55,7,1,9,9);         //For skills

		//PDF EXPORT ================================================================


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
        $ais = creator()->getEgoAi();
        $egoBonusMalus = creator()->getBonusMalusEgo();
//         $egoBonusMalus = getDescOnlyBM($egoBonusMalus);

        $formattedSkills = $this->formatSkills($skillList,'getEgoValue');
        $formattedNegTraits = $this->formatGearData($egoNegTraits);
        $softGears = $this->formatGearData(creator()->getEgoSoftGears());
        $formattedPosTraits = $this->formatGearData($egoPosTraits);
        $formattedStats = $this->formatStats($aptitudes,'getEgoValue');

        //TODO: This is actually displayed on each morph page...
        $notes = $character->note;

        $pdf->AddPage('P', 'A4');//A4 EGO PAGE

				//SET BAGROUNT PNG-----------------------------
        $pdf->Image(public_path() . "/img/pdf/EP_BCK_PDF_EGO.png", 0, 0, -150);

				//DEFINE FONTS ---------------------------------
				$pdf->AddFont('Lato-Lig', '', 'Lato-Light.php');
				$pdf->AddFont('Lato-LigIta', '', 'Lato-LightItalic.php');
				$pdf->AddFont('Lato-Reg', '', 'Lato-Regular.php');

        //BEGIN FILLING SHEET------------------------------

        //NAMES
        $pdf->SetFont('Lato-Lig', '', 10);
        $pdf->Text(60, 12, $characterName);
        $pdf->Text(143, 12, $playerName);

        //ORIGINS
        $pdf->Text(37, 26, toUpper($backgroundName));
        $pdf->Text(37, 33, toUpper($factionName));

        $pdf->SetFont('Lato-LigIta', '', 7);
        $this->writeBookLink($backgroundName, 85, 27, $pdf);//Background bookLink
        $this->writeBookLink($factionName, 85, 34, $pdf);//Faction bookLink

        //AGE - SEX
        $pdf->SetFont('Lato-Lig', '', 10);
        $pdf->Text(143, 26,$birthGender); //Birth gender
        $pdf->Text(143, 33,$realAge); //Real age

        //CREDIT
        $pdf->SetFont('Lato-Lig', '', 10);
        $pdf->Text(10, 53, $credits); //Credit

        $pdf->SetFont('Lato-LigIta', '', 7);
        $pdf->Text(40, 49, "(EP p.137)");//Credit bookLink

        //EGO APTITUDES
        $pdf->Text(90, 49, "(EP p.122)");//Aptitudes bookLink

        $pdf->SetXY(58,50);
        $format = PdfHelpers::setTwoColFormat(30,10,2,10,10);
        PdfHelpers::writeTwoColumns($pdf,$formattedStats,$format,3.5,2);

        //REPUTATION
        $pdf->SetFont('Lato-LigIta', '', 7);
        $pdf->Text(138, 49, "(EP p.285)");//Reputation bookLink


        $pdf->SetXY(111,50);
        $format = PdfHelpers::setTwoColFormat(25,10,2,10,10);
        PdfHelpers::writeTwoColumns($pdf, $this->formatStats($reputations), $format,3.5,2);

        //MOTIVATION
        $pdf->SetFont('Lato-LigIta', '', 7);
        $pdf->Text(192, 49, "(EP p.120)");//Motivation bookLink

        $apt_x = 158;
        $pdf->SetFont('Lato-Lig', '', 10);
        $pdf->SetXY($apt_x,51);
        foreach($motivations as $mot)
        {
            $pdf->MultiCell(50, 3.5, toUpper($mot));//Motivations
            $pdf->SetX($apt_x);
        }

        //EGO SKILLS
        $pdf->SetFont('Lato-LigIta', '', 7);
        $pdf->Text(64, 81, "(EP p.176)");//Skills bookLink

        $pdf->SetXY(8,84);
        $this->writeTwoColumnsOvf($ovf,$pdf,$formattedSkills,$skillFormat,3.5,2,60,"Ego Skills Overflow");

        //EGO NEG TRAIT
        $pdf->SetXY(80,102);
        $format = PdfHelpers::setTwoColFormat(18,15,1,8,7);
        PdfHelpers::writeTwoColumns($pdf,$formattedNegTraits,$format,3);

        //EGO POS TRAIT
        $pdf->SetXY(116,102);
        $format = PdfHelpers::setTwoColFormat(25,15,1,8,7);
        PdfHelpers::writeTwoColumns($pdf,$formattedPosTraits,$format,3);

        //PSI SLEIGHTS
        $formattedPsi = array();
        foreach($psySleights as $sleight)
        {
            $book = new EPBook($sleight->getName());
            $item = array();
            //set the slight token to active or passive
            if($sleight->psyType == EPPsySleight::$ACTIVE_PSY) {
                $type = "(A) ";
            } else {
                $type = "(P) ";
            }

            $item[0] = toUpper($type . $sleight->getName());
            $item[2] = $book->getPrintableName();
            array_push($formattedPsi,$item);
        }
        $pdf->SetXY(158,102);
        PdfHelpers::writeTwoColumns($pdf,$formattedPsi,$traitFormat,3);

        //SOFT GEAR
        $pdf->SetXY(85,152);
        PdfHelpers::writeTwoColumns($pdf,$softGears,$traitFormat,3);

        //AI
        $apt_x = 132;
        $apt_y = 155;

        foreach($ais as $ai) {
            $pdf->SetFont('Lato-Lig', '', 8);
            $pdf->Text($apt_x, $apt_y, toUpper($ai->getName()));//ai name

            $pdf->SetFont('Lato-LigIta', '', 6);
            $this->writeBookLink($ai->getName(), ($apt_x + 14), ($apt_y + 2), $pdf);//ai bookLink

            $skillAptNonformated = "";
            foreach($ai->aptitudes as $aiApt) {
                $skillAptNonformated .= $aiApt->abbreviation . "[";
                $skillAptNonformated .= $aiApt->value . "]\n";
            }

            //construct a skill string for each skill
            foreach($ai->skills as $aiSkill) {
                $skillCompleteName = "";
                if(!empty($aiSkill->prefix))
                    $skillCompleteName = $aiSkill->prefix . " : ";

                $skillCompleteName .= $aiSkill->getName();
                $skillAptNonformated .= $skillCompleteName . "(";
                $skillAptNonformated .= $aiSkill->baseValue . ")\n";
            }

            $pdf->SetFont('Lato-LigIta', '', 7);
            $pdf->SetXY($apt_x + 27,$apt_y);
            $pdf->MultiCell(30,3,$skillAptNonformated);
            $pdf->Line($apt_x+27,$pdf->GetY(),$apt_x+57,$pdf->GetY());

            $apt_y = $pdf->GetY();
        }

        //MEMO (all ego bonus malus)
        $formattedMemo = $this->formatMemoData($egoBonusMalus);
        $pdf->SetXY(80,230);
        $format = PdfHelpers::setTwoColFormat(45,80,2,7,5);
        $this->writeTwoColumnsOvf($ovf,$pdf,$formattedMemo,$format,3,2,14,"Ego Memo Overflow");

        //END EGO PAGE

        //MORPHS ============================================================

        //DO ONE PAGE PER MORPH
        $morphs = creator()->getCurrentMorphs();
        foreach($morphs as $morph)
        {
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
            $skillList = creator()->getSkills();
            $stats = $this->formatStats(creator()->getStats());
            $aptitudes = $this->formatStats(creator()->getAptitudes());
            $morphGear = creator()->getGearForCurrentMorph();

            $morphBonusMalus = creator()->getBonusMalusForMorph($morph);

            $formattedNegTraits = $this->formatGearData($morphNegTraits);
            $formattedPosTraits = $this->formatGearData($morphPosTraits);
            $formattedSkills = $this->formatSkills($skillList,'getValue');
            $weapons = filterWeaponOnly($morphGear); //TODO: This filter should be on the EPAtom
            $armor = filterArmorOnly($morphGear); //TODO: This filter should be on the EPAtom
            $gear = filterGeneralOnly($morphGear); //TODO: This filter should be on the EPAtom
            $implants = filterImplantOnly($morphGear); //TODO: This filter should be on the EPAtom
            $formattedGear = $this->formatGearData($gear);
            $formattedImplants = $this->formatGearData($implants);
            $formattedMemo = $this->formatMemoData($morphBonusMalus);

            $pdf->AddPage('P', 'A4');//A4 MORPH

            //SET BAGROUNT PNG-----------------------------
            $pdf->Image(public_path() . "/img/pdf/EP_BCK_PDF_MORPH.png", 0, 0, -150);

            $pdf->SetFont('Lato-Lig', '', 8);

            //DETAILS DATA$skillList

            $pdf->Text(55, 11.5, toUpper($morphName . " " . $type));//morph Name type

            $pdf->SetFont('Lato-LigIta', '', 5);
            $this->writeBookLink($morphName, 105, 11.5, $pdf);//morph bookLink

            $pdf->SetFont('Lato-Lig', '', 8);
            $pdf->Text(140, 12, $morphNickname);//morph nickname
            $pdf->Text(50, 19, $morphAge);
            $pdf->Text(140, 19, $morphLocation);
            $pdf->Text(50, 26, $playerName);
            $pdf->Text(140, 26, $morphGender);

            //MORPH NEG TRAIT
            $pdf->SetXY(5,43);
            PdfHelpers::writeTwoColumns($pdf,$formattedNegTraits,$traitFormat,4);

            //MORPH POS TRAIT
            $pdf->SetXY(52,43);
            PdfHelpers::writeTwoColumns($pdf,$formattedPosTraits,$traitFormat,4);

            //MORPH STATS
            $pdf->SetFont('Lato-LigIta', '', 7);
            $pdf->Text(118, 40, "(EP p.121)");//Stats bookLink

            $pdf->SetXY(102,43);
            $format = PdfHelpers::setTwoColFormat(28,7,1,7,7);
            PdfHelpers::writeTwoColumns($pdf,$stats,$format,3.5,2);

            //MORPH APTITUDES
            $pdf->SetFont('Lato-LigIta', '', 7);
            $pdf->Text(173, 40, "(EP p.122)");//Aptitude bookLink

            $pdf->SetXY(142,43);
            $format = PdfHelpers::setTwoColFormat(30,10,2,10,10);
            PdfHelpers::writeTwoColumns($pdf,$aptitudes,$format,3.5,2);

            //MORPH SKILLS
            $pdf->SetFont('Lato-LigIta', '', 7);
            $pdf->Text(64, 79, "(EP p.176)");//Skills bookLink
            $pdf->SetXY(8,84);
            $this->writeTwoColumnsOvf($ovf,$pdf,$formattedSkills,$skillFormat,3.5,2,60,"Morph Skills Overflow");

            //NOTES
            $apt_x = 81;
            $apt_y = 81;
            $pdf->SetFont('Lato-Lig', '', 5);
            $pdf->SetXY($apt_x,$apt_y);
            $pdf->MultiCell(95,2, $notes,0,'l');

            //WEAPONS
            $apt_x = 83;

            //if more than 8 weapon, change fontsize and spaces
            if(count($weapons) <= 8)
            { //default
                $fontsize = 8;
                $y_space = 3.5;
                $apt_y = 112;
            }
            else
            { //overflow resize
                $fontsize = 5;
                $y_space = 2;
                $apt_y = 110.5;
            }

            foreach($weapons as $w)
            {

                if($w->gearType == EPGear::$WEAPON_ENERGY_GEAR) $type = "energy";
                else if($w->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR) $type = "explos.";
                else if($w->gearType == EPGear::$WEAPON_SPRAY_GEAR) $type = "spray";
                else if($w->gearType == EPGear::$WEAPON_SEEKER_GEAR) $type = "seeker";
                else if($w->gearType == EPGear::$WEAPON_AMMUNITION) $type = "ammo";
                else if($w->gearType == EPGear::$WEAPON_MELEE_GEAR) $type = "melee";
                else $type = "kinetic";

                if($w->getOccurrence() > 1)
                    $occ = "(" . $w->getOccurrence() . ") ";
                else
                    $occ = "";

                $pdf->SetFont('Lato-Lig', '', $fontsize);
                $pdf->Text($apt_x, $apt_y, toUpper("[" . $type . "]"));//Weapon type
                $pdf->Text(($apt_x + 13), $apt_y, toUpper($occ . $w->getName()));//Weapon name
                $pdf->Text(($apt_x + 57), $apt_y, toUpper("DV: " . $w->degat));//Weapon degats
                $pdf->Text(($apt_x + 97), $apt_y, toUpper("AP : " . $w->armorPenetration));//Weapon Armor penetration

                $pdf->SetFont('Lato-LigIta', '', 6);
                $this->writeBookLink($w->getName(), ($apt_x + 108), $apt_y, $pdf);//Weapon bookLink

                $apt_y += $y_space;
            }

            //ARMORS
            $apt_x = 83;

            //if more than 5 armour then change fontsize and spaces
            if(count($armor) <= 5)
            {
                $fontsize = 8;
                $y_space = 3.5;
                $apt_y = 145;
            }
            else
            {
                $fontsize = 5;
                $y_space = 2;
                $apt_y = 143;
            }

            foreach($armor as $a)
            {
                if($a->getOccurrence() > 1)
                    $occ = "(" . $a->getOccurrence() . ") ";
                else
                    $occ = "";

                $pdf->SetFont('Lato-Lig', '', $fontsize);
                $pdf->Text( $apt_x, $apt_y, toUpper($occ . $a->getName()));//Armor name

                if($a->armorKinetic == 0 && $a->armorEnergy == 0)
                {
                    $pdf->Text(($apt_x + 58), $apt_y, toUpper("see memo"));//No protec, see memeo
                }
                else
                {
                    $pdf->Text(($apt_x + 58), $apt_y, toUpper("Kin: " . $a->armorKinetic));//Armor Kinetic
                    $pdf->Text(($apt_x + 68), $apt_y, toUpper("Ene: " . $a->armorEnergy));//Armor Energy
                }

                $pdf->SetFont('Lato-LigIta', '', 6);
                $this->writeBookLink($a->getName(), ($apt_x + 108), $apt_y, $pdf);//Armor bookLink

                $apt_y += $y_space;
            }

            //GEAR
            $pdf->SetXY(83,168);
            $format = PdfHelpers::setTwoColFormat(35,18,1,7,7);
            $this->writeTwoColumnsOvf($ovf,$pdf,$formattedGear,$format,3,0,15,"Gear Overflow");

            //IMPLANTS
            $pdf->SetXY(140,168);
            $format = PdfHelpers::setTwoColFormat(40,20,1,7,7);
            $this->writeTwoColumnsOvf($ovf,$pdf,$formattedImplants,$format,3,0,18,"Implant Overflow");

            //MEMO (all morph bonus malus descriptive only, enargy degat and kinetic degat and melle degat)
            $pdf->SetXY(80,230);
            $format = PdfHelpers::setTwoColFormat(45,80,2,7,5);
            $this->writeTwoColumnsOvf($ovf,$pdf,$formattedMemo,$format,3,2,14,$morph->getName() . " Memo Overflow");
        }

        //===================
        $ovf->printOverflowPages();
		$file_util = new EPFileUtility(creator()->character);
		$filename = $file_util->buildExportFilename('EPCharacter', 'pdf');
// 		$pdf->Output($filename, 'D');
		$pdf->Output($filename, 'I');
		return true;
	}

	//Block Writers ===============================================================

    /**
     * Prepare skill data for printing
     *
     * @param EPSkill[] $skillList
     * @param string    $functionName The Name of the function used to get the skills value
     *                                'getEgoValue' for ego skills
     *                                'getValue' for morph skills
     * @return array
     */
    function formatSkills(array $skillList, string $functionName)
    {
        $formattedSkills = array();
        foreach($skillList as $skill)
        {
            $item = array();
            if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE)
            {
                //set the active or knowledge skill token
                if($skill->isKnowledge())
                    $skillType = "K";
                else
                    $skillType = "A";

                $item[0] = toUpper($skillType."   ".$skill->getPrintableName());
                $item[2] = toUpper($skill->$functionName());
                array_push($formattedSkills,$item);

                if(!empty($skill->specialization))
                {
                    $item = array();
                    $item[0] = toUpper("     spec[" . $skill->specialization . "]");
                    $item[2] = "";
                    $item['isContinuation'] = "Set!";
                    array_push($formattedSkills,$item);
                }
            }
        }
        return $formattedSkills;
    }

    /**
     * Prepare aptitude/stats/rep data for printing
     * @param EPStat[]  $stats
     * @param string $functionName The Name of the function used to get the skills value
     *                             'getEgoValue' for ego aptitudes
     *                             'getValue' for morph aptitudes and everything else
     * @return array
     */
    function formatStats(array $stats,string $functionName = 'getValue'): array
    {
        $data = array();
        foreach($stats as $stat)
        {
            $item = array();
            $item[0] = toUpper($stat->getName());
            $item[2] = toUpper($stat->$functionName());
            array_push($data,$item);
        }
        return $data;
    }

    /**
     * Prepare gear/item/trait data for printing
     * @param EPGear[] $gears
     * @return array
     */
    function formatGearData(array $gears): array
    {
        $data = array();
        foreach($gears as $g)
        {
            $book = new EPBook($g->getName());
            try{$count = $g->getOccurrence();} catch (Error $e){$count = 0;};

            $item = array();
            $item[0] = toUpper($g->getName());
            if($count > 1)
                $item[0] = toUpper("(" . $count . ") ". $g->getName());
            $item[2] = $book->getPrintableName();
            array_push($data,$item);
        }
        return $data;
    }

    /**
     * Prepare memo data for printing
     *
     * Bonus/Malus means good/bad in Latin
     * @param EPBonusMalus[] $filteredBM
     * @return array
     */
    function formatMemoData(array $filteredBM): array
    {
        $data = array();
        foreach($filteredBM as $bm)
        {
            $item = array();
            $item[0] = toUpper($bm->getName().($bm->isChoice()? (': '.$bm->forTargetNamed): ''));
            $item[2] = $bm->getDescription();
            array_push($data,$item);
        }
        return $data;
    }

    //Wrapper that allows for the creation of overflow pages if too many elements are entered
    //
    // @param $ovf                  The overflow object to store extra items in
    // @param $overflow_number      The max number of items before overflow occurs
    // @param $overflow_message     The message to put on the overflow page
    function writeTwoColumnsOvf(Overflow $ovf,$pdf,$data,$rowFormat,$row_height,$seperator_type = 0,$overflow_number = 0,$overflow_message = "")
    {
        //Don't bother when not given input data
        if(count($data) == 0)
            return;

        //If overflow number is unset, fall back to normal PdfHelpers::writeTwoColumns
        if($overflow_number > 0)
        {
            $chunks = array_chunk($data,$overflow_number);
            if(isset($chunks[1]))
            {
                error_log("Overflow:  ".$overflow_message);
                $ovf->generateOverflowPage($overflow_message,$chunks[1]);
            }
            PdfHelpers::writeTwoColumns($pdf,$chunks[0],$rowFormat,$row_height,$seperator_type);

        }
        else
            PdfHelpers::writeTwoColumns($pdf,$data,$rowFormat,$row_height,$seperator_type);
    }

	//HELPERS ===============================================================
    //TODO:  This should all be in tables linked by ids or on the models themselves
	function writeBookLink(string $atomName, int $x, int $y, FpdfCustomFonts $pdf)
	{
        $book = new EPBook($atomName);
		$pdf->Text($x, $y, $book->getPrintableName());
	}
}

/**
 * There are some cases where there is more information than can fit in the appropriate spot on a page
 * To deal with this, we save the data, and print extra pages at the end if needed
 */
class Overflow
{
    /**
     * A page's worth of data that's overflowed
     * @var array
     */
    private $pageData;
    /**
     * @var FpdfCustomFonts
     */
    private $pdfWriter;


    function __construct(FpdfCustomFonts $pdfWriter)
    {
        $this->pdfWriter = $pdfWriter;
        $this->pageData  = array();
    }
    function generateOverflowPage($pageName,$data)
    {
        $item = array();
        $item['name'] = $pageName;
        $item['data'] = $data;
        array_push($this->pageData,$item);
    }
    function printOverflowPages()
    {
        foreach($this->pageData as $page)
        {
            $this->pdfWriter->AddPage('P', 'A4');
            $this->pdfWriter->SetFont('Lato-Reg', '', 30);
            $this->pdfWriter->Text(5, 15, toUpper($page['name']));
            $this->pdfWriter->SetXY(5,20);
            $format = PdfHelpers::setTwoColFormat(60,90,2,8,8);
            PdfHelpers::writeTwoColumns($this->pdfWriter, $page['data'], $format, 4, 2);
        }

    }
}

class PdfHelpers {
    /**
     * Format the multi-column data for two columns with a spacer in between
     * @param int $col1_width     How wide column 1 is
     * @param int $col2_width     How wide column 2 is
     * @param int $col_spacing    Spacing between columns
     * @param int $col1_font_size The font size for column 1
     * @param int $col2_font_size The font size for column 2
     * @return array
     */
    public static function setTwoColFormat(int $col1_width, int $col2_width, int $col_spacing, int $col1_font_size, int $col2_font_size)
    {
        $rowFormat = array();
        $rowFormat[0]=array();
        $rowFormat[0]['width'] = $col1_width;
        $rowFormat[0]['font_size'] = $col1_font_size;
        $rowFormat[1]=array();
        $rowFormat[1]['width'] = $col_spacing;
        $rowFormat[1]['font_size'] = 0;     //Doesn't matter (yet)
        $rowFormat[2]=array();
        $rowFormat[2]['width'] = $col2_width;
        $rowFormat[2]['font_size'] = $col2_font_size;
        return $rowFormat;
    }

    /**
     * Writes out multi-column data
     * @param FpdfCustomFonts $pdf            The pdf to write to
     * @param array           $data           The data to be written
     *                                        This is an array containing row data.  Each row consists of multiple columns, sequentially numbered from '0'
     *                                        If 'isContinuation' is set for a row, then a separator is not placed between it and the previous row
     * @param array           $rowFormat      How each row is formatted
     *                                        This is an array containing formatting information for each column.
     *                                        Each column format is an array containing 'width' and 'font_size'
     * @param int             $row_height     How high each row is
     * @param int             $seperator_type The type of separator between items
     *                                        0 no seperator
     *                                        1 line seperator
     *                                        2 every other row has a gray background
     *                                        3 every other row is bolded
     */
    public static function writeTwoColumns(FpdfCustomFonts $pdf, array $data, array $rowFormat, int $row_height, int $seperator_type = 0)
    {
        $x_position = $pdf->GetX();
        $pdf->SetFillColor(200);    //Fill color for separating items

        $fontName = 'Lato-Lig';
        $i=0;
        $useFill = false;

        //Verify That format data is correct
//         foreach($rowFormat as $colFormat)
//         {
//             error_log("Width: ".$colFormat['width']);
//             error_log("font_size: ".$colFormat['font_size']);
//         }

        foreach($data as $item)
        {
            //Handle row separators
            if(!isset($item['isContinuation']))
            {
                if($seperator_type == 1)
                {
                    $pdf->Line($x_position,$pdf->GetY(),$x_position+$rowFormat[0]['width']+$rowFormat[1]['width']+$rowFormat[2]['width'],$pdf->GetY());
                }
                if($seperator_type == 2)
                {
                    if($i%2 == 0)
                        $useFill = false;
                    else
                        $useFill = true;
                }
                if($seperator_type == 3)
                {
                    if($i%2 == 0)
                        $fontName = 'Lato-Lig';
                    else
                        $fontName = 'Lato-Reg';
                }
                $i++;
            }

            $pdf->SetFont($fontName, '',$rowFormat[0]['font_size']);
            $pdf->singleCell($rowFormat[0]['width'],$row_height,$item[0],$useFill);

            if(!isset($item[1]))
                $item[1]="";
            $pdf->SetFont($fontName, '', $rowFormat[1]['font_size']);
            $pdf->Cell($rowFormat[1]['width'],$row_height,$item[1],0,0,'l',$useFill);

            $pdf->SetFont($fontName, '', $rowFormat[2]['font_size']);
            $pdf->MultiCell($rowFormat[2]['width'],$row_height,$item[2],0,'l',$useFill);

            $pdf->SetX($x_position);
        }
    }
}