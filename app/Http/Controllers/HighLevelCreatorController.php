<?php

namespace App\Http\Controllers;

use App\Creator\EPCharacterCreator;
use App\Creator\EPFileUtility;
use App\Creator\EPValidation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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
     * Only allow access if a creator already exists
     *
     * Unlike most controllers, this one has exceptions so a creator can be made/loaded
     */
    public function __construct()
    {
        $this->middleware('creator', ['except' => ['store', 'update']]);
    }

    /**
     * Display information about the current Character Creator
     *
     * @param Request $request
     * @return Response
     */
    public function get(Request $request)
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
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'creationPoints' => 'required|int',
        ]);
        session()->put('cc', new EPCharacterCreator($request->get('creationPoints')));
        return response(['Success' => True]);
    }

    /**
     * Obtain a json representation of the current character (for saving)
     *
     * @return Response
     */
    public function save()
    {
        $file_util = new EPFileUtility(creator()->character);
        $filename = $file_util->buildExportFilename('EPCharacterSave', 'json');

        $json = json_encode(creator()->getSavePack());
        return response($json)->withHeaders([
            'Content-Disposition' => "attachment; filename=$filename"
        ]);
    }

    /**
     * Load from a .json file
     *
     * Has the side effect of destroying the old one
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|array',
            'creationMode' => 'required',
            'rezPoints' => 'required_if:creationMode,==,false',
            'reputationPoints' => 'required_if:creationMode,==,false',
            'creditsEarned' => 'required_if:creationMode,==,false',
        ]);

        $saveFile = $request->get('file');

        if (empty($saveFile['versionNumber']) || floatval($saveFile['versionNumber']) < config('epcc.versionNumberMin')){
            return response(['Errors' => ["Incompatible file version!"]]);
        }
        session()->put('cc', new EPCharacterCreator());
        creator()->back = new EPCharacterCreator();

        creator()->loadSavePack($saveFile);
        creator()->back->loadSavePack($saveFile);
        //TODO:  These should be set in the creator itself
        creator()->back->setMaxRepValue(config('epcc.EvoMaxRepValue'));
        creator()->setMaxRepValue(config('epcc.EvoMaxRepValue'));
        creator()->back->setMaxSkillValue(config('epcc.SkillEvolutionMaxPoint'));
        creator()->setMaxSkillValue(config('epcc.SkillEvolutionMaxPoint'));

        // Save pack and user both say we are in creation mode
        if (creator()->creationMode == true && $request->get('creationMode') ){
            creator()->creationMode = true; //We stay in creation mode
        }else{
            // Make sure it's a valid character for play
            if (creator()->checkValidation()){
                // Switch to Evo Mode
                creator()->creationMode = false;
                creator()->evoRezPoint += $request->get('rezPoints');
                creator()->evoRepPoint += $request->get('reputationPoints');
                creator()->evoCrePoint += $request->get('creditsEarned');
            }else{
                // Stay in creation mode
                creator()->creationMode = true;
                //return static::treatCreatorErrors(new EPCreatorErrors("File is not valid for play!  Staying in creation mode!",EPCreatorErrors::$RULE_ERROR));
            }

        }

        if (!empty(creator()->character->morphs)){
            creator()->activateMorph(creator()->character->morphs[0]);
        }
        creator()->adjustAll();
        return response(['Success' => True]);
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
