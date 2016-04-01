<?php
	
	require_once '../../../php/EPCharacterCreator.php';
	require_once '../../../php/EPFileUtility.php';	
	require_once './fpdf/fpdf.php';	
	session_start();
	
	
	/*
		Overview of the PDF export
		
		Ego - 1 page
		
		Morph(s) - 1 page each
	
	
	
	*/
	
	if(isset($_SESSION['cc']))
	{ 
		
		//provider for book pages
		$p = new EPListProvider('../../../php/config.ini');
		
		$pdf = new FPDF();
		$ovf = new Overflow();
		
		$morphs = $_SESSION['cc']->getCurrentMorphs();

		//PDF EXPORT ================================================================
	
				
			//EGO ================================================================ 

				$pdf->AddPage('P', 'A4');//A4 EGO PAGE
				
				$searchpath = dirname(dirname(dirname(__FILE__)));//."/input";
				//SET BAGROUNT PNG-----------------------------
				$pdf->Image($searchpath . "/version4/exporter/EP_BCK_PDF_EGO.png", 0, 0, -150);
							
				//DEFINE FONTS ---------------------------------
				$pdf->AddFont('Lato-Lig', '', 'Lato-Lig.php');
				$pdf->AddFont('Lato-LigIta', '', 'Lato-LigIta.php');
				$pdf->AddFont('Lato-Reg', '', 'Lato-Reg.php');
				
				//BEGIN FILLING SHEET------------------------------
				$character = $_SESSION['cc']->character;

				//NAMES
				$pdf->SetFont('Lato-Lig', '', 10);
				$pdf->Text(60, 12, $character->charName);//Character Name
				$pdf->Text(143, 12, $character->playerName);//Player Name
				
				//ORIGINES	
				$pdf->Text(37, 26, formatIt($_SESSION['cc']->getCurrentBackground()->name)); //Background
				$pdf->Text(37, 33, formatIt($_SESSION['cc']->getCurrentFaction()->name)); //Faction
				
				$pdf->SetFont('Lato-LigIta', '', 7);
				writeBookLink($_SESSION['cc']->getCurrentBackground()->name, 85, 27, $p, $pdf);//Background bookLink
				writeBookLink($_SESSION['cc']->getCurrentFaction()->name, 85, 34, $p, $pdf);//Faction bookLink
				
				//AGE - SEX
				$birthGender = " ";
				if($character->birthGender == 'M') 
					$birthGender = 'male';
				else 
					$birthGender = 'female';
				
				$pdf->SetFont('Lato-Lig', '', 10);
				$pdf->Text(143, 26, formatIt($birthGender)); //Birth gender
				$pdf->Text(143, 33, formatIt($character->realAge)); //Real age
				
				//CREDIT
				$pdf->SetFont('Lato-Lig', '', 10);
				$pdf->Text(10, 53, formatIt($_SESSION['cc']->getCredit())); //Credit
				
				$pdf->SetFont('Lato-LigIta', '', 7);
				$pdf->Text(40, 49, "(EP p.137)");//Credit bookLink
				
				//EGO APTITUDES
				$pdf->Text(90, 49, "(EP p.122)");//Aptitudes bookLink
				
				$aptitudes = $_SESSION['cc']->getAptitudes();

                $formattedAptitudes = array();
                foreach($aptitudes as $apt)
                {
                    $item = array();
                    $item[0] = formatIt($apt->name);
                    $item[1] = formatIt($apt->getvalue());
                    array_push($formattedAptitudes,$item);
                }
                $pdf->SetXY(58,50);
                writeTwoColumns($pdf,$formattedAptitudes,30,10,2,3.5,10,10,2);

				//REPUTATION
				$pdf->SetFont('Lato-LigIta', '', 7);
				$pdf->Text(138, 49, "(EP p.285)");//Reputation bookLink
				
				$reputations = $_SESSION['cc']->getReputations();

                $formattedReputations = array();
                foreach($reputations as $rep)
                {
                    $item = array();
                    $item[0] = formatIt($rep->name);
                    $item[1] = formatIt($rep->getvalue());
                    array_push($formattedReputations,$item);
                }
                $pdf->SetXY(111,50);
                writeTwoColumns($pdf,$formattedReputations,25,10,2,3.5,10,10,2);
				
				//MOTIVATION
				$pdf->SetFont('Lato-LigIta', '', 7);
				$pdf->Text(192, 49, "(EP p.120)");//Motivation bookLink
				
				$motivations = $_SESSION['cc']->getMotivations();
				$y_space = 3.5;
				$apt_x = 161;
				$apt_y = 53;
				
				$pdf->SetFont('Lato-Lig', '', 10);
				foreach($motivations as $mot)
				{
					$pdf->Text($apt_x, $apt_y, formatIt($mot));//Motivations 
					$apt_y += $y_space;
				}
				
				//EGO SKILLS
				$pdf->SetFont('Lato-LigIta', '', 7);
				$pdf->Text(64, 81, "(EP p.176)");//Skills bookLink
				
				$skillList = $_SESSION['cc']->getSkills();


                $formattedSkills = array();
                foreach($skillList as $skill)
                {
                    $item = array();
                    if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE)
                    {
                        //set the active or knowledge skill token
                        if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE)
                            $skillType = "K";
                        else
                            $skillType = "A";

                        $skillCompleteName = "";
                        if(!empty($skill->prefix))
                            $skillCompleteName = $skill->prefix . " : ";

                        $skillCompleteName .= $skill->name;

                        if($skill->defaultable == EPSkill::$NO_DEFAULTABLE)
                            $skillCompleteName .= " *";

                        $item[0] = formatIt($skillType."   ".$skillCompleteName);
                        $item[1] = formatIt($skill->getEgoValue());
                        array_push($formattedSkills,$item);

                        if(!empty($skill->specialization))
                        {
                            $item = array();
                            $item[0] = formatIt("     spec[" . $skill->specialization . "]");
                            $item[1] = "";
                            $item[2] = "Set!";
                            array_push($formattedSkills,$item);
                        }
                    }
                }
                $pdf->setXY(8,84);
                writeTwoColumnsOvf($ovf,$pdf,$formattedSkills,55,7,1,3.5,9,9,2,60,"Ego Skills Overflow");

                //EGO NEG TRAIT
                $egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(), EPTrait::$NEGATIVE_TRAIT);

                $formattedNegTraits = array();
                foreach($egoNegTraits as $trait)
                {
                    $item = array();
                    $item[0] = formatIt($trait->name);
                    $item[1] = getBookLink($trait->name,$p);
                    array_push($formattedNegTraits,$item);
                }
                $pdf->setXY(80,102);
                writeTwoColumns($pdf,$formattedNegTraits,18,15,1,3,8,7);

				//EGO POS TRAIT
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(), EPTrait::$POSITIVE_TRAIT);

                $formattedPosTraits = array();
                foreach($egoNegTraits as $trait)
                {
                    $item = array();
                    $item[0] = formatIt($trait->name);
                    $item[1] = getBookLink($trait->name,$p);
                    array_push($formattedPosTraits,$item);
                }
                $pdf->setXY(116,102);
                writeTwoColumns($pdf,$formattedPosTraits,25,15,1,3,8,7);

				//PSI SLEIGHTS
				$psySleights = $_SESSION['cc']->getCurrentPsySleights();
				$y_space = 3;
				$apt_x = 160;
				$apt_y = 105;
				
				foreach($psySleights as $sleight)
				{
					//set the slight token to active or passive
					if($sleight->psyType == EPPsySleight::$ACTIVE_PSY) 
						$type = "(A)";
					else
						$type = "(P)";

					$pdf->SetFont('Lato-Lig', '', 7);
					$pdf->Text($apt_x, $apt_y, formatIt($type));//PsySleight type 
					$pdf->Text(($apt_x + 4), $apt_y, formatIt($sleight->name));//PsySleight name 
					
					$pdf->SetFont('Lato-LigIta', '', 6);
					writeBookLink($sleight->name, ($apt_x + 36), $apt_y, $p, $pdf);//PsySleight bookLink
					
					$apt_y += $y_space;
				}	
				
				//SOFT GEAR
				$softGears = $_SESSION['cc']->getEgoSoftGears();

				$formatedSoftGears = array();
				foreach($softGears as $gear)
                {
                    $occ = "";
                    if($gear->occurence > 1)
                    {
                        $occ = "(" . $gear->occurence . ") ";
                    }

                    $item[0] = formatIt($occ . $gear->name);
                    $item[1] = getBookLink($gear->name,$p);
                    array_push($formatedSoftGears,$item);
                }
                $pdf->SetXY(85,152);
                writeTwoColumns($pdf,$formatedSoftGears,30,15,1,3,7,7);

				//AI
				$ais = $_SESSION['cc']->getEgoAi();
				$y_space = 1;
				$apt_x = 132;
				$apt_y = 155;
				
				foreach($ais as $ai)
				{
					if($ai->occurence > 1) 
						$occ = "(" . $ai->occurence . ") ";
					else 
						$occ = "";
					
					$pdf->SetFont('Lato-Lig', '', 8);
					$pdf->Text($apt_x, $apt_y, formatIt($occ . $ai->name));//ai name 
					
					$pdf->SetFont('Lato-LigIta', '', 6);
					writeBookLink($ai->name, ($apt_x + 14), ($apt_y + 2), $p, $pdf);//ai bookLink
					
					$skillAptNonformated = "";
					foreach($ai->aptitudes as $aiApt)
					{
						$skillAptNonformated .= $aiApt->abbreviation . "[";
						$skillAptNonformated .= $aiApt->value . "]   ";
					}
					
					//construct a skill string for each skill
					foreach($ai->skills as $aiSkill)
					{
						$skillCompleteName = "";
						if(!empty($aiSkill->prefix)) 
							$skillCompleteName = $aiSkill->prefix . " : ";
						
						$skillCompleteName .= $aiSkill->name;
						$skillAptNonformated .= $skillCompleteName . "(";
						$skillAptNonformated .= $aiSkill->baseValue . ")  ";
					}
					
					
					$aiSkillsApt = formatItForRect($skillAptNonformated, 35);
					$paddle = 0;
					
					$pdf->SetFont('Lato-LigIta', '', 7);
					foreach($aiSkillsApt as $line)
					{
						$pdf->Text(($apt_x + 27), ($apt_y + $paddle), formatIt($line));//ai skill apt
						$paddle += 3;
					} 
					
					$apt_y += $y_space + $paddle;
				}	

				//MEMO (all ego bonus malus)
				$egoBonusMalus = $_SESSION['cc']->getBonusMalusEgo();
// 				writeMemo($ovf,$pdf,getDescOnlyBM($egoBonusMalus));
				writeMemo($ovf,$pdf,$egoBonusMalus);
				
				//END EGO PAGE
					
					//MORPHS ============================================================ 
					
					//DO ONE PAGE PER MORPH
					$morphs = $_SESSION['cc']->getCurrentMorphs();
					foreach($morphs as $morph)
					{
						//ACTIVATE THE MORPH
						$_SESSION['cc']->activateMorph($morph);
						$pdf->AddPage('P', 'A4');//A4 MORPH
						
						$searchpath = dirname(dirname(dirname(__FILE__)));//."/input";
						//SET BAGROUNT PNG-----------------------------
						$pdf->Image($searchpath . "/version4/exporter/EP_BCK_PDF_MORPH.png", 0, 0, -150);
						
						$pdf->SetFont('Lato-Lig', '', 8);
	
						//DETAILS DATA
						if($morph->morphType == EPMorph::$BIOMORPH) $type = "[bio]";
						else if($morph->morphType == EPMorph::$SYNTHMORPH) $type = "[synth]";
						else if($morph->morphType == EPMorph::$INFOMORPH) $type = "[info]";
						else if($morph->morphType == EPMorph::$PODMORPH) $type = "[pod]";
						
						$pdf->Text(55, 11.5, formatIt($morph->name . " " . $type));//morph Name type
						
						$pdf->SetFont('Lato-LigIta', '', 5);
						writeBookLink($morph->name, 105, 11.5, $p, $pdf);//morph bookLink
						
						$pdf->SetFont('Lato-Lig', '', 8);
						$pdf->Text(140, 12, formatIt($morph->nickname));//morph nickname
						$pdf->Text(50, 19, formatIt($morph->age));//morph apparent age
						$pdf->Text(140, 19, formatIt($morph->location));//morph Location
						$pdf->Text(50, 26, formatIt($character->playerName));//morph player
						
						$morphGender = " ";
						if($character->birthGender == 'M') 
							$morphGender = 'male';
						else if($character->birthGender == 'F') 
							$morphGender = 'female';
						else 
							$morphGender = 'none';
						
						$pdf->Text(140, 26, formatIt($morphGender));//morph gender
						
                        //MORPH NEG TRAIT
                        $morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph), EPTrait::$NEGATIVE_TRAIT);

                        $formattedNegTraits = array();
                        foreach($morphNegTraits as $trait)
                        {
                            $item = array();
                            $item[0] = formatIt($trait->name);
                            $item[1] = getBookLink($trait->name,$p);
                            array_push($formattedNegTraits,$item);
                        }
                        $pdf->setXY(5,43);
                        writeTwoColumns($pdf,$formattedNegTraits,29,15,1,4,8,7);


                        //MORPH POS TRAIT
                        $morphPosTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph), EPTrait::$POSITIVE_TRAIT);

                        $formattedPosTraits = array();
                        foreach($morphPosTraits as $trait)
                        {
                            $item = array();
                            $item[0] = formatIt($trait->name);
                            $item[1] = getBookLink($trait->name,$p);
                            array_push($formattedPosTraits,$item);
                        }
                        $pdf->setXY(52,43);
                        writeTwoColumns($pdf,$formattedPosTraits,29,15,1,4,8,7);

						//MORPH STATS
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(118, 40, "(EP p.121)");//Stats bookLink
						$stats = $_SESSION['cc']->getStats();

                        $formattedStats = array();
                        foreach($stats as $s)
                        {
                            $item = array();
                            $item[0] = formatIt($s->name);
                            $item[1] = formatIt($s->getValue());
                            array_push($formattedStats,$item);
                        }
                        $pdf->SetXY(102,43);
                        writeTwoColumns($pdf,$formattedStats,28,7,1,3.5,7,7,2);

						
						//MORPH APTITUDES
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(173, 40, "(EP p.122)");//Aptitude bookLink
						$aptitudes = $_SESSION['cc']->getAptitudes();

                        $formattedAptitudes = array();
                        foreach($aptitudes as $apt)
                        {
                            $item = array();
                            $item[0] = formatIt($apt->name);
                            $item[1] = formatIt($apt->getvalue());
                            array_push($formattedAptitudes,$item);
                        }
                        $pdf->SetXY(142,43);
                        writeTwoColumns($pdf,$formattedAptitudes,30,10,2,3.5,10,10,2);
					
						//MORPH SKILLS
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(64, 79, "(EP p.176)");//Skills bookLink
						$skillList = $_SESSION['cc']->getSkills();

                        $formattedSkills = array();
                        foreach($skillList as $skill)
                        {
                            $item = array();
                            if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE)
                            {
                                //set the active or knowledge skill token
                                if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE)
                                    $skillType = "K";
                                else
                                    $skillType = "A";

                                $skillCompleteName = "";
                                if(!empty($skill->prefix))
                                    $skillCompleteName = $skill->prefix . " : ";

                                $skillCompleteName .= $skill->name;

                                if($skill->defaultable == EPSkill::$NO_DEFAULTABLE)
                                    $skillCompleteName .= " *";

                                $item[0] = formatIt($skillType."   ".$skillCompleteName);
                                $item[1] = formatIt($skill->getEgoValue());
                                array_push($formattedSkills,$item);

                                if(!empty($skill->specialization))
                                {
                                    $item = array();
                                    $item[0] = formatIt("     spec[" . $skill->specialization . "]");
                                    $item[1] = "";
                                    $item[2] = "Set!";
                                    array_push($formattedSkills,$item);
                                }
                            }
                        }
                        $pdf->setXY(8,84);
                        writeTwoColumnsOvf($ovf,$pdf,$formattedSkills,55,7,1,3.5,9,9,2,60,"Morph Skills Overflow");
							
						//NOTES 
						$apt_x = 81;
						$apt_y = 81;
						$pdf->SetFont('Lato-Lig', '', 5);
						$pdf->SetXY($apt_x,$apt_y);
						$pdf->MultiCell(95,2,$character->note,0,'l');

						//WEAPONS
						$morphGear = $_SESSION['cc']->getGearForCurrentMorph();		
						$weapons = filterWeaponOnly($morphGear);
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
							
							if($w->occurence > 1) 
								$occ = "(" . $w->occurence . ") ";
							else 
								$occ = "";
							
							$pdf->SetFont('Lato-Lig', '', $fontsize);	
							$pdf->Text($apt_x, $apt_y, formatIt("[" . $type . "]"));//Weapon type 
							$pdf->Text(($apt_x + 13), $apt_y, formatIt($occ . $w->name));//Weapon name 
							$pdf->Text(($apt_x + 57), $apt_y, formatIt("DV: " . $w->degat));//Weapon degats
							$pdf->Text(($apt_x + 97), $apt_y, formatIt("AP : " . $w->armorPenetration));//Weapon Armor penetration 
							
							$pdf->SetFont('Lato-LigIta', '', 6);
							writeBookLink($w->name, ($apt_x + 108), $apt_y, $p, $pdf);//Weapon bookLink
							
							$apt_y += $y_space;
						}
						
						//ARMORS	
						$armor = filterArmorOnly($morphGear);
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
							if($a->occurence > 1) 
								$occ = "(" . $a->occurence . ") ";
							else 
								$occ = "";
							
							$pdf->SetFont('Lato-Lig', '', $fontsize);	
							$pdf->Text( $apt_x, $apt_y, formatIt($occ . $a->name));//Armor name 
							
							if($a->armorKinetic == 0 && $a->armorEnergy == 0)
							{
								$pdf->Text(($apt_x + 58), $apt_y, formatIt("see memo"));//No protec, see memeo
							}
							else
							{
								$pdf->Text(($apt_x + 58), $apt_y, formatIt("Kin: " . $a->armorKinetic));//Armor Kinetic
								$pdf->Text(($apt_x + 68), $apt_y, formatIt("Ene: " . $a->armorEnergy));//Armor Energy
							}
							
							$pdf->SetFont('Lato-LigIta', '', 6);
							writeBookLink($a->name, ($apt_x + 108), $apt_y, $p, $pdf);//Armor bookLink
							
							$apt_y += $y_space;
						}
							
                        //GEAR
                        $gear = filterGeneralOnly($morphGear);

                        $formattedGear = array();
                        foreach($gear as $g)
                        {
                            $occ = "";
                            if($g->occurence > 1)
                                $occ = "(" . $g->occurence . ") ";

                            $item = array();
                            $item[0] = formatIt($occ . $g->name);
                            $item[1] = getBookLink($g->name,$p);
                            array_push($formattedGear,$item);
                        }
                        $pdf->SetXY(83,168);
                        writeTwoColumnsOvf($ovf,$pdf,$formattedGear,35,18,1,3,7,7,0,15,"Gear Overflow");

                        //IMPLANTS
                        $implants = filterImplantOnly($morphGear);

                        $formattedImplants = array();
                        foreach($implants as $i)
                        {
                            $occ = "";
                            if($i->occurence > 1)
                                $occ = "(" . $i->occurence . ") ";

                            $item = array();
                            $item[0] = formatIt($occ . $i->name);
                            $item[1] = getBookLink($i->name,$p);
                            array_push($formattedImplants,$item);
                        }
                        $pdf->SetXY(140,168);
                        writeTwoColumnsOvf($ovf,$pdf,$formattedImplants,40,20,1,3,7,7,0,18,"Implant Overflow");

						
						//MEMO (all morph bonus malus descriptive only, enargy degat and kinetic degat and melle degat)
						$morphBonusMalus = $_SESSION['cc']->getBonusMalusForMorph($morph);
						writeMemo($ovf,$pdf,getMorphMemoBM($morphBonusMalus));
						
					}
				
			//===================
        $ovf->printOverflowPages($pdf);
		$file_util = new EPFileUtility($_SESSION['cc']->character);
		$filename = $file_util->buildExportFilename('EPCharacter', 'pdf');
		$pdf->Output($filename, 'D');
	}
	
	//NO CHARACTER CREATOR ! ================================================
	else
	{	
		header("Status: 500 Internal Server Error", true, 500);
		echo "Bad news, something went wrong, we can not print your character, verify your character and try again.";
		die;	
	}
	
	//Block Writers ===============================================================

    //There are some cases where there is more information than can fit in the appropriate spot on a page
    //To deal with this, we save the data, and print extra pages at the end if needed
    class Overflow
    {
        var $page_data;  //A page's worth of data that's overflowed
        function Overflow()
        {
            $this->page_data = array();
        }
        function generateOverflowPage($pageName,$data)
        {
            $item = array();
            $item['name'] = $pageName;
            $item['data'] = $data;
            array_push($this->page_data,$item);
        }
        function printOverflowPages($pdf)
        {
            foreach($this->page_data as $page)
            {
                $pdf->AddPage('P', 'A4');
                $pdf->SetFont('Lato-Reg', '', 30);
                $pdf->Text(5, 15, formatIt($page['name']));
                $pdf->SetXY(5,20);
                writeTwoColumns($pdf,$page['data'],60,90,2,4,8,8,2);
            }

        }
    }

    // Writes out multi-column data
    //
    // @param $pdf              The pdf to write to
    // @param $data             The data to be written
    //  This is an array containing touples of columns
    //  If the third column is set, then seperator functions will treat the next row as a continuation of the previous one
    // @param $col1_width       How wide column 1 is
    // @param $col1_width       How wide column 2 is
    // @param $col_spacing      Spacing between columns
    // @param $row_height       How high each row is
    // @param $col1_font_size   The font size for column 1
    // @param $col2_font_size   The font size for column 2
    // @param $seperator_type   The type of separator between items
    //  0 no seperator
    //  1 line seperator
    //  2 every other row has a gray background
    //  3 every other row is bolded
    function writeTwoColumns($pdf,$data,$col1_width,$col2_width,$col_spacing,$row_height,$col1_font_size,$col2_font_size,$seperator_type = 0)
    {
        $x_position = $pdf->GetX();
        $pdf->SetFillColor(175);    //Fill color for separating items

        $fontName = 'Lato-Lig';
        $i=0;
        $useFill = false;
        foreach($data as $item)
        {
            //Handle seperators between items
            if(!isset($item[2]))
            {
                if($seperator_type == 1)
                {
                    $pdf->Line($x_position,$pdf->GetY(),$x_position+$col1_width+$col_spacing+$col2_width,$pdf->GetY());
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

            $pdf->SetFont($fontName, '', $col1_font_size);
            //If the first column is too long, drop the font size accordingly so it fits in a single line
            while($pdf->GetStringWidth($item[0]) > $col1_width)
            {
                $col1_font_size-=1;
                $pdf->SetFontSize($col1_font_size);
                error_log($col1_font_size."->".$item[0].":  ".$pdf->GetStringWidth($item[0]));
            }
            $pdf->Cell($col1_width,$row_height,$item[0],0,0,'l',$useFill);

            $pdf->SetFont($fontName, '', $col2_font_size);
//             $pdf->SetX($pdf->GetX()+$col_spacing);
            $pdf->Cell($col_spacing,$row_height,"",0,0,'l',$useFill);
            $pdf->MultiCell($col2_width,$row_height,$item[1],0,'l',$useFill);

            $pdf->SetX($x_position);
        }
    }

    //Wrapper that allows for the creation of overflow pages if too many elements are entered
    //
    // @param $ovf                  The overflow object to store extra items in
    // @param $overflow_number      The max number of items before overflow occurs
    // @param $overflow_message     The message to put on the overflow page
    function writeTwoColumnsOvf($ovf,$pdf,$data,$col1_width,$col2_width,$col_spacing,$row_height,$col1_font_size,$col2_font_size,$seperator_type = 0,$overflow_number = 0,$overflow_message = "")
    {
        if($overflow_number != 0)
        {
            $chunks = array_chunk($data,$overflow_number);
            if(isset($chunks[1]))
                $ovf->generateOverflowPage($overflow_message,$chunks[1]);
            writeTwoColumns($pdf,$chunks[0],$col1_width,$col2_width,$col_spacing,$row_height,$col1_font_size,$col2_font_size,$seperator_type);

        }
        else
            writeTwoColumns($pdf,$data,$col1_width,$col2_width,$col_spacing,$row_height,$col1_font_size,$col2_font_size,$seperator_type);
    }

	//Bonus/Malus means good/bad in Latin
	//MEMO (all bonus malus descriptive only)
	function writeMemo($ovf,$pdf,$filteredBM)
	{
        //Convert data to display into the correct format
        $data = array();
        foreach($filteredBM as $bm)
        {
            $item = array();
            $item[0] = formatIt($bm->name);
            $item[1] = $bm->description;
            array_push($data,$item);
        }
        $pdf->SetXY(80,230);
        writeTwoColumnsOvf($ovf,$pdf,$data,45,80,2,3,7,5,2,14,"Memo Overflow");
    }

	//HELPERS ===============================================================

	function formatIt($string)
	{
		if($string == null) 
			$string = " ";
		
		return strtoupper($string);
	}
	
	function formatItForRect($string, $length)
	{
		$final_result = str_split(formatIt($string), $length);
		return $final_result;
	}
	
	function getImplants($objArray)
	{
		$final = "*";
		foreach($objArray as $m)
		{
			if($m->gearType == EPGear::$IMPLANT_GEAR)
				$final .= $m->name . "*";
		}
		return $final;
	}
	
	function getGear($objArray)
	{
		$final = "*";
		foreach($objArray as $m)
		{
			if($m->gearType =! EPGear::$IMPLANT_GEAR)
				$final .= $m->name . "*";
		}
		return $final;
	}
	
	function getTraits($objArray)
	{
		$final = "*";
		foreach($objArray as $m)
		{
			$final .= $m->name . "*";
		}
		return $final;
	}
	
	function filterPosNegTrait($traits, $type)
	{
		$result = array();
		foreach($traits as $t)
		{
			if($t->traitPosNeg == $type)
				array_push($result, $t);
		}
		return $result;
	}
	
	function getDescOnlyBM($bonusMalus)
	{
		$result = array();
		foreach($bonusMalus as $bm)
		{
			if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY)
				array_push($result, $bm);
		}
		return $result;
	}
	
	function getMorphMemoBM($bonusMalus)
	{
		$result = array();
		foreach($bonusMalus as $bm)
		{
			if( $bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE)
			{
				array_push($result, $bm);
			}
		}
		return $result;
	}
	
	function filterWeaponOnly($gears)
	{
		$result = array();
		foreach($gears as $g)
		{
			if( $g->gearType == EPGear::$WEAPON_MELEE_GEAR ||
				$g->gearType == EPGear::$WEAPON_ENERGY_GEAR ||	
				$g->gearType == EPGear::$WEAPON_KINETIC_GEAR ||
				$g->gearType == EPGear::$WEAPON_AMMUNITION ||
				$g->gearType == EPGear::$WEAPON_SEEKER_GEAR ||
				$g->gearType == EPGear::$WEAPON_SPRAY_GEAR)
			{
				array_push($result, $g);
			}
			
			if( $g->gearType == EPGear::$IMPLANT_GEAR )
			{
				if($g->degat != "0")
					array_push($result, $g);
			}
		}
		return $result;
	}
	
	function filterArmorOnly($gears)
	{
		$result = array();
		foreach($gears as $g)
		{
			if( $g->gearType == EPGear::$ARMOR_GEAR)
				array_push($result, $g);
		}
		return $result;
	}
	
	function filterImplantOnly($gears)
	{
		$result = array();
		foreach($gears as $g)
		{
			if( $g->gearType == EPGear::$IMPLANT_GEAR)
				array_push($result, $g);
		}
		return $result;
	}

	
	function filterGeneralOnly($gears)
	{
		$result = array();
		foreach($gears as $g)
		{
			if( $g->gearType == EPGear::$STANDARD_GEAR ||
				$g->gearType == EPGear::$DRUG_GEAR ||	
				$g->gearType == EPGear::$CHEMICALS_GEAR ||
				$g->gearType == EPGear::$POISON_GEAR ||
				$g->gearType == EPGear::$PET_GEAR ||
				$g->gearType == EPGear::$VEHICLES_GEAR ||
				$g->gearType == EPGear::$ROBOT_GEAR ||
				$g->gearType == EPGear::$FREE_GEAR )
			{
					array_push($result, $g);
			}
		}
		return $result;
	}

	function getBookLink($atomeName, $provider)
	{
		$bookFullName = $provider->getBookForName($atomeName);
		if($bookFullName == EPListProvider::$BOOK_ECLIPSEPHASE) $book = "EP";
		else if($bookFullName == EPListProvider::$BOOK_TRANSHUMAN) $book = "TH";
		else if($bookFullName == EPListProvider::$BOOK_GATECRASHING) $book = "GC";
		else if($bookFullName == EPListProvider::$BOOK_SUNWARD) $book = "SW";
		else if($bookFullName == EPListProvider::$BOOK_PANOPTICON) $book = "PAN";
		else if($bookFullName == EPListProvider::$BOOK_RIMWARD) $book = "RW";
		else $book = "??";

		$page = $provider->getPageForName($atomeName);
		return "(" . $book . " p." . $page . ")";
	}

	function writeBookLink($atomeName, $x, $y, $provider, $pdf)
	{
		$pdf->Text($x, $y, getBookLink($atomeName,$provider));
	}
?>
