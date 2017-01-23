<?php
require_once('../../../php/EPBook.php');
function getBPHtml($atomName){
		$provider = new EPListProvider('../../../php/config.ini'); 
		
		echo "<li>";
        echo "		<label class='listSection'>Find more on</label>";
        echo "</li>"; 
		echo "<li>";
		
		$book = $provider->getBookForName($atomName);
		
		if(empty($book)){
			$book = "Unknown book";
		}
		
		$page = $provider->getPageForName($atomName);
		
		if(empty($page)){
			$page = "Unknown page";
		}

		
		echo "<label class='bmDesc'><b>".$book."</b>   <i>p.".$page."</i></label>";
		
		
		
		echo "</li>";	
		   
}

function getListStampHtml($atomName){
    $book = new EPBook($atomName);
    return "<span class='bookIcon ".$book->getAbbreviation()."'></span>";
}


?>
