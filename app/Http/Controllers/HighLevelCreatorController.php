<?php

namespace App\Http\Controllers;

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
}
