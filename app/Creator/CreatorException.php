<?php


namespace App\Creator;

/**
 * This is thrown when the system is asked to do something it really shouldn't.
 *
 * Things such as adding traits to characters who already posses them, or ego traits to morphs, etc...
 * The UI shouldn't let this happen in the first place, so when it does, something went wrong.
 */
class CreatorException extends \Exception
{

}