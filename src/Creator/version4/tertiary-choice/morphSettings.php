<?php
	require_once '../../../php/EPCharacterCreator.php'; //BMD stand for : Bonus Malus Description
	include('../other/bonusMalusLayer.php');
	include('../other/bookPageLayer.php');
	session_start();
	$currentMorphsList = $_SESSION['cc']->getCurrentMorphs();
	$currentMorph = $_SESSION['cc']->getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
	if($currentMorph == null)
	{
		$currentMorph = $_SESSION['cc']->getMorphByName($_SESSION['currentMorph']);
	}
?>
<label class="descriptionTitle"><?php echo $currentMorph->name; ?></label>
<input type="hidden" id="morphName"/>
<ul class="mainlist" id="morph-settings">
	<li>
		<label>nickname</label>
		<input type='text' id="mNickname" placeholder="morph nickname"/>
	</li>
	<li>
		<label>location</label>
		<input type='text' id="mLocation" placeholder="morph location"/>
	</li>
	<li>
		<label>apparent age</label>
		<input type='number' id="mAge" value="25" />
	</li>
	<li>
		<label>Gender</label>
		<select id="mGender">
			<option value="M">Male</option>
			<option value="F">Female</option>
			<option value="N">None</option>
		</select>
	</li>
	<li>
		<label>Max aptitude</label>
		<span id="mMaxApt"></span>
	</li>
	<li>
		<label>Durability</label>
		<span id="mDur"></span>
	</li>
</ul>