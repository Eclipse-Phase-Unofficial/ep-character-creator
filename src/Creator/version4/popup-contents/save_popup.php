
	<table id="table_save" align="center">
		<tr align="center">
			<td>
				<h1><b><u> Save character </u></b></h1>
			</td>
		</tr>
		
		<tr align="center">
			<td>
				Enter a file name
				<br><br>
				<form action="other/save.php" id="saveForm" method="POST" enctype="multipart/form-data">
					<input style="line-height: 1em;" id="saveName" name="saveName" type="text" value="EPCreatorSave">
				</form>
				<br><br>
			</td>
		</tr>
		
		
		
		<tr align="center">
			<td>
				<div id="errorSaveMsg"></div>
			</td>
		</tr>
		
		<tr align="center">
			<td>
				<br><br>
				<button class="saveSaveButton">
			       			Save 
		        </button>
		        
		        <button class="cancelSaveButton">
			       			Cancel
		        </button>
			</td>	
		</tr>
	</table>
