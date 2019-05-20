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
	<li>
		<select id="actprefix">
		<?php
				 $provider = new EPListProvider();
				 $prefixList =  $provider->getListPrefix(); 
		         foreach($prefixList as $m){
		         	if($provider->getTypeForPrefix($m) == EPSkill::$ACTIVE_SKILL_TYPE){
			        	echo "<option value='".$m."'>".$m."</option>";
			        }
		         }
		?>
		</select>
		<input  type='text' id='actToAdd' placeholder='Enter a field' />
		<span class="addOrSelectedIcon" id="addActSkill" data-icon="&#x3a;"></span>
	</li>
</ul>
<div id="actSklDiv">
	<table class="skills" id="actSkills">			    
			<thead>
				<tr>
					<th></th> 
					<th>sp</th>	
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
				 foreach(creator()->character->ego->getActiveSkills() as $m){
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

					echo "		<td><input class='actskillbase' type='number' atomic='".$m->getUid()."' min=0 step=5 value='".$m->baseValue."'/></td>\n";
		        	echo "		<td>".$m->linkedApt->abbreviation."</td>\n";
/* 		        	echo "		<td>".$m->morphMod."</td>"; */
/* 		        	echo "		<td>".$other."</td>"; */
		        	echo "		<td class='skillTotal'>".$m->getValue()."</td>\n";
		        	if($m->tempSkill){
						echo "		<td><span class='remActSkill' atomic='".$m->getUid()."' data-icon='&#x39;'></span></td>\n";
		        	}
		        	else{
			        	echo "		<td></td>\n";
		        	}
		        	echo "</tr>\n";
		         }
			?>
			</tbody>
	</table>
</div>


