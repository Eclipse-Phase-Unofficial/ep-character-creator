// ================= Main Menu =================
//Requires: ajax_helper.js

//BACKGROUND
$("a.backgrounds").click(function(){
    loadSecondary("secondary-choice/backgrounds.php");
    do_ajax({getBcg : 'get'},function(response){
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
    return false;
});

//APTITUDES
$("a.aptitudes").click(function(){
    loadSecondary("secondary-choice/aptitudes.php");
    return false;
});

//REPUTATIONS
$("a.rep").click(function(){
    do_ajax( {
            getCrePoint : 'get'
        },
        function(response){
            setRemainingPoint(response);
            loadSecondary("secondary-choice/reputations.php");
        });

    return false;
});

//POSITIVE TRAITS
$("a.posTrait").click(function(){
    loadSecondary("secondary-choice/positive-traits.php");
    return false;
});

//NEUTRAL TRAITS
$("a.neuTrait").click(function(){
    loadSecondary("secondary-choice/neutral-traits.php");
    return false;
});

//NEGATIVE TRAITS
$("a.negTrait").click(function(){
    loadSecondary("secondary-choice/negative-traits.php");
    return false;
});

//ACTIVE SKILLS
$("a.activeSkills").click(function(){
    loadSecondary("secondary-choice/active-skills.php");
    return false;
});

//KNOWLEDGE SKILLS
$("a.knowledgeSkills").click(function(){
    loadSecondary("secondary-choice/knowledge-skills.php");
    return false;
});

//MORPHS
$("a.morph").click(function(){
    hideErrorsMsg();
    $("#secondary").load("secondary-choice/morph.php", function(){
        setupFoldingList();
    });
});

//SOFT GEAR AI
$("a.soft").click(function(){
    hideErrorsMsg();
    $("#secondary").load("secondary-choice/softGear.php", function(){
        setupFoldingList();
    });

    return false;
});

//PSI SLEIGHTS
$("a.psy").click(function(){
    hideErrorsMsg();
    $("#secondary").load("secondary-choice/psy-sleights.php", function(){
        setupFoldingList();
    });

    return false;
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
