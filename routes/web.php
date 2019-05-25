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

Route::prefix('api')->group(function () {
    Route::get('/version', function() {
        return [
            'version'     => config('epcc.versionNumber'),
            'versionName' => config('epcc.versionName'),
            'releaseDate' => config('epcc.releaseDate')->format('F Y')
//            Use this once Laravel allows Carbon 2: 'releaseDate' => config('epcc.releaseDate')->isoFormat('MMMM G')
        ];
    });
});

Route::prefix('others')->group(function () {
    Route::post('/save', 'SaveLoadController@save')->name('save');
    Route::get('/uploadFile', function() {
        return view('popup-contents.upload_file_iframe');
    });
    Route::post('/uploadFile', function() {
        return view('popup-contents.upload_file_iframe');
    });
});

Route::prefix('export')->group(function () {
    Route::get('/pdf', function() {
        $exporter = new \App\Creator\Exporters\pdfExporterV2_fpdf();
        if(!$exporter->export()){
            return response("Bad news, something went wrong, we can not print your character, verify your character and try again.", 500);
        }
    });
    Route::get('/txt', function() {
        include(app_path('Creator/Exporters/txtExporter.php'));
    });
});

Route::prefix('popup-contents')->group(function () {
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

//Panel 2
Route::prefix('secondary-choice')->group(function () {
    Route::get('/active-skills', function () {
        return view('secondary-choice.active-skills');
    });
    Route::get('/aptitudes', function () {
        return view('secondary-choice.aptitudes');
    });
    Route::get('/backgrounds', function () {
        return view('secondary-choice.backgrounds');
    });
    Route::get('/credits', function () {
        return view('secondary-choice.credits');
    });
    Route::get('/factions', function () {
        return view('secondary-choice.factions');
    });
    Route::get('/knowledge-skills', function () {
        return view('secondary-choice.knowledge-skills');
    });
    Route::get('/last-details', function () {
        return view('secondary-choice.last-details');
    });
    Route::get('/morph', function () {
        return view('secondary-choice.morph');
    });
    Route::get('/motivations', function () {
        return view('secondary-choice.motivations');
    });
    Route::get('/negative-traits', function () {
        return view('secondary-choice.negative-traits');
    });
    Route::get('/neutral-traits', function () {
        return view('secondary-choice.neutral-traits');
    });
    Route::get('/positive-traits', function () {
        return view('secondary-choice.positive-traits');
    });
    Route::get('/psy-sleights', function () {
        return view('secondary-choice.psy-sleights');
    });
    Route::get('/reputations', function () {
        return view('secondary-choice.reputations');
    });
    Route::get('/softGear', function () {
        return view('secondary-choice.softGear');
    });
    Route::get('/stats', function () {
        return view('secondary-choice.stats');
    });
});

//Panel 3
Route::prefix('tertiary-choice')->group(function () {
    Route::get('/aiBMD', function () {
        return view('tertiary-choice.aiBMD');
    });
    Route::get('/aptsWithMorph', function () {
        return view('tertiary-choice.aptsWithMorph');
    });
    Route::get('/backgroundBMD', function () {
        return view('tertiary-choice.backgroundBMD');
    });
    Route::get('/factionBMD', function () {
        return view('tertiary-choice.factionBMD');
    });
    Route::get('/gears', function () {
        return view('tertiary-choice.gears');
    });
    Route::get('/implants', function () {
        return view('tertiary-choice.implants');
    });
    Route::get('/morphBMD', function () {
        return view('tertiary-choice.morphBMD');
    });
    Route::get('/morphNegTraits', function () {
        return view('tertiary-choice.morphNegTraits');
    });
    Route::get('/morphNeuTraits', function () {
        return view('tertiary-choice.morphNeuTraits');
    });
    Route::get('/morphPosTraits', function () {
        return view('tertiary-choice.morphPosTraits');
    });
    Route::get('/morphSettings', function () {
        return view('tertiary-choice.morphSettings');
    });
    Route::get('/psySleightBDM', function () {
        return view('tertiary-choice.psySleightBDM');
    });
    Route::get('/softGearBMD', function () {
        return view('tertiary-choice.softGearBMD');
    });
    Route::get('/statsWithMorph', function () {
        return view('tertiary-choice.statsWithMorph');
    });
    Route::get('/traitBMD', function () {
        return view('tertiary-choice.traitBMD');
    });
});

//Panel 4
Route::prefix('quaternary-choice')->group(function () {
    Route::get('/gearMorphBMD', function () {
        return view('quaternary-choice.gearMorphBMD');
    });
    Route::get('/traitMorphBMD', function () {
        return view('quaternary-choice.traitMorphBMD');
    });
});

// This handles pretty much every API request
// TODO:  Split this to the dispatch routes
// TODO:  Convert this to using request instead of $_POST
Route::post('/dispatcher', 'Dispatcher@process');

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