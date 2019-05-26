<?php

namespace App\Http\Controllers;

use App\Creator\EPValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Handle high level details of the Character Creator.
 *
 * This doesn't match one to one with CRUD since there is only a single creator per session.
 * Plus there are two levels of detail (high level vs actually retrieving a full json save file)
 * @package App\Http\Controllers
 */
class HighLevelCreatorController extends Controller
{
    /**
     * Display information about the current Character Creator
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $return = [];
        $return['rez_remain'] = creator()->getRezPoints();
        $return['creation_remain'] = creator()->getCreationPoint();
        $return['aptitude_remain'] = creator()->getAptitudePoint();
        $return['asr_remain'] = creator()->getActiveRestNeed();
        $return['ksr_remain'] = creator()->getKnowledgeRestNeed();
        $return['reputation_remain'] = creator()->getReputationPoints();
        $return['credits'] = creator()->getCredit();
        return response($return);
    }

    /**
     * Create a new Character Creator from scratch
     *
     * Has the side effect of destroying the old one
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Obtain a json representation of the current character (for saving)
     *
     * @return Response
     */
    public function show()
    {
        //
    }

    /**
     * Load from a .json file
     *
     * Has the side effect of destroying the old one
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }

    private static function buildValidationResponsePart(string $name, bool $isValid, string $errorMessage): array
    {
        return [
            $name => [
                'name' => $name,
                'isValid' => $isValid,
                'errorMessage' => $errorMessage
            ]
        ];
    }

    /**
     * Check if a character is valid or not
     *
     * @return Response
     */
    public function validateCharacter()
    {
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
        $validatorResponse += static::buildValidationResponsePart("Background",$bck,"You have to choose a background.");
        $validatorResponse += static::buildValidationResponsePart("Faction",$fac,"You have to choose a faction.");
        $validatorResponse += static::buildValidationResponsePart("Motivations",$mot,"You have to choose at least three motivations.");
        $validatorResponse += static::buildValidationResponsePart("Active Skills",$acSkill,"<b>(Need: ".$cpActivRestNeed."CP)</b> You have to spend more points on your Active Skills.");
        $validatorResponse += static::buildValidationResponsePart("Knowlege Skills",$knSkill,"<b>(Need: ".$cpKnowRestNeed."CP)</b> You have to spend more points on your Knowlege Skills.");
        $validatorResponse += static::buildValidationResponsePart("Creation Points",($CP==0),"<b>(Unspent: ".$CP."AP)</b> You have unspent Creation Points.");
        $validatorResponse += static::buildValidationResponsePart("Aptitude Points",$aptPoint,"<b>(Unspent: ".$AP."AP)</b> You have unspent Aptitude Points.");
        $validatorResponse += static::buildValidationResponsePart("Reputation Points",$repPoint,"<b>(Unspent: ".$RP."RP)</b> You have unspent Reputation Points.");
        $validatorResponse += static::buildValidationResponsePart("Morph(s)",$morph,"You have to choose at least one Morph.");
        $validatorResponse += static::buildValidationResponsePart("Character name",$charName,"Your Character must have a name.");

        return response(['isValid' => $isValid, 'validators' => $validatorResponse]);
    }
}
