<?php
declare(strict_types=1);
?>
<table class="popup_table" id="table_reset" align="center">
	<tr align="center">
		<td>
			<h1><b><u> Eclipse phase character creator</u></b></h1>
		</td>
	</tr>
	
	<tr align="center">
		<td>
			Welcome on the character creator for 
			<a href="http://eclipsephase.com" target="_blank">Eclipse Phase</a>
			<br><br>
			First you have to choose the amount of creation point (min 700 CP) you want to 
			use for creating your character.
			<br><br>
		</td>
	</tr>
	
	<tr align="center">
		<td>
			<input style="line-height: 1em;" id="startCP" type="number" min="800" value="1000"> Creation points.
		</td>
	</tr>
	
	<tr align="center">
		<td>
			<button class="startButton popupInnerButton">
		       			Begin!
	        </button>
	        <button class="closeButton popupInnerButton">
		       			Cancel
	        </button>
		</td>
	</tr>
	
	<tr align="center">
		<td>
			JavaScript and Cookie must be enabled.
			<br><br>
			Please submit all suggestions and bug reports to the <br>
         GitHub <a href="https://github.com/EmperorArthur/ep-character-creator/issues" target="_blank">Issues</a> page.
         <br><br>
		</td>
	</tr>
	
	<tr align="center">
		<td>
			{{config('epcc.versionName')}}
            <br>
            <small><a href="https://github.com/EmperorArthur/ep-character-creator/" target="_blank">@component('components.version')@endcomponent</a></small>
			<br><br>
		</td>
	</tr>
</table>
