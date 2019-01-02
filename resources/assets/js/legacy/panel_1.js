// ================= Panel 1 =================
//Requires: ajax_helper.js

//BACKGROUND
$("a.backgrounds").click(function(){
    loadSecondary("secondary-choice/backgrounds");
    do_ajax({
            getBcg : 'get'
        },
        function(response){
            if(response.currentBcg != null){
                loadTertiary("tertiary-choice/backgroundBMD");
            }
    });
    return false;
});

//FACTION
$("a.faction").click(function(){
    loadSecondary("secondary-choice/factions");
    do_ajax({
            getFac : 'get'
        },
        function(response){
            if(response.currentFac != null){
                loadTertiary("tertiary-choice/factionBMD");
            }
    });
    return false;
});

//MOTIVATION
$("a.motivations").click(function(){
    loadSecondary("secondary-choice/motivations");
});

//APTITUDES
$("a.aptitudes").click(function(){
    loadSecondary("secondary-choice/aptitudes");
});

//REPUTATIONS
$("a.rep").click(function(){
    loadSecondary("secondary-choice/reputations");
});

//POSITIVE TRAITS
$("a.posTrait").click(function(){
    loadSecondary("secondary-choice/positive-traits");
});

//NEUTRAL TRAITS
$("a.neuTrait").click(function(){
    loadSecondary("secondary-choice/neutral-traits");
});

//NEGATIVE TRAITS
$("a.negTrait").click(function(){
    loadSecondary("secondary-choice/negative-traits");
});

//ACTIVE SKILLS
$("a.activeSkills").click(function(){
    loadSecondary("secondary-choice/active-skills");
});

//KNOWLEDGE SKILLS
$("a.knowledgeSkills").click(function(){
    loadSecondary("secondary-choice/knowledge-skills");
});

//MORPHS
$("a.morph").click(function(){
    loadSecondary("secondary-choice/morph");
});

//SOFT GEAR AI
$("a.soft").click(function(){
    loadSecondary("secondary-choice/softGear");
});

//PSI SLEIGHTS
$("a.psy").click(function(){
    loadSecondary("secondary-choice/psy-sleights");
});

//CREDIT
$("a.credit").click(function(){
    loadSecondary("secondary-choice/credits");
});

//STATS
$("a.moxie").click(function(){
    loadSecondary("secondary-choice/stats");
});

//LAST DETAILS
$("a.lastdetails").click(function(){
    loadSecondary("secondary-choice/last-details");
});
