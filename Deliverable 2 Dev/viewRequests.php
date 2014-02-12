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
        <link rel='stylesheet' type='text/css' href='cssTimetable.css'>
        <title>View Current Requests</title>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
        //onload ----------------------------------------------------------------//
		$(document).ready(function(){getUserPrefs();});
        $(document).ready(function(){wrRequestsTable();});

		// GLOBALS -----------------------------------------------------------------//
		var viewHeaders = new Array();
		var headersArray = new Array("Module Code", "Module Title", "Priority", "Year", "Semester", "Day", "Start Time", "End Time", "Period", "Duration", "No Of Students", "No Of Rooms", "Preferred Rooms", "Quality Room", "Wheelchair Access", "Data Projector", "Double Projector", "Visualiser", "Video/DVD/BluRay", "Computer", "White Board", "Chalk Board");
		var way = "1";
		var userPrefPeriod = "";
		var userPrefTimeformat = "";
		var userPrefStartWeek = "";
		var userPrefEndWeek = "";
		var userPrefLocation = "";
		var userPrefHeader1 = "";
		var userPrefHeader2 = "";
		var userPrefHeader3 = "";
		var userPrefHeader4 = "";
		var userPrefHeader5 = "";
		var userPrefHeader6 = "";
		// MAIN FUNCTIONS ---------------------------------------------------------------------------------------//
		
		function getUserPrefs(){
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "GETallPreferences.php",

				//data: {'username': $_session['username']},

				success: function(JSON){
					userPrefHeader1 = JSON[0].header1;
					userPrefHeader2 = JSON[0].header2;
					userPrefHeader3 = JSON[0].header3;
					userPrefHeader4 = JSON[0].header4;
					userPrefHeader5 = JSON[0].header5;
					userPrefHeader6 = JSON[0].header6;
				}
			});

		}
		
		//Rewrite with for loops from a GET from preferences table Header 1-6 changing number to writing..

        function wrRequestsTable(type){
            //writes and populates Requests table. needs preferences input
			
			var searchval = document.getElementById("search").value;
			var semsval = "0";
			if(type==""){
				search = " ";
			}
			
			if(document.getElementById("semester1").checked){semsval = '1'};
			if(document.getElementById("semester2").checked){semsval = '2'};
			if(document.getElementById("semester0").checked){semsval = '0';}
			
			$("#tableBox").empty();
			$.ajax({
                type: "GET",
                dataType: "json",
                url: "GETallRequests.php",

				data: {'type':type, 'searchval': searchval, 'semsval': semsval},

				//data: {'username': $_session['username']},

                success: function(JSON){
                    var codeStr = "";
                    codeStr += '<table id="RequestsTable">';
                    codeStr += '<tr id='+i+'>';

                    //Added sorting of th's
					viewHeaders[0] = userPrefHeader1;
					viewHeaders[1] = userPrefHeader2;
					viewHeaders[2] = userPrefHeader3;
					viewHeaders[3] = userPrefHeader4;
					viewHeaders[4] = userPrefHeader5;
					viewHeaders[5] = userPrefHeader6;
					var countersort = 0;
					for (var z=0;z < viewHeaders.length;z++){
						codeStr += '	<th onclick="sortTable(' + countersort + ')">' + headersArray[viewHeaders[z]] + '</th>';
						countersort += 1;
					}
                    codeStr += '    <th onclick="sortTable(6)">Details</th>';
                    codeStr += '    <th onclick="sortTable(7)">Edit Request</th>';
                    codeStr += '    <th onclick="sortTable(8)">Add Similar Request</th>';
                    codeStr += '    <th onclick="sortTable(9)">Status</th>';
                    codeStr += '</tr>';

                    for(var i=0;i<JSON.length;i++){

                        codeStr += '<tr class="requestsRow">';

						for (var h=0;h<6;h++){
							var starttime = parseInt(JSON[i].period) + 8;
							if (starttime == 9)
								starttime = "0" + starttime;
							starttime = starttime + ":00";
							var endtime = parseInt(JSON[i].period) + parseInt(JSON[i].duration) + 7;
							if (endtime == 9)
								endtime = "0" + endtime;
							endtime = endtime + ":50";
							
							if(viewHeaders[h] == "0")
								codeStr += '    	<td>' + JSON[i].modulecode + '</td>';
							else if(viewHeaders[h] == "1")
								codeStr += '    	<td>' + JSON[i].moduletitle + '</td>';
							else if(viewHeaders[h] == "2")
								codeStr += '    	<td>' + JSON[i].priority + '</td>';
							else if(viewHeaders[h] == "3")
								codeStr += '    	<td>' + JSON[i].year + '</td>';
							else if(viewHeaders[h] == "4")
								codeStr += '    	<td>' + JSON[i].semester + '</td>';
							else if(viewHeaders[h] == "5")
								codeStr += '    	<td>' + JSON[i].day + '</td>';
							else if(viewHeaders[h] == "6")
								codeStr += '    	<td>' + starttime + '</td>';
							else if(viewHeaders[h] == "7")
								codeStr += '    	<td>' + endtime + '</td>';
							else if(viewHeaders[h] == "8")
								codeStr += '    	<td>' + JSON[i].period + '</td>';
							else if(viewHeaders[h] == "9")
								codeStr += '    	<td>' + JSON[i].duration + '</td>';
							else if(viewHeaders[h] == "10")
								codeStr += '    	<td>' + JSON[i].noofstudents + '</td>';
							else if(viewHeaders[h] == "11")
								codeStr += '    	<td>' + JSON[i].noofrooms + '</td>';
							else if(viewHeaders[h] == "12")
								codeStr += '    	<td>' + JSON[i].preferredrooms + '</td>';
							else if(viewHeaders[h] == "13")
								codeStr += '    	<td>' + JSON[i].qualityroom + '</td>';
							else if(viewHeaders[h] == "14")
								codeStr += '    	<td>' + JSON[i].wheelchairaccess + '</td>';
							else if(viewHeaders[h] == "15")
								codeStr += '    	<td>' + JSON[i].dataprojector + '</td>';
							else if(viewHeaders[h] == "16")
								codeStr += '    	<td>' + JSON[i].doubleprojector + '</td>';
							else if(viewHeaders[h] == "17")
								codeStr += '    	<td>' + JSON[i].visualiser + '</td>';
							else if(viewHeaders[h] == "18")
								codeStr += '    	<td>' + JSON[i].videodvdbluray + '</td>';
							else if(viewHeaders[h] == "19")
								codeStr += '    	<td>' + JSON[i].computer + '</td>';
							else if(viewHeaders[h] == "20")
								codeStr += '    	<td>' + JSON[i].whiteboard + '</td>';
							else if(viewHeaders[h] == "21")
								codeStr += '    	<td>' + JSON[i].chalkboard + '</td>';
						}
							
						
						


                        codeStr += '    	<td><input type="button" class="detailsButton" value="Details" onclick="showDetails(' + JSON[i].requestid + ',this)"></input></td>';
						
                        codeStr += '    	<td><input type="button" value="Edit" onclick="editRequest(' + JSON[i].requestid + ')"></td>';
                        codeStr += '    	<td><input type="button" value="AddSimilar" onclick="addSimilarRequest(' + JSON[i].modulecode + JSON[i].moduletitle + JSON[i].noofstudents + ')"></td>';

                        codeStr += '    	<td>' + JSON[i].requeststatus + '</td>';
						codeStr += '	</tr>';
                    }
                    codeStr += "</table>";
					

                    //clears and writes table into container
                    // UPDATE: empty function produced 'false' onscreen when already empty
                    //$("#tableBox").empty();
                    $("#tableBox").append(codeStr);
                }
            });
        }



		function showDetails(requestID,button){

			
			$.get("GETdetailedRequests.php", {id: requestID}, function(JSON){
				
				$("#detailsBox").empty();
				
				var codeStl = "<div>";


				codeStl += "<td>" + "Request ID: " + JSON[0].requestid + "</br></td>";
				codeStl += "<td>" + "Duration: " + JSON[0].duration + "</br></td>";
				var starttime = parseInt(JSON[0].period) + 8;
				if (starttime == 9)
								starttime = "0" + starttime;
							starttime = starttime + ":00";
							var endtime = parseInt(JSON[0].period) + parseInt(JSON[0].duration) + 7;
							if (endtime == 9)
								endtime = "0" + endtime;
							endtime = endtime + ":50";
				codeStl += "<td>" + "Start Time: " + starttime + "</br></td>";
				codeStl += "<td>" + "End Time: " + endtime + "</br></td>";
				codeStl += "<td>" + "No. Students: " + JSON[0].noofstudents + "</br></td>";
				codeStl += "<td>" + "No. Rooms: " + JSON[0].noofrooms + "</br></td>";
							 if(JSON[0].preferredrooms==1){
				 codeStl += "<td>" + "Preferred room: "+ JSON[0].roomid +"</br></td>";}
				
				codeStl += "<td>" + "Year: " + JSON[0].year + "</br></td>";
							if(JSON[0].qualityroom==1){
				codeStl += "<td>" + "Quality Room" + "</br></td>";}
				if(JSON[0].wheelchairaccess==1){
				codeStl += "<td>" + "Wheelchair Access" + "</br></td>";}
				if(JSON[0].dataprojector==1){
				codeStl += "<td>" + "Data Projector" + "</br></td>";}
				if(JSON[0].doubleprojector==1){
				codeStl += "<td>" + "Double Projector" + "</br></td>";}
				if(JSON[0].visualiser==1){
				codeStl += "<td>" + "Visualiser" + "</br></td>";}
				codeStl += "</div>";
				if(JSON[0].videodvdbluray==1){
				codeStl += "<td>" + "Video/DVD/Bluray" + "</br></td>";}
				if(JSON[0].computer==1){
				codeStl += "<td>" + "Computer" + "</br></td>";}
				if(JSON[0].whiteboard==1){
				codeStl += "<td>" + "White Board" + "</br></td>";}
				if(JSON[0].chalkboard==1){
				codeStl += "<td>" + "Chalk Board" + "</br></td>";}
				if(JSON[0].nearestroom==1){
				codeStl += "<td>" + "Nearest Room" + "</br></td>";}

				
				$("#detailsBox").append(codeStl);
				
				
            }, 'json');


			$(button).parent().parent().toggleClass('requestsRowClk');
	
        }
		
		function editRequest(requestID){
			//Post into AddRequestTabe.php the information to fill all their selection boxes with this requestID's data.
			
		}
		
		function addSimilarRequest(modulecode, modluetitle, capacity){
			//Post into AddRequestTabe.php the information to fill module code, module title and capacity boxes with this requestID's data.
			// var gatherValues = new Array();
			// gatherValues[0] = modulecode;
			// gatherValues[1] = moduletitle;
			// gatherValues[2] = capacity;
			// $.ajax({
				// type: "GET", 
				// url: "AddSimilar.php",
				// data: {'values': gatherValues}
			// });

		}
			
			


		//Sort functions. Asc, Desc alternating. Bubble sort.
		
		function sortTable(colnumber){
			//Fill 2D array with each row of table.
			var value=new Array();
			var rows = RequestsTable.getElementsByTagName('tr');
			for (var i = 1; i < rows.length; i++){
				var row = rows[i];
				var cols = row.getElementsByTagName('td');
				var row_data = [];
				value[i]=new Array();
				for (var j = 0; j < cols.length; j++){
					value[i][j]=cols[j].innerHTML;
				}
			}
			//Sort descending.
			var temp=new Array();
			if (way == "1"){
				var swapped = true;
				while(swapped){
					swapped = false;
					for (var k = 1; k < value.length-1; k++){
						if (value[k][colnumber] > value[k+1][colnumber]){
							temp[0]=value[k];
							value[k]=value[k+1];
							value[k+1]=temp[0];
							swapped = true;
						}
					}
				}
				way = "2";
			}
			//Sort ascending
			else if (way == "2"){
				var swapped2 = true;
				while(swapped2){
					swapped2 = false;
					for (var m = 1; m < value.length-1; m++){
						if (value[m][colnumber] < value[m+1][colnumber]){
							temp[0]=value[m];
							value[m]=value[m+1];
							value[m+1]=temp[0];
							swapped2 = true;
						}
					}
				}
				way = "1";
			}
			// Recreate table after sorting
			var codeStr = "";
			codeStr += '<table id="RequestsTable">';
			codeStr += '<tr>';
			var countersort = 0;
			for (var z=0;z<viewHeaders.length;z++){
				codeStr += '	<th onclick="sortTable(' + countersort + ')">' + headersArray[viewHeaders[z]] + '</th>';
				countersort += 1;
			}
			

			codeStr += '    <th onclick="sortTable(6)">Details</th>';
            codeStr += '    <th onclick="sortTable(7)">Edit Request</th>';
			codeStr += '    <th onclick="sortTable(8)">Add Similar Request</th>';
			codeStr += '    <th onclick="sortTable(9)">Status</th>';
            codeStr += '</tr>';
			for(var l=1;l<value.length;l++){
				codeStr += '	<tr>';
				codeStr += '    	<td>' + value[l][0] + '</td>';
				codeStr += '    	<td>' + value[l][1] + '</td>';
				codeStr += '    	<td>' + value[l][2] + '</td>';
				codeStr += '    	<td>' + value[l][3] + '</td>';
				codeStr += '    	<td>' + value[l][4] + '</td>';
				codeStr += '    	<td>' + value[l][5] + '</td>';
				codeStr += '    	<td>' + value[l][6] + '</td>';
				codeStr += '    	<td>' + value[l][7] + '</td>';
				codeStr += '	</tr>';
			}
			codeStr += '</table>';
			//Empty and refill table's div tag.
			document.getElementById("tableBox").innerHTML = "";
			document.getElementById("tableBox").innerHTML = codeStr;
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
            </ul>
        </div>
        <div id="pagewrap">
            <div class="contentBox" id="searchBox">

			<input type="text" name="search" id="search" onkeyup="wrRequestsTable(colSelect.value)" />
			<select id="colSelect" name="colSelect" onclick="" onchange="">
			<option value="">Search by</option>
			<option value="0">Module Code</option>
			<option value="1">Module Title</option>
			<option value="2">Day</option>
			<option value="3">Status</option>
			<option value="4">Period</option>
			<option value="5">Duration</option>
			<option value="6">Priority</option>
			<option value="7">No of Students</option>
			<option value="8">Quality Room</option>
			<option value="9">Preferred Room</option>
			<option value="11">No of Rooms</option>
			<option value="12">Wheelchair Access</option>
			<option value="13">Data Projector</option>
			<option value="14">Double Projector</option>
			<option value="15">Visualiser</option>
			<option value="16">Video/DVD/Bluray</option>
			<option value="17">Computer</option>
			<option value="18">White Board</option>
			<option value="19">Chalk Board</option>
			</select>
			</br>
			<label id="semLabel">Semester</label>
			<input type="radio" name="Semester" id="semester1" onclick="wrRequestsTable('')" value="1"/><label>1</label>
			<input type="radio" name="Semester" id="semester2" onclick="wrRequestsTable('')" value="2"/><label>2</label>
			<input type="radio" name="Semester" id="semester0" onclick="wrRequestsTable('')" value="0"/><label>Both</label>

			</div>
            <div class="contentBox" id="detailsBox">
			
			</div>
            <div class="contentBox" id="roundsBox"></div>


            <div class="contentBox" id="tableBox"></div>

        </div>
    </body>
</html>
