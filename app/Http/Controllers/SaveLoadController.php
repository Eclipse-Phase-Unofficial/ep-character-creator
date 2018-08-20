<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Creator\EPFileUtility;

class SaveLoadController extends Controller
{
    public function save(Request $request){
        $this->validate($request, [
            'saveName' => 'required'
        ]);

        $filename = $request->get('saveName');

        $filename = EPFileUtility::sanitizeFilename($filename);

        // append .json extension if it is missing
        if('.json' !== substr($filename, -5, 5)) {
            $filename .= '.json';
        }

//        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $json = json_encode(creator()->getSavePack());
        return response($json)->withHeaders([
            'Content-Disposition' => 'attachment',
        ]);
    }
}
