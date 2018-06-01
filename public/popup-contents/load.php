<form id="loadForm" method="POST">
	<table class="popup_table" id="table_load" align="center">
		<tr align="center">
			<td>
				<h1><b><u> Load file </u></b></h1>
			</td>
		</tr>
		
		<tr align="center">
			<td>
				If you have save a character you can reload it on the system for evolving or changing details.
				<br><br>
				Choose a file: <iframe style="position: absolute; border:none;" src="other/upload_file.php"></iframe>
				<br><br>
			</td>
		</tr>
		
		 <tr align="center">	
			<td>
				<input type="checkbox" id="creationMode" name="creationMode" value="cre" checked/> Check if you want to continue on creation mode.
				<br><br>
				I have earned <input style="line-height: 1em;" id="rezPoints" name="rezPoints" type="number" min="0" value="0"> Rez points.
				<br><br>
				I have earned <input style="line-height: 1em;" id="repPoints" name="repPoints" type="number" min="0" value="0"> Reputation points.
				<br><br>
				I have earned <input style="line-height: 1em;" id="credPoints" name="credPoints" type="number" min="0" value="0"> Credits.
				<br><br>
			</td>
		</tr>

		<tr align="center">
			<td>
				<br><br>
				<button type="submit" class="popupInnerButton">
			       			Load 
		        </button>
		        <button type="button" class="closeButton popupInnerButton">
			       			Cancel
		        </button>
			</td>	
		</tr>
	</table>
</form>
