<?php
declare(strict_types=1);

use App\Creator\Atoms\EPAtom;

	$currentMorphsList = creator()->getCurrentMorphs();
	$currentMorph = EPAtom::getAtomByName($currentMorphsList,$_SESSION['currentMorph']);
	if($currentMorph == null)
	{
		$currentMorph = EpDatabase()->getMorphByName($_SESSION['currentMorph']);
	}
?>
<label class="descriptionTitle"><?php echo $currentMorph->getName(); ?></label>
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
		<input type='number' id="mAge" />
	</li>
	<li>
		<label>Gender</label>
		<input  type='text' id="mGender" placeholder="morph gender"/>
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
