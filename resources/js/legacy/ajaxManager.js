
var firstTime = true;

var SECONDARY_INFO_HTML = "<div id='secondary_infos'></div>";
var TERTIARY_INFO_HTML = "<div id='tertiary_infos'></div>";
var QUATERNARY_INFO_HTML = "<div id='quaternary_infos'></div>";

var DISPLAY_ON_4 = 4;
var DISPLAY_ON_3 = 3;
var DISPLAY_ON_TOP = 1;
var DISPLAY_ON_MSG = 0;

var focusOn = "";
var focusOnSkill = "";

$(document).ready(function(){

    endLoading();

        //Tooltips (used for help buttons)
        $(document).tooltip({
            position: { at: "right center" },
            content: function(){
                var element = $( this );
                return element.attr('title');
            }
        });

        //BACKGROUND
    	//click on background
        $(document).on('click', '.bck' , function () {
            var me = this;
            do_ajax({
                        origine : $(this).attr('id'),
                        getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/backgroundBMD");
                    setRemainingPoint(response);
            });
            return false;
        });
               
        //FACTION

		//click on faction
        $(document).on('click', '.fac' ,function () {
            var me = this;
            do_ajax( {
                        faction : $(this).attr('id'),
                        getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/factionBMD");
                    setRemainingPoint(response);
                });
            return false;
        
        });
        //MOTIVATION

        function addMotivation() {
            var me = this;
            do_ajax( {
                    newMot : $('#motToAdd').val()
                },
                function(response){
                    reloadPanel(me);
                });
            return false;
        }
    	//enter key on the motivation text field
    	$(document).on('keydown', '#motToAdd' ,function (e) {
    		if(e.keyCode == 13) {
                addMotivation.call(this);
				return false;
			}
        });
    	
    	//click on addButton
    	$(document).on('click', '#addMotiv' ,addMotivation);
        //click on removeButton
    	$(document).on('click', '.remMotiv' ,function () {
            var me = this;
            do_ajax( {
                    remMot : $(this).attr('id')
                },
                function(response){
                    reloadPanel(me);
                });
            return false;
        });
    	
        //APTITUDES
    	//before apt chage get the focused html element
        $(document).on('focusin', '#COG,#COO,#INT,#REF,#SAV,#SOM,#WIL' ,function (e) {
        		focusOn = "#"+($(this).attr('id'));
				//console.log("focin"+focusOn);
        });

    	//apt value change
        $(document).on('change ', '#COG,#COO,#INT,#REF,#SAV,#SOM,#WIL' ,function (e) {
            var returnArray = {
                getCrePoint: 'get'
            };
            returnArray[e.target.id.toLowerCase()] = $(e.target).val();
            ajax_helper(
                returnArray,
                function (response) {
                    $(e.target).css("background-color", "#FEFEFE");
                    if (response.error) {
                        treatMessageError(response);
                        $("#" + response.aptError).css("background-color", "#BA0050");
                        $("#" + response.aptError).select();
                    }
                    else {
                        setRemainingPoint(response);
                        $("#secondary").attr('src', "secondary-choice/aptitudes");
                        $("#secondary").load("secondary-choice/aptitudes", function () {
                            //console.log("change"+focusOn);
                            $(focusOn).select();
                        });
                    }
                }
            );
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
                    		 	loadTertiary("tertiary-choice/aptsWithMorph");
                    });
				return false;
        
        });

        
        //REPUTATIONS
		//before rep chage get the focused html element
        $(document).on('focusin', '#\\@-Rep,#G-Rep,#C-Rep,#I-Rep,#E-Rep,#R-Rep,#F-Rep' ,function (e) {
        		focusOn = "#"+($(this).attr('id'));
				//console.log("focin"+focusOn);
        });
		
		//Rep value change
	    $(document).on('change', '#\\@-Rep,#G-Rep,#C-Rep,#I-Rep,#E-Rep,#R-Rep,#F-Rep',function(e) {
            switch (e.target.id) {
                case '@-Rep':
                    var shortRep = 'atrep';
                    break;
                case 'G-Rep':
                    var shortRep = 'grep';
                    break;
                case 'C-Rep':
                    var shortRep = 'crep';
                    break;
                case 'I-Rep':
                    var shortRep = 'irep';
                    break;
                case 'E-Rep':
                    var shortRep = 'erep';
                    break;
                case 'R-Rep':
                    var shortRep = 'rrep';
                    break;
                case 'F-Rep':
                    var shortRep = 'frep';
                    break;
            }
            var returnArray = {
                getCrePoint: 'get'
            };
            returnArray[shortRep] = $(e.target).val();
            ajax_helper(
                returnArray,
                function (response) {
                    $(e.target).css("background-color", "#FEFEFE");
                    if (response.error) {
                        treatMessageError(response);
                        $("#" + response.repError).css("background-color", "#BA0050");
                        $("#" + response.repError).select();
                    }
                    else {
                        setRemainingPoint(response);
                        $("#secondary").attr('src', "secondary-choice/reputations");
                        $("#secondary").load("secondary-choice/reputations", function () {
                            $(focusOn).select();
                        });
                    }
                }
            );
            return false;
		});

    	//POSITIVE TRAITS
        //click on any trait
        $(document).on('click', '.posTrait, .negTrait, .neuTrait' ,function () {
            do_ajax( {
                    traitHover : $(this).attr('id')
                },
                function(response){
                    loadTertiary("tertiary-choice/traitBMD");
                });
            return false;
        });
        
        
		//click on pos trait
        $(document).on('click', '.addSelPosTraitIcon' ,function () {
            var me = this;
            do_ajax( {
                    posTrait : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/traitBMD");
                    setRemainingPoint(response);
                });
            return false;
        
        });
        //NEGATIVE TRAITS
		//click on neg trait
        $(document).on('click', '.addSelNegTraitIcon' ,function () {
            var me = this;
            do_ajax( {
                negTrait : $(this).attr('id'),
                getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/traitBMD");
                    setRemainingPoint(response);
                });
            return false;
        });
        
         //NEUTRAL TRAITS

		//click on neu trait
        $(document).on('click', '.addSelNeuTrait' ,function () {
            var me = this;
            do_ajax( {
                negTrait : $(this).attr('id'), //use negTrait, but dont matter, cost = 0
                getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/traitBMD");
                    setRemainingPoint(response);
                });
            return false;
        });

        //PSI SLEIGHTS  
		//click on psi
        $(document).on('click', '.addSelPsySleightIcon' ,function () {
            var me = this;
            do_ajax( {
                        psyS : $(this).attr('id'),
                        getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/psySleightBDM");
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
                    			loadTertiary("tertiary-choice/psySleightBDM");
                    });
				return false;
        
        });
        
		//ACTIVE SKILLS
		//before active skill change get the focused html element
        $(document).on('focusin', '.actskillbase' ,function (e) {
        		focusOnSkill = "[atomic='"+$(this).attr('atomic')+"']";
				//console.log("focin"+focusOn);
        });
		
		//change active skill value
		$(document).on('change ', '.actskillbase' ,function () {
            changeSkill($(this), "secondary-choice/active-skills");
            return false;
		});
		
		//add a temp active skill
        function addActiveSkill() {
            if(!$('#actToAdd').val()){
                displayRulesMessage("Attempting to add a blank skill!");
                return false;
            }
            var me = this;
            do_ajax( {
                    newTmpActSkill : $('#actToAdd').val(),
                    newTmpSkillPrefix : $('#actprefix').val()
                },
                function(response){
                    reloadPanel(me);
                });
            return false;
        }
		$(document).on('click', '#addActSkill' ,addActiveSkill);
        //return key in the temp active field 
        $(document).on('keydown', '#actToAdd' ,function (e) {
            if(e.keyCode == 13) {
                addActiveSkill();
                return false;
            }
        });
        
        //remove a temp active skill
        $(document).on('click', '.remActSkill' ,function () {
            removeSkill($(this));
            return false;
        });
        
		//remove specialization
		 $(document).on('click', '.remSpeSkill' ,function () {
	           do_ajax( {
	                        remSpeSkill : $(this).attr('atomic'),
	                        getCrePoint : 'get'
	                },
	                function(response){
                                var comeFrom = $('.skills').attr('id');
                    			if(comeFrom == "actSkills"){
                    				loadSecondary("secondary-choice/active-skills");
                    			}
                    			else{
	                    			loadSecondary("secondary-choice/knowledge-skills");
                    			}
	                			setRemainingPoint(response);
	                });
				return false;
        });
		
		//KNOWLEDGE SKILLS

		//before knowlege skill change get the focused html element
        $(document).on('focusin', '.knoskillbase' ,function (e) {
        		focusOnSkill = "[atomic='"+$(this).attr('atomic')+"']";
				//console.log("focin"+focusOn);
        });
		
		//change knowlege skill value
		$(document).on('change ', '.knoskillbase' ,function () {
            changeSkill($(this), "secondary-choice/knowledge-skills");
            return false;
		});
		//Add the native language
        function addNativeLanguage() {
            if(!$('#langToAdd').val()){
                displayRulesMessage("Attempting to add a blank skill!");
                return false;
            }
            do_ajax( {
                        newNatLanguageSkill : $('#langToAdd').val()
                },
                function(response){
                            loadSecondary("secondary-choice/knowledge-skills");
                });
            return false;
        }
		$(document).on('click', '#addNativeLanguage' ,addNativeLanguage);
        //return key in the native language 
        $(document).on('keydown', '#langToAdd' ,function (e) {
            if(e.keyCode == 13) {
                addNativeLanguage();
                return false;
            }
        });

		
		//add a temp knowledge  skill
        function addKnowSkill() {
            if(!$('#knoToAdd').val()){
                displayRulesMessage("Attempting to add a blank skill!");
                return false;
            }
            do_ajax( {
                        newTmpKnoSkill : $('#knoToAdd').val(),
                        newTmpSkillPrefix : $('#knoprefix').val()
                },
                function(response){
                            loadSecondary("secondary-choice/knowledge-skills");
                });
            return false;
        }
		$(document).on('click', '#addKnowSkill' ,addKnowSkill);
        //Return key on the knowledge skill
        $(document).on('keydown', '#knoToAdd' ,function (e) {
            if(e.keyCode == 13) {
                addKnowSkill();
            }
        });
        
        //remove a temp knowlege skill
        $(document).on('click', '.remKnowSkill' ,function () {
            removeSkill($(this));
            return false;
        });
		
		//GENERAL SKILLS
		//click on skill for desc
        $(document).on('click', '.skName' ,function () {
                do_ajax( {
                            skill : $(this).attr('atomic')
					},
                    function(response){
                    			displayMessageOnTertiary(response.desc,response.title);
								setRemainingPoint(response);
                    });
				return false;
        });
        
        //click + skill specialization
        function addSkillSpec() {
            var id_spez = '#spezBox'+$(this).attr('atomic');

            if($(id_spez).css('visibility') == 'hidden'){
                $(id_spez).css('visibility', 'visible').find(".spezInt").select();
            }
            else{
                $(id_spez).css('visibility', 'hidden');
                var speId = '#spe_'+$(this).attr('atomic');
                var speVal = $(speId).val();

                if(speVal != null || speVal != ""){
                    var me=this;
                    do_ajax( {
                            addSpe : speVal,
                            addSpeSkill : $(this).attr('atomic'),
                            getCrePoint : 'get'
                        },
                        function(response){
                            reloadPanel(me);
                            setRemainingPoint(response);
                        });
                }
            }
            return false;
        }
        $(document).on('click', '.addSkillSpec' ,addSkillSpec);
        //enter on the input text
        $(document).on('keypress','.skName',function (e) {
            if (e.which == 13) {
                addSkillSpec.call(this);
                return false;
            }
        });
        
        //MORPHS

    	//hover on morph
		 $(document).on('click', '.morphHover' ,function () {
		 	do_ajax( {
                            morphHover : $(this).attr('id')
                    },
                    function(response){
                    			displayMessageOnTertiary(response.desc, response.title);
                    });

		 	return false;
        
        });

        //click on the add / remove morph button
        $(document).on('click','.addRemMorph' ,function () {
            hideErrorsMsg();
            var me = this;
            do_ajax( {
                        addRemMorph : $(this).attr('id'),
                        getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    displayMessageOnTertiary(response.desc, response.title);
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
								loadTertiary("tertiary-choice/morphBMD");
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
                                $("#tertiary").attr('src',"tertiary-choice/morphSettings");
								$("#tertiary").load("tertiary-choice/morphSettings", function(){
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
         $(document).on('change', '#mNickname,#mLocation,#mAge,#mGender' ,function () {
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
	                            loadTertiary("tertiary-choice/morphPosTraits");
                    });
				return false;
        });
        
        //click on morph pos trait for selection deselection
        $(document).on('click', '.addSelMorphPosTraitIcon' ,function () {
            var me = this;
            do_ajax( {
                    morphPosTrait : $(this).attr('id').trim(),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/traitMorphBMD");
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
	                            loadTertiary("tertiary-choice/morphNeuTraits");
                    });
				return false;
        });
        
        //click on morph neutral trait for selection deselection
        $(document).on('click', '.addSelMorphNeuTraitIcon' ,function () {
            var me = this;
            do_ajax( {
                    morphPosTrait : $(this).attr('id'), //keep call to posTrait, it's the same anyway
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/traitMorphBMD");
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
	                            loadTertiary("tertiary-choice/morphNegTraits");
                    });
				return false;
        });
        //click on morph neg trait for selection deselection
        $(document).on('click', '.addSelMorphNegTraitIcon' ,function () {
            var me = this;
            do_ajax( {
                    morphNegTrait : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/traitMorphBMD");
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
                    loadQuaternary("quaternary-choice/traitMorphBMD");
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
	                            loadTertiary("tertiary-choice/implants");
                    });
				return false;
        });
        
         //click on implants for selection deselection
        $(document).on('click', '.addSelMorphImplantIcon',function () {
            var me = this;
            do_ajax( {
                    morphImplant : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/gearMorphBMD");
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
                        loadTertiary("tertiary-choice/gears");
                    });
				return false;
        });
        
       
         //click on gears for selection deselection
        $(document).on('click', '.addSelMorphGearIcon' ,function () {
            var me = this;
            do_ajax( {
                    morphGear : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/gearMorphBMD");
                    setRemainingPoint(response);
                });
            return false;
        });
        
        //remove free morph gear
         $(document).on('click', '.remFreeMorphGear' ,function () {
            var me = this;
            var name = $(this).attr('id');
            do_ajax( {
                    morphFreeGear : name,
                    morphFreePrice : 0,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        });

        //add free morph gear
        function addFreeMorphGear(){
            var me = this;
            var name = $("#freeMorphGearToAdd").val();
            var price = $("#freeMorphGearPrice").val();
            do_ajax( {
                    morphFreeGear : name,
                    morphFreePrice : price,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadQuaternary("quaternary-choice/gearMorphBMD");
                    setRemainingPoint(response);
                });
            return false;
        }
        //Click on the button
        $(document).on('click', '#addFreeMorphGear' ,addFreeMorphGear);
        //return on the free morph gear text
        $(document).on('keydown', '#freeMorphGearToAdd' ,function (e) {
            if(e.keyCode == 13) {
                addFreeMorphGear.call(this);
                return false;
            }
        });



		//hover on morph implant or gear
        $(document).on('click', '.morphGear' ,function () {
            do_ajax( {
                    morphImplantGearHover : $(this).attr('id')
                },
                function(response){
                        loadQuaternary("quaternary-choice/gearMorphBMD");
                });
            return false;
        });

		//SOFT GEAR AI
    	//click on ai
        $(document).on('click', '.addSelAiIcon' , function () {
            var me = this;
            do_ajax( {
                    ai : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/aiBMD");
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
								loadTertiary("tertiary-choice/aiBMD");
                    });
				return false;
        });
        
        //click on soft
        $(document).on('click', '.addSelSoftGearIcon' , function () {
            var me = this;
            do_ajax( {
                    softg : $(this).attr('id'),
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    loadTertiary("tertiary-choice/softGearBMD");
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
								loadTertiary("tertiary-choice/softGearBMD");
                    });
				return false;
        });

         //remove free ego gear
         $(document).on('click', '.remFreeEgoGear' ,function () {
            var me = this;
            var name = $(this).attr('id');
            do_ajax( {
                    egoFreeGear : name,
                    egoFreePrice : 0,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        });

        //add free ego gear
        function addFreeEgoGear(){
            var me = this;
            var name = $("#freeEgoGearToAdd").val();
            var price = $("#freeEgoGearPrice").val();
            do_ajax( {
                    egoFreeGear : name,
                    egoFreePrice : price,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        }
        // Click on the icon
        $(document).on('click', '#addFreeEgoGear' ,addFreeEgoGear);
        //return on the free ego gear text
        $(document).on('keydown', '#freeEgoGearToAdd' ,function (e) {
            if(e.keyCode == 13) {
                addFreeEgoGear.call(this);
                return false;
            }
        });

        //CREDIT
        //click on addButton
        $(document).on('click', '#addCredit' ,function () {
            var me = this;
            do_ajax( {
                    addCredit :'get',
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        });

        //click on remove Button
        $(document).on('click', '#removeCredit' ,function () {
            var me = this;
            do_ajax( {
                    remCredit :'get',
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        });

        //STATS
        //click on addButton
        $(document).on('click', '#addMoxie' ,function () {
            var me = this;
            do_ajax( {
                    addMoxie :'get',
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        });

        //click on moxie for description
        $(document).on('click', '.descMoxie' ,function () {
            do_ajax( {
                    mox : true
                },
                function(response){
                    displayMessageOnTertiary(response.desc);
                });
            return false;
        });

        //click on remove Button
        $(document).on('click', '#removeMoxie' ,function () {
            var me = this;
            do_ajax( {
                    remMoxie :'get',
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
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
                    		 	loadTertiary("tertiary-choice/statsWithMorph");
                    });
				return false;
        
        });
        
        //LAST DETAILS

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
    	$(document).on('click', '.iconebmChoice' ,function () {
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
                    			if(parentType_var == 'origine') loadTertiary("tertiary-choice/backgroundBMD");
                    			else if(parentType_var == 'faction') loadTertiary("tertiary-choice/factionBMD");
                    			else if(parentType_var == 'trait') loadTertiary("tertiary-choice/traitBMD");
                    			else if(parentType_var == 'psi') loadTertiary("tertiary-choice/psySleightBDM");
                    			else if(parentType_var == 'morph') loadTertiary("tertiary-choice/morphBMD");
                    			else if(parentType_var == 'morphTrait') loadQuaternary("quaternary-choice/traitMorphBMD");
                    			else if(parentType_var == 'morphGear') loadQuaternary("quaternary-choice/gearMorphBMD");
                    			else if(parentType_var == 'ai') loadTertiary("tertiary-choice/aiBMD");
                    			else if(parentType_var == 'soft') loadTertiary("tertiary-choice/softGearBMD");
                    });
				return false;
        
        });
        //click on removeButton from a skill BM  choice
    	$(document).on('click', '.iconebmRemChoice' ,function () {
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
                    			if(parentType_var == 'origine') loadTertiary("tertiary-choice/backgroundBMD");
                    			else if(parentType_var == 'faction') loadTertiary("tertiary-choice/factionBMD");
                    			else if(parentType_var == 'trait') loadTertiary("tertiary-choice/traitBMD");
                    			else if(parentType_var == 'psi') loadTertiary("tertiary-choice/psySleightBDM");
                    			else if(parentType_var == 'morph') loadTertiary("tertiary-choice/morphBMD");
                    			else if(parentType_var == 'morphTrait') loadQuaternary("quaternary-choice/traitMorphBMD");
                    			else if(parentType_var == 'morphGear') loadQuaternary("quaternary-choice/gearMorphBMD");
                    			else if(parentType_var == 'ai') loadTertiary("tertiary-choice/aiBMD");
                    			else if(parentType_var == 'soft') loadTertiary("tertiary-choice/softGearBMD");
                    });
				return false;
        
        });

        //Click on occurence button ADD and REMOVE
        function addOccurence(occurenceId){
            var me = this;
            do_ajax( {
                    addOccurence : occurenceId,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        }
        function remOccurence(occurenceId){
            var me = this;
            do_ajax( {
                    remOccurence : occurenceId,
                    getCrePoint : 'get'
                },
                function(response){
                    reloadPanel(me);
                    setRemainingPoint(response);
                });
            return false;
        }
        //AI
        $(document).on('click', '#addOccurence_AI' ,function () {
            addOccurence.call(this,'AI');
            return false;
        });
        $(document).on('click', '#removeOccurence_AI' ,function () {
            remOccurence.call(this,'AI');
            return false;
        });
        //SOFT
        $(document).on('click', '#addOccurence_SOFT' ,function () {
            addOccurence.call(this,'SOFT');
            return false;
        });
        $(document).on('click', '#removeOccurence_SOFT' ,function () {
            remOccurence.call(this,'SOFT');
            return false;
        });
        //MORPH
        $(document).on('click', '#addOccurence_MORPH' ,function () {
            addOccurence.call(this,'MORPH');
            return false;
        });
        $(document).on('click', '#removeOccurence_MORPH' ,function () {
            remOccurence.call(this,'MORPH');
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
    var skId = node.attr('atomic');

    do_ajax( {
            changeSkill : node.attr('atomic'),
            changeSkillValue : node.val(),
            getCrePoint : 'get'
        },
        function(response){
                $("[id="+skId+"]").css("background-color", "#FEFEFE");
                $("#secondary").attr('src',after);
                $("#secondary").load(after, function(){
                    $(focusOnSkill).select();
                });
                setRemainingPoint(response);
        });
}

/**
 * Remove a temporary skill
 */
function removeSkill(node) {
    do_ajax( {
                remSkill : node.attr('atomic')
        },
        function(response){
                    reloadPanel(node);
        });
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

