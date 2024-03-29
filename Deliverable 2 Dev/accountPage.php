<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href='cssTimetable.css' rel='stylesheet' type='text/css'>
        <title>Account Preferences</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type='text/javascript'>
			//---------------------------------------------------------------------------------------//
			var periodValue = "";
			var timeValue = "";
			var weeksValue = "";
			var weekeValue = "";
			var parkValue = "";
			var roomsQueue = [];
			var passedUsername = "";
			//-----------------------------------------------------------------------------------------//
			$(document).ready(function(){theOnLoad()});
			//-----------------------------------------------------------------------------------------//
			function theOnLoad(){
				validateUser();
				getUser();
				wrPreferencesTable();
				addModuleSection();
				clearAddBoxes();
			}
			
			function getUser(){
				passedUsername = "<?php echo $_SESSION['username'] ?>";
			}
			function clearAddBoxes(){
				document.getElementById("entercode").value="";
				document.getElementById("entertitle").value="";
			}
			
			function validateUser(){
				var user= "<?php echo $_SESSION['username'] ?>";
				var sessionid= "<?php echo session_id(); ?>";
				$.get("GETuserpassdeets.php", {'username':user, 'sessionid':sessionid}, function(JSON){
					if (JSON.length==0)
					window.location.replace("login.php");
				}, 'json');
			}	
			
			//Read and write the preferences from database into the preferences table.
			function wrPreferencesTable(){
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "GETallPreferences.php",
					data: {'username': passedUsername},
					success: function(JSON){
						var codeStr = '';
						codeStr += '<table id="PreferencesTable">';
						codeStr += '<tr>';
						codeStr += '<th colspan=2>Preferences for Adding Requests</th>';
						codeStr += '</tr>';
						codeStr += '	<tr><td colspan=4>Choose which location you want initially selected</td></tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="park" value="ANY" id="ANY"></input><label for="ANY">Location: Any</label></td>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="park" value="C" id="C"></input><label for="C">Location: Central Park</label></td>';
						codeStr += '</tr><tr>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="park" value="W" id="W"></input><label for="W">Location: West Park</label></td>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="park" value="E" id="E"></input><label for="E">Location: East Park</label></td>';
						codeStr += '</tr>';
						codeStr += '	<tr><td colspan=2>Choose which time format you want displayed</td></tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="time" value="1" id="24hr"></input><label for="24hr">24hour Format</label></td>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="time" value="0" id="12hr"></input><label for="12hr">12hour Format</label></td>';
						codeStr += '</tr>';
						codeStr += '	<tr><td colspan=2>Choose whether you prefer periods or times being displayed</td></tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="period" value="1" id="period"></input><label for="period">Periods and Duration</label></td>';
						codeStr += '	<td><input class="prefInput" type="Radio" name="period" value="0" id="time"></input><label for="time">Start Time and End Time</label></td>';
						codeStr += '</tr>';
						codeStr += '	<tr><td> </td></tr>';
						codeStr += '<tr>';
						codeStr += '	<td>Default Start Week: ';
						codeStr += '	<select name="weeks" id="weeks">';
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
						codeStr += '	<td>Default End Week: ';
						codeStr += '	<select name="weeke" id="weeke">';
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
						codeStr += '	<tr><td> </td></tr>';
						codeStr += '<tr>';
						codeStr += '	<td><input type=button value="Save" onclick="savePrefs()"></input></td>';
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
						
						var codeStg = '';
						codeStg += '<table id="ColumnsTable">';
						codeStg += '<tr>';
						codeStg += '<th colspan=2>Show These Columns In Viewing Requests</th>';
						codeStg += '</tr>';
						codeStg += '	<tr><td> </td></tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="0" onclick="clicked(this)" ></input><label for="0">Module Code</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="1" onclick="clicked(this)" ></input><label for="1">Module Title</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="2" onclick="clicked(this)" ></input><label for="2">Priority</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="3" onclick="clicked(this)" ></input><label for="3">Year</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="4" onclick="clicked(this)" ></input><label for="4">Semester</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="5" onclick="clicked(this)" ></input><label for="5">Day</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="6" onclick="clicked(this)" ></input><label for="6">Start Time</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="7" onclick="clicked(this)" ></input><label for="7">End Time</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="8" onclick="clicked(this)" ></input><label for="8">Period</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="9" onclick="clicked(this)" ></input><label for="9">Duration</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="10" onclick="clicked(this)" ></input><label for="10">Number Of Students</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="11" onclick="clicked(this)" ></input><label for="11">Number Of Rooms</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="12" onclick="clicked(this)" ></input><label for="12">Preferred Rooms</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="13" onclick="clicked(this)" ></input><label for="13">Quality Room</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="14" onclick="clicked(this)" ></input><label for="14">Wheelchair Access</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="15" onclick="clicked(this)" ></input><label for="15">Data Projector</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="16" onclick="clicked(this)" ></input><label for="16">Double Projector</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="17" onclick="clicked(this)" ></input><label for="17">Visualiser</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="18" onclick="clicked(this)" ></input><label for="18">Video/DVD/Bluray</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="19" onclick="clicked(this)" ></input><label for="19">Computer</label></td>';
						codeStg += '</tr>';
						codeStg += '<tr>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="20" onclick="clicked(this)" ></input><label for="20">White Board</label></td>';
						codeStg += '	<td><input class="prefInput" type=checkbox id="21" onclick="clicked(this)" ></input><label for="21">Chalk Board</label></td>';
						codeStg += '</tr>';
						codeStg += '	<tr><td> </td></tr>';
						codeStg += '<tr>';
						codeStg += '	<td colspan=2><input type=button value="Submit" onclick="changeView()"></td>';
						codeStg += '</tr>';
						codeStg += '</table>';
						
						//clears and writes table into container
						// UPDATE: empty function produced 'false' onscreen when already empty
						//$("#prefDemoBox").empty();
						$("#prefDemoBox").append(codeStg);
						
						//Sets the original values to ones pulled from preferences table
						document.getElementById(JSON[0].header1).checked=true;
						document.getElementById(JSON[0].header2).checked=true;
						document.getElementById(JSON[0].header3).checked=true;
						document.getElementById(JSON[0].header4).checked=true;
						document.getElementById(JSON[0].header5).checked=true;
						document.getElementById(JSON[0].header6).checked=true;
						roomsQueue[0] = document.getElementById(JSON[0].header1).id;
						roomsQueue[1] = document.getElementById(JSON[0].header2).id;
						roomsQueue[2] = document.getElementById(JSON[0].header3).id;
						roomsQueue[3] = document.getElementById(JSON[0].header4).id;
						roomsQueue[4] = document.getElementById(JSON[0].header5).id;
						roomsQueue[5] = document.getElementById(JSON[0].header6).id;
						
					}
				});
			}
			//Ensures user can only have a maximum of 8 columns on the view requests page. (RequestDetails and RequestStatus always there)
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
			//Posts the selected headers to the database to populate view requests table
			function changeView(){
				if (confirm("Are you sure these are the columns you want?")){
					if (roomsQueue.length == 6){
						var h1=roomsQueue[0];
						var h2=roomsQueue[1];
						var h3=roomsQueue[2];
						var h4=roomsQueue[3];
						var h5=roomsQueue[4];
						var h6=roomsQueue[5];
						//POST values in prefBox to Preferences table
						$.ajax({
							type: "GET", 
							url: "POSTcolumnPrefs.php",
							async: false,
							data: {'username': passedUsername, 'h1': h1, 'h2': h2, 'h3': h3, 'h4': h4, 'h5': h5, 'h6': h6},
							success: function(){
								alert("Preferences have been saved. \n Feel free to continue.");

							},
						});
						$("#prefBox").empty();
						$("#prefDemoBox").empty();
						wrPreferencesTable();
					}
					else{
						alert("Please select 6 columns to be shown in the viewing page.");
					}
				}	
			}
			//Posts the selected preferences for use in adding new requests
			function savePrefs(){
				if (confirm("Are you sure you want to save these preferences?")){
			
					for (var i=0;i<document.getElementsByName('period').length;i++){
						if (document.getElementsByName('period')[i].checked==true)
							periodValue = document.getElementsByName('period')[i].value;
					}
					for (var i=0;i<document.getElementsByName('time').length;i++){
						if (document.getElementsByName('time')[i].checked==true)
							timeValue = document.getElementsByName('time')[i].value;
					}
					weeksValue = document.getElementById('weeks').value;
					weekeValue = document.getElementById('weeke').value;
					for (var i=0;i<document.getElementsByName('park').length;i++){
						if (document.getElementsByName('park')[i].checked==true)
							parkValue = document.getElementsByName('park')[i].value;
					}
					//POST values in prefBox to Preferences table
					$.ajax({
						type: "GET", 
						url: "POSTviewingPrefs.php",
						async: false,
						data: {'username': passedUsername, 'per': periodValue, 'hour': timeValue, 'start': weeksValue, 'end': weekeValue, 'location': parkValue},
						success: function(){
							alert("Preferences have been saved. \n Feel free to continue.");
						},
					});
					$("#prefBox").empty();
					$("#prefDemoBox").empty();
					wrPreferencesTable();
				}	
			}
			
			//Checks input of new module and adds to database if valid
			function addModule(){

				if (confirm("Are you sure you want to submit this?")){
					//Gather data for add
					var newModuleCode = document.getElementById("entercode").value;
					var newModuleTitle = document.getElementById("entertitle").value;
					var newDepartmentID = "";
					$.ajax({
						type: "GET",
						dataType: "json",
						url: "GETdepartmentID.php",
						async: false,
						data: {'username': passedUsername},
						success: function(JSON){
							newDepartmentID = JSON[0].departmentid;
						}
					});
					//Validation of input
					var patt1 = /(A|B|C|D|F)$/;
					var patt2 = /[0-9]{3}$/;
					if (newModuleCode.substr(0,2) == newDepartmentID){
						if (patt1.test(newModuleCode.substr(2,1))){
							if (patt2.test(newModuleCode.substr(3,3))){
								if (newModuleTitle != ""){
									//AJAX POST TO ModuleTable
									$.ajax({
										type: "GET", 
										url: "POSTmoduleTable.php",
										data: {'code': newModuleCode, 'title': newModuleTitle, 'dept': newDepartmentID},
										success: function(){alert("The new module has been added to the database. \n Feel free to continue.");},
									});
								}
								else{
									alert("Please enter a valid module title.");}
							}
							else{
								alert("Please enter a valid module code.");}
						}
						else{
							alert("Please enter a valid module code.");}
					}
					else{
						alert("Please enter a valid module code.");}
				}
			}
			
			function addModuleSection(){
				var Person = "";
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "GETdepartmentID.php",
					async: false,
					data: {'username': passedUsername},
					success: function(JSON){
						Person = JSON[0].name;
					}
				});				
				codeStr = '';
				codeStr += '<table>';
				codeStr += '	<tr>';
				codeStr += '		<th>' + Person + '\'s Preferences</th>';
				codeStr += '	</tr>';
				codeStr += '	<tr><td> </td></tr>';
				codeStr += '	<tr>';
				codeStr += '	<th>Add New Module</th>';
				codeStr += '	</tr>';
				codeStr += '	<tr>';
				codeStr += '		<td>Module Code: </td>';
				codeStr += '		<td><input type="text" placeholder="Module code" name="entercode" id="entercode"></td>';
				codeStr += '	</tr>';
				codeStr += '	<tr>';
				codeStr += '		<td>Module Title: </td>';
				codeStr += '		<td><input type="text" placeholder="Module title" name="entertitle" id="entertitle"></td>';
				codeStr += '	</tr>';
				codeStr += '	<tr>';
				codeStr += '		<td><input type="button" value="Submit" onclick="addModule()"></td>';
				codeStr += '	</tr>';
				codeStr += '</table>';
				$("#userPrefBox").append(codeStr);

			}
		</script>
    </head>
    <body>
        <div id="navwrap">
            <ul id="topnav">
                <li><a href="viewRequests.php"><img src="LU-mark-rgb.png" alt="Home"></a></li>
                <li><a href="addRequests.php">Add New Requests</a></li>
                <li><a href="viewTimetable.php">View Timetable</a></li>
                <li><a href="accountPage.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">
			
			<div class="" id="userPrefBox"></div>
			
            <div class="contentBox" id="prefDemoBox"></div>
			
			
			<div class="contentBox" id="prefBox"></div>
            
        </div>
    </body>
</html>

