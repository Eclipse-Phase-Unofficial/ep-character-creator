<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('main');
});

Route::prefix('popup-contents')->group(function () {
    Route::get('/about', function () {
        return view('popup-contents.about');
    });
    Route::get('/load', function () {
        return view('popup-contents.load');
    });
    Route::get('/reset', function () {
        return view('popup-contents.reset');
    });
    Route::get('/save_popup', function () {
        return view('popup-contents.save_popup');
    });
    Route::get('/validation', function () {
        return view('popup-contents.validation');
    });
});

//All the routes to get and set data
//These were all originally in dispatcher.php
Route::prefix('dispatch')->group(function () {
    //if a file to load LOAD FILE
    Route::post('load_char', function () {
    });

    //FIRST RUN (originally 'firstTime')
    Route::get('creatorExists', function () {
    });

    //SET CP FOR A NEW CHARACTER
    Route::post('setCP', function () {
    });

    Route::prefix('ego')->group(function () {
        //GET BACKGROUND (originally 'getBcg')
        Route::get('backgrounds/{background}', function () {
        });
        //SET BACKGROUND (originally 'origine')
        Route::post('backgrounds', function () {
        });

        //GET FACTION (originally 'getFac')
        Route::get('factions/{faction}', function () {
        });
        //SET FACTION
        Route::post('factions', function () {
        });

        //HOVER POS/NEG TRAIT (originally 'traitHover')
        Route::get('traits/{trait}', function () {
        });
        //SET TRAIT (originally 'posTrait', and 'negTrait')
        Route::post('traits', function () {
        });
        //Remove trait (originally 'posTrait', and 'negTrait')
        Route::delete('traits/{trait}', function () {
        });

        //HOVER PSY SLEIGHT (originally 'hoverPsyS')
        Route::get('psySleights/{psySleight}', function () {
        });
        //SET PSY SLEIGHT (originally 'PsyS')
        Route::post('psySleights', function () {
        });

        //SET APTITUDES
        Route::post('aptitudes', function () {
        });
        //SET REPUTATION
        Route::post('reputations', function () {
        });

        //Skills
        Route::prefix('skills')->group(function () {
            //ADD TMP SKILL (originally 'newTmpActSkill', 'newTmpActSkill', and 'newNatLanguageSkill')
            Route::post('/', function () {
            });
            //GET SKILL DESCRIPTION (originally 'skill')
            Route::get('{skill}', function () {
            });
            //CHANGE SKILL VALUE
            Route::post('{skill}', function () {
            });
            //REMOVE TMP SKILL
            Route::delete('{skill}', function () {
            });

            //ADD SKILL SPECIALIZATION
            Route::post('{skill}/specialization', function () {
            });
            //REMOVE SKILL SPECIALIZATION
            Route::delete('{skill}/specialization', function () {
            });
        });

        //SET FREE EGO GEAR (originally 'egoFreeGear')
        Route::post('freeGears', function () {
        });
        //REMOVE FREE EGO GEAR (originally 'egoFreeGear')
        Route::delete('freeGears/{gear}', function () {
        });
    });

    //Add a motivation (originally 'newMot')
    Route::post('motivations', function () {
    });
    //Remove a motivation (originally 'remMot')
    Route::delete('motivations/{motivation}', function () {
    });


    //MORPH SELECTED ON GUI (originally 'currentMorphUsed')
    //TODO:  Remove this one
    Route::post('currentMorph', function () {
    });

    Route::prefix('morphs')->group(function () {
        //ADD MORPH (originally 'addRemMorph')
        Route::post('/', function () {
        });
        //HOVER MORPH (originally 'morphHover', )
        Route::get('{morph}', function () {
        });
        //REMOVE MORPH (originally 'addRemMorph')
        Route::delete('{morph}', function () {
        });

        //GET MORPH SETTINGS (originally 'morphSettings')
        Route::get('{morph}/settings', function () {
        });
        //SET MORPH SETTINGS (originally 'morphSettingsChange')
        Route::post('{morph}/settings', function () {
        });

        //HOVER MORPH NEG-POS TRAIT (originally 'morphTraitHover')
        Route::get('traits/{trait}', function () {
        });
        //SET TRAIT (originally 'morphPosTrait', and 'morphNegTrait')
        Route::post('traits', function () {
        });
        //Remove trait (originally 'morphPosTrait', and 'morphNegTrait')
        Route::delete('traits/{trait}', function () {
        });


        //TODO:  Merge implants, gear, and free gear, then filter them by type in the UI
        //HOVER ON MORPH GEAR OR IMPLANT (originally 'morphImplantGearHover')
        Route::get('gears/{gear}', function () {
        });

        //SET MORPH IMPLANTS (originally 'morphImplant')
        Route::post('implants', function () {
        });
        //REMOVE MORPH IMPLANTS (originally 'morphImplant')
        Route::delete('implants/{implant}', function () {
        });

        //SET MORPH GEAR (originally 'morphGear')
        Route::post('gears', function () {
        });
        //REMOVE MORPH GEAR (originally 'morphGear')
        Route::delete('gears/{gear}', function () {
        });

        //SET FREE MORPH GEAR (originally 'morphFreeGear')
        Route::post('freeGears', function () {
        });
        //REMOVE FREE MORPH GEAR (originally 'morphFreeGear')
        Route::delete('freeGears/{gear}', function () {
        });
    });
});