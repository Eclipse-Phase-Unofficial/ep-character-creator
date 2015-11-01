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
				setBookLink($_SESSION['cc']->getCurrentBackground()->name, 85, 27, $p, $pdf);//Background bookLink
				setBookLink($_SESSION['cc']->getCurrentFaction()->name, 85, 34, $p, $pdf);//Faction bookLink
				
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
				$y_space = 3.5;
				$apt_x = 55;
				$apt_y = 53;
				
				$pdf->SetFont('Lato-Lig', '', 10);
				foreach($aptitudes as $apt)
				{
					$pdf->Text($apt_x, $apt_y, formatIt($apt->name));//Apt Name
					$pdf->Text(($apt_x + 37), $apt_y, formatIt($apt->value));//Apt Value Ego
					
					$apt_y += $y_space;
				}
				
				//REPUTATION
				$pdf->SetFont('Lato-LigIta', '', 7);
				$pdf->Text(138, 49, "(EP p.285)");//Reputation bookLink
				
				$reputations = $_SESSION['cc']->getReputations();
				$y_space = 3.5;
				$apt_x = 108;
				$apt_y = 53;
				
				$pdf->SetFont('Lato-Lig', '', 10);
				foreach($reputations as $rep)
				{
					$pdf->Text($apt_x, $apt_y, formatIt($rep->name));//Rep name
					$pdf->Text(($apt_x + 33), $apt_y, formatIt($rep->getvalue()));//Rep value
					
					$apt_y += $y_space;
				}
				
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
				$apt_x = 12;
				
				//Count skills and specializations
				$printedSkills = 0;
				foreach($skillList as $skill)
				{
					if($skill->getValue() > 0)
						$printedSkills++;
					
					if(!empty($skill->specialization))
						$printedSkills++;
				}
				//if more than 60 skills, reduce the font and spaces
				if($printedSkills <= 60)
				{
					$fontsize = 9;
					$y_space = 3.5;
					$apt_y = 86;
				}
				else
				{
					$fontsize = 6;
					$y_space = 3;
					$apt_y = 86;	
				} 
				
				$i = 1;
				foreach($skillList as $skill)
				{
					if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE)
					{
						//set the bold or normal font
						if($i%2 == 0) 
							$pdf->SetFont('Lato-Reg', '', $fontsize);
						else 
							$pdf->SetFont('Lato-Lig', '', $fontsize);
						
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
						
						$pdf->Text(($apt_x -4), $apt_y, formatIt($skillType));//Skill Type
						$pdf->Text($apt_x, $apt_y, formatIt($skillCompleteName));//Skill complete name
						$pdf->Text(($apt_x + 53), $apt_y, formatIt($skill->getEgoValue()));//Skill ego value;
						
						if(!empty($skill->specialization))
						{
							$pdf->SetFont('Lato-Lig', '', $fontsize);
							$apt_y += $y_space;
							$pdf->Text($apt_x, $apt_y, formatIt("spec[" . $skill->specialization . "]"));//Skill specialization
						}
						
						$apt_y += $y_space;
						$i++;
					}
				}
				
				//EGO NEG TRAIT
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(), EPTrait::$NEGATIVE_TRAIT);
				$y_space = 3;
				$apt_x = 83;
				$apt_y = 105;
				
				foreach($egoNegTraits as $trait)
				{
					$pdf->SetFont('Lato-Lig', '', 6);
					$pdf->Text($apt_x, $apt_y, formatIt($trait->name));//Trait Neg name 
					
					$pdf->SetFont('Lato-LigIta', '', 5);
					setBookLink($trait->name, ($apt_x - 8), $apt_y, $p, $pdf);//Trait Neg bookLink
					
					$apt_y += $y_space;
				}
				
				//EGO POS TRAIT
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(), EPTrait::$POSITIVE_TRAIT);
				$y_space = 3;
				$apt_x = 116;
				$apt_y = 105;
				
				//need to either add some additional spacing or test book name length PAN p100+ causes issues
				foreach($egoNegTraits as $trait)
				{
					$pdf->SetFont('Lato-Lig', '', 6);
					$pdf->Text($apt_x, $apt_y, formatIt($trait->name));//Trait Neg name 
					
					$pdf->SetFont('Lato-LigIta', '', 5);
					setBookLink($trait->name, ($apt_x + 30), $apt_y, $p, $pdf);//Trait Neg bookLink
					
					$apt_y += $y_space;
				}
				
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
					setBookLink($sleight->name, ($apt_x + 36), $apt_y, $p, $pdf);//PsySleight bookLink
					
					$apt_y += $y_space;
				}	
				
				//SOFT GEAR
				$softGears = $_SESSION['cc']->getEgoSoftGears();
				$apt_x = 86;
				
				if(count($softGears) <= 18)
				{
					$fontsize = 8;
					$y_space = 3.5;
					$apt_y = 155;
				}
				else
				{
					$fontsize = 6;
					$y_space = 3;
					$apt_y = 155;	
				}
				
				foreach($softGears as $gear)
				{
					if($gear->occurence > 1) 
						$occ = "(" . $gear->occurence . ") ";
					else 
						$occ = "";
					
					$pdf->SetFont('Lato-Lig', '', $fontsize);
					$pdf->Text($apt_x, $apt_y, formatIt($occ . $gear->name));//soft gear name 
					
					$pdf->SetFont('Lato-LigIta', '', 6);
					setBookLink($gear->name, ($apt_x - 11), $apt_y, $p, $pdf);//soft gear bookLink
					
					$apt_y += $y_space;
				}
				
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
					setBookLink($ai->name, ($apt_x + 14), ($apt_y + 2), $p, $pdf);//ai bookLink
					
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
// 				writeMemo($pdf,getDescOnlyBM($egoBonusMalus));
				writeMemo($pdf,$egoBonusMalus);
				
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
						setBookLink($morph->name, 105, 11.5, $p, $pdf);//morph bookLink
						
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
						$y_space = 3;
						$apt_x = 13;
						$apt_y = 45;
						foreach($morphNegTraits as $trait)
						{
							$pdf->SetFont('Lato-Lig', '', 7);
							$pdf->Text( $apt_x, $apt_y, formatIt($trait->name));//Trait Neg name 
							
							$pdf->SetFont('Lato-LigIta', '', 5);
							setBookLink($trait->name, ($apt_x - 9), $apt_y, $p, $pdf);//Trait Neg bookLink
							
							$apt_y += $y_space;
						}
					
						//MORPH POS TRAIT
						$morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph), EPTrait::$POSITIVE_TRAIT);
						$y_space = 3;
						$apt_x = 52;
						$apt_y = 45;
						
						foreach($morphNegTraits as $trait)
						{
							$pdf->SetFont('Lato-Lig', '', 7);
							$pdf->Text($apt_x, $apt_y, formatIt($trait->name));//Trait Neg name 
							
							$pdf->SetFont('Lato-LigIta', '', 5);
							setBookLink($trait->name, ($apt_x + 41), $apt_y, $p, $pdf);//Trait Neg bookLink
							
							$apt_y += $y_space;
						}
							
						//MORPH STATS
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(118, 40, "(EP p.121)");//Stats bookLink
						$stats = $_SESSION['cc']->getStats();
						$y_space = 3.5;
						$apt_x = 103;
						$apt_y = 46;
						$i = 1;
						foreach($stats as $s)
						{
							if($i%2 == 0)
								$pdf->SetFont('Lato-Reg', '', 8);
							else 
								$pdf->SetFont('Lato-Lig', '', 8);
							
							$pdf->Text($apt_x, $apt_y, formatIt($s->name));//Stat name name 
							$pdf->Text(($apt_x + 30), $apt_y, formatIt($s->getValue()));//Stat Value 
							
							$apt_y += $y_space;
							$i++;
						}
						
						//MORPH APTITUDES
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(173, 40, "(EP p.122)");//Aptitude bookLink
						$aptitudes = $_SESSION['cc']->getAptitudes();
						$y_space = 3.5;
						$apt_x = 142;
						$apt_y = 46;
						$i = 1;
						
						foreach($aptitudes as $apt)
						{
							if($i%2 == 0) 
								$pdf->SetFont('Lato-Reg', '', 8); //bold
							else 
								$pdf->SetFont('Lato-Lig', '', 8); //normal
							
							$pdf->Text($apt_x, $apt_y, formatIt($apt->name));//Apt Name
							$pdf->Text(($apt_x + 32), $apt_y, formatIt($apt->getValue()));//Apt Value 
							
							$apt_y += $y_space;
							$i++;
						}
					
						//MORPH SKILLS
						$pdf->SetFont('Lato-LigIta', '', 7);
						$pdf->Text(64, 79, "(EP p.176)");//Skills bookLink
						$skillList = $_SESSION['cc']->getSkills();
						$apt_x = 12;
						
						//Count skills and specializations
						$printedSkills = 0;
						foreach($skillList as $skill)
						{
							if($skill->getValue() > 0)
								$printedSkills++;

							if(!empty($skill->specialization))
								$printedSkills++;
						}
						//if more than 60 skills, reduce the font and spaces
						if($printedSkills <= 60)
						{
							$fontsize = 9;
							$y_space = 3.5;
							$apt_y = 84;
						}
						else
						{
							$fontsize = 6;
							$y_space = 3;
							$apt_y = 84;	
						} 
						
						$i = 1;
						foreach($skillList as $skill)
						{
							if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE)
							{
								//set bold or normal row
								if($i%2 == 0) 
									$pdf->SetFont('Lato-Reg', '', $fontsize); //bold
								else 
									$pdf->SetFont('Lato-Lig', '', $fontsize); //light
								
								//set the active or knowledge token
								if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) 
									$skillType = "K";
								else
									$skillType = "A";
								
								//construct the full name prefix-name-postfix
								$skillCompleteName = "";
								
								if(!empty($skill->prefix)) 
									$skillCompleteName = $skill->prefix . " : ";
								
								$skillCompleteName .= $skill->name;
								
								if($skill->defaultable == EPSkill::$NO_DEFAULTABLE) 
									$skillCompleteName .= " *";
								
								//needs a check for total character length with either a word wrap option of a one time text size drop
							
								//write main skill row to the pdf
								$pdf->Text(($apt_x - 4), $apt_y, formatIt($skillType));//Skill Type
								$pdf->Text($apt_x, $apt_y, formatIt($skillCompleteName));//Skill complete name
								$pdf->Text(($apt_x + 53), $apt_y, formatIt($skill->getValue()));//Skill morph value;
								
								//write the optional specialization to the pdf
								if(!empty($skill->specialization))
								{
									$pdf->SetFont('Lato-Lig', '', $fontsize); //normal font
									$apt_y += $y_space;  //increment the y position
									$pdf->Text($apt_x, ($apt_y), formatIt("spec[" . $skill->specialization . "]"));//Skill specialization
								}
								
								$apt_y += $y_space; //increment the y position
								$i++;
							}
						}
							
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
							setBookLink($w->name, ($apt_x + 108), $apt_y, $p, $pdf);//Weapon bookLink
							
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
							setBookLink($a->name, ($apt_x + 108), $apt_y, $p, $pdf);//Armor bookLink
							
							$apt_y += $y_space;
						}
							
						//GEARS
						$armor = filterGeneralOnly($morphGear);
						$apt_x = 83;
						
						//if more than 15 gear then change the fontsize and spaces
						if(count($armor) <= 15)
						{
							$fontsize = 8;
							$y_space = 3.5;
							$apt_y = 171.5;
						}
						else
						{
							$fontsize = 5;
							$y_space = 2;
							$apt_y = 170;	
						} 
						
						foreach($armor as $a)
						{
							if($a->occurence > 1) 
								$occ = "(" . $a->occurence . ") ";
							else 
								$occ = "";
							
							$pdf->SetFont('Lato-Lig', '', $fontsize);
							$pdf->Text($apt_x, $apt_y, formatIt($occ . $a->name));//Gear name 
							
							$pdf->SetFont('Lato-LigIta', '', 6);
							setBookLink($a->name, ($apt_x - 12.5), $apt_y, $p, $pdf);//Gear bookLink
							
							$apt_y += $y_space;
						}
						
						//IMPLANTS
						$armor = filterImplantOnly($morphGear);
						$apt_x = 141;
						
						//if more than 17 implants then change the fontsize and spaces
						if(count($armor) <= 17)
						{ //default
							$fontsize = 8;
							$y_space = 3.5;
							$apt_y = 171.5;
						}
						else
						{ //overflow resize
							$fontsize = 5;
							$y_space = 2;
							$apt_y = 170;	
						} 
						
						foreach($armor as $a)
						{
							if($a->occurence > 1) 
								$occ = "(" . $a->occurence . ") ";
							else 
								$occ = "";
							
							$pdf->SetFont('Lato-Lig', '', $fontsize);							
							$pdf->Text($apt_x, $apt_y, formatIt($occ . $a->name));//Implant name 
							
							$pdf->SetFont('Lato-LigIta', '', 6);
							setBookLink($a->name, ($apt_x + 52), $apt_y, $p, $pdf);//Implant bookLink
							
							$apt_y += $y_space;
						}
						
						//MEMO (all morph bonus malus descriptive only, enargy degat and kinetic degat and melle degat)
						$morphBonusMalus = $_SESSION['cc']->getBonusMalusForMorph($morph);
						writeMemo($pdf,getMorphMemoBM($morphBonusMalus));
						
					}
				
			//===================
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


	$apt_x = 81;
	$apt_y = 81;
	$pdf->SetFont('Lato-Lig', '', 5);
	$pdf->SetXY($apt_x,$apt_y);
	$pdf->MultiCell(95,2,$character->note,0,'l');

	//Bonus/Malus means good/bad in Latin
	//MEMO (all bonus malus descriptive only)
	function writeMemo($pdf,$filteredBM)
	{
		$apt_x = 80;
		$apt_y = 230;
		$y_space = 4;	//vertical spacing for the BM group
		$fontsize = 9;
		$fontsizetxt = 7;

		//if more than 10 Bonus/Malus, resize
		if(count($filteredBM) > 10)
		{
			$fontsize = 7;
			$fontsizetxt = 5;
			$y_space = 3;
		}

		$pdf->SetXY($apt_x,$apt_y);
		foreach($filteredBM as $bm)
		{
			$name = formatIt($bm->name);
			$pdf->SetFont('Lato-Lig', '', $fontsize);
			//If the name is too long, drop the font size accordingly so it fits
			while($pdf->GetStringWidth($name) > 35)
			{
				$fontsize-=1;
				$pdf->SetFontSize($fontsize);
// 				error_log($fontsize."->".$bm->name.":  ".$pdf->GetStringWidth($bm->name));
			}
			$pdf->Cell(45,$y_space,$name,0,0,'l');

			$pdf->SetFont('Lato-Lig', '', $fontsizetxt);

			$pdf->SetX($pdf->GetX()+2);
			$pdf->MultiCell(80,$y_space,$bm->description,0,'l');

			$pdf->Line($apt_x,$pdf->GetY(),$apt_x+45+2+80,$pdf->GetY());
			$pdf->SetX($apt_x);
		}
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
	
	function setBookLink($atomeName, $x, $y, $provider, $pdf)
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
		$pdf->Text($x, $y, "(" . $book . " p." . $page . ")");
	}
?>