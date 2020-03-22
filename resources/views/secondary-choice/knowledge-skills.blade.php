<?php
declare(strict_types=1);

use App\Creator\EPListProvider;
use App\Creator\Atoms\EPSkill;

?>
<ul class="mainlist" id="enterSkill">
	<li class='listSection'>
		<div>1 creation points < 60 % < 2 creation points</div>
		<div>Specialization: 5 creation points</div>
	</li>
	<?php
		if(!creator()->isNativeLanguageSet()){
		echo "<li>
				<label>language : </label>
				<input  type='text' id='langToAdd' placeholder='Native language' />
				<span class='addOrSelectedIcon' id='addNativeLanguage' data-icon='&#x3a;'></span>
				</li>";
		}
	?>
	<li>
		<select id="knoprefix">
		<?php
			 $provider = new EPListProvider();
			 $prefixList =  $provider->getListPrefix();
	         foreach($prefixList as $m){
	         	if(!$provider->isPrefixActive($m)){
		        	echo "<option value='".$m."'>".$m."</option>";
		        }
	         }
		?>
		</select>
		<input  type='text' id='knoToAdd' placeholder='Enter a field' />
		<span class="addOrSelectedIcon" id="addKnowSkill" data-icon="&#x3a;"></span>
	</li>
</ul>
<div id="knoSklDiv">
	<table class="skills" id="knoSkills">
			<thead>
				<tr>
					<th></th>
					<th>spe</th>
					<th align="center">base</th>
					<th align="center"><span class="iconeSkill" data-icon="&#x21;"></span></th>
<!-- 					<th align="center"><span class="iconeSkill" data-icon="&#x32;"></span></th>	 -->
<!-- 					<th align="center"><span class="iconeSkill" data-icon="&#x33;"></span></th>	 -->
					<th align="center">t</th>
					<th align="center"></th>
				</tr>
			</thead>
			<tbody>
			<?php
		         foreach(creator()->character->ego->getKnowledgeSkills() as $m){
		        	$prefix = $m->prefix;
		        	$spe = $m->specialization;
					$name = $m->getPrintableName();

					echo "<tr>\n";

					echo "		<td class='skName' id='".$m->getUid()."' atomic='".$m->getUid()."'><div class='spezBox' id='spezBox".$m->getUid()."'><input class='spezInt' type='text' id='spe_".$m->getUid()."' /></div>";
					echo $name;

		        	if($spe != null || $spe != ""){
						echo "<br><label class='speLabel'>spe : ".$spe."</label></td>\n";
						echo "		<td align='center'><span class='remSpeSkill' atomic='".$m->getUid()."' data-icon='&#x39;'></span></span></td>\n";
		        	}
		        	else{
						echo "</td>\n";
						echo "		<td align='center'><span class='addSkillSpec' atomic='".$m->getUid()."' data-icon='&#x3a;'></span></td>\n";
		        	}

					echo "		<td><input class='knoskillbase' type='number' atomic='".$m->getUid()."' min=0 step=5 value='".$m->baseValue."'/></td>\n";
		        	echo "		<td>".$m->linkedAptitude->abbreviation."</td>\n";
/* 		        	echo "		<td>".$m->morphMod."</td>"; */
/* 		        	echo "		<td>".$other."</td>"; */
		        	echo "		<td class='skillTotal'>".$m->getValue()."</td>\n";
		        	if($m->tempSkill){
						echo "		<td><span class='remKnowSkill' atomic='".$m->getUid()."' data-icon='&#x39;'></span></td>\n";
		        	}
		        	else{
			        	echo "		<td></td>\n";
		        	}
		        	echo "</tr>";
		         }
			?>
			</tbody>
	</table>
</div>
