<?php
	header("Content-type: application/pdf");
	
	header('Content-Disposition: attachment; filename="EP_'.date('j-m-Y').'.pdf"');

	require_once '../../../php/EPCharacterCreator.php';		
	session_start();
	
	if(isset($_SESSION['cc']))
	{ 
		
		//provider for book pages
		$p = new EPListProvider('../../../php/config.ini');
		
		$pdf = PDF_new();
		PDF_begin_document($pdf,"","");
		
		$morphs = $_SESSION['cc']->getCurrentMorphs();

		//PDF EXPORT ================================================================
	
				
			//EGO ================================================================ 
				
				PDF_begin_page_ext($pdf, 595, 842,"");//A4 EGO PAGE
				
				//SET BACKGROUND PDF (BETHER BUT NOT SUPORTED WITH THE PDFLITE ON MY SERVER)---------------------------
				//$canevas = PDF_open_pdi_document ( $pdf , "./EP_BCK_PDF_EGO.pdf", "" );
				//if ($canevas != -1) $canevas_page = PDF_open_pdi_page ( $pdf , $canevas , 1 , "" );
				//if ($canevas_page != -1) PDF_fit_pdi_page ( $pdf , $canevas_page , 0 , 0 , "" );
				
				$searchpath = dirname(dirname(dirname(__FILE__)));//."/input";
				//SET BAGROUNT PNG-----------------------------
				$image = PDF_load_image($pdf,"auto",$searchpath."/version4/exporter/EP_BCK_PDF_EGO.png", "");
				if (!$image) error_log(PDF_get_errmsg($pdf)); 
				else PDF_fit_image($pdf,$image,0,0,"");
			
				//DEFINE FONTS ---------------------------------
				PDF_set_parameter($pdf, "textformat", "utf8");   
				PDF_set_parameter($pdf,"FontOutline","Lato-Lig=".$searchpath."/version4/exporter/Lato-Lig.ttf");
				PDF_set_parameter($pdf,"FontOutline","Lato-LigIta=".$searchpath."/version4/exporter/Lato-LigIta.ttf");
				PDF_set_parameter($pdf,"FontOutline","Lato-Reg=".$searchpath."/version4/exporter/Lato-Reg.ttf");
				$fontHand_standard = PDF_load_font($pdf, "Lato-Lig", "iso8859-1", "embedding=true");
				$fontHand_italic = PDF_load_font($pdf, "Lato-LigIta", "iso8859-1", "embedding");
				$fontHand_bold = PDF_load_font($pdf, "Lato-Reg", "iso8859-1", "embedding");
				if (!$fontHand_standard) error_log("Cannot found standard font !");
				if (!$fontHand_italic) error_log("Cannot found italic font !");
				if (!$fontHand_bold) error_log("Cannot found bold font !");
				PDF_setfont($pdf,$fontHand_standard,10);
				
				//BEGIN FILLING SHEET------------------------------
				$character = $_SESSION['cc']->character;
				
				//NAMES
				PDF_show_xy($pdf, formatIt($character->charName), 175, 808);//Character Name
				PDF_show_xy($pdf, formatIt($character->playerName), 410, 808);//Player Name
				
				//ORIGINES
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getCurrentBackground()->name), 105, 768);//Background
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getCurrentFaction()->name), 105, 748);//Faction
				
				PDF_setfont($pdf,$fontHand_italic,5);
				setBookLink($_SESSION['cc']->getCurrentBackground()->name,250,763,$p,$pdf);//Background bookLink
				setBookLink($_SESSION['cc']->getCurrentFaction()->name,250,743,$p,$pdf);//Faction bookLink
				PDF_setfont($pdf,$fontHand_standard,10);
				
				//AGE - SEX
				$birthGender = " ";
				if($character->birthGender == 'M') $birthGender= 'male';
				else $birthGender = 'female';
				PDF_show_xy($pdf, formatIt($birthGender), 405, 768);//Birth gender
				PDF_show_xy($pdf, formatIt($character->realAge), 405, 748);//Real age
				
				PDF_setfont($pdf,$fontHand_standard,8);
				//CREDIT
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getCredit()), 25, 693);//Credit
				PDF_setfont($pdf,$fontHand_italic,5);
				PDF_show_xy($pdf, "(EP p.137)", 115, 703);//Credit bookLink
				PDF_setfont($pdf,$fontHand_standard,10);
				
				//EGO APTITUDES
				PDF_setfont($pdf,$fontHand_italic,5);
				PDF_show_xy($pdf, "(EP p.122)", 226, 703);//Aptitudes bookLink
				PDF_setfont($pdf,$fontHand_standard,10);
				$aptitudes = $_SESSION['cc']->getAptitudes();
				$y_space = 10;
				$apt_x = 155;
				$apt_y = 693;
				foreach($aptitudes as $apt){
					PDF_show_xy($pdf, formatIt($apt->name), $apt_x, $apt_y);//Apt Name
					PDF_show_xy($pdf, formatIt($apt->value), ($apt_x+100), $apt_y);//Apt Value Ego
					$apt_y -= $y_space;
				}
				
				//REPUTATION
				PDF_setfont($pdf,$fontHand_italic,5);
				PDF_show_xy($pdf, "(EP p.285)", 362, 703);//Reputation bookLink
				PDF_setfont($pdf,$fontHand_standard,10);
				$reputations = $_SESSION['cc']->getReputations();
				$y_space = 10;
				$apt_x = 305;
				$apt_y = 693;
				foreach($reputations as $rep){
					PDF_show_xy($pdf, formatIt($rep->name), $apt_x, $apt_y);//Rep name
					PDF_show_xy($pdf, formatIt($rep->getvalue()), ($apt_x+88), $apt_y);//Rep value
					$apt_y -= $y_space;
				}
				
				//MOTIVATION
				PDF_setfont($pdf,$fontHand_italic,5);
				PDF_show_xy($pdf, "(EP p.120)", 550, 703);//Motivation bookLink
				PDF_setfont($pdf,$fontHand_standard,10);
				$motivations = $_SESSION['cc']->getMotivations();
				$y_space = 10;
				$apt_x = 450;
				$apt_y = 693;
				foreach($motivations as $mot){
					PDF_show_xy($pdf, formatIt($mot), $apt_x, $apt_y);//Motivations 
					$apt_y -= $y_space;
				}
				
				//EGO SKILLS
				PDF_setfont($pdf,$fontHand_italic,5);
				PDF_show_xy($pdf, "(EP p.176)", 155, 608);//Skills bookLink
				PDF_setfont($pdf,$fontHand_standard,8);
				$skillList = $_SESSION['cc']->getSkills();
				$y_space = 10;
				$apt_x = 35;
				$apt_y = 598;
				$i = 1;
				foreach($skillList as $skill){
					if($skill->baseValue > 0 || $skill->defaultable == EPSkill::$DEFAULTABLE){
						if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
						else PDF_setfont($pdf,$fontHand_standard,8);
						$skillCompleteName = "";
						$prefix = $skill->prefix;
						if(!empty($prefix)) $skillCompleteName = $prefix." : ";
						$skillCompleteName .= $skill->name;
						if($skill->defaultable == EPSkill::$NO_DEFAULTABLE) $skillCompleteName .= " *";
						$skillType = "A";
						if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) $skillType = "K";
						PDF_show_xy($pdf, formatIt($skillType), ($apt_x -10), $apt_y);//Skill Type
						PDF_show_xy($pdf, formatIt($skillCompleteName), $apt_x, $apt_y);//Skill complete name
						$hadSpec = false;
						if(!empty($skill->specialization)){
							PDF_setfont($pdf,$fontHand_standard,6);
							PDF_show_xy($pdf, formatIt("spec[".$skill->specialization."]"), $apt_x, ($apt_y-$y_space+2));//Skill speci
							$hadSpec = true;
							if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
							else PDF_setfont($pdf,$fontHand_standard,8);
						}
						PDF_show_xy($pdf, formatIt($skill->getEgoValue()), ($apt_x+155), $apt_y);//Skill ego value;
						if($hadSpec) $apt_y -= ($y_space+8);
						else $apt_y -= $y_space;
						$i++;
					}
				}
				
				PDF_setfont($pdf,$fontHand_standard,7);
				
				//EGO NEG TRAIT
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(),EPTrait::$NEGATIVE_TRAIT);
				$y_space = 10;
				$apt_x = 225;
				$apt_y = 545;
				foreach($egoNegTraits as $trait){
					PDF_show_xy($pdf, formatIt($trait->name), $apt_x, $apt_y);//Trait Neg name 
					PDF_setfont($pdf,$fontHand_italic,5);
					setBookLink($trait->name,$apt_x+75,$apt_y-4,$p,$pdf);//Trait Neg bookLink
					PDF_setfont($pdf,$fontHand_standard,7);
					$apt_y -= $y_space;
				}
				
				//EGO POS TRAIT
				$egoNegTraits = filterPosNegTrait($_SESSION['cc']->getEgoTraits(),EPTrait::$POSITIVE_TRAIT);
				$y_space = 10;
				$apt_x = 330;
				$apt_y = 545;
				foreach($egoNegTraits as $trait){
					PDF_show_xy($pdf, formatIt($trait->name), $apt_x, $apt_y);//Trait Pos name 
					PDF_setfont($pdf,$fontHand_italic,5);
					setBookLink($trait->name,$apt_x+75,$apt_y-4,$p,$pdf);//Trait Pos bookLink
					PDF_setfont($pdf,$fontHand_standard,7);
					$apt_y -= $y_space;
				}
				
				//PSI SLEIGHTS
				$psySleights =  $_SESSION['cc']->getCurrentPsySleights();
				$y_space = 10;
				$apt_x = 450;
				$apt_y = 545;
				foreach($psySleights as $sleight){
					$type = "(P)";
					if($sleight->psyType == EPPsySleight::$ACTIVE_PSY) $type="(A)";
					PDF_show_xy($pdf, formatIt($type), $apt_x, $apt_y);//PsySleight type 
					PDF_show_xy($pdf, formatIt($sleight->name), ($apt_x+13), $apt_y);//PsySleight name 
					PDF_setfont($pdf,$fontHand_italic,5);
					setBookLink($sleight->name,$apt_x+105,$apt_y-4,$p,$pdf);//PsySleight bookLink
					PDF_setfont($pdf,$fontHand_standard,7);

					$apt_y -= $y_space;
				}	
				
				//SOFT GEAR
				$softGears = $_SESSION['cc']->getEgoSoftGears();
				$y_space = 10;
				$apt_x = 245;
				$apt_y = 403;
				
				$fontsize = 7;
						
				if(count($softGears) > 18)
				{
					$fontsize = 5;
					$y_space = 6;
					$apt_y = 407;	
				} 
				
				PDF_setfont($pdf,$fontHand_standard,$fontsize);
				
				
				foreach($softGears as $gear){
					if($gear->occurence > 1) $occ = "(".$gear->occurence.") ";
					else $occ = "";
					PDF_show_xy($pdf, formatIt($occ.$gear->name), $apt_x, $apt_y);//soft gear name 
					PDF_setfont($pdf,$fontHand_italic,5);
					setBookLink($gear->name,$apt_x+95,$apt_y-4,$p,$pdf);//soft gear bookLink
					PDF_setfont($pdf,$fontHand_standard,$fontsize);
					$apt_y -= $y_space;
				}	
				
				//AI
				$ais = $_SESSION['cc']->getEgoAi();
				$y_space = 10;
				$apt_x = 375;
				$apt_y = 403;
				foreach($ais as $ai){
					if($ai->occurence > 1) $occ = "(".$ai->occurence.") ";
					else $occ = "";
					PDF_show_xy($pdf, formatIt($occ.$ai->name), $apt_x, $apt_y);//ai name 
					PDF_setfont($pdf,$fontHand_italic,5);
					setBookLink($ai->name,$apt_x+45,$apt_y-6,$p,$pdf);//soft gear bookLink
					PDF_setfont($pdf,$fontHand_standard,7);
					
					$skillAptNonformated = "";
					foreach($ai->aptitudes as $aiApt){
						$skillAptNonformated .= $aiApt->abbreviation."[";
						$skillAptNonformated .= $aiApt->value."]   ";
					}
					foreach($ai->skills as $aiSkill){
						$skillCompleteName = "";
						$prefix = $aiSkill->prefix;
						if(!empty($prefix)) $skillCompleteName = $prefix." : ";
						$skillCompleteName .= $aiSkill->name;
						$skillAptNonformated .= $skillCompleteName."(";
						$skillAptNonformated .= $aiSkill->baseValue.")  ";
					}
					
					PDF_setfont($pdf,$fontHand_standard,6);
					$aiSkillsApt = formatItForRect($skillAptNonformated,40);
					$paddle = 0;
					foreach($aiSkillsApt as $line){
						PDF_show_xy($pdf, formatIt($line), ($apt_x+75), $apt_y-$paddle);//ai skill apt
						$paddle +=8;
					} 
					$apt_y -= $y_space+$paddle-8;
					PDF_setfont($pdf,$fontHand_standard,7);
				}	
					
				//MEMO (all ego bonus malus descriptive only)
				$egoBonusMalus = $_SESSION['cc']->getBonusMalusEgo();
				$filteredBM = getDescOnlyBM($egoBonusMalus);
				$y_space = 10;
				$apt_x = 228;
				$apt_y = 192;
				
				$fontsize = 8;
				$fontsizetxt = 6;
				$rectlength = 65;
				$paddleIncrement = 8;
				if(count($filteredBM) > 10)
				{
					$fontsize = 5;
					$y_space = 6;
					$apt_y = 186;
					$fontsizetxt = 3;
					$rectlength = 100;	
					$paddleIncrement = 5;
				} 
				PDF_setfont($pdf,$fontHand_standard,$fontsize);
				foreach($filteredBM as $bm){
					PDF_show_xy($pdf, formatIt($bm->name), $apt_x, $apt_y);//bm name 
					PDF_setfont($pdf,$fontHand_standard,$fontsizetxt);
					//error_log($bm->name);
					$bmdescs = formatItForRect($bm->description,$rectlength);
					$paddle = 0;
					foreach($bmdescs as $line){
						PDF_show_xy($pdf, formatIt($line), ($apt_x+135), $apt_y-$paddle);//Bm desc
						$paddle +=$paddleIncrement;
					} 
					$apt_y -= $y_space+$paddle-$paddleIncrement;
					PDF_setfont($pdf,$fontHand_standard,$fontsize);
				}	
				
				//END EGO PAGE
				PDF_end_page_ext($pdf,"");
					
					//MORPHS ============================================================ 
					
					//DO ONE PAGE PER MORPH
					$morphs = $_SESSION['cc']->getCurrentMorphs();
					foreach($morphs as $morph){
						//ACTIVATE THE MORPH
						$_SESSION['cc']->activateMorph($morph);
						PDF_begin_page_ext($pdf, 595, 842,"");//A4 VERSO
						
						//SET MORPH BACKGROUND PDF (BETHER BUT NOT SUPORTED WITH THE PDFLITE ON MY SERVER)---------------------------
						//$canevas = PDF_open_pdi_document ( $pdf , "./EP_BCK_PDF_MORPH.pdf", "" );
						//if ($canevas != -1) $canevas_page = PDF_open_pdi_page ( $pdf , $canevas , 1 , "" );
						//if ($canevas_page != -1) PDF_fit_pdi_page ( $pdf , $canevas_page , 0 , 0 , "" );
						
						//SET BAGROUNT PNG-----------------------------
						$image = PDF_load_image($pdf,"auto",$searchpath."/version4/exporter/EP_BCK_PDF_MORPH.png", "");
						if (!$image) error_log(PDF_get_errmsg($pdf)); 
						else PDF_fit_image($pdf,$image,0,0,"");
						
						PDF_setfont($pdf,$fontHand_standard,10);
	
						//DETAILS DATA
						if($morph->morphType == EPMorph::$BIOMORPH) $type = "[bio]";
						if($morph->morphType == EPMorph::$SYNTHMORPH) $type = "[synth]";
						if($morph->morphType == EPMorph::$INFOMORPH) $type = "[info]";
						if($morph->morphType == EPMorph::$PODMORPH) $type = "[pod]";
						PDF_show_xy($pdf, formatIt($morph->name." ".$type), 160, 808);//morph Name type
						PDF_setfont($pdf,$fontHand_italic,5);
						setBookLink($morph->name,290,804,$p,$pdf);//morph bookLink
						PDF_setfont($pdf,$fontHand_standard,10);
						PDF_show_xy($pdf, formatIt($morph->nickname), 400, 808);//morph nickname
						PDF_show_xy($pdf, formatIt($morph->age), 140, 788);//morph apprent age
						PDF_show_xy($pdf, formatIt($morph->location), 400, 788);//morph Location
						PDF_show_xy($pdf, formatIt($character->playerName), 140, 768);//morph player
						
						$morphGender = " ";
						if($character->birthGender == 'M') $morphGender= 'male';
						else if($character->birthGender == 'F') $morphGender= 'female';
						else $morphGender = 'none';
						PDF_show_xy($pdf, formatIt($morphGender), 400, 768);//morph gender
						
						//MORPH NEG TRAIT
						PDF_setfont($pdf,$fontHand_standard,7);
						$morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph),EPTrait::$NEGATIVE_TRAIT);
						$y_space = 10;
						$apt_x = 20;
						$apt_y = 713;
						foreach($morphNegTraits as $trait){
							PDF_show_xy($pdf, formatIt($trait->name), $apt_x, $apt_y);//Trait Neg name 
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($trait->name,$apt_x+100,$apt_y-4,$p,$pdf);//Trait Neg bookLink
							PDF_setfont($pdf,$fontHand_standard,7);
							$apt_y -= $y_space;
						}
						
						//MORPH POS TRAIT
						PDF_setfont($pdf,$fontHand_standard,7);
						$morphNegTraits = filterPosNegTrait($_SESSION['cc']->getCurrentTraits($morph),EPTrait::$POSITIVE_TRAIT);
						$y_space = 10;
						$apt_x = 150;
						$apt_y = 713;
						foreach($morphNegTraits as $trait){
							PDF_show_xy($pdf, formatIt($trait->name), $apt_x, $apt_y);//Trait pos name 
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($trait->name,$apt_x+100,$apt_y-4,$p,$pdf);//Trait pos bookLink
							PDF_setfont($pdf,$fontHand_standard,7);
							$apt_y -= $y_space;
						}
						
						//MORPH STATS
						PDF_setfont($pdf,$fontHand_italic,5);
						PDF_show_xy($pdf, "(EP p.121)", 345, 723);//Stats bookLink
						PDF_setfont($pdf,$fontHand_standard,8);
						$stats = $_SESSION['cc']->getStats();
						$y_space = 10;
						$apt_x = 290;
						$apt_y = 713;
						$i = 1;
						foreach($stats as $s){
							if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
							else PDF_setfont($pdf,$fontHand_standard,8);
							PDF_show_xy($pdf, formatIt($s->name), $apt_x, $apt_y);//Stat name name 
							PDF_show_xy($pdf, formatIt($s->getValue()), ($apt_x+90), $apt_y);//Stat Value 
							$apt_y -= $y_space;
							$i++;
						}
						
						//MORPH APTITUDES
						PDF_setfont($pdf,$fontHand_italic,5);
						PDF_show_xy($pdf, "(EP p.122)", 460, 723);//Aptitude bookLink
						PDF_setfont($pdf,$fontHand_standard,8);
						$aptitudes = $_SESSION['cc']->getAptitudes();
						$y_space = 10;
						$apt_x = 400;
						$apt_y = 713;
						$i = 1;
						foreach($aptitudes as $apt){
							if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
							else PDF_setfont($pdf,$fontHand_standard,8);
							PDF_show_xy($pdf, formatIt($apt->name), $apt_x, $apt_y);//Apt Name
							PDF_show_xy($pdf, formatIt($apt->getValue()), ($apt_x+95), $apt_y);//Apt Value 
							$apt_y -= $y_space;
							$i++;
						}
						
						//MORPH SKILLS
						PDF_setfont($pdf,$fontHand_italic,5);
						PDF_show_xy($pdf, "(EP p.176)", 155, 615);//Skills bookLink
						PDF_setfont($pdf,$fontHand_standard,8);
						$skillList = $_SESSION['cc']->getSkills();
						$y_space = 10;
						$apt_x = 35;
						$apt_y = 605;
						$i = 1;
						foreach($skillList as $skill){
							if($skill->getValue() > 0){
								if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
								else PDF_setfont($pdf,$fontHand_standard,8);
								$skillCompleteName = "";
								$prefix = $skill->prefix;
								if(!empty($prefix)) $skillCompleteName = $prefix." : ";
								$skillCompleteName .= $skill->name;
								if($skill->defaultable == EPSkill::$NO_DEFAULTABLE) $skillCompleteName .= " *";
								$skillType = "A";
								if($skill->skillType == EPSkill::$KNOWLEDGE_SKILL_TYPE) $skillType = "K";
								PDF_show_xy($pdf, formatIt($skillType), ($apt_x -10), $apt_y);//Skill Type
								PDF_show_xy($pdf, formatIt($skillCompleteName), $apt_x, $apt_y);//Skill complete name
								$hadSpec = false;
								if(!empty($skill->specialization)){
									PDF_setfont($pdf,$fontHand_standard,6);
									PDF_show_xy($pdf, formatIt("spec[".$skill->specialization."]"), $apt_x, ($apt_y-$y_space+2));//Skill speci
									$hadSpec = true;
									if($i%2 == 0) PDF_setfont($pdf,$fontHand_bold,8);
									else PDF_setfont($pdf,$fontHand_standard,8);
								}
								PDF_show_xy($pdf, formatIt($skill->getValue()), ($apt_x+155), $apt_y);//Skill value;
								if($hadSpec) $apt_y -= ($y_space+8);
								else $apt_y -= $y_space;
								$i++;
							}
						}
						
						//NOTES 
						$y_space = 10;
						$apt_x = 235;
						$apt_y = 605;
						PDF_setfont($pdf,$fontHand_standard,6);	
						$note = formatItForRect($character->note,75);
						$paddle = 0;
						foreach($note as $line){
							PDF_show_xy($pdf, formatIt($line), $apt_x, $apt_y-$paddle);//Bm desc
							$paddle +=8;
						} 
						$apt_y -= $y_space+$paddle-8;
						
						$morphGear = $_SESSION['cc']->getGearForCurrentMorph();
						//WEAPONS	
						PDF_setfont($pdf,$fontHand_standard,8);	
						$weapons = filterWeaponOnly($morphGear);
						$y_space = 10;
						$apt_x = 235;
						$apt_y = 525;
						$fontsize = 8;
						
						if(count($weapons) > 8)
						{
							$fontsize = 5;
							$y_space = 6;
							$apt_y = 528;	
						} 
						
						PDF_setfont($pdf,$fontHand_standard,$fontsize);
						foreach($weapons as $w){
							$type = "kinetic";
							if($w->gearType == EPGear::$WEAPON_ENERGY_GEAR) $type="energy";
							if($w->gearType == EPGear::$WEAPON_EXPLOSIVE_GEAR) $type="explos.";
							if($w->gearType == EPGear::$WEAPON_SPRAY_GEAR) $type="spray";
							if($w->gearType == EPGear::$WEAPON_SEEKER_GEAR) $type="seeker";
							if($w->gearType == EPGear::$WEAPON_AMMUNITION) $type="ammo";
							if($w->gearType == EPGear::$WEAPON_MELEE_GEAR) $type="melee";
							if($w->occurence > 1) $occ = "(".$w->occurence.") ";
							else $occ = "";
							PDF_show_xy($pdf, formatIt("[".$type."]"), $apt_x, $apt_y);//Weapon type 
							PDF_show_xy($pdf, formatIt($occ.$w->name), ($apt_x+40), $apt_y);//Weapon name 
							PDF_show_xy($pdf, formatIt("DV: ".$w->degat), ($apt_x+180), $apt_y);//Weapon degats
							PDF_show_xy($pdf, formatIt("AP : ".$w->armorPenetration), ($apt_x+280), $apt_y);//Weapon Armor penetration 
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($w->name,$apt_x+315,$apt_y,$p,$pdf);//Weapon bookLink
							PDF_setfont($pdf,$fontHand_standard,$fontsize); 
							$apt_y -= $y_space;
						}
						//ARMORS	
						PDF_setfont($pdf,$fontHand_standard,8);	
						$armor = filterArmorOnly($morphGear);
						$y_space = 10;
						$apt_x = 235;
						$apt_y = 433;
						
						$fontsize = 8;
						
						if(count($armor) > 5)
						{
							$fontsize = 5;
							$y_space = 6;
							$apt_y = 436;	
						} 
						
						PDF_setfont($pdf,$fontHand_standard,$fontsize);

						
						foreach($armor as $a){
							if($a->occurence > 1) $occ = "(".$a->occurence.") ";
							else $occ = "";
							PDF_show_xy($pdf, formatIt($occ.$a->name), ($apt_x), $apt_y);//Armor name 
							if($a->armorKinetic == 0 && $a->armorEnergy==0){
								PDF_show_xy($pdf, formatIt("see memo"), ($apt_x+180), $apt_y);//No protec, see memeo
							}
							else{
								PDF_show_xy($pdf, formatIt("Kin: ".$a->armorKinetic), ($apt_x+180), $apt_y);//Armor Kinetic
								PDF_show_xy($pdf, formatIt("Ene: ".$a->armorEnergy), ($apt_x+210), $apt_y);//Armor Energy
							}
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($a->name,$apt_x+315,$apt_y,$p,$pdf);//Armor bookLink
							PDF_setfont($pdf,$fontHand_standard,$fontsize); 
							$apt_y -= $y_space;
						}
						
						//GEARS
						PDF_setfont($pdf,$fontHand_standard,7);	
						$armor = filterGeneralOnly($morphGear);
						$y_space = 10;
						$apt_x = 235;
						$apt_y = 357;
						$fontsize = 7;
						if(count($armor) > 15)
						{
							$fontsize = 5;
							$y_space = 6;
							$apt_y = 362;	
						} 
						PDF_setfont($pdf,$fontHand_standard,$fontsize);
						foreach($armor as $a){
							if($a->occurence > 1) $occ = "(".$a->occurence.") ";
							else $occ = "";
							PDF_show_xy($pdf, formatIt($occ.$a->name), ($apt_x), $apt_y);//Gear name 
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($a->name,$apt_x+120,$apt_y-4,$p,$pdf);//Gear bookLink
							PDF_setfont($pdf,$fontHand_standard,$fontsize);
							$apt_y -= $y_space;
						}
						
						//IMPLANTS
						PDF_setfont($pdf,$fontHand_standard,7);	
						$armor = filterImplantOnly($morphGear);
						$y_space = 10;
						$apt_x = 400;
						$apt_y = 357;
						$fontsize = 7;
						if(count($armor) > 17)
						{
							$fontsize = 5;
							$y_space = 6;
							$apt_y = 362;	
						} 
						PDF_setfont($pdf,$fontHand_standard,$fontsize);	
						foreach($armor as $a){
							if($a->occurence > 1) $occ = "(".$a->occurence.") ";
							else $occ = "";
							PDF_show_xy($pdf, formatIt($occ.$a->name), ($apt_x), $apt_y);//Implant name 
							PDF_setfont($pdf,$fontHand_italic,5);
							setBookLink($a->name,$apt_x+140,$apt_y-4,$p,$pdf);//Implant bookLink
							PDF_setfont($pdf,$fontHand_standard,$fontsize);
							$apt_y -= $y_space;
						}
						
						//MEMO (all morph bonus malus descriptive only, enargy degat and kinetic degat and melle degat)
						PDF_setfont($pdf,$fontHand_standard,8);
						$morphBonusMalus = $_SESSION['cc']->getBonusMalusForMorph($morph);
						$filteredBM = getMorphMemoBM($morphBonusMalus);
						$y_space = 10;
						$apt_x = 228;
						$apt_y = 184;
						$fontsize = 8;
						$fontsizetxt = 6;
						$rectlength = 65;
						$paddleIncrement = 8;
						if(count($filteredBM) > 10)
						{
							$fontsize = 5;
							$y_space = 6;
							$apt_y = 186;
							$fontsizetxt = 3;
							$rectlength = 100;	
							$paddleIncrement = 5;
						} 
						
						PDF_setfont($pdf,$fontHand_standard,$fontsize);
						foreach($filteredBM as $bm){
							PDF_show_xy($pdf, formatIt($bm->name), $apt_x, $apt_y);//bm name 
							PDF_setfont($pdf,$fontHand_standard,$fontsizetxt);
							
							$bmdescs = formatItForRect($bm->description,$rectlength);
							$paddle = 0;
							foreach($bmdescs as $line){
								PDF_show_xy($pdf, formatIt($line), ($apt_x+135), $apt_y-$paddle);//Bm desc
								$paddle +=$paddleIncrement;
							} 
							$apt_y -= $y_space+$paddle-$paddleIncrement;
							PDF_setfont($pdf,$fontHand_standard,$fontsize);
						}	

										
											
						PDF_end_page_ext($pdf,"");
					}
			
	
			//===================
			PDF_end_document($pdf,"");
			
			$data = PDF_get_buffer($pdf);
			echo $data;
		
	} 
	//NO CHARACTER CREATOR ! ================================================
	else{
		
		$errorTxt = "Bad news, something went wrong, we can not print your character, verify your character and try again.";
	
		$pdf = PDF_new();
		PDF_begin_document($pdf,"","");
		PDF_begin_page_ext($pdf, 595, 842,"");
		$fontHand = PDF_load_font($pdf,  "Lato-Reg", "iso8859-1", "embedding");
		if ($fontHand == -1 || $fontHand == 0) die("Cannot found standard font !");
		PDF_setfont($pdf,$fontHand,12);
		PDF_show_xy($pdf, $errorTxt, 50, 750);
		PDF_end_page_ext($pdf,"");
		PDF_end_document($pdf,"");
		$data = PDF_get_buffer($pdf);
			
		echo $data;
	}
	
	
	
	//HELPERS ===============================================================
	
	function formatIt($string){
		$res = $string;
		if($res == null) $res = " ";
		$res = strtoupper($res);
		return $res;
	}
	
	function formatItForRect($string,$length){
		$final_result = str_split(formatIt($string),$length);
		return $final_result;
	}
	
	function getImplants($objArray){
		$final = "*";
		foreach($objArray as $m){
			if($m->gearType == EPGear::$IMPLANT_GEAR){
				$final .= $m->name."*";
			}
		}
		return $final;
	}
	
	function getGear($objArray){
		$final = "*";
		foreach($objArray as $m){
			if($m->gearType =! EPGear::$IMPLANT_GEAR){
				$final .= $m->name."*";
			}
		}
		return $final;
	}
	
	function getTraits($objArray){
		$final = "*";
		foreach($objArray as $m){
			$final .= $m->name."*";
		}
		return $final;
	}
	
	function filterPosNegTrait($traits,$type){
		$result = array();
		foreach($traits as $t){
			if($t->traitPosNeg == $type){
				array_push($result, $t);
			}
		}
		return $result;
	}
	
	function getDescOnlyBM($bonusMalus){
		$result = array();
		foreach($bonusMalus as $bm){
			if($bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY){
				array_push($result, $bm);
			}
		}
		return $result;
	}
	
	function getMorphMemoBM($bonusMalus){
		$result = array();
		foreach($bonusMalus as $bm){
			if( $bm->bonusMalusType == EPBonusMalus::$DESCRIPTIVE_ONLY ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_ARMOR ||
				$bm->bonusMalusType == EPBonusMalus::$ON_ENERGY_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_KINETIC_WEAPON_DAMAGE ||
				$bm->bonusMalusType == EPBonusMalus::$ON_MELEE_WEAPON_DAMAGE){
				array_push($result, $bm);
			}
		}
		return $result;
	}
	
	function filterWeaponOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$WEAPON_MELEE_GEAR ||
				$g->gearType == EPGear::$WEAPON_ENERGY_GEAR ||	
				$g->gearType == EPGear::$WEAPON_KINETIC_GEAR ||
				$g->gearType == EPGear::$WEAPON_AMMUNITION ||
				$g->gearType == EPGear::$WEAPON_SEEKER_GEAR ||
				$g->gearType == EPGear::$WEAPON_SPRAY_GEAR){
					array_push($result, $g);
			}
			if( $g->gearType == EPGear::$IMPLANT_GEAR ){
				if($g->degat != "0"){
					array_push($result, $g);
				}
				
			}
		}
		return $result;
	}
	
	function filterArmorOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$ARMOR_GEAR){
					array_push($result, $g);
			}
		}
		return $result;
	}
	
	function filterImplantOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$IMPLANT_GEAR){
					array_push($result, $g);
			}
		}
		return $result;
	}

	
	function filterGeneralOnly($gears){
		$result = array();
		foreach($gears as $g){
			if( $g->gearType == EPGear::$STANDARD_GEAR ||
				$g->gearType == EPGear::$DRUG_GEAR ||	
				$g->gearType == EPGear::$CHEMICALS_GEAR ||
				$g->gearType == EPGear::$POISON_GEAR ||
				$g->gearType == EPGear::$PET_GEAR ||
				$g->gearType == EPGear::$VEHICLES_GEAR ||
				$g->gearType == EPGear::$ROBOT_GEAR ||
				$g->gearType == EPGear::$FREE_GEAR ){
					array_push($result, $g);
			}
		}
		return $result;
	}
	
	function setBookLink($atomeName,$x,$y,$provider,$pdf){
		$bookFullName = $provider->getBookForName($atomeName);
		if($bookFullName == EPListProvider::$BOOK_ECLIPSEPHASE) $book = "EP";
		else if($bookFullName == EPListProvider::$BOOK_TRANSHUMAN) $book = "TH";
		else if($bookFullName == EPListProvider::$BOOK_GATECRASHING) $book = "GC";
		else if($bookFullName == EPListProvider::$BOOK_SUNWARD) $book = "SW";
		else if($bookFullName == EPListProvider::$BOOK_PANOPTICON) $book = "PAN";
		else if($bookFullName == EPListProvider::$BOOK_RIMWARD) $book = "RW";
		else $book = "??";
		$page = $provider->getPageForName($atomeName);
		PDF_show_xy($pdf, "(".$book." p.".$page.")", $x, $y);
	}
	
	
?>