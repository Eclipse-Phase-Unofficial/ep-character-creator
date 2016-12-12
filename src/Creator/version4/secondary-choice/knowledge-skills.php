<?php
	require_once '../../../php/EPListProvider.php';
	require_once '../../../php/EPCharacterCreator.php';
	session_start();
?>
<ul class="mainlist" id="enterSkill">
	<li>
		<label class='listSection'> 1 creation points < 60 % < 2 creation points</label>
		<label class='listSection'> SPE cost 5 creation points</label>
	</li>
	<?php
		if(!$_SESSION['cc']->isNativeLanguageSet()){
		echo "<li>
				<label>language : </label>
				<input  type='text' id='langToAdd' placeholder='Native language' />
				<span class='icone' id='addNativeLanguage' data-icon='&#x3a;'></span>
				</li>";
		}
	?>
	<li>
		<select id="knoprefix">
		<?php
			 $provider = new EPListProvider('../../../php/config.ini'); 
			 $prefixList =  $provider->getListPrefix();  
	         foreach($prefixList as $m){
	         	if($provider->getTypeForPrefix($m) == EPSkill::$KNOWLEDGE_SKILL_TYPE){
		        	echo "<option value='".$m."'>".$m."</option>";
		        }
	         }
		?>
		</select>
		<input  type='text' id='knoToAdd' placeholder='Enter a field' />
		<span class="icone" id="addKnowSkill" data-icon="&#x3a;"></span>
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
				 $lineNumeber = 1;
		         foreach($_SESSION['cc']->getKnowledgeSkills() as $m){
		        	$prefix = $m->prefix;
		        	$spe = $m->specialization;
		        	if($lineNumeber%2 == 0){
		        		echo "<tr>";
		        	}
		        	else{
			        	echo "<tr class='alternateLine'>";
		        	}
		        	$replace_char = array('/','\s');
		        	$id = str_replace($replace_char, '_', $m->atomUid);

		        	if($prefix != null || $prefix != ""){
			        	echo "		<td class='skName' id='$id' data-skillname='$id'><div class='spezBox' id='spezBox".$id."'><input class='spezInt' type='text' id='spe_".$id."' /></div> ".$prefix." : ".$m->name;
		        	}
		        	else{
		        		echo "		<td class='skName' id='$id' data-skillname='$id'><div class='spezBox' id='spezBox".$id."'><input class='spezInt' type='text' id='spe_".$id."' /></div>".$m->name;
		        	}

		        	if($spe != null || $spe != ""){
						echo "<br><label class='speLabel'>spe : ".$spe."</label></td>\n";
		        		echo "		<td align='center'><span class='icone remSpeSkill' data-skillname='$id' data-icon='&#x39;'></span></span></td>\n";
		        	}
		        	else{
						echo "</td>\n";
			        	echo "		<td align='center'><span class='icone addSkillSpec' data-skillname='$id' data-icon='&#x3a;'></span></td>\n";
		        	}

		        	echo "		<td><input class='knoskillbase' type='number' data-skillname='$id' min=0 step=5 value='".$m->baseValue."'/></td>\n";
		        	echo "		<td>".$m->linkedApt->abbreviation."</td>\n";
/* 		        	echo "		<td>".$m->morphMod."</td>"; */
/* 		        	echo "		<td>".$other."</td>"; */
		        	echo "		<td id='skillTotalCol'>".$m->getValue()."</td>\n";
		        	if($m->tempSkill){
		        		echo "		<td><span class='icone remKnowSkill' data-skillname='$id' data-icon='&#x39;'></span></td>\n";
		        	}
		        	else{
			        	echo "		<td></td>\n";
		        	}
		        	echo "</tr>";
		        	$lineNumeber++;
		         }
			?>
			</tbody>
	</table>
</div>
