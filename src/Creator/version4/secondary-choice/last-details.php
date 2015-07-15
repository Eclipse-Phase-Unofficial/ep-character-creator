<?php
require_once '../../../php/EPCharacterCreator.php';
session_start();
?>
<ul class="mainlist" id="lastDetails">
	  <?php

		$playerName		= $_SESSION['cc']->character->playerName;
		$charName		= $_SESSION['cc']->character->charName;
		$realAge		= $_SESSION['cc']->character->realAge;
		$birthGender	= $_SESSION['cc']->character->birthGender;
		$note			= $_SESSION['cc']->character->note;

		echo "	<li>
					<label>Player Name</label>
					<input  type='text' id='mPlayerName' placeholder='your name' value='".$playerName."' />
				</li>
				<li>
					<label>Char. Name</label>
					<input  type='text' id='mCharacterName' placeholder='character name' value='".$charName."' />
				</li>
				<li>
					<label>Real age</label>
					<input type='number' id='mRealAge' value='".$realAge."' />
				</li>
				<li>
					<label>Birth Gender</label>
					<select id='mBirthGender'>
						<option value='N' "; if($birthGender == 'N'){echo "selected";} echo " >None</option>
						<option value='M' "; if($birthGender == 'M'){echo "selected";} echo " >Male</option>
						<option value='F' "; if($birthGender == 'F'){echo "selected";} echo " >Female</option>
					</select>
				</li>
				<li>
					<label>Notes</label>
					<textarea id='mNote' cols='32' rows='3'  placeholder='Notes about your character'  >".$note."</textarea>
				</li>";
	?>
</ul>