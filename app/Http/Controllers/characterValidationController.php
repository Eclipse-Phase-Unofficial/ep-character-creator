<?php


namespace App\Http\Controllers;


use App\Creator\EPValidation;
use function Clue\StreamFilter\append;
use Illuminate\Http\Request;

class characterValidationController
{
    private static function buildResponsePart(string $name, bool $isValid, string $errorMessage): array
    {
        return [
            $name => [
                'name' => $name,
                'isValid' => $isValid,
                'errorMessage' => $errorMessage
            ]
        ];
    }

    public function read(Request $request){
        $isValid = creator()->checkValidation();

        $aptPoint = creator()->validation->items[EPValidation::$APTITUDE_POINT_USE];
        $repPoint = creator()->validation->items[EPValidation::$REPUTATION_POINT_USE];
        $bck 	  = creator()->validation->items[EPValidation::$BACKGROUND_CHOICE];
        $fac      = creator()->validation->items[EPValidation::$FACTION_CHOICE];
        $charName = creator()->validation->items[EPValidation::$CHARACTER_NAME_CHOICE];
        $morph    = creator()->validation->items[EPValidation::$MORPH_CHOICE];
        $mot      = creator()->validation->items[EPValidation::$MOTIVATION_THREE_CHOICE];
        $acSkill  = creator()->validation->items[EPValidation::$ACTIVE_SKILLS_MIN];
        $knSkill  = creator()->validation->items[EPValidation::$KNOWLEDGE_SKILLS_MIN];

        $AP = creator()->getAptitudePoint();
        $CP = creator()->getCreationPoint();
        $RP = creator()->getReputationPoints();
        $cpActivRestNeed = creator()->getActiveRestNeed();
        $cpKnowRestNeed = creator()->getKnowledgeRestNeed();

        $validatorResponse = [];
        $validatorResponse += static::buildResponsePart("Background",$bck,"You have to choose a background.");
        $validatorResponse += static::buildResponsePart("Faction",$fac,"You have to choose a faction.");
        $validatorResponse += static::buildResponsePart("Motivations",$mot,"You have to choose at least three motivations.");
        $validatorResponse += static::buildResponsePart("Active Skills",$acSkill,"<b>(Need: ".$cpActivRestNeed."CP)</b> You have to spend more points on your Active Skills.");
        $validatorResponse += static::buildResponsePart("Knowlege Skills",$knSkill,"<b>(Need: ".$cpKnowRestNeed."CP)</b> You have to spend more points on your Knowlege Skills.");
        $validatorResponse += static::buildResponsePart("Creation Points",($CP==0),"<b>(Unspent: ".$CP."AP)</b> You have unspent Creation Points.");
        $validatorResponse += static::buildResponsePart("Aptitude Points",$aptPoint,"<b>(Unspent: ".$AP."AP)</b> You have unspent Aptitude Points.");
        $validatorResponse += static::buildResponsePart("Reputation Points",$repPoint,"<b>(Unspent: ".$RP."RP)</b> You have unspent Reputation Points.");
        $validatorResponse += static::buildResponsePart("Morph(s)",$morph,"You have to choose at least one Morph.");
        $validatorResponse += static::buildResponsePart("Character name",$charName,"Your Character must have a name.");

        return ['isValid' => $isValid, 'validators' => $validatorResponse];
    }
}