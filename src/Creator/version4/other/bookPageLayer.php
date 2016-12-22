<?php
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
		$provider = new EPListProvider('../../../php/config.ini');
		$book = $provider->getBookForName($atomName);

		$supPastille = "<span class='";
		 
		if($book == EPListProvider::$BOOK_RIMWARD) $supPastille .=  "RW";
		else if($book == EPListProvider::$BOOK_PANOPTICON) $supPastille .=  "PAN";
		else if($book == EPListProvider::$BOOK_SUNWARD) $supPastille .=  "SW";
		else if($book == EPListProvider::$BOOK_GATECRASHING) $supPastille .=  "GC";
		else if($book == EPListProvider::$BOOK_TRANSHUMAN) $supPastille .=  "TH";
		else if($book == EPListProvider::$BOOK_ECLIPSEPHASE) $supPastille .=  "EP";

		$supPastille .= "'></span>";

		return $supPastille;
}


?>
