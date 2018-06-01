// ================= Panel 1 =================
//Requires: ajax_helper.js

//BACKGROUND
$("a.backgrounds").click(function(){
    loadSecondary("secondary-choice/backgrounds.php");
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
});

//APTITUDES
$("a.aptitudes").click(function(){
    loadSecondary("secondary-choice/aptitudes.php");
});

//REPUTATIONS
$("a.rep").click(function(){
    loadSecondary("secondary-choice/reputations.php");
});

//POSITIVE TRAITS
$("a.posTrait").click(function(){
    loadSecondary("secondary-choice/positive-traits.php");
});

//NEUTRAL TRAITS
$("a.neuTrait").click(function(){
    loadSecondary("secondary-choice/neutral-traits.php");
});

//NEGATIVE TRAITS
$("a.negTrait").click(function(){
    loadSecondary("secondary-choice/negative-traits.php");
});

//ACTIVE SKILLS
$("a.activeSkills").click(function(){
    loadSecondary("secondary-choice/active-skills.php");
});

//KNOWLEDGE SKILLS
$("a.knowledgeSkills").click(function(){
    loadSecondary("secondary-choice/knowledge-skills.php");
});

//MORPHS
$("a.morph").click(function(){
    loadSecondary("secondary-choice/morph.php");
});

//SOFT GEAR AI
$("a.soft").click(function(){
    loadSecondary("secondary-choice/softGear.php");
});

//PSI SLEIGHTS
$("a.psy").click(function(){
    loadSecondary("secondary-choice/psy-sleights.php");
});

//CREDIT
$("a.credit").click(function(){
    loadSecondary("secondary-choice/credits.php");
});

//STATS
$("a.moxie").click(function(){
    loadSecondary("secondary-choice/stats.php");
});

//LAST DETAILS
$("a.lastdetails").click(function(){
    loadSecondary("secondary-choice/last-details.php");
});
