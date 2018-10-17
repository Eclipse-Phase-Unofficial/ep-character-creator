// ================= Panel 1 =================
//Requires: ajax_helper.js
function testAJAX(url){
    $.ajax({
        type : 'GET',
        url : 'rest/'+url+'.php',
        success: function(response){
            console.log('got something');
            console.log(response);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            // displayError('An Error Occured!<br>'+textStatus+'<br>'+errorThrown+'<br>The Server May Be Down!<br>Please Try Again Later!');
            console.error(textStatus)
            console.error(errorThrown)
        }
    });
}
//BACKGROUND
$("a.backgrounds").click(function(){
    loadSecondary("secondary-choice/backgrounds.php");
    testAJAX('backgrounds')

    do_ajax({
            getBcg : 'get'
        },
        function(response){
            if(response.currentBcg != null){
                loadTertiary("tertiary-choice/backgroundBMD.php");
            }
    });
    return false;
});

//FACTION
$("a.faction").click(function(){
    loadSecondary("secondary-choice/factions.php");
    testAJAX('factions')

    do_ajax({
            getFac : 'get'
        },
        function(response){
            if(response.currentFac != null){
                loadTertiary("tertiary-choice/factionBMD.php");
            }
    });
    return false;
});

//MOTIVATION
$("a.motivations").click(function(){
    loadSecondary("secondary-choice/motivations.php");
    testAJAX('motivations')
});

//APTITUDES
$("a.aptitudes").click(function(){
    loadSecondary("secondary-choice/aptitudes.php");
    testAJAX('aptitudes')
});

//REPUTATIONS
$("a.rep").click(function(){
    loadSecondary("secondary-choice/reputations.php");
    testAJAX('reputations')
});

//POSITIVE TRAITS
$("a.posTrait").click(function(){
    loadSecondary("secondary-choice/positive-traits.php");
    testAJAX('positive-traits')
});

//NEUTRAL TRAITS
$("a.neuTrait").click(function(){
    loadSecondary("secondary-choice/neutral-traits.php");
    testAJAX('neutral-traits')
});

//NEGATIVE TRAITS
$("a.negTrait").click(function(){
    loadSecondary("secondary-choice/negative-traits.php");
    testAJAX('negative-traits')
});

//ACTIVE SKILLS
$("a.activeSkills").click(function(){
    loadSecondary("secondary-choice/active-skills.php");
    testAJAX('active-skills')
});

//KNOWLEDGE SKILLS
$("a.knowledgeSkills").click(function(){
    loadSecondary("secondary-choice/knowledge-skills.php");
    testAJAX('knowledge-skills')
});

//MORPHS
$("a.morph").click(function(){
    loadSecondary("secondary-choice/morph.php");
    testAJAX('morph')
});

//SOFT GEAR AI
$("a.soft").click(function(){
    loadSecondary("secondary-choice/softGear.php");
    testAJAX('soft-gear')
});

//PSI SLEIGHTS
$("a.psy").click(function(){
    loadSecondary("secondary-choice/psy-sleights.php");
    // TODO
});

//CREDIT
$("a.credit").click(function(){
    loadSecondary("secondary-choice/credits.php");
    testAJAX('credit')
});

//STATS
$("a.moxie").click(function(){
    loadSecondary("secondary-choice/stats.php");
    testAJAX('stats')
});

//LAST DETAILS
$("a.lastdetails").click(function(){
    loadSecondary("secondary-choice/last-details.php");
    testAJAX('last-details')
});
