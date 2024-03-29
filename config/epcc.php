<?php

use Carbon\Carbon as Carbon;

//This replaces the old confi.ini file that the character creator was using before
//TODO:  Some of the settings here probably belong somewhere else, like app
//TODO:  Some of these should probably be loaded from the .env file
return [
    'versionName' => "Gate Jump",
    'releaseDate' => Carbon::parse("December 2018"),
    'versionNumber' => 1.52,
    'versionNumberMin' => 0.91,
    'googleAnalyticsId' => env('MIX_GOOGLE_ANALYTICS_ID', ''),
    //RulesValues
    'AptitudesPoint' => 105,
    'AptitudesMinValue' => 5,
    'AptitudesMaxValue' => 30,
    'AbsoluteAptitudesMaxValue' => 40,
    'MoxieStartValue' => 1,
    'SpeedStartValue' => 1,
    'CreditStart' => 5000,
    'RepStart' => 50,
    'CreationPoint' => 1000,
    'NativeTongueBaseValue' => 70,
    'ActiveSkillsMinimum' => 400,
    'KnowledgeSkillsMinimum' => 300,
    'MoxiePointCost' => 15,
    'AptitudePointCost' => 10,
    'SpecializationPointCost' => 5,
    'SkillPointUnderCost' => 1,
    'SkillPointUpperCost' => 2,
    'CreditPointCost' => 0.001,
    'RepPointCost' => 0.1,
    'SkillMaxPoint' => 80,
    'SkillEvolutionMaxPoint' => 99,
    'SkillMinPoint' => 0,
    'SkillLimitForImprove' => 60,
    'RepMaxPoint' => 80,
    'RepMinPoint' => 0,
    'MoxMaxPoint' => 8,
    'MoxEvoMaxPoint' => 10,
    'MoxMinPoint' => 1,
    'NativeTongueBonus' => 60,
    'SpecializationCost' => 5,
    'PsyCpCost' => 5,
    'MaxPointPositiveTrait' => 50,
    'MaxPointNegativeTrait' => 50,
    'MaxPointNegativeTraitOnMorph' => 25,
    'EvoMaxRepValue' => 99,
    'MaxCreditPurchaseWithCp' => 100,
    'SpeedMaxValue' => 4,
];