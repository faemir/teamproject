<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href='cssTimetable.css' rel='stylesheet' type='text/css'>
        <title>Account Preferences</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type='text/javascript'>
			$(document).ready(function(){wrPreferencesTable();});
			var periodValue = "";
			var timeValue = "";
			var weeksValue = "";
			var weekeValue = "";
			var parkValue = "";
			//Read and write the preferences from database into the preferences table.
			function wrPreferencesTable(){
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "GETallPreferences.php",
					success: function(JSON){
						var codeStr = '';
						codeStr += '<table id="PreferencesTable">';
						codeStr += '<tr>';
						codeStr += '<th colspan=4>Preferences for Viewing Requests</th>';
						codeStr += '</tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input type="Radio" name="park" value="any" id="any">Default Location: Any</td>';
						codeStr += '	<td><input type="Radio" name="park" value="central" id="central">Default Location: Central Park</td>';
						codeStr += '	<td><input type="Radio" name="park" value="west" id="west">Default Location: West Park</td>';
						codeStr += '	<td><input type="Radio" name="park" value="east" id="east">Default Location: East Park</td>';
						codeStr += '</tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input type="Radio" name="time" value="1" id="24hr">24hour Format</td>';
						codeStr += '	<td><input type="Radio" name="time" value="0" id="12hr">12hour Format</td>';
						codeStr += '</tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input type="Radio" name="period" value="0" id="period">Periods and Duration</td>';
						codeStr += '	<td><input type="Radio" name="period" value="1" id="time">Start Time and End Time</td>';
						codeStr += '</tr>';
						codeStr += '<tr>';
						codeStr += '	<td>Default Start Week:</td>';
						codeStr += '	<td><select name="weeks" id="weeks">';
						codeStr += '		<option value="1">1</option>';
						codeStr += '		<option value="2">2</option>';
						codeStr += '		<option value="4">4</option>';
						codeStr += '		<option value="5">5</option>';
						codeStr += '		<option value="6">6</option>';
						codeStr += '		<option value="7">7</option>';
						codeStr += '		<option value="8">8</option>';
						codeStr += '		<option value="9">9</option>';
						codeStr += '		<option value="10">10</option>';
						codeStr += '		<option value="11">11</option>';
						codeStr += '		<option value="12">12</option>';
						codeStr += '		<option value="13">13</option>';
						codeStr += '		<option value="14">14</option>';
						codeStr += '		<option value="15">15</option>';
						codeStr += '	</select></td>';
						codeStr += '	<td>Default End Week:</td>';
						codeStr += '	<td><select name="weeke" id="weeke">';
						codeStr += '		<option value="1">1</option>';
						codeStr += '		<option value="2">2</option>';
						codeStr += '		<option value="3">3</option>';
						codeStr += '		<option value="4">4</option>';
						codeStr += '		<option value="5">5</option>';
						codeStr += '		<option value="6">6</option>';
						codeStr += '		<option value="7">7</option>';
						codeStr += '		<option value="8">8</option>';
						codeStr += '		<option value="9">9</option>';
						codeStr += '		<option value="10">10</option>';
						codeStr += '		<option value="11">11</option>';
						codeStr += '		<option value="12">12</option>';
						codeStr += '		<option value="13">13</option>';
						codeStr += '		<option value="14">14</option>';
						codeStr += '		<option value="15">15</option>';
						codeStr += '	</select></td>';
						codeStr += '</tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input type=button value="Save" onclick="savePrefs()"></td>';
						codeStr += '</tr>';
						codeStr += '</table>';
						
						//clears and writes table into container
						// UPDATE: empty function produced 'false' onscreen when already empty
						//$("#prefBox").empty();
						$("#prefBox").append(codeStr);
						
						//Sets the original values to ones pulled from preferences table
						document.getElementById(JSON[0].defaultlocation).checked=true;
						if (JSON[0].hr24format == '0')
							document.getElementById('12hr').checked=true;
						else
							document.getElementById('24hr').checked=true;
						if (JSON[0].period == '0')
							document.getElementById('time').checked=true;
						else
							document.getElementById('period').checked=true;
						document.getElementById('weeks').value = JSON[0].defaultstartweek;
						document.getElementById('weeke').value = JSON[0].defaultendweek;
					}
				});
			}
			//Ensures user can only have a maximum of 10 columns on the view requests page.
			var roomsQueue = [0,2,4,5,7,8];
			function clicked(currentBox){
				var counter = 0;
				var boxID = currentBox.id;
				if (currentBox.checked==true){
					if (roomsQueue.length < 6){
						roomsQueue[roomsQueue.length] = boxID;
					}
					//Removes the oldest column.
					else{
						document.getElementById(roomsQueue[0]).checked = false;
						roomsQueue[0]=roomsQueue[1];
						roomsQueue[1]=roomsQueue[2];
						roomsQueue[2]=roomsQueue[3];
						roomsQueue[3]=roomsQueue[4];
						roomsQueue[4]=roomsQueue[5];
						roomsQueue[5] = boxID;
					}
				}
				else{
					while (roomsQueue[counter] != boxID){
						counter = counter +1;
					}
					for(var j = counter; j<roomsQueue.length; j++){
						roomsQueue[j]=roomsQueue[j+1];
					}
					roomsQueue.length = roomsQueue.length-1;
				}
			}
			function changeView(){
				if (roomsQueue.length == 6){s
					var h1=roomsQueue[0];
					var h2=roomsQueue[1];
					var h3=roomsQueue[2];
					var h4=roomsQueue[3];
					var h5=roomsQueue[4];
					var h6=roomsQueue[5];
					//POST values in prefBox to Preferences table
					$.ajax({
						type: "GET", 
						url: "POSTcolumnPrefs",
						data: {'h1': h1, 'h2': h2, 'h3': h3, 'h4': h4, 'h5': h5, 'h5': h6}
					});
					
				}
				else{
					alert("Please select 6 columns to be shown in the viewing page.");}
			}
			function savePrefs(){
				periodValue = document.getElementsByName('period').value;
				timeValue = document.getElementsByName('time').value;
				weeksValue = document.getElementsByName('weeks').value;
				weekeValue = document.getElementsByName('weeke').value;
				parkValue = document.getElementsByName('park').value;
				//POST values in prefBox to Preferences table
				$.ajax({
					type: "GET", 
					url: "POSTviewingPrefs",
					data: {'per': periodValue, 'hour': timeValue, 'start': weeksValue, 'end': weekeValue, 'location': parkValue}
				});
				alert("Preferences have been saved. \n Feel free to continue.");
				
			}
		</script>
    </head>
     
    <body>
        <div id="navwrap">
            <ul id="topnav">
                <li><a href="viewRequests.php"><img src="LU-mark-rgb.png" alt="Home"></a></li>
                <li><a href="addRequests.php">Add New Requests</a></li>
                <li><a href="viewTimetable.php">View Timetable</a></li>
                <li><a href="helpPage.php">Help</a></li>
                <li><a href="accountPage.php">Username(pref)</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">
        
            <div class="contentBox" id="userPrefBox"></div>
			
            <div class="contentBox" id="prefDemoBox">
				<!--Maybe move this table to viewrequests.php-->
				<table>
					<tr>
						<th colspan=2>Show These Columns in View Requests</th>
					</tr>
					<tr>
						<td><input type=checkbox id="0" onclick="clicked(this)" checked> Module Code</td>
						<td><input type=checkbox id="1" onclick="clicked(this)" > Module Title</td>
					</tr>
					<tr>
						<td><input type=checkbox id="2" onclick="clicked(this)" checked> Priority</td>
						<td><input type=checkbox id="3" onclick="clicked(this)" > Year</td>
					</tr>
					<tr>
						<td><input type=checkbox id="4" onclick="clicked(this)" checked> Semester</td>
						<td><input type=checkbox id="5" onclick="clicked(this)" checked> Day</td>
					</tr>
					<tr>
						<td><input type=radio name="start" id="6" onclick="clicked(this)" > Start Time</td>
						<td><input type=radio name="end" id="7" onclick="clicked(this)" checked> End Time</td>
					</tr>
					<tr>
						<td><input type=radio name="start" id="8" onclick="clicked(this)" checked> Period</td>
						<td><input type=radio name="end" id="9" onclick="clicked(this)" > Duration</td>
					</tr>
					<tr>
						<td><input type=checkbox id="10" onclick="clicked(this)" > Number Of Students</td>
						<td><input type=checkbox id="11" onclick="clicked(this)" > Number Of Rooms</td>
					</tr>
					<tr>
						<td><input type=checkbox id="12" onclick="clicked(this)" > Preferred Rooms</td>
						<td><input type=checkbox id="13" onclick="clicked(this)" > Quality Room</td>
					</tr>
					<tr>
						<td><input type=checkbox id="14" onclick="clicked(this)" > Wheelchair Access</td>
						<td><input type=checkbox id="15" onclick="clicked(this)" > Data Projector</td>
					</tr>
					<tr>
						<td><input type=checkbox id="16" onclick="clicked(this)" > Double Projector</td>
						<td><input type=checkbox id="17" onclick="clicked(this)" > Visualiser</td>
					</tr>
					<tr>
						<td><input type=checkbox id="18" onclick="clicked(this)" > Video/DVD/Bluray</td>
						<td><input type=checkbox id="19" onclick="clicked(this)" > Computer</td>
					</tr>
					<tr>
						<td><input type=checkbox id="20" onclick="clicked(this)" > White Board</td>
						<td><input type=checkbox id="21" onclick="clicked(this)" > Chalk Board</td>
					</tr>
					<tr>
						<td colspan=2><input type=button value="Submit" onclick="changeView()"></td>
					</tr>
				</table>
			</div>
			
			
			<div class="contentBox" id="prefBox"></div>
            
        </div>
    </body>
</html>