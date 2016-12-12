
var firstTime = true;

var TERTIARY_INFO_HTML = "<div id='tertiary_infos'></div>";
var QUATERNARY_INFO_HTML = "<div id='quaternary_infos'></div>";
var USER_MSG_HTML = "<div id='user-messages'></div>";

var DISPLAY_ON_4 = 4;
var DISPLAY_ON_3 = 3;
var DISPLAY_ON_TOP = 1;
var DISPLAY_ON_MSG = 0;

var focusOn = "";
var focusOnSkill = "";

$(document).ready(function(){

        //FIRST OPEN WEB PAGE -- INIT
        if(firstTime){
        	
        	//hide help panel
        	$(".help").hide();
        	$("#messages").fadeOut();
        	
        	startLoading();
        	//initialize character and extract first data
            ajax_helper({
                        firstTime : 'first',
                        getCrePoint : 'get'
                },
                function(response){
                		if(response.versioningFault){
	                        closeAllPopup();
	                        endLoading();
                            loadPopup("#load_popup","popup-contents/load.php");
                        }
                        else if(response.sessionExist){
	                        setRemainingPoint(response);
	                        loaddingLoad();
                        }
                        else{
	                        closeAllPopup();
	                        endLoading();
				        	loadPopup("#reset_popup","popup-contents/reset.php");
                        }
                });
            firstTime = false;
        }

        //HELPS (Sliding from the bottom)
        //first html page (don't know why the second option is not working for the first page)
        $('.btnhelp').on('click' , function () {
            do_ajax({infosId : $(this).attr('id')},
                    function(response){
                        displayMessageOnTop(response.infoData);
            });
        return false;
        });

        //dynamic pages  (morph page)
        $(document).on('click', '.btnhelp' , function () {
            do_ajax({infosId : $(this).attr('id')},
                    function(response){
                        displayMessageOnTop(response.infoData);
            });
        return false;
        });

        //BACKGROUND
        //click on main menu
        $("a.background").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/backgrounds.php");
            do_ajax({getBcg : 'get'},function(response){
                if(response.currentBcg != null){
                    //displayMessageOnTertiary(response.desc);
                    $("#secondary").load("secondary-choice/backgrounds.php");
                    $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                }
            });
			return false;
    	});
    	
    	//click on background
        $(document).on('click', '.bck' , function () {
            do_ajax({
                        origine : $(this).attr('id'),
                        getCrePoint : 'get'
                },
                function(response){
                            //displayMessageOnTertiary(response.desc);
                            $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                            $("#secondary").load("secondary-choice/backgrounds.php");
                            setRemainingPoint(response);
            });
            return false;
        });
               
        //FACTION
        //click on main menu
        $("a.faction").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/factions.php");
    		do_ajax({
                            getFac : 'get'
                    },
                    function(response){
                        if(response.currentFac != null){
                            $("#secondary").load("secondary-choice/factions.php");
                            $("#tertiary").load("tertiary-choice/factionBMD.php");
                            //displayMessageOnTertiary(response.desc);
                        }
            });
    		return false;
    	});
		
		//click on faction
        $(document).on('click', '.fac' ,function () {
                do_ajax( {
                            faction : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/factions.php");
								$("#tertiary").load("tertiary-choice/factionBMD.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
                    });
				return false;
        
        });
        //MOTIVATION
        //click on main menu
        $("a.motivations").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/motivations.php");
    		return false;
    	});
    	//enter key on the motivation text field
    	$(document).on('keydown', '#motToAdd' ,function (e) {
    		if(e.keyCode == 13) {
    	 		do_ajax( {
                            newMot : $('#motToAdd').val()                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/motivations.php");
                    });
				return false;
			}
        });
    	
    	//click on addButton
    	$(document).on('click', '#addMotiv' ,function () {
                do_ajax( {
                            newMot : $('#motToAdd').val()                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/motivations.php");
                    });
				return false;
        
        });
        //click on removeButton
    	$(document).on('click', '.remMotiv' ,function () {
                do_ajax( {
                            remMot : $(this).attr('id')
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/motivations.php");
                    });
				return false;
        
        });
    	
        //APTITUDES
        //click on main menu
        $("a.aptitudes").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/aptitudes.php");
			return false;
    	});
    	
    	//before apt chage get the focused html element
        $(document).on('focusin', '#COG,#COO,#INT,#REF,#SAV,#SOM,#WIL' ,function (e) {
        		focusOn = "#"+($(this).attr('id'));
				//console.log("focin"+focusOn);
        });

    	//apt value change
        $(document).on('change ', '#COG,#COO,#INT,#REF,#SAV,#SOM,#WIL' ,function (e) {
                ajax_helper( {
                            cog : $('#COG').val(),
                            coo : $('#COO').val(),
                            int : $('#INT').val(),
                            ref : $('#REF').val(),
                            sav : $('#SAV').val(),
                            som : $('#SOM').val(),
                            wil : $('#WIL').val(),
                            getCrePoint : 'get'
                    },
                    function(response){
                    		$('#COG').css("background-color", "#FEFEFE");
                			$('#COO').css("background-color", "#FEFEFE");
                			$('#INT').css("background-color", "#FEFEFE");
                			$('#REF').css("background-color", "#FEFEFE");
                			$('#SAV').css("background-color", "#FEFEFE");
                			$('#SOM').css("background-color", "#FEFEFE");
                			$('#WIL').css("background-color", "#FEFEFE");
                    		if(response.error){
                    			 treatMessageError(response);
                    			 $("#"+response.aptError).css("background-color", "#BA0050");
                    		}
                    		else {
                    			setRemainingPoint(response);
                    			$("#secondary").load("secondary-choice/aptitudes.php" , function(){
                    				//console.log("change"+focusOn);
	                    			$(focusOn).focus();
                    			});
                    		}
                            
                    });
				return false;
        
        });
        
        //click on a aptitude for description
    	$(document).on('click', '.apt' ,function () {
    			hideErrorsMsg();
        		var aptNameTotal = $(this).attr('id');
        		var aptName = aptNameTotal.substr(0, 3);
                do_ajax( {
                            apt :aptName             
                    },
                    function(response){
                    		 	displayMessageOnTertiary(response.desc);
                    });
				return false;
        
        });
        
         //click on a morph for apts
    	$(document).on('click', '.aptMorph' ,function () {
    			hideErrorsMsg();
        		var morphName = $(this).attr('id');	
                do_ajax( {
                            currentMorphUsed :morphName             
                    },
                    function(response){
                    		 	$("#tertiary").load("tertiary-choice/aptsWithMorph.php");
                    });
				return false;
        
        });

        
        
        //REPUTATIONS
        //click on main menu
        $("a.rep").click(function(){
        	hideErrorsMsg();
        	do_ajax( {
	                    getCrePoint : 'get'
	            },
	            function(response){
							setRemainingPoint(response);
							$("#secondary").load("secondary-choice/reputations.php");
	            });

			return false;
		});
		
		//before rep chage get the focused html element
        $(document).on('focusin', '#\\@-Rep,#G-Rep,#C-Rep,#I-Rep,#E-Rep,#R-Rep,#F-Rep' ,function (e) {
        		focusOn = "#"+($(this).attr('id'));
				//console.log("focin"+focusOn);
        });
		
		//Rep value change
	    $(document).on('change', '#\\@-Rep,#G-Rep,#C-Rep,#I-Rep,#E-Rep,#R-Rep,#F-Rep',function() {
	        ajax_helper({
	                    atrep : $('#\\@-Rep').val(),
	                    grep : $('#G-Rep').val(),
	                    crep : $('#C-Rep').val(),
	                    irep : $('#I-Rep').val(),
	                    erep : $('#E-Rep').val(),
	                    rrep : $('#R-Rep').val(),
	                    frep : $('#F-Rep').val(),
	                    getCrePoint : 'get'
	            },
	            function(response){
	            		$('#\\@-Rep').css("background-color", "#FEFEFE");
                		$('#G-Rep').css("background-color", "#FEFEFE");
                		$('#C-Rep').css("background-color", "#FEFEFE");
                		$('#I-Rep').css("background-color", "#FEFEFE");
                		$('#E-Rep').css("background-color", "#FEFEFE");
                		$('#R-Rep').css("background-color", "#FEFEFE");
                	    $('#F-Rep').css("background-color", "#FEFEFE");
	                   if(response.error){
                			 treatMessageError(response);
                			 $("#"+response.repError).css("background-color", "#BA0050");                		
						}
                		else {
							setRemainingPoint(response);
							$("#secondary").load("secondary-choice/reputations.php", function(){
	                    			$(focusOn).focus();
                    		});
                		}
	            });
			return false;
		});
		
		//click on a reputation for description
    	$(document).on('click', '.rep' ,function () {
    			hideErrorsMsg();
        		var repNameTotal = $(this).attr('id');	
        		var repName = repNameTotal.substr(0, 5);
                do_ajax( {
                            rep :repName             
                    },
                    function(response){
                    		 	displayMessageOnTertiary(response.desc);
                    });
				return false;
        
        });
		
    	//POSITIVE TRAITS
    	//click on mainmenu
    	$("a.positive-traits").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/positive-traits.php");
			return false;
		});
		//hover on pos trait
		 $(document).on('click', '.posTrait' ,function () {
		 	do_ajax( {
                            posTraitHover : $(this).attr('id')
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
                    });

		 	return false;
        
        });
        
        
		//click on pos trait
        $(document).on('click', '.addPosTraitIcon,.selPosTraitIcon' ,function () {
                do_ajax( {
                            posTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/positive-traits.php");
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
                    });
				return false;
        
        });
        //NEGATIVE TRAITS
    	//click on mainmenu
    	$("a.negative-traits").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/negative-traits.php");
   			return false;
		});
		//hover on neg trait
		 $(document).on('click', '.negTrait' ,function () {
		 	do_ajax( {
                            negTraitHover : $(this).attr('id')
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
                    });

		 	return false;
        
        });

		//click on neg trait
        $(document).on('click', '.addNegTraitIcon,.selNegTraitIcon' ,function () {
                do_ajax( {
                            negTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnTertiary(response.desc);
								$("#secondary").load("secondary-choice/negative-traits.php");
								$("#tertiary").load("tertiary-choice/traitBMD.php");
								setRemainingPoint(response);
                    });
				return false;
        });
        
         //NEUTRAL TRAITS
    	//click on mainmenu
    	$("a.neutral-traits").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/neutral-traits.php");
   			return false;
		});
		//hover on neu trait
		 $(document).on('mouseover', '.neuTrait' ,function () {
		 	do_ajax( {
                            negTraitHover : $(this).attr('id')
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
                    });

		 	return false;
        
        });

		//click on neu trait
        $(document).on('click', '.neuTrait' ,function () {
                do_ajax( {
                            negTrait : $(this).attr('id'), //use negTrait, but dont matter, cost = 0
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnTertiary(response.desc);
								$("#secondary").load("secondary-choice/neutral-traits.php");
								$("#tertiary").load("tertiary-choice/traitBMD.php");
								setRemainingPoint(response);
                    });
				return false;
        });

        
        //PSI SLEIGHTS  
        //click on mainmenu
    	$("a.psy").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/psy-sleights.php", function(){
    			setupFoldingList();
    		});

			return false;
		});
		//click on psi
        $(document).on('click', '.addPsySleightIcon,.selPsySleightIcon' ,function () {
                do_ajax( {
                            psyS : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/psy-sleights.php", function(){
					    			setupFoldingList();
					    		});

                    			$("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
                    });
				return false;
        
        });
        
        //hover on psi
		$(document).on('click', '.psyS' ,function () {
                do_ajax( {
                            hoverPsyS : $(this).attr('id')
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    });
				return false;
        
        });
        
        
		//ACTIVE SKILLS
    	//click on mainmenu
    	$("a.active-skills").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/active-skills.php");
			return false;
		});
		
		
		//before active skill change get the focused html element
        $(document).on('focusin', '.actskillbase' ,function (e) {
        		focusOnSkill = "[id='"+$(this).attr('id')+"']";
				//console.log("focin"+focusOn);
        });
		
		//change active skill value
		$(document).on('change ', '.actskillbase' ,function () {
            changeSkill($(this), "secondary-choice/active-skills.php");
            return false;
		});
		
		//add a temp active skill
		$(document).on('click', '#addActSkill' ,function () {
                do_ajax( {
                            newTmpActSkill : $('#actToAdd').val(),
                            newTmpActSkillPrefix : $('#actprefix').val()
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/active-skills.php");
                    });
				return false;
        });
        //return key in the temp active field 
        $(document).on('keydown', '#actToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	                do_ajax( {
	                            newTmpActSkill : $('#actToAdd').val(),
	                            newTmpActSkillPrefix : $('#actprefix').val()
	                    },
	                    function(response){
	                    			$("#secondary").load("secondary-choice/active-skills.php");
	                    });
					return false;
				}
        });
        
        //remove a temp active skill
        $(document).on('click', '.remActSkill' ,function () {
            removeSkill($(this), "secondary-choice/active-skills.php");
            return false;
        });
        
		//remove specialization
		 $(document).on('click', '.remSpeSkill' ,function () {
	           do_ajax( {
	                        remSpeSkillName : $(this).attr('data-skillname'),
	                        getCrePoint : 'get'
	                },
	                function(response){
                                var comeFrom = $('.skills').attr('id');
                    			if(comeFrom == "actSkills"){
                    				$("#secondary").load("secondary-choice/active-skills.php");
                    			}
                    			else{
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    			}
	                			setRemainingPoint(response);
	                });
				return false;
        });
       
		
		//KNOWLEDGE SKILLS
		//click on mainmenu
    	$("a.knowledge-skills").click(function(){
    		hideErrorsMsg();
    		$("#secondary").load("secondary-choice/knowledge-skills.php");
			return false;
		});
		
		//before knowlege skill change get the focused html element
        $(document).on('focusin', '.knoskillbase' ,function (e) {
        		focusOnSkill = "[id='"+$(this).attr('id')+"']";
				//console.log("focin"+focusOn);
        });
		
		//change knowlege skill value
		$(document).on('change ', '.knoskillbase' ,function () {
            changeSkill($(this), "secondary-choice/knowledge-skills.php");
            return false;
		});
		//Add the native language
		$(document).on('click', '#addNativeLanguage' ,function () {
                do_ajax( {
                            newNatLanguageSkill : $('#langToAdd').val()
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    });
				return false;
        });
        //return key in the native language 
        $(document).on('keydown', '#langToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	                do_ajax( {
	                            newNatLanguageSkill : $('#langToAdd').val()
	                    },
	                    function(response){
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
	                    });
					return false;
				}
        });

		
		//add a temp knowledge  skill
		$(document).on('click', '#addKnowSkill' ,function () {
                do_ajax( {
                            newTmpKnoSkill : $('#knoToAdd').val(),
                            newTmpKnoSkillPrefix : $('#knoprefix').val()
                    },
                    function(response){
                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    });
				return false;
        
        });
        //Return key on the knowledge skill
        $(document).on('keydown', '#knoToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	        		do_ajax( {
	                            newTmpKnoSkill : $('#knoToAdd').val(),
	                            newTmpKnoSkillPrefix : $('#knoprefix').val()
	                    },
	                    function(response){
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
	                    });
					return false;
			  }
        });
        
        //remove a temp knowlege skill
        $(document).on('click', '.remKnowSkill' ,function () {
            removeSkill($(this), "secondary-choice/knowledge-skills.php");
            return false;
        });
		
		//GENERAL SKILLS
		//click on skill for desc
        $(document).on('click', '.skName' ,function () {
                do_ajax( {
                            skill : $(this).attr('data-skillname')
					},
                    function(response){
                    			displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //click + skill specialization
        $(document).on('click', '.addSkillSpec' ,function () {
        		var id_spez = '#spezBox'+$(this).attr('data-skillname').replace(/[\/\s_]+/g, '');

        		if($(id_spez).css('visibility') == 'hidden'){
                	$(id_spez).css('visibility', 'visible').find(".spezInt").focus();
                }
                else{
	               $(id_spez).css('visibility', 'hidden'); 
	               var speId = '#spe_'+$(this).attr('data-skillname').replace(/[\/\s_]+/g, '');
				   var speVal = $(speId).val();

				   if(speVal != null || speVal != ""){
		               do_ajax( {
		                            addSpe : speVal,
		                            addSpeSkillName : $(this).attr('id'),
		                            getCrePoint : 'get'
		                    },
		                    function(response){
		                    			var comeFrom = $('.skills').attr('id');
		                    			if(comeFrom == "actSkills"){
		                    				$("#secondary").load("secondary-choice/active-skills.php");
		                    			}
		                    			else{
			                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
		                    			}		                    			
		                    			setRemainingPoint(response);
		                    });
					}
                }
				return false;
        });
        //enter on the input text
        $(document).on('keypress','.skName',function (e) {
		  if (e.which == 13) {
		    	$(this).css('visibility') == 'hidden';
		    	var speId = '#spe_'+$(this).attr('id').replace(/[\/\s_]+/g, '');
		    	var speVal = $(speId).val();

		    	if(speVal != null || speVal != ""){
	               do_ajax( {
	                            addSpe : speVal,
	                            addSpeSkillName : $(this).attr('data-skillname'),
	                            getCrePoint : 'get'
	                    },
	                    function(response){
		                    			var comeFrom = $('.skills').attr('id');
		                    			if(comeFrom == "actSkills"){
		                    				$("#secondary").load("secondary-choice/active-skills.php");
		                    			}
		                    			else{
			                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
		                    			}
		                    			setRemainingPoint(response);
	                    });
				}
		  }
		});
        
        //MORPHS
        //click on main menu
        $("a.morph").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/morph.php", function(){
    			setupFoldingList();
    		});
    	});
    	
    	//hover on morph
		 $(document).on('click', '.addMorph,.remMorph' ,function () {
		 	do_ajax( {
                            morphHover : $(this).attr('id')
                    },
                    function(response){
                    			displayMessageOnTertiary(response.desc, response.title);
                    });

		 	return false;
        
        });

    	
    	//click on add morph
        $(document).on('click','.addMorphIcone' ,function () {
        		hideErrorsMsg();
                do_ajax( {
                            addMorph : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                                displayMessageOnTertiary(response.desc, response.title);
								$("#secondary").load("secondary-choice/morph.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //click on remove morph
        $(document).on('click','.remMorphIcone' ,function () {
        		hideErrorsMsg();
                do_ajax( {
                            remMorph : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#secondary").load("secondary-choice/morph.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
                    });
				return false;
        });
        
         //click on morph bonus and description
        $(document).on('click','.morph-BMD', function() {
        	 hideErrorsMsg();
	         do_ajax( {
                            currentMorphUsed : $(this).attr('id')
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/morphBMD.php");
                    });
			return false;
        });
        
        //click on morph settings
        $(document).on('click','.morph-settings', function() {
        	 hideErrorsMsg();
	         do_ajax( {
                            morphSettings : $(this).attr('id'),
                            currentMorphUsed : $(this).attr('id')
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/morphSettings.php", function(){
									$("#morphName").attr('value',response.morphName);									
									$("#mNickname").val(response.nickname);
									$("#mLocation").val(response.location);
									$("#mAge").val(response.age);
									$("#mGender").val(response.gender);
									$("#mMaxApt").html("["+response.morphMaxApt+"]");
									$("#mDur").html("["+response.morphDur+"]");
								});
                    });
			return false;
        });
        
        //morph settings changes
         $(document).on('change', '#mNicknamem,#mLocation,#mAge,#mGender' ,function () {
          do_ajax( {
                            morphSettingsChange : $("#morphName").attr('value'),
							morphNickname : $("#mNickname").val(),
							morphLocation : $("#mLocation").val(),
							morphAge : $("#mAge").val(),  
							morphGender : $("#mGender").val()                  
                    },
                    function(response){}
          );
			return false;
        });
        
        //click on morph pos traits button
        $(document).on('click','.morph-positive-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				do_ajax( {
                            currentMorphUsed : morphName
                    },
                    function(response){
	                            $("#tertiary").load("tertiary-choice/morphPosTraits.php");
                    });
				return false;
        });
        
        //click on morph pos trait for selection deselection
        $(document).on('click', '.addMorphPosTraitIcon,.selMorphPosTraitIcon' ,function () {
                do_ajax( {
                            morphPosTrait : $(this).attr('id').trim(),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphPosTraits.php");
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;
        });
        
         //click on morph neutral traits button
        $(document).on('click','.morph-neutral-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				do_ajax( {
                            currentMorphUsed : morphName
                    },
                    function(response){
	                            $("#tertiary").load("tertiary-choice/morphNeuTraits.php");
                    });
				return false;
        });
        
        //click on morph neutral trait for selection deselection
        $(document).on('click', '.addMorphNeuTraitIcon,.selMorphNeuTraitIcon' ,function () {
                do_ajax( {
                            morphPosTrait : $(this).attr('id'), //keep call to posTrait, it's the same anyway
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphNeuTraits.php");
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //click on morph neg traits button
        $(document).on('click','.morph-negative-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				do_ajax( {
                            currentMorphUsed : morphName
                    },
                    function(response){
	                            $("#tertiary").load("tertiary-choice/morphNegTraits.php");
                    });
				return false;
        });
        //click on morph neg trait for selection deselection
        $(document).on('click', '.addMorphNegTraitIcon,.selMorphNegTraitIcon' ,function () {
                do_ajax( {
                            morphNegTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphNegTraits.php");	
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //hover on morph pos or neg or neu trait
        $(document).on('click', '.morphPosTrait,.morphNegTrait,.morphNeuTrait' ,function () {
            do_ajax( {
                    morphTraitHover : $(this).attr('id')
                },
                function(response){
                        $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                });

            return false;

        });

        
        //click on implants button
        $(document).on('click','.implants', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				do_ajax( {
                            currentMorphUsed : morphName
                    },
                    function(response){
	                            $("#tertiary").load("tertiary-choice/implants.php");
                    });
				return false;
        });
        
         //click on implants for selection deselection
        $(document).on('click', '.addMorphImplantIcon,.selMorphImplantIcon' ,function () {
                do_ajax( {
                            morphImplant : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/implants.php");
								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;
        });
        
        
        //click on gear button
        $(document).on('click','.gear', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');	
				do_ajax( {
                            currentMorphUsed : morphName
                    },
                    function(response){
	                            $("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});
                    });
				return false;
        });
        
       
         //click on gears for selection deselection
        $(document).on('click', '.addMorphGearIcon,.selMorphGearIcon' ,function () {
                do_ajax( {
                            morphGear : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //remove free morph gear
         $(document).on('click', '.remFreeGear' ,function () {
        		var name = $(this).attr('id');
                do_ajax( {
                            morphFreeGear : name,
                            morphFreePrice : 0,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
                    });
				return false;
        });

        //add free morph gear
        $(document).on('click', '#addFreeMorphGear' ,function () {
        		var name = $("#freeMorphGearToAdd").val();
        		var price = $("#freeMorphGearPrice").val();
                do_ajax( {
                            morphFreeGear : name,
                            morphFreePrice : price,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;
        });
        //return on the free morph gear text
        $(document).on('keydown', '#freeMorphGearToAdd' ,function (e) {
    		if(e.keyCode == 13) {
    	 		var name = $("#freeMorphGearToAdd").val();
        		var price = $("#freeMorphGearPrice").val();
                do_ajax( {
                            morphFreeGear : name,
                            morphFreePrice : price,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
                    });
				return false;

			}
        });



		//hover on morph implant or gear
         //click on implants for selection deselection
        $(document).on('click', '.morphImplant,.morphGear' ,function () {
            do_ajax( {
                    morphImplantGearHover : $(this).attr('id')
                },
                function(response){
                        $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                });
            return false;
        });

		//SOFT GEAR AI
        //click on main menu
        $("a.soft").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/softGear.php", function(){
    			setupFoldingList();
    		});

			return false;
    	});
    	
    	//click on ai
        $(document).on('click', '.addAiIcon,.selAiIcon' , function () {
                do_ajax( {
                            ai : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
								//displayMessageOnTertiary(response.desc);
								$("#tertiary").load("tertiary-choice/aiBMD.php");
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
                    });
				return false;
        });
        
        //hover on ai
         $(document).on('click', '.ai' , function () {
                do_ajax( {
                            hoverAi : $(this).attr('id')
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/aiBMD.php");
                    });
				return false;
        });
        
        //click on soft
        $(document).on('click', '.addSoftGearIcon,.selSoftGearIcon' , function () {
                do_ajax( {
                            softg : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    function(response){
								//displayMessageOnTertiary(response.desc);
								$("#tertiary").load("tertiary-choice/softGearBMD.php");
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
                    });
				return false;
        });
        
        //Hover on soft gear
        $(document).on('click', '.softG' , function () {
                do_ajax( {
                            hoverSoftg : $(this).attr('id')
                    },
                    function(response){
								$("#tertiary").load("tertiary-choice/softGearBMD.php");
                    });
				return false;
        });
        
         //remove free ego gear
         $(document).on('click', '.remFreeEgoGear' ,function () {
        		var name = $(this).attr('id');
                do_ajax( {
                            egoFreeGear : name,
                            egoFreePrice : 0,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
                    });
				return false;
        });

        
         //add free ego gear
        $(document).on('click', '#addFreeEgoGear' ,function () {
        		var name = $("#freeEgoGearToAdd").val();
        		var price = $("#freeEgoGearPrice").val();
                do_ajax( {
                            egoFreeGear : name,
                            egoFreePrice : price,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
                    });
				return false;
        });
        //return on the free ego gear text
        $(document).on('keydown', '#freeEgoGearToAdd' ,function (e) {
    		if(e.keyCode == 13) {
    	 		var name = $("#freeEgoGearToAdd").val();
        		var price = $("#freeEgoGearPrice").val();
                do_ajax( {
                            egoFreeGear : name,
                            egoFreePrice : price,
                            getCrePoint : 'get'
                    },
                    function(response){
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
                    });
				return false;			
			}
        });

        
        
        //CREDIT
        //click on main menu
        $("a.credit").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/credits.php");
    	});
        
        //click on addButton
    	$(document).on('click', '#addCredit' ,function () {
                do_ajax( {
                            addCredit :'get',
                            getCrePoint : 'get'                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/credits.php");
                    		 	setRemainingPoint(response);
                    });
				return false;
        
        });
        
          //click on remove Button
    	$(document).on('click', '#removeCredit' ,function () {
                do_ajax( {
                            remCredit :'get',
                            getCrePoint : 'get'                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/credits.php");
                    		 	setRemainingPoint(response);
                    });
				return false;
        
        });

         //STATS
        //click on main menu
        $("a.stat").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/stats.php");
    	});
    	
    	//click on addButton
    	$(document).on('click', '#addMoxie' ,function () {
                do_ajax( {
                            addMoxie :'get',
                            getCrePoint : 'get'                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/stats.php");
                    		 	setRemainingPoint(response);
                    });
				return false;
        
        });
        
         //click on moxie for description
    	$(document).on('click', '.descMoxie' ,function () {
    			hideErrorsMsg();
        		var moxName = $(this).attr('id');	
                do_ajax( {
                            mox :moxName             
                    },
                    function(response){
                    		 	displayMessageOnTertiary(response.desc);
                    });
				return false;
        
        });

        
        //click on a stat for description
    	$(document).on('click', '.statMorph' ,function () {
    			//hideErrorsMsg();
        		var statName = $(this).attr('id');	
                do_ajax( {
                            stat :statName             
                    },
                    function(response){
                    		 	displayMessageOnQuaternary(response.desc);
                    });
				return false;
        });

        
          //click on remove Button
    	$(document).on('click', '#removeMoxie' ,function () {
                do_ajax( {
                            remMoxie :'get',
                            getCrePoint : 'get'                   
                    },
                    function(response){
                    		 	$("#secondary").load("secondary-choice/stats.php");
                    		 	setRemainingPoint(response);
                    });
				return false;
        
        });

    	
    	 //click on a morph for stat
    	$(document).on('click', '.callStatMorph' ,function () {
    			hideErrorsMsg();
        		var morphName = $(this).attr('id');	
                do_ajax( {
                            currentMorphUsed :morphName             
                    },
                    function(response){
                    		 	$("#tertiary").load("tertiary-choice/statsWithMorph.php");
                    });
				return false;
        
        });
        
        
        //LAST DETAILS
        //click on main menu
        $("a.lastdetails").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/last-details.php");
    	});
    	
    	//last details settings changes
         $(document).on('change', '#mPlayerName,#mCharacterName,#mRealAge,#mBirthGender,#mNote' ,function () {
          do_ajax( {
                            lastDetailsChange : 'get',
							playerName : $("#mPlayerName").val(),
							characterName : $("#mCharacterName").val(),
							realAge : $("#mRealAge").val(),  
							birthGender : $("#mBirthGender").val(),
							noteDetails : $("#mNote").val()                  
                    },
                    function(response){}
          );
			return false;
        });
        
        //Bonus Malus Description
        //click on addButton from a skill BM  choice
    	$(document).on('click', '.iconebmChoice,.iconebmSimpleChoice' ,function () {
    			var targetId = $(this).attr('id');
    			var parentId = $("#"+targetId+"ParentId").val();
    			if(parentId != null){
    				var bMcase_var = $("#"+parentId+"Case").val();
    			}
    			else{
	    			var bMcase_var = $("#"+targetId+"Case").val();
    			}
    			if(bMcase_var == 'MUL'){
                    var bmMultiName_var = $("#"+targetId+"MultiName").val();
	    			var addTargetTo_var = $("#"+parentId+"BmName").val();
                    var targetVal_var = $("#"+targetId+"Sel").val();
                    var parentName_var = $("#"+parentId+"Parent").val();
                    var parentType_var = $("#"+parentId+"Type").val();

    			}
    			else{
    				var bmMultiName_var = "none";
	    			var addTargetTo_var = $("#"+targetId+"BmName").val();
                    var targetVal_var = $("#"+targetId+"Sel").val();
                    var parentName_var = $("#"+targetId+"Parent").val();
                    var parentType_var = $("#"+targetId+"Type").val();
    			}

                do_ajax( {
                            addTargetTo : addTargetTo_var,
                            targetVal : targetVal_var,
                            parentName : parentName_var, 
                            parentType : parentType_var,
                            bMcase : bMcase_var, 
                            bmMultiName : bmMultiName_var ,
                            bmId : targetId,
                            parentBmId : parentId         
                    },
                    function(response){
                    			if(parentType_var == 'origine') $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                    			else if(parentType_var == 'faction') $("#tertiary").load("tertiary-choice/factionBMD.php");
                    			else if(parentType_var == 'trait') $("#tertiary").load("tertiary-choice/traitBMD.php");
                    			else if(parentType_var == 'psi') $("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			else if(parentType_var == 'morph') $("#tertiary").load("tertiary-choice/morphBMD.php");
                    			else if(parentType_var == 'morphTrait') $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                    			else if(parentType_var == 'morphGear') $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			else if(parentType_var == 'ai') $("#tertiary").load("tertiary-choice/aiBMD.php");
                    			else if(parentType_var == 'soft') $("#tertiary").load("tertiary-choice/softGearBMD.php");
                    });
				return false;
        
        });
        //click on removeButton from a skill BM  choice
    	$(document).on('click', '.iconebmRemChoice,.iconebmSimpleRemChoice' ,function () {
    			var targetId = $(this).attr('id');
    			var parentId = $("#"+targetId+"ParentId").val();
    			if(parentId != null){
    				var bMcase_var = $("#"+parentId+"Case").val();
    			}
    			else{
	    			var bMcase_var = $("#"+targetId+"Case").val();
    			}
    			if(bMcase_var == 'MUL'){
                    var bmMultiName_var = $("#"+targetId+"MultiName").val();
	    			var removeTargetFrom_var = $("#"+parentId+"BmName").val();
                    var targetVal_var = $("#"+targetId).val();
                    var parentName_var = $("#"+parentId+"Parent").val();
                    var parentType_var = $("#"+parentId+"Type").val();

    			}
    			else{
    				var bmMultiName_var = "none";
	    			var removeTargetFrom_var = $("#"+targetId+"BmName").val();
                    var targetVal_var = $("#"+targetId).val();
                    var parentName_var = $("#"+targetId+"Parent").val();
                    var parentType_var = $("#"+targetId+"Type").val();
    			}
                do_ajax( {
                            removeTargetFrom : removeTargetFrom_var,
                            targetVal : targetVal_var,
                            parentName : parentName_var, 
                            parentType : parentType_var,
                            bMcase : bMcase_var, 
                            bmMultiName : bmMultiName_var,
                            bmId : targetId,
                            parentBmId : parentId 
                                       
                    },
                    function(response){
                    			if(parentType_var == 'origine') $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                    			else if(parentType_var == 'faction') $("#tertiary").load("tertiary-choice/factionBMD.php");
                    			else if(parentType_var == 'trait') $("#tertiary").load("tertiary-choice/traitBMD.php");
                    			else if(parentType_var == 'psi') $("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			else if(parentType_var == 'morph') $("#tertiary").load("tertiary-choice/morphBMD.php");
                    			else if(parentType_var == 'morphTrait') $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                    			else if(parentType_var == 'morphGear') $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			else if(parentType_var == 'ai') $("#tertiary").load("tertiary-choice/aiBMD.php");
                    			else if(parentType_var == 'soft') $("#tertiary").load("tertiary-choice/softGearBMD.php");
                    });
				return false;
        
        });
        
        //Click on occurence button ADD and REMOVE
        //AI
        $(document).on('click', '#addOccurence_AI' ,function () {
    		    do_ajax( {
                            addOccurence : 'AI' ,
                            getCrePoint : 'get'              
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/aiBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        });
        $(document).on('click', '#removeOccurence_AI' ,function () {
    		    do_ajax( {
                            remOccurence : 'AI'  ,
                            getCrePoint : 'get'             
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/aiBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        
        });
		//SOFT
		 $(document).on('click', '#addOccurence_SOFT' ,function () {
    		    do_ajax( {
                            addOccurence : 'SOFT' ,
                            getCrePoint : 'get'              
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/softGearBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        });
        $(document).on('click', '#removeOccurence_SOFT' ,function () {
    		    do_ajax( {
                            remOccurence : 'SOFT'  ,
                            getCrePoint : 'get'             
                    },
                    function(response){
                    			$("#tertiary").load("tertiary-choice/softGearBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        
        });
        //MORPH
         $(document).on('click', '#addOccurence_MORPH' ,function () {
    		    do_ajax( {
                            addOccurence : 'MORPH' ,
                            getCrePoint : 'get'              
                    },
                    function(response){
                    			$("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        });
        $(document).on('click', '#removeOccurence_MORPH' ,function () {
    		    do_ajax( {
                            remOccurence : 'MORPH'  ,
                            getCrePoint : 'get'             
                    },
                    function(response){
                    			$("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			setRemainingPoint(response);
                    });
				return false;
        
        });

        //FOLDING LIST MANAGEMENT
	    $(document).on('click','.foldingListSection', function () {
        	var targetId = $(this).attr('id');
        	if($("."+targetId).css('display') == 'none'){
        		document.cookie = targetId+"=Y;path=/";
        		$("."+targetId).show();
        	}
        	else{
        		document.cookie = targetId+"=N;path=/";
	        	$("."+targetId).hide();
        	}
	    });	   
	
});

function changeSkill(node, after) {
    //change skill value
    var skId = node.attr('data-skillname').replace(/[\/\s]+/g,"");

    do_ajax( {
            changeSkillName : node.attr('data-skillname'),
            changeSkillValue : node.val(),
            getCrePoint : 'get'
        },
        function(response){
                $("[id="+skId+"]").css("background-color", "#FEFEFE");
                $("#secondary").load(after, function(){
                    $(focusOnSkill).focus();
                });
                setRemainingPoint(response);
        });
}

function removeSkill(node, after) {
    //remove a temp active skill
    do_ajax( {
                remSkill : node.attr('data-skillname')
        },
        function(response){
                    $("#secondary").load(after);
        });
}

function treatMessageError(response){
	if(response.erType == "rules" || response.erType == "system"){
		displayRulesMessage(response.msg);
	}
	else if(response.msg == ''){
		displayError('An Error Occured!<br>No Error Message Recieved!');
	}
	else{
		displayError(response.msg);
	}
}


function displayMessageOnTop(msg){
	$("#base-infos").html(msg);
    $(".help").animate({height: "toggle"}, 350, 'easeInOutQuint');
}

function displayMessageOnTertiary(msg,title){
    var titleHtml = '';

    if (title) {
        titleHtml = '<label class="descriptionTitle">' + title + '</label>';
    }

	$("#tertiary").html(titleHtml + TERTIARY_INFO_HTML);
	$("#tertiary_infos").html(msg);
    $("#tertiary_infos").css('visibility','visible');

}

function displayMessageOnQuaternary(msg){
	$("#quaternary").html(QUATERNARY_INFO_HTML);
	$("#quaternary_infos").html(msg);
    $("#quaternary_infos").css('visibility','visible');

}

function displayRulesMessage(msg){
	$("#messages").stop( true, true ).fadeOut();
	$("#user-messages").stop( true, true ).fadeOut();
	$("#messages").html(USER_MSG_HTML);
	$("#user-messages").html(msg);
	$("#messages").fadeIn();
	$("#user-messages").fadeIn();
    $("#messages").fadeOut(15000);
}


function hideErrorsMsg(){
	hideRulesMessage();
	hideQuaternaryContent();
	hideTertiaryContent();
}

function hideQuaternaryContent(){
	$("#quaternary").html(QUATERNARY_INFO_HTML);
    $("#quaternary_infos").css('visibility','hidden');
}

function hideTertiaryContent(){
	$("#tertiary").html(TERTIARY_INFO_HTML);
    $("#tertiary_infos").css('visibility','hidden');
}

function hideRulesMessage(){
	$("#messages").html(USER_MSG_HTML);
    $("#user-messages").fadeOut();
}

function setRemainingPoint(ajaxData){
     $("#creation_remain").html(ajaxData.creation_remain);
     $("#credit_remain").html(ajaxData.credit_remain);
     $("#aptitude_remain").html(ajaxData.aptitude_remain);
     $("#reputation_remain").html(ajaxData.reputation_remain);
     $("#rez_remain").html(ajaxData.rez_remain);
     $("#asr_remain").html(ajaxData.asr_remain);
     $("#ksr_remain").html(ajaxData.ksr_remain);
}
//loading function
function loaddingReset(){
	setTimeout(function(){
		location.reload();
		$("#reset_popup").css('opacity',0);
		$("#reset_popup").css('visibility','hidden');
	    closeAllPopup();
	},1000);
}

function loaddingLoad(){
	setTimeout(function(){
		endLoading();
		$("#reset_popup").css('opacity',0);
		$("#reset_popup").css('visibility','hidden');
	    closeAllPopup();
	},3000);
}

//CREATE FOLDING LIST SESSION PREFERECES and SET THE RIGHT CSS FOR FOLDING LIST
function setupFoldingList(){
     $('.foldingListSection').each(function(){
    	var targetId = $(this).attr('id');
    	if(getCookie(targetId) == null){
       		document.cookie = targetId+"=N;path=/";
       		$("."+targetId).hide();
       	}
       	else{
           	if(getCookie(targetId) == "Y"){
	           $("."+targetId).show();
	        }
	        else{
		        $("."+targetId).hide();
	        }
       	}
     });
 }    

function getCookie(name)
{
	var re = new RegExp(name + "=([^;]+)");
	var value = re.exec(document.cookie);
	return (value != null) ? unescape(value[1]) : null;
}

