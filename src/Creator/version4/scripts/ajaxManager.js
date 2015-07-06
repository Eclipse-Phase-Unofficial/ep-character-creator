
var firstTime = true;
var dispatcherURL = 'scripts/dispatcher.php';

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
            $.ajax({
                type : 'POST',
                contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                url : dispatcherURL,
                dataType : 'json',
                data: {
                        firstTime : 'first',
                        getCrePoint : 'get'
                },
                success : function(response){
                		if(response.versioningFault){
	                        closeAllPopup();
	                        endLoading();
				        	$("#load_popup").load("popup-contents/load.php");
							$("#load_popup").css('opacity',1);
							$("#load_popup").css('visibility','visible');
                        }
                        else if(response.sessionExist){
	                        setRemainingPoint(response);
	                        loaddingLoad();
                        }
                        else{
	                        closeAllPopup();
	                        endLoading();
				        	$("#reset_popup").load("popup-contents/reset.php");
							$("#reset_popup").css('opacity',1);
							$("#reset_popup").css('visibility','visible');
                        }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                            $("#secondary").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                }
            });
                
		  
            firstTime = false;
        }
        
        
        //HELPS (Sliding from the bottom)
        //first html page (don't know why the second option is not working for the first page)
         $('.btnhelp').on('click' , function () {
       		 $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            infosId : $(this).attr('id')
                    },
                    success : function(response){
                           if(response.error) {
                           		treatMessageError(response,DISPLAY_ON_TOP);
                           }
                           else {
                           		$("#base-infos").html(response.infoData);
                           	}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                $("#base-infos").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
			});
       		$(".help").animate({height: "toggle"}, 350, 'easeInOutQuint');
       		return false;
       	 });
       	 
       	 $('.btnhelpPoint').on('click' , function () {
       		 $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            infosId : "points"
                    },
                    success : function(response){
                           if(response.error) {
                           		treatMessageError(response,DISPLAY_ON_TOP);
                           }
                           else {
                           		$("#base-infos").html(response.infoData);
                           	}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                $("#base-infos").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
			});
       		$(".help").animate({height: "toggle"}, 350, 'easeInOutQuint');
       		return false;
       	 });
       	 
       	 //dynamic pages  (morph page)
       	 $(document).on('click', '.btnhelp' , function () {
       		 $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            infosId : $(this).attr('id')
                    },
                    success : function(response){
                           if(response.error) {
                           		treatMessageError(response,DISPLAY_ON_TOP);
                           }
                           else {
                           		$("#base-infos").html(response.infoData);
                           }	
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                $("#base-infos").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
			});
       		$(".help").animate({height: "toggle"}, 350, 'easeInOutQuint');
       		return false;
       	 });
        
        //BACKGROUND
        //click on main menu
        $("a.background").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/backgrounds.php");
    		$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            getBcg : 'get'
                    },
                    success : function(response){
                    		if(response.error){
	                    		treatMessageError(response,DISPLAY_ON_3);
                    		} 
                    		else {
                    			if(response.currentBcg != null){
                    				//displayMessageOnTertiary(response.desc);
                    				$("#secondary").load("secondary-choice/backgrounds.php");
                    				$("#tertiary").load("tertiary-choice/backgroundBMD.php");
                    			}
                    			
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
			});
			return false;
    	});
    	
    	//click on background
        $(document).on('click', '.bck' , function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            origine : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {                    		
								//displayMessageOnTertiary(response.desc);
								$("#tertiary").load("tertiary-choice/backgroundBMD.php");
								$("#secondary").load("secondary-choice/backgrounds.php");
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
				});
				return false;
        });
               
        //FACTION
        //click on main menu
        $("a.faction").click(function(){
        	hideErrorsMsg();
    		$("#secondary").load("secondary-choice/factions.php");
    		$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            getFac : 'get'
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {
                    			if(response.currentFac != null){
                    				$("#secondary").load("secondary-choice/factions.php");
									$("#tertiary").load("tertiary-choice/factionBMD.php");
                    				//displayMessageOnTertiary(response.desc);                    	
                    			}
                    			
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
			});
    		return false;
    	});
		
		//click on faction
        $(document).on('click', '.fac' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            faction : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error){
	                             treatMessageError(response,DISPLAY_ON_3);
	                        }
                    		else {
                    			$("#secondary").load("secondary-choice/factions.php");
								$("#tertiary").load("tertiary-choice/factionBMD.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
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
    	 		$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            newMot : $('#motToAdd').val()                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/motivations.php");
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
			}
        });
    	
    	//click on addButton
    	$(document).on('click', '#addMotiv' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            newMot : $('#motToAdd').val()                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/motivations.php");
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        //click on removeButton
    	$(document).on('click', '.remMotiv' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remMot : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/motivations.php");
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            cog : $('#COG').val(),
                            coo : $('#COO').val(),
                            int : $('#INT').val(),
                            ref : $('#REF').val(),
                            sav : $('#SAV').val(),
                            som : $('#SOM').val(),
                            wil : $('#WIL').val(),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		$('#COG').css("background-color", "#FEFEFE");
                			$('#COO').css("background-color", "#FEFEFE");
                			$('#INT').css("background-color", "#FEFEFE");
                			$('#REF').css("background-color", "#FEFEFE");
                			$('#SAV').css("background-color", "#FEFEFE");
                			$('#SOM').css("background-color", "#FEFEFE");
                			$('#WIL').css("background-color", "#FEFEFE");
                    		if(response.error){
                    			 treatMessageError(response,DISPLAY_ON_MSG);
                    			 $("#"+response.aptError).css("background-color", "#BA0050");
                    		}
                    		else {
                    			setRemainingPoint(response);
                    			$("#secondary").load("secondary-choice/aptitudes.php" , function(){
                    				//console.log("change"+focusOn);
	                    			$(focusOn).focus();
                    			});
                    		}
                            
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        
        });
        
        //click on a aptitude for description
    	$(document).on('click', '.apt' ,function () {
    			hideErrorsMsg();
        		var aptNameTotal = $(this).attr('id');
        		var aptName = aptNameTotal.substr(0, 3);
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            apt :aptName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	displayMessageOnTertiary(response.desc);	
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        
         //click on a morph for apts
    	$(document).on('click', '.aptMorph' ,function () {
    			hideErrorsMsg();
        		var morphName = $(this).attr('id');	
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed :morphName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#tertiary").load("tertiary-choice/aptsWithMorph.php");	
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });

        
        
        //REPUTATIONS
        //click on main menu
        $("a.rep").click(function(){
        	hideErrorsMsg();
        	$.ajax({
	            type : 'POST',
	            contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	            url : dispatcherURL,
	            dataType : 'json',
	            data: {
	                    getCrePoint : 'get'
	            },
	            success : function(response){
	                   if(response.error){
                			 treatMessageError(response,DISPLAY_ON_MSG);                		
						}
                		else {
							setRemainingPoint(response);
							$("#secondary").load("secondary-choice/reputations.php");
                		}
	            },
	            error : function(XMLHttpRequest, textStatus, errorThrown) {
	                       displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
	            }
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
	        $.ajax({
	            type : 'POST',
	            contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	            url : dispatcherURL,
	            dataType : 'json',
	            data: {
	                    atrep : $('#\\@-Rep').val(),
	                    grep : $('#G-Rep').val(),
	                    crep : $('#C-Rep').val(),
	                    irep : $('#I-Rep').val(),
	                    erep : $('#E-Rep').val(),
	                    rrep : $('#R-Rep').val(),
	                    frep : $('#F-Rep').val(),
	                    getCrePoint : 'get'
	            },
	            success : function(response){
	            		$('#\\@-Rep').css("background-color", "#FEFEFE");
                		$('#G-Rep').css("background-color", "#FEFEFE");
                		$('#C-Rep').css("background-color", "#FEFEFE");
                		$('#I-Rep').css("background-color", "#FEFEFE");
                		$('#E-Rep').css("background-color", "#FEFEFE");
                		$('#R-Rep').css("background-color", "#FEFEFE");
                	    $('#F-Rep').css("background-color", "#FEFEFE");
	                   if(response.error){
                			 treatMessageError(response,DISPLAY_ON_MSG);
                			 $("#"+response.repError).css("background-color", "#BA0050");                		
						}
                		else {
							setRemainingPoint(response);
							$("#secondary").load("secondary-choice/reputations.php", function(){
	                    			$(focusOn).focus();
                    		});
                		}
	            },
	            error : function(XMLHttpRequest, textStatus, errorThrown) {
	                       displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
	            }
			});
	
			return false;
		});
		
		//click on a reputation for description
    	$(document).on('click', '.rep' ,function () {
    			hideErrorsMsg();
        		var repNameTotal = $(this).attr('id');	
        		var repName = repNameTotal.substr(0, 5);
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            rep :repName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	displayMessageOnTertiary(response.desc);	
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
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
		 	$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            posTraitHover : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
				});

		 	return false;
        
        });
        
        
		//click on pos trait
        $(document).on('click', '.addPosTraitIcon,.selPosTraitIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            posTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/positive-traits.php");
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
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
		 	$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            negTraitHover : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
				});

		 	return false;
        
        });

		//click on neg trait
        $(document).on('click', '.addNegTraitIcon,.selNegTraitIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            negTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			//displayMessageOnTertiary(response.desc);
								$("#secondary").load("secondary-choice/negative-traits.php");
								$("#tertiary").load("tertiary-choice/traitBMD.php");
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
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
		 	$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            negTraitHover : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#tertiary").load("tertiary-choice/traitBMD.php");
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
				});

		 	return false;
        
        });

		//click on neu trait
        $(document).on('click', '.neuTrait' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            negTrait : $(this).attr('id'), //use negTrait, but dont matter, cost = 0
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			//displayMessageOnTertiary(response.desc);
								$("#secondary").load("secondary-choice/neutral-traits.php");
								$("#tertiary").load("tertiary-choice/traitBMD.php");
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            psyS : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/psy-sleights.php", function(){
					    			setupFoldingList();
					    		});

                    			$("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			//displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
				});
				return false;
        
        });
        
        //hover on psi
		$(document).on('click', '.psyS' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            hoverPsyS : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#tertiary").load("tertiary-choice/psySleightBDM.php");                    		
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            newTmpActSkill : $('#actToAdd').val(),
                            newTmpActSkillPrefix : $('#actprefix').val()
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/active-skills.php");
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        //return key in the temp active field 
        $(document).on('keydown', '#actToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	                $.ajax({
	                    type : 'POST',
	                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	                    url : dispatcherURL,
	                    dataType : 'json',
	                    data: {
	                            newTmpActSkill : $('#actToAdd').val(),
	                            newTmpActSkillPrefix : $('#actprefix').val()
	                    },
	                    success : function(response){
	                            if(response.error) {
	                            	treatMessageError(response,DISPLAY_ON_3);
	                            }
	                    		else {
	                    			$("#secondary").load("secondary-choice/active-skills.php");
	                    		}
	                    },
	                    error : function(XMLHttpRequest, textStatus, errorThrown) {
	                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
	                    }
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
	           $.ajax({
	                type : 'POST',
	                contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	                url : dispatcherURL,
	                dataType : 'json',
	                data: {
	                        remSpeSkillName : $(this).attr('data-skillname'),
	                        getCrePoint : 'get'
	                },
	                success : function(response){
	                        if(response.error){ 
	                        	treatMessageError(response,DISPLAY_ON_3);	                        }
	                		else {
	                			var comeFrom = $('.skills').attr('id');
                    			if(comeFrom == "actSkills"){
                    				$("#secondary").load("secondary-choice/active-skills.php");
                    			}
                    			else{
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    			}
	                			setRemainingPoint(response);
	                		}
	                },
	                error : function(XMLHttpRequest, textStatus, errorThrown) {
	                           displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
	                }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            newNatLanguageSkill : $('#langToAdd').val()
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        //return key in the native language 
        $(document).on('keydown', '#langToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	                $.ajax({
	                    type : 'POST',
	                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	                    url : dispatcherURL,
	                    dataType : 'json',
	                    data: {
	                            newNatLanguageSkill : $('#langToAdd').val()
	                    },
	                    success : function(response){
	                            if(response.error) {
	                            	treatMessageError(response,DISPLAY_ON_3);
	                            }
	                    		else {
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
	                    		}
	                    },
	                    error : function(XMLHttpRequest, textStatus, errorThrown) {
	                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
	                    }
					});
					return false;
				}
        });

		
		//add a temp knowledge  skill
		$(document).on('click', '#addKnowSkill' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            newTmpKnoSkill : $('#knoToAdd').val(),
                            newTmpKnoSkillPrefix : $('#knoprefix').val()
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        
        });
        //Return key on the knowledge skill
        $(document).on('keydown', '#knoToAdd' ,function (e) {
        		if(e.keyCode == 13) {
	        		$.ajax({
	                    type : 'POST',
	                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	                    url : dispatcherURL,
	                    dataType : 'json',
	                    data: {
	                            newTmpKnoSkill : $('#knoToAdd').val(),
	                            newTmpKnoSkillPrefix : $('#knoprefix').val()
	                    },
	                    success : function(response){
	                            if(response.error) {
	                            	treatMessageError(response,DISPLAY_ON_3);
	                            }
	                    		else {
	                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
	                    		}
	                    },
	                    error : function(XMLHttpRequest, textStatus, errorThrown) {
	                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
	                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            skill : $(this).attr('data-skillname')
					},
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			displayMessageOnTertiary(response.desc);
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
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
		               $.ajax({
		                    type : 'POST',
		                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
		                    url : dispatcherURL,
		                    dataType : 'json',
		                    data: {
		                            addSpe : speVal,
		                            addSpeSkillName : $(this).attr('id'),
		                            getCrePoint : 'get'
		                    },
		                    success : function(response){
		                            if(response.error){ 
		                            	treatMessageError(response,DISPLAY_ON_3);
                                    }
		                    		else {
		                    			var comeFrom = $('.skills').attr('id');
		                    			if(comeFrom == "actSkills"){
		                    				$("#secondary").load("secondary-choice/active-skills.php");
		                    			}
		                    			else{
			                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
		                    			}		                    			
		                    			setRemainingPoint(response);
		                    		}
		                    },
		                    error : function(XMLHttpRequest, textStatus, errorThrown) {
		                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');   
		                    }
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
	               $.ajax({
	                    type : 'POST',
	                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
	                    url : dispatcherURL,
	                    dataType : 'json',
	                    data: {
	                            addSpe : speVal,
	                            addSpeSkillName : $(this).attr('data-skillname'),
	                            getCrePoint : 'get'
	                    },
	                    success : function(response){
	                            if(response.error){ 
		                            	treatMessageError(response,DISPLAY_ON_3);
                                    }
		                    		else {
		                    			var comeFrom = $('.skills').attr('id');
		                    			if(comeFrom == "actSkills"){
		                    				$("#secondary").load("secondary-choice/active-skills.php");
		                    			}
		                    			else{
			                    			$("#secondary").load("secondary-choice/knowledge-skills.php");
		                    			}
		                    			setRemainingPoint(response);
		                    		}
	                    },
	                    error : function(XMLHttpRequest, textStatus, errorThrown) {
	                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
	                    }
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
		 	$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphHover : $(this).attr('id')
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
                            }
                    		else {
                    			displayMessageOnTertiary(response.desc, response.title);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');    
                    }
				});

		 	return false;
        
        });

    	
    	//click on add morph
        $(document).on('click','.addMorphIcone' ,function () {
        		hideErrorsMsg();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addMorph : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else{
                                displayMessageOnTertiary(response.desc, response.title);
								$("#secondary").load("secondary-choice/morph.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });
        
        //click on remove morph
        $(document).on('click','.remMorphIcone' ,function () {
        		hideErrorsMsg();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remMorph : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else{
								$("#secondary").load("secondary-choice/morph.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });
        
         //click on morph bonus and description
        $(document).on('click','.morph-BMD', function() {
        	 hideErrorsMsg();
	         $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : $(this).attr('id')
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else{
								$("#tertiary").load("tertiary-choice/morphBMD.php");
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');       
                    }
			});
			return false;
        });
        
        //click on morph settings
        $(document).on('click','.morph-settings', function() {
        	 hideErrorsMsg();
	         $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphSettings : $(this).attr('id'),
                            currentMorphUsed : $(this).attr('id')
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else{
								$("#tertiary").load("tertiary-choice/morphSettings.php", function(){
									$("#morphName").attr('value',response.morphName);									
									$("#mNickname").val(response.nickname);
									$("#mLocation").val(response.location);
									$("#mAge").val(response.age);
									$("#mGender").val(response.gender);
									$("#mMaxApt").html("["+response.morphMaxApt+"]");
									$("#mDur").html("["+response.morphDur+"]");
								});
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');       
                    }
			});
			return false;
        });
        
        //morph settings changes
         $(document).on('change', '#mNicknamem,#mLocation,#mAge,#mGender' ,function () {
          $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphSettingsChange : $("#morphName").attr('value'),
							morphNickname : $("#mNickname").val(),
							morphLocation : $("#mLocation").val(),
							morphAge : $("#mAge").val(),  
							morphGender : $("#mGender").val()                  
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');       
                    }
			});
			return false;
        });
        
        //click on morph pos traits button
        $(document).on('click','.morph-positive-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : morphName
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                            else{
	                            $("#tertiary").load("tertiary-choice/morphPosTraits.php");	
                            }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        //click on morph pos trait for selection deselection
        $(document).on('click', '.addMorphPosTraitIcon,.selMorphPosTraitIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphPosTrait : $(this).attr('id').trim(),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphPosTraits.php");
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
         //click on morph neutral traits button
        $(document).on('click','.morph-neutral-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : morphName
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                            else{
	                            $("#tertiary").load("tertiary-choice/morphNeuTraits.php");	
                            }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        //click on morph neutral trait for selection deselection
        $(document).on('click', '.addMorphNeuTraitIcon,.selMorphNeuTraitIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphPosTrait : $(this).attr('id'), //keep call to posTrait, it's the same anyway
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphNeuTraits.php");
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        //click on morph neg traits button
        $(document).on('click','.morph-negative-traits', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : morphName
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                            else{
	                            $("#tertiary").load("tertiary-choice/morphNegTraits.php");	
                            }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});	
				return false;
        });
        //click on morph neg trait for selection deselection
        $(document).on('click', '.addMorphNegTraitIcon,.selMorphNegTraitIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphNegTrait : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/morphNegTraits.php");	
								$("#quaternary").load("quaternary-choice/traitMorphBMD.php");
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        //hover on morph pos or neg or neu trait
        $(document).on('click', '.morphPosTrait,.morphNegTrait,.morphNeuTrait' ,function () {
            $.ajax({
                type : 'POST',
                contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                url : dispatcherURL,
                dataType : 'json',
                data: {
                    morphTraitHover : $(this).attr('id')
                },
                success : function(response){
                    if(response.error){
                        treatMessageError(response,DISPLAY_ON_3);
                    }
                    else {
                        $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                }
            });

            return false;

        });

        
        //click on implants button
        $(document).on('click','.implants', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');
				$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : morphName
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                            else{
	                            $("#tertiary").load("tertiary-choice/implants.php");
                            }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});	
				return false;
        });
        
         //click on implants for selection deselection
        $(document).on('click', '.addMorphImplantIcon,.selMorphImplantIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphImplant : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_MSG);
                            }
                    		else {
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/implants.php");
								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        
        //click on gear button
        $(document).on('click','.gear', function() {
        		hideErrorsMsg();
        		var morphName = $(this).attr('id');	
				$.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed : morphName
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                            else{
	                            $("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

                            }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
       
         //click on gears for selection deselection
        $(document).on('click', '.addMorphGearIcon,.selMorphGearIcon' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphGear : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
                    			//displayMessageOnQuaternary(response.desc);
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        
        //remove free morph gear
         $(document).on('click', '.remFreeGear' ,function () {
        		var name = $(this).attr('id');
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphFreeGear : name,
                            morphFreePrice : 0,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });

        //add free morph gear
        $(document).on('click', '#addFreeMorphGear' ,function () {
        		var name = $("#freeMorphGearToAdd").val();
        		var price = $("#freeMorphGearPrice").val();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphFreeGear : name,
                            morphFreePrice : price,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        //return on the free morph gear text
        $(document).on('keydown', '#freeMorphGearToAdd' ,function (e) {
    		if(e.keyCode == 13) {
    	 		var name = $("#freeMorphGearToAdd").val();
        		var price = $("#freeMorphGearPrice").val();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            morphFreeGear : name,
                            morphFreePrice : price,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#tertiary").load("tertiary-choice/gears.php", function(){
					    			setupFoldingList();
					    		});

								$("#quaternary").load("quaternary-choice/gearMorphBMD.php");	
								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;

			}
        });



		//hover on morph implant or gear
         //click on implants for selection deselection
        $(document).on('click', '.morphImplant,.morphGear' ,function () {
            $.ajax({
                type : 'POST',
                contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                url : dispatcherURL,
                dataType : 'json',
                data: {
                    morphImplantGearHover : $(this).attr('id')
                },
                success : function(response){
                    if(response.error) {
                        treatMessageError(response,DISPLAY_ON_4);
                    }
                    else {
                        $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            ai : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {                    		
								//displayMessageOnTertiary(response.desc);
								$("#tertiary").load("tertiary-choice/aiBMD.php");
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
				});
				return false;
        });
        
        //hover on ai
         $(document).on('click', '.ai' , function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            hoverAi : $(this).attr('id')
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {                    		
								$("#tertiary").load("tertiary-choice/aiBMD.php");
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
				});
				return false;
        });
        
        //click on soft
        $(document).on('click', '.addSoftGearIcon,.selSoftGearIcon' , function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            softg : $(this).attr('id'),
                            getCrePoint : 'get'
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {                    		
								//displayMessageOnTertiary(response.desc);
								$("#tertiary").load("tertiary-choice/softGearBMD.php");
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
				});
				return false;
        });
        
        //Hover on soft gear
        $(document).on('click', '.softG' , function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            hoverSoftg : $(this).attr('id')
                    },
                    success : function(response){
                    		if(response.error) {
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    		else {                    		
								$("#tertiary").load("tertiary-choice/softGearBMD.php");							
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                    			displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');
                    }
				});
				return false;
        });
        
         //remove free ego gear
         $(document).on('click', '.remFreeEgoGear' ,function () {
        		var name = $(this).attr('id');
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            egoFreeGear : name,
                            egoFreePrice : 0,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });

        
         //add free ego gear
        $(document).on('click', '#addFreeEgoGear' ,function () {
        		var name = $("#freeEgoGearToAdd").val();
        		var price = $("#freeEgoGearPrice").val();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            egoFreeGear : name,
                            egoFreePrice : price,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
				});
				return false;
        });
        //return on the free ego gear text
        $(document).on('keydown', '#freeEgoGearToAdd' ,function (e) {
    		if(e.keyCode == 13) {
    	 		var name = $("#freeEgoGearToAdd").val();
        		var price = $("#freeEgoGearPrice").val();
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            egoFreeGear : name,
                            egoFreePrice : price,
                            getCrePoint : 'get'
                    },
                    success : function(response){
                            if(response.error) {
                            	treatMessageError(response,DISPLAY_ON_4);
                            }
                    		else {
								$("#secondary").load("secondary-choice/softGear.php", function(){
					    			setupFoldingList();
					    		});

								setRemainingPoint(response);
							}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                                displayMessageOnQuaternary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addCredit :'get',
                            getCrePoint : 'get'                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/credits.php");
                    		 	setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        
          //click on remove Button
    	$(document).on('click', '#removeCredit' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remCredit :'get',
                            getCrePoint : 'get'                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/credits.php");
                    		 	setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addMoxie :'get',
                            getCrePoint : 'get'                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/stats.php");
                    		 	setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        
         //click on moxie for description
    	$(document).on('click', '.descMoxie' ,function () {
    			hideErrorsMsg();
        		var moxName = $(this).attr('id');	
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            mox :moxName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	displayMessageOnTertiary(response.desc);	
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });

        
        //click on a stat for description
    	$(document).on('click', '.statMorph' ,function () {
    			//hideErrorsMsg();
        		var statName = $(this).attr('id');	
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            stat :statName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	displayMessageOnQuaternary(response.desc);		
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });

        
          //click on remove Button
    	$(document).on('click', '#removeMoxie' ,function () {
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remMoxie :'get',
                            getCrePoint : 'get'                   
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#secondary").load("secondary-choice/stats.php");
                    		 	setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });

    	
    	 //click on a morph for stat
    	$(document).on('click', '.callStatMorph' ,function () {
    			hideErrorsMsg();
        		var morphName = $(this).attr('id');	
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            currentMorphUsed :morphName             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    		 	$("#tertiary").load("tertiary-choice/statsWithMorph.php");	
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
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
          $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            lastDetailsChange : 'get',
							playerName : $("#mPlayerName").val(),
							characterName : $("#mCharacterName").val(),
							realAge : $("#mRealAge").val(),  
							birthGender : $("#mBirthGender").val(),
							noteDetails : $("#mNote").val()                  
                    },
                    success : function(response){
                    		if(response.error){ 
                    			treatMessageError(response,DISPLAY_ON_3);
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');       
                    }
			});
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

                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addTargetTo : addTargetTo_var,
                            targetVal : targetVal_var,
                            parentName : parentName_var, 
                            parentType : parentType_var,
                            bMcase : bMcase_var, 
                            bmMultiName : bmMultiName_var ,
                            bmId : targetId,
                            parentBmId : parentId         
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    			if(parentType_var == 'origine') $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                    			else if(parentType_var == 'faction') $("#tertiary").load("tertiary-choice/factionBMD.php");
                    			else if(parentType_var == 'trait') $("#tertiary").load("tertiary-choice/traitBMD.php");
                    			else if(parentType_var == 'psi') $("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			else if(parentType_var == 'morph') $("#tertiary").load("tertiary-choice/morphBMD.php");
                    			else if(parentType_var == 'morphTrait') $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                    			else if(parentType_var == 'morphGear') $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			else if(parentType_var == 'ai') $("#tertiary").load("tertiary-choice/aiBMD.php");
                    			else if(parentType_var == 'soft') $("#tertiary").load("tertiary-choice/softGearBMD.php");
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
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
                $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            removeTargetFrom : removeTargetFrom_var,
                            targetVal : targetVal_var,
                            parentName : parentName_var, 
                            parentType : parentType_var,
                            bMcase : bMcase_var, 
                            bmMultiName : bmMultiName_var,
                            bmId : targetId,
                            parentBmId : parentId 
                                       
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_3);
							}
                    		else{
                    			if(parentType_var == 'origine') $("#tertiary").load("tertiary-choice/backgroundBMD.php");
                    			else if(parentType_var == 'faction') $("#tertiary").load("tertiary-choice/factionBMD.php");
                    			else if(parentType_var == 'trait') $("#tertiary").load("tertiary-choice/traitBMD.php");
                    			else if(parentType_var == 'psi') $("#tertiary").load("tertiary-choice/psySleightBDM.php");
                    			else if(parentType_var == 'morph') $("#tertiary").load("tertiary-choice/morphBMD.php");
                    			else if(parentType_var == 'morphTrait') $("#quaternary").load("quaternary-choice/traitMorphBMD.php");
                    			else if(parentType_var == 'morphGear') $("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			else if(parentType_var == 'ai') $("#tertiary").load("tertiary-choice/aiBMD.php");
                    			else if(parentType_var == 'soft') $("#tertiary").load("tertiary-choice/softGearBMD.php");
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        
        //Click on occurence button ADD and REMOVE
        //AI
        $(document).on('click', '#addOccurence_AI' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addOccurence : 'AI' ,
                            getCrePoint : 'get'              
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#tertiary").load("tertiary-choice/aiBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });
        $(document).on('click', '#removeOccurence_AI' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remOccurence : 'AI'  ,
                            getCrePoint : 'get'             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#tertiary").load("tertiary-choice/aiBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
		//SOFT
		 $(document).on('click', '#addOccurence_SOFT' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addOccurence : 'SOFT' ,
                            getCrePoint : 'get'              
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#tertiary").load("tertiary-choice/softGearBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });
        $(document).on('click', '#removeOccurence_SOFT' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remOccurence : 'SOFT'  ,
                            getCrePoint : 'get'             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#tertiary").load("tertiary-choice/softGearBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });
        //MORPH
         $(document).on('click', '#addOccurence_MORPH' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            addOccurence : 'MORPH' ,
                            getCrePoint : 'get'              
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        });
        $(document).on('click', '#removeOccurence_MORPH' ,function () {
    		    $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            remOccurence : 'MORPH'  ,
                            getCrePoint : 'get'             
                    },
                    success : function(response){
                            if(response.error){
                            	 treatMessageError(response,DISPLAY_ON_MSG);
							}
                    		else{
                    			$("#quaternary").load("quaternary-choice/gearMorphBMD.php");
                    			setRemainingPoint(response);
                    		 }
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');      
                    }
				});
				return false;
        
        });

        
        //POPUP MANAGEMENT =================
        //click on button summary
        $(".summaryButton").click(function() {
        	if($("#summary_popup").css('visibility') == 'visible'){
				$("#summary_popup").css('opacity',0);
				$("#summary_popup").css('visibility','hidden');
            }
            else{
            	closeAllPopup();
            	$("#summary_popup").load("popup-contents/summary.php");
				$("#summary_popup").css('opacity',1);
				$("#summary_popup").css('visibility','visible');
	        }
        });
        //Click on summary popup for close
        $("#summary_popup").click(function() {
        	$("#summary_popup").css('opacity',0);
			$("#summary_popup").css('visibility','hidden');
        });
        
        
         //click on button validation
        $(".validateButton").click(function() {
        	if($("#validation_popup").css('visibility') == 'visible'){
				$("#validation_popup").css('opacity',0);
				$("#validation_popup").css('visibility','hidden');
            }
            else{
            	closeAllPopup();
            	$("#validation_popup").load("popup-contents/validation.php");
				$("#validation_popup").css('opacity',1);
				$("#validation_popup").css('visibility','visible');
	        }
        });
        //Click on validation popup for close
        $("#validation_popup").click(function() {
        	$("#validation_popup").css('opacity',0);
			$("#validation_popup").css('visibility','hidden');
        });
        
          //click on button reset
        $(".settingsButton").click(function() {
        	closeAllPopup();
        	$("#reset_popup").load("popup-contents/reset.php");
			$("#reset_popup").css('opacity',1);
			$("#reset_popup").css('visibility','visible');
        });
       //Click on the begin button
       $(document).on("click",".startButton",function() {
       		startLoading();
       		 $.ajax({
                    type : 'POST',
                    contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
                    url : dispatcherURL,
                    dataType : 'json',
                    data: {
                            setCP :  $("#startCP").val(),
                            getCrePoint : 'get'               
                    },
                    success : function(response){
                    		if(response.error){ 
                    			$("#reset_popup").html(response.msg);
                    		}
                    		else{
                    			loaddingReset();
	                    		setRemainingPoint(response);
	                    		
                    		}
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                               $("#reset_popup").html('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');       
                    }
			});
			
        	return false;
        });
        
        

       //Click on the cancel button
       $(document).on("click",".cancelButton",function() {
        	location.reload();
        });
        
        //Click on the load cancel button
       $(document).on("click",".cancelLoadButton",function() {
        	location.reload();
        });
        
        //Click on the load load button
       $(document).on("click",".loadLoadButton",function() {
        	$('#loadForm').submit();
        });
        
         //Click on the save cancel button
       $(document).on("click",".cancelSaveButton",function() {
        	$("#save_popup").css('opacity',0);
			$("#save_popup").css('visibility','hidden');
        });
        
        //Click on the save save button
       $(document).on("click",".saveSaveButton",function() {
        	$('#saveForm').submit();
        	$("#save_popup").css('opacity',0);
			$("#save_popup").css('visibility','hidden');
        });

		//click on button export txt
        $(".exportTxtButton").click(function() {
        	 window.open("./exporter/txtExporter.php");
        });

        
        //click on button export pdf
        $(".exportButton").click(function() {
        	 window.open("./exporter/pdfExporterV2_fpdf.php");
        });
        
        //click on button save
        $(".saveButton").click(function() {
        	// window.open("./other/save.php");
        	 closeAllPopup();
        	$("#save_popup").load("popup-contents/save_popup.php");
			$("#save_popup").css('opacity',1);
			$("#save_popup").css('visibility','visible');
        });
        //click on button load on main page
        $(".loadButton").click(function() {
        	closeAllPopup();
        	$("#load_popup").load("popup-contents/load.php");
			$("#load_popup").css('opacity',1);
			$("#load_popup").css('visibility','visible');
        });

      //click on button about
      $(".aboutButton").click(function() {

        closeAllPopup();
        //window.location.href = "/Creator/version4/about.html";
        $("#about_popup").load("popup-contents/about.php");
        $("#about_popup").css('opacity',1);
        $("#about_popup").css('visibility','visible');
      });

      $(document).on("click",".closeAboutButton",function() {
        $("#about_popup").css('opacity',0);
        $("#about_popup").css('visibility','hidden');
      });
        
        
        //Load file
        $(document).on('submit','#loadForm', function () {
        	var fileVal = $('#fileName').val();
	    	if(fileVal == ""){
	    		$('#errorLoadMsg').html("Choose a file !");
				return false;
			}
			else{
				$("#load_popup").css('opacity',0);
				$("#load_popup").css('visibility','hidden');
	            closeAllPopup();
			}  
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

    $.ajax({
        type : 'POST',
        contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
        url : dispatcherURL,
        dataType : 'json',
        data: {
            changeSkillName : node.attr('data-skillname'),
            changeSkillValue : node.val(),
            getCrePoint : 'get'
        },
        success : function(response){
            if(response.error) {
                treatMessageError(response,DISPLAY_ON_3);
                $("[id="+skId+"]").css("background-color", "#BA0050");
            }
            else {
                $("[id="+skId+"]").css("background-color", "#FEFEFE");
                $("#secondary").load(after, function(){
                    $(focusOnSkill).focus();
                });
                setRemainingPoint(response);
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
        }
    });
}

function removeSkill(node, after) {
    //remove a temp active skill
    $.ajax({
        type : 'POST',
        contentType: 'application/x-www-form-urlencoded;charset=ISO-8859-1',
        url : dispatcherURL,
        dataType : 'json',
        data: {
                remSkill : node.attr('data-skillname')
        },
        success : function(response){
                if(response.error) {
                    treatMessageError(response,DISPLAY_ON_3);
                }
                else {
                    $("#secondary").load(after);
                }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
                   displayMessageOnTertiary('There was an error.<br>'+textStatus+'<br>'+errorThrown+'<br>');     
        }
    });
}

function startLoading(){
	$("#loading_popup").show();
}

function endLoading(){
	$("#loading_popup").hide();
}

function closeAllPopup(){
	$("#summary_popup").css('opacity',0);
	$("#summary_popup").css('visibility','hidden');
	
	$("#validation_popup").css('opacity',0);
	$("#validation_popup").css('visibility','hidden');

  $("#about_popup").css('opacity',0);
  $("#about_popup").css('visibility','hidden');
}

function treatMessageError(response,preferenceDisplay){
	if(response.erType == "system"){
		if(preferenceDisplay == DISPLAY_ON_MSG){
			displayRulesMessage(response.msg);
		}
		else if(preferenceDisplay == DISPLAY_ON_TOP){
			displayMessageOnTop(response.msg);
		}
		else if(preferenceDisplay == DISPLAY_ON_3){
			displayMessageOnTertiary(response.msg);
		}
		else if(preferenceDisplay == DISPLAY_ON_4){
			displayMessageOnQuaternary(response.msg);
		}
	}
	else if(response.erType == "rules"){
		displayRulesMessage(response.msg);
	}
	else{
		displayMessageOnQuaternary(response.msg);
	}
}


function displayMessageOnTop(msg){
	$("#base-infos").html(msg);
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

