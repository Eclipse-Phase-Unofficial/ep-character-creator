<?php
session_start();
?>
<form action="index.php" id="loadForm" method="POST" enctype="multipart/form-data">
	<table id="table_load" align="center">
		<tr align="center">
			<td>
				<h1><b><u> Load file </u></b></h1>
			</td>
		</tr>
		
		<tr align="center">
			<td>
				If you have save a character you can reload it on the system for evolving or changing details.
				<br><br>
				
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
				Choose a file to upload: <input id="fileName" name="uploadedfile" type="file">
				<br><br>
			</td>
		</tr>
		
		 <tr align="center">	
			<td>
				<input type="checkbox" name="creationMode" value="cre"/> Check if you want to continue on creation mode.
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
				<div id="errorLoadMsg">
					<?php
						if($_SESSION['versioningFault']){
							echo "<label style='color:red'>Your file version is too old and no more compatible, sorry.</label>";
						}
						$_SESSION['versioningFault'] = false;
					?>
				</div>
			</td>
		</tr>
		
		<tr align="center">
			<td>
				<br><br>
				<button class="loadLoadButton">
			       			Load 
		        </button>
		        </form>
		        <button class="cancelLoadButton">
			       			Cancel
		        </button>
			</td>	
		</tr>
	</table>
