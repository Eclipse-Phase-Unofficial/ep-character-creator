<?php
	header("Content-type: application/pdf");

	require_once '../../../php/EPCharacterCreator.php';		
	session_start();

	if(isset($_SESSION['cc']))
	{ 
		
		$pdf = PDF_new();
		
		PDF_begin_document($pdf,"","");
		
		$morphs = $_SESSION['cc']->getCurrentMorphs();
		if($morphs == null || count($morphs) == 0) {
			$errorTxt = "You must have at least one morph !";
	
			PDF_begin_page_ext($pdf, 595, 842,"");
			$fontHand = PDF_load_font($pdf, "Lato-Reg", "iso8859-1", "embedding");
			PDF_setfont($pdf,$fontHand,12);
			PDF_show_xy($pdf, $errorTxt, 50, 750);
			PDF_end_page_ext($pdf,"");
			
		}
		foreach($_SESSION['cc']->getCurrentMorphs() as $m){
		
			$_SESSION['cc']->activateMorph($m);
			
			//================= RECTO
				PDF_begin_page_ext($pdf, 595, 842,"");//A4 RECTO
				
				$image = PDF_load_image($pdf,"auto","./EP_BCK_PDF_R.png", "");
				if ($image != -1) PDF_fit_image($pdf,$image,0,0,"");
				
				$fontHand = PDF_load_font($pdf, "Lato-Reg", "iso8859-1", "embedding");
				PDF_setfont($pdf,$fontHand,12);
				
				$character = $_SESSION['cc']->character;
				
				PDF_show_xy($pdf, formatIt($character->charName), 145, 775);//Character Name
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getCurrentBackground()->name), 145, 750);//Background
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getCurrentFaction()->name), 145, 725);//Faction
				
				$birthGender = " ";
				if($character->birthGender == 'M') $birthGender= 'male';
				else $birthGender = 'female';
				PDF_show_xy($pdf, formatIt($birthGender), 145, 700);//Birth gender
				PDF_show_xy($pdf, formatIt($character->realAge), 145, 675);//Real age
				PDF_show_xy($pdf, formatIt($character->ego->getCredit()), 145, 650);//Credit
				
				$motivations = array_pad($_SESSION['cc']->getMotivations(),5,"");
				PDF_show_xy($pdf, formatIt($motivations[0]), 145, 627);//Motivations 1
				PDF_show_xy($pdf, formatIt($motivations[1]), 145, 616);//Motivations 2
				PDF_show_xy($pdf, formatIt($motivations[2]), 145, 605);//Motivations 3
				PDF_show_xy($pdf, formatIt($motivations[3]), 145, 594);//Motivations 4
				PDF_show_xy($pdf, formatIt($motivations[4]), 145, 583);//Motivations 5
				
				
				PDF_show_xy($pdf, formatIt($character->playerName), 425, 800);//Player
				
				$note = formatItForRect($character->note,3,32);
				PDF_show_xy($pdf, $note[0], 325, 761);//Notes line 1 (32char)
				PDF_show_xy($pdf, $note[1], 325, 750);//Notes line 2
				PDF_show_xy($pdf, $note[2], 325, 739);//Notes line 3
				
				$ego_traits = array_pad($_SESSION['cc']->getCurrentTraits(),13,null);
				PDF_show_xy($pdf, formatIt($ego_traits[0]->name), 425, 715);//Trait 1
				PDF_show_xy($pdf, formatIt($ego_traits[1]->name), 425, 704);//Trait 2
				PDF_show_xy($pdf, formatIt($ego_traits[2]->name), 425, 693);//Trait 3
				PDF_show_xy($pdf, formatIt($ego_traits[3]->name), 425, 682);//Trait 4
				PDF_show_xy($pdf, formatIt($ego_traits[4]->name), 425, 671);//Trait 5
				PDF_show_xy($pdf, formatIt($ego_traits[5]->name), 425, 660);//Trait 6
				PDF_show_xy($pdf, formatIt($ego_traits[6]->name), 425, 649);//Trait 7
				PDF_show_xy($pdf, formatIt($ego_traits[7]->name), 425, 638);//Trait 8
				PDF_show_xy($pdf, formatIt($ego_traits[8]->name), 425, 627);//Trait 9
				PDF_show_xy($pdf, formatIt($ego_traits[9]->name), 425, 616);//Trait 10
				PDF_show_xy($pdf, formatIt($ego_traits[10]->name), 425, 605);//Trait 11
				PDF_show_xy($pdf, formatIt($ego_traits[11]->name), 425, 594);//Trait 12
				PDF_show_xy($pdf, formatIt($ego_traits[12]->name), 425, 583);//Trait 13
				
				
				$morph = $m;
				
				PDF_show_xy($pdf, formatIt($morph->name), 125, 552);//Morph Type
				PDF_show_xy($pdf, formatIt($morph->nickname), 145, 520);//Morph nickname
				PDF_show_xy($pdf, formatIt($morph->location), 145, 495);//Location
				PDF_show_xy($pdf, formatIt($morph->age), 145, 470);//Apparent age
				
				if($morph->gender == 'M')$morphGender = "Male";
				else if($morph->gender == 'F')$morphGender = "Female";
				else $morphGender = "None";
				PDF_show_xy($pdf, formatIt($morphGender), 145, 445);//Gender
				
				$gear_implant = getImplants($_SESSION['cc']->getGearForCurrentMorph());
				$implants = formatItForRect($gear_implant,4,33);
				PDF_show_xy($pdf, $implants[0], 45, 405);//Implant line 1 (33 char)
				PDF_show_xy($pdf, $implants[1], 45, 394);//Implant line 2 
				PDF_show_xy($pdf, $implants[2], 45, 383);//Implant line 3 
				PDF_show_xy($pdf, $implants[3], 45, 372);//Implant line 4 
				
				$morph_traits = getTraits($_SESSION['cc']->getCurrentMorphTraits($m->name));
				$traits = formatItForRect($morph_traits,5,33);
				PDF_show_xy($pdf, $traits[0], 45, 325);//Traits line 1 (33 char)
				PDF_show_xy($pdf, $traits[1], 45, 314);//Traits line 2 
				PDF_show_xy($pdf, $traits[2], 45, 303);//Traits line 3 
				PDF_show_xy($pdf, $traits[3], 45, 292);//Traits line 4 
				PDF_show_xy($pdf, $traits[4], 45, 281);//Traits line 5 
				
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("@-Rep")->getvalue()), 405, 523);//Rep @rep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("C-Rep")->getvalue()), 405, 498);//Rep Crep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("E-Rep")->getvalue()), 405, 473);//Rep Erep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("F-Rep")->getvalue()), 405, 448);//Rep Frep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("G-Rep")->getvalue()), 525, 523);//Rep Grep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("I-Rep")->getvalue()), 525, 498);//Rep Irep
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getReputationByName("R-Rep")->getvalue()), 525, 473);//Rep Rrep
				
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$COGNITION)->getValue()), 360, 375);//Apt COG
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$COORDINATION)->getValue()), 360, 350);//Apt COO
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$INTUITION)->getValue()), 430, 375);//Apt INT
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$REFLEXS)->getValue()), 430, 350);//Apt REF
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$SAVVY)->getValue()), 500, 375);//Apt SAV
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$SOMATICS)->getValue()), 500, 350);//Apt SOM
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getAptitudeByAbbreviation(EPAptitude::$WILLPOWER)->getValue()), 540, 355);//Apt WIL
				
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$TRAUMATHRESHOLD)->getValue()), 360, 287);//Stat TT
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$WOUNDTHRESHOLD)->getValue()), 360, 262);//Stat WT
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$INITIATIVE)->getValue()), 360, 237);//Stat INIT
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$LUCIDITY)->getValue()), 430, 287);//Stat LUC
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$DURABILITY)->getValue()), 430, 262);//Stat DUR
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$SPEED)->getValue()), 430, 237);//Stat SPD
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$INSANITYRATING)->getValue()), 500, 287);//Stat IR
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$DEATHRATING)->getValue()), 500, 262);//Stat DR
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$DAMAGEBONUS)->getValue()), 500, 237);//Stat DB
				PDF_show_xy($pdf, formatIt($_SESSION['cc']->getStatByAbbreviation(EPStat::$MOXIE)->getValue()), 540, 260);//Stat MOX
				
				
				$morph_gear = getGear($_SESSION['cc']->getGearForCurrentMorph());
				$gear = formatItForRect($morph_gear,10,69);
				PDF_show_xy($pdf, $gear[0], 45, 175);//Gear line 1 (69 char)
				PDF_show_xy($pdf, $gear[1], 45, 164);//Gear line 2
				PDF_show_xy($pdf, $gear[2], 45, 153);//Gear line 3
				PDF_show_xy($pdf, $gear[3], 45, 142);//Gear line 4
				PDF_show_xy($pdf, $gear[4], 45, 131);//Gear line 5
				PDF_show_xy($pdf, $gear[5], 45, 120);//Gear line 6
				PDF_show_xy($pdf, $gear[6], 45, 109);//Gear line 8
				PDF_show_xy($pdf, $gear[7], 45, 98);//Gear line 9
				PDF_show_xy($pdf, $gear[8], 45, 87);//Gear line 10
				
				PDF_end_page_ext($pdf,"");
				
				//================== VERSO
				PDF_begin_page_ext($pdf, 595, 842,"");//A4 VERSO
				
				$fontHand = PDF_load_font($pdf, "Lato-Reg", "iso8859-1", "embedding");
				PDF_setfont($pdf,$fontHand,12);
				
				$image = PDF_load_image($pdf,"auto","./EP_BCK_PDF_V.png", "");
				if ($image != -1) PDF_fit_image($pdf,$image,0,0,"");
				
				PDF_show_xy($pdf, formatIt($character->charName), 145, 790);//Character Name
				PDF_show_xy($pdf, formatIt($morph->nickname), 415, 790);//Morph Nickname
				
				
				PDF_setfont($pdf,$fontHand,8);
				
				$left_x_name = 41;
				$left_x_value = 145;
				$left_x_spe = 192;
				$left_y = 725;
				
				$right_x_name = 315;
				$right_x_value = 419;
				$right_x_spe = 467;
				$right_y = 725;
				
				$trans = 20.5;
				
				$left = true;
				
				foreach($_SESSION['cc']->getActiveSkills() as $m){
				
					$sk_name = "";
					if($m->prefix == null || $m->prefix == ""){
						$sk_name = $m->name;
					}
					else{
						$sk_name = $m->prefix.":".$m->name;
					}
					
					if($left){
						PDF_show_xy($pdf, formatIt($sk_name), $left_x_name, $left_y);//Active Skill Name left (max 18 char)
						PDF_show_xy($pdf, formatIt($m->getValue())." %", $left_x_value, $left_y);//Active Skill value left
						PDF_show_xy($pdf, formatIt($m->specialization), $left_x_spe, $left_y);//Active Skill spe left
						
						$left_y = $left_y - $trans;
						$left = false;
						
					}
					else{
						PDF_show_xy($pdf, formatIt($sk_name), $right_x_name, $right_y);//Active Skill Name right 
						PDF_show_xy($pdf, formatIt($m->getValue())." %", $right_x_value, $right_y);//Active Skill value right
						PDF_show_xy($pdf, formatIt($m->specialization), $right_x_spe, $right_y);//Active Skill spe right
						
						$right_y = $right_y - $trans;
						$left = true;
					}
				
				}
				
				
				$left_x_name = 41;
				$left_x_value = 145;
				$left_x_spe = 192;
				$left_y = 165;
				
				$right_x_name = 315;
				$right_x_value = 419;
				$right_x_spe = 467;
				$right_y = 165;
				
				$trans = 20.5;
				
				$left = true;
				
				foreach($_SESSION['cc']->getKnowledgeSkills() as $m){
				
					$sk_name = "";
					if($m->prefix == null || $m->prefix == ""){
						$sk_name = $m->name;
					}
					else{
						$sk_name = $m->prefix.":".$m->name;
					}
					
					if($left){
					
						PDF_show_xy($pdf, formatIt($sk_name), $left_x_name, $left_y);//Knowledge Skill Name left (max 18 char)
						PDF_show_xy($pdf, formatIt($m->getValue())." %", $left_x_value, $left_y);//Knowledge Skill value left
						PDF_show_xy($pdf, formatIt($m->specialization), $left_x_spe, $left_y);//Knowledge Skill spe left
						
						$left_y = $left_y - $trans;
						$left = false;
						
					}
					else{
				
						PDF_show_xy($pdf, formatIt($sk_name), $right_x_name, $right_y);//Knowledge Skill Name right 
						PDF_show_xy($pdf, formatIt($m->getValue())." %", $right_x_value, $right_y);//Knowledge Skill value right
						PDF_show_xy($pdf, formatIt($m->specialization), $right_x_spe, $right_y);//Knowledge Skill spe right
						
						$right_y = $right_y - $trans;
						$left = true;
					}
				
				}
				
				PDF_end_page_ext($pdf,"");
		
		}

		
		//===================
		PDF_end_document($pdf,"");
		
		$data = PDF_get_buffer($pdf);
		
		echo $data;
	} 
	else{
		
		$errorTxt = "Bad news, something went wrong, we can not print your character, verify your character and try again.";
	
		$pdf = PDF_new();
		PDF_begin_document($pdf,"","");
		PDF_begin_page_ext($pdf, 595, 842,"");
		$fontHand = PDF_load_font($pdf,  "Lato-Reg", "iso8859-1", "embedding");
		PDF_setfont($pdf,$fontHand,12);
		PDF_show_xy($pdf, $errorTxt, 50, 750);
		PDF_end_page_ext($pdf,"");
		PDF_end_document($pdf,"");
		$data = PDF_get_buffer($pdf);
			
		echo $data;
	}
	
	function formatIt($string){
		$res = $string;
		if($res == null) $res = " ";
		$res = strtoupper($res);
		return $res;
	}
	
	function formatItForRect($string,$nbr_line,$length){
		$result_slice = str_split(formatIt($string),$length);
		$final_result = array_pad($result_slice,$nbr_line," ");
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

	
	
?>