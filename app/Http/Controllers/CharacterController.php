<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CharacterController extends Controller
{
    /**
     * Only allow access if a creator already exists
     */
    public function __construct()
    {
        $this->middleware('creator');
    }

    /**
     * Display a listing of the resource.
     * This includes the "Last Details" of a character (which are used for more than just "Last Details)
     *
     * @return Response
     */
    public function get()
    {
        $character = creator()->character;
        $return = [];
        $return['playerName'] = $character->playerName;
        $return['realAge'] = $character->realAge;
        $return['birthGender'] = $character->birthGender;
        $return['note'] = $character->note;
        $return['currentMorphUid'] = $character->currentMorph? $character->currentMorph->getUid(): null;
        return response($return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        //
    }
}
