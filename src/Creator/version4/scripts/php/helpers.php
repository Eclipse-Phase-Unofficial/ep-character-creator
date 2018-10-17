<?php
//FUNCTION-HELPERS=============================
function returnErrors(&$data,$msg=""){
    $data['error'] = true;
    $data['erType'] = "system";
    $data['msg'] = "SYSTEM ERROR : ".$msg;
}

function returnMessage(&$data,$msg=""){
    $data['error'] = true;
    $data['erType'] = "rules";
    $data['msg'] = "RULES : ".$msg;
}

function treatCreatorErrors(&$data,$creatorError){
    if(is_string($creatorError)) {
        returnErrors($data,$creatorError);
    }
	else if(strcmp($creatorError->typeError, EPCreatorErrors::$SYSTEM_ERROR) == 0){
		returnErrors($data,$creatorError->textError);
	}
	else if(strcmp($creatorError->typeError, EPCreatorErrors::$RULE_ERROR) == 0){
		returnMessage($data,$creatorError->getTextOnly());
	}
	else{
		returnErrors($data,"Unknown error ? : ".$creatorError->textError);
	}
    echo json_encode($data);
    exit(1);
}
?>