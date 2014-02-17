<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
	$_SESSION["editBool"] = "false";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel='stylesheet' type='text/css' href='cssTimetable.css'>
        <title>View Current Requests</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">

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
		var lastHead ="";
		var lastRow = "";
		var timeFormat;
		var startTimeList1 = ["09:00","10:00","11:00","12:00"];
		var startTimeList2 = ["13:00","14:00","15:00","16:00","17:00"];
		var startTimeList3 = ["01:00","02:00","03:00","04:00","05:00"];
		var endTimeList1 = ["09:50","10:50","11:50","12:50"];
		var endTimeList2 = ["13:50","14:50","15:50","16:50","17:50"];
		var endTimeList3 = ["01:50","02:50","03:50","04:50","05:50"];
		var passedUsername = "";
		var noofaccepted = 0;
		var noofrejected = 0;
		var seshId = "";
		var roundsNumber=0;
		var currentYear = 13;
		var prevBool = false;
		//onload ----------------------------------------------------------------//
		$(document).ready(function(){theOnLoad();});
		
		
		// MAIN FUNCTIONS ---------------------------------------------------------------------------------------//
		
		function theOnLoad(){
			getCurrentyear();
			validateUser();
			getUser();
			getUserPrefs();
			rdRoundData();
			wrRequestsTable(false);
			wrdetailsTitle();
			wrRoundsTable();
		}
		
		function getUser(){
			passedUsername = "<?php echo $_SESSION['username'] ?>";
			seshId = "<?php echo session_id();?>";
		}
		function getUserPrefs(){
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "GETallPreferences.php",
				async: false,
				data: {'username': passedUsername},
				success: function(JSON){
					userPrefHeader1 = JSON[0].header1;
					userPrefHeader2 = JSON[0].header2;
					userPrefHeader3 = JSON[0].header3;
					userPrefHeader4 = JSON[0].header4;
					userPrefHeader5 = JSON[0].header5;
					userPrefHeader6 = JSON[0].header6;
					timeFormat = JSON[0].hr24format
				}
			});
		}
		function validateUser(){
			var user= "<?php echo $_SESSION['username'] ?>";
			var sessionid= "<?php echo session_id(); ?>";
			$.ajax({
				type: "GET",
				url: "GETuserpassdeets.php",
				dataType: "JSON",
				data: {'username':user, 'sessionid':sessionid},
				async: false,
				success: function(){
					if (JSON.length==0){
						window.location.replace("index.htm");
					}
				}
			});
		}

			
		function wrdetailsTitle(){
			$("#detailsBox").empty();
			
			var codeStd = "<table>";
			codeStd += "<tr>";
			codeStd += 'Click details for more info';
			codeStd += "</tr>";
			codeStd += "<tr>";
			$("#detailsBox").append(codeStd);

		}
			
		function getCurrentyear(){
			$.ajax({
				type: "GET",
				url: "GETcurrentYear.php",
				dataType: "JSON",
				async: false,
				success: function(JSON){
					currentYear = JSON[0].year;
				}
			});
			// $.get("GETcurrentYear.php",function(JSON){
				// currentYear = JSON[0].year;
			// },'json');
		}		
			
		//Rewrite with for loops from a GET from preferences table Header 1-6 changing number to writing..
		function wrRequestsTable(bool){
			//writes and populates Requests table. needs preferences input
			prevBool = bool;
			var reqYear  = currentYear;
			if(prevBool){
				reqYear = reqYear - 1;
			}
			noofaccepted=0;
			var searchval = document.getElementById("search").value;
			var searchtype = document.getElementById("colSelect").value;
			
			if(searchtype == 6 || searchtype == 8 || (searchtype > 11 && searchtype !=20)){
				searchval = searchval.toLowerCase();
				if(searchval == "y" || searchval == "ye" || searchval == "yes"){
					searchval = 1;
				}
				if(searchval == "n" || searchval == "no"){
					searchval = 0;
				}
			}
			if(searchtype == 20){
				searchval = searchval.toLowerCase();
				if(searchval == "yes"){
					searchval = 1;
				}
				if(searchval == "no"){
					searchval = 0;
				}
			}
			
			var semsval = "0";
			if(document.getElementById("semester1").checked){semsval = '1'};
			if(document.getElementById("semester2").checked){semsval = '2'};
			if(document.getElementById("semester0").checked){semsval = '0'};
			$("#tableBox").empty();
			$.ajax({
                type: "GET",
                dataType: "JSON",
                url: "GETallRequests.php",
				data: {'type':searchtype, 'searchval': searchval, 'semsval': semsval, 'username': passedUsername, 'year': reqYear},
				async: false,
                success: function(JSON){
					var codeStb = "";
					if(JSON.length!=0){
						$('#deptLabel').text(JSON[0].departmentname + " Requests");
					}
					$("#search").before(codeStb);
					
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
						codeStr += '	<th onclick="sortTable(' + countersort + ',this)" id="h'+countersort+'" class="tableH">' + headersArray[viewHeaders[z]] + '</th>';
						countersort += 1;
					}
                    codeStr += '    <th onclick="sortTable(6,this)" id="h6" class="tableH">Details</th>';
                    codeStr += '    <th onclick="sortTable(7,this)" id="h7" class="tableH">Edit</th>';
					codeStr += '    <th onclick="sortTable(8,this)" id="h8" class="tableH">Delete</th>';
                    codeStr += '    <th onclick="sortTable(9,this)" id="h9" class="tableH">Add Similar</th>';
                    codeStr += '    <th onclick="sortTable(10,this)" id="h10" class="tableH">Status</th>';
                    codeStr += '</tr>';
					if(JSON.length == 0){
						codeStr += '<tr class="requestsRow">';
						codeStr += '<td colspan="11">No Results Found</td>';
						codeStr += '</tr>';
					}
					else{
						for(var i=0;i<JSON.length;i++){
							
							codeStr += '<tr class="requestsRow" id=r'+i+'>';
							for (var h=0;h<6;h++){
								if (timeFormat==1){
									var Starttime = startTimeList1.concat(startTimeList2);
									var Endtime = endTimeList1.concat(endTimeList2)
								}
								else{
									var Starttime = startTimeList1.concat(startTimeList3);
									var Endtime = endTimeList1.concat(endTimeList3);
								}
								var starttime = Starttime[JSON[i].period-1];
								var endtime = Endtime[(parseInt(JSON[i].period) + parseInt(JSON[i].duration)-2)];								
								
								if(viewHeaders[h] == "0")
									codeStr += '    	<td class="requestCells">' + JSON[i].modulecode + '</td>';
								else if(viewHeaders[h] == "1")
									codeStr += '    	<td class="requestCells">' + JSON[i].moduletitle + '</td>';
								else if(viewHeaders[h] == "2"){
									if (JSON[i].priority == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "3")
									codeStr += '    	<td class="requestCells">' + JSON[i].year + '</td>';
								else if(viewHeaders[h] == "4")
									codeStr += '    	<td class="requestCells">' + JSON[i].semester + '</td>';
								else if(viewHeaders[h] == "5")
									codeStr += '    	<td class="requestCells">' + JSON[i].day + '</td>';
								else if(viewHeaders[h] == "6")
									codeStr += '    	<td class="requestCells">' + starttime + '</td>';
								else if(viewHeaders[h] == "7")
									codeStr += '    	<td class="requestCells">' + endtime + '</td>';
								else if(viewHeaders[h] == "8")
									codeStr += '    	<td class="requestCells">' + JSON[i].period + '</td>';
								else if(viewHeaders[h] == "9")
									codeStr += '    	<td class="requestCells">' + JSON[i].duration + '</td>';
								else if(viewHeaders[h] == "10")
									codeStr += '    	<td class="requestCells">' + JSON[i].noofstudents + '</td>';
								else if(viewHeaders[h] == "11")
									codeStr += '    	<td class="requestCells">' + JSON[i].noofrooms + '</td>';
								else if(viewHeaders[h] == "12"){
									if (JSON[i].preferredrooms == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "13"){
									if (JSON[i].qualityroom == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "14"){
									if (JSON[i].wheelchairaccess == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "15"){
									if (JSON[i].dataprojector == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "16"){
									if (JSON[i].doubleprojector == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "17"){
									if (JSON[i].visualiser == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "18"){
									if (JSON[i].videodvdbluray == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "19"){
									if (JSON[i].computer == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "20"){
									if (JSON[i].whiteboard == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}
								else if(viewHeaders[h] == "21"){
									if (JSON[i].chalkboard == '1')
										codeStr += '    	<td class="requestCells">Yes</td>';
									else
										codeStr += '    	<td class="requestCells">No</td>';
								}

							}
							codeStr += '    	<td class="butCells"><input type="button" class="requestButtons" value="Details" onclick="showDetails(' + JSON[i].requestid + ',this)"></input></td>';
							if (JSON[i].requeststatus=="accepted"){
									noofaccepted=noofaccepted+1;
									codeStr += '    	<td class="butCells"><input type="button" class="requestButtons" value="Edit" onclick=" alert(\'Cannot edit accepted Request\'); "></td>';
	
							}
							else if (JSON[i].requeststatus=="rejected"){
									noofrejected=noofrejected+1;
									codeStr += '    	<td class="butCells"><form id="formButs"  method="POST" action="addRequests.php?PHPSESSID=' + seshId +'"><input type="hidden" name= "reqid" value="' + JSON[i].requestid + '"></input><input type="hidden" name="bool" value="true"></input><input type="hidden" name="similar" value="false"></input><input type="submit" class="requestButtons" value="Edit"></input></form></td>';
							}
							else{
								codeStr += '    	<td class="butCells"><form id="formButs"  method="POST" action="addRequests.php?PHPSESSID=' + seshId +'"><input type="hidden" name= "reqid" value="' + JSON[i].requestid + '"></input><input type="hidden" name="bool" value="true"></input><input type="hidden" name="similar" value="false"></input><input type="submit" class="requestButtons" value="Edit"></input></form></td>';
							}
							if(prevBool){
								codeStr +='    	<td class="butCells"><input type="button" class="requestButtons" value="Delete" onclick="alert(\'Cannot Delete Requests From Previous Year\');"></td>';
							
							}
							else{
								codeStr += '    	<td class="butCells"><input type="button" class="requestButtons" value="Delete" onclick="deleteRequest(' + JSON[i].requestid + ')"></td>';
							}
							
							codeStr += '    	<td class="butCells"><form id="formButs"  method="POST" action="addRequests.php?PHPSESSID=' + seshId +'"><input type="hidden" name= "reqid" value="' + JSON[i].requestid + '"></input><input type="hidden" name="bool" value="true"></input><input type="hidden" name="similar" value="true"></input><input type="submit" class="requestButtons" value="+"></input></form></td>';
							
							if(JSON[i].requeststatus == "accepted"){
								codeStr += '    	<td  class="requestCells" id="celltrue">Accepted</td>';
							}
							else if(JSON[i].requeststatus == "rejected"){
								codeStr += '    	<td  class="requestCells" id="cellrej">Rejected</td>';
							}
							else{
								codeStr += '    	<td  class="requestCells" id="cellpend">Pending</td>';
							}
							codeStr += '	</tr>';
						}
					}
                    codeStr += "</table>";
                    //Writes table into div tag
                    $("#tableBox").append(codeStr);
					document.getElementById("cTR").innerHTML ="No of Requests: " + JSON.length;
					document.getElementById("acceptedreq").innerHTML="No of Accepted: " + noofaccepted;
					document.getElementById("rejectedreq").innerHTML="No of Rejected: " + noofrejected;
                }
            });
		 
		}
		
	
		function showDetails(requestID,button){
			
			$.ajax({
				type: "GET",
				url: "GETdetailedRequests.php",
				dataType: "JSON",
				data: {id: requestID},
				async: false,
				success: function(JSON){
					$("#detailsBox").empty();
				
					var codeStl = "<table>";
					codeStl += "<tr>";
					codeStl += "<h4>Selected room details</h4>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>Request ID: " + JSON[0].requestid + "</td>";
					codeStl += "<td>Year: " + JSON[0].year + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>Module Code: " + JSON[0].modulecode + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td colspan='2'>" + JSON[0].moduletitle + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>Day: " + JSON[0].day + "</td>";
					codeStl += "<td>Period: " + JSON[0].period + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					
					if (timeFormat==1){
						var Starttime = startTimeList1.concat(startTimeList2);
						var Endtime = endTimeList1.concat(endTimeList2)
					}
					else{
						var Starttime = startTimeList1.concat(startTimeList3);
						var Endtime = endTimeList1.concat(endTimeList3);
					}
					var starttime = Starttime[JSON[0].period-1];
					var endtime = Endtime[(parseInt(JSON[0].period) + parseInt(JSON[0].duration)-2)];
					codeStl += "<td colspan='2'>Requested Time: " + starttime + "-" + endtime + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>No. Students: " + JSON[0].noofstudents + "</td>";
					codeStl += "<td>No. Rooms: " + JSON[0].noofrooms + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
									codeStl +="<td colspan='2'>Preferred room: ";
					var NAbool = true;
						for(i=0;i<JSON.length;i++){
							if(JSON[i].preferredrooms==1){
								codeStl += JSON[i].roomid +", ";	
							}
							if(JSON[i].roomid=="NULL" && NAbool==true){
							codeStl += "N/A";
							NAbool = false;
							}
						}
					codeStl +="</td>"
					codeStl += "</tr><tr><td colspan='2'>Weeks: ";
					$.ajax({
					type: "GET",
					dataType: "json",
					url: "GETweek.php",	
					async: false,
					data: {'id': JSON[0].weekid},
					success: function(JSON2){
						if (JSON2[0].week1==1){
							codeStl += "1, ";
						}
						if (JSON2[0].week2==1){
							codeStl += "2, ";
						}
						if (JSON2[0].week3==1){
							codeStl += "3, ";
						}
						if (JSON2[0].week4==1){
							codeStl += "4, ";
						}
						if (JSON2[0].week5==1){
							codeStl += "5, ";
						}
						if (JSON2[0].week6==1){
							codeStl += "6, ";
						}
						if (JSON2[0].week7==1){
							codeStl += "7, ";
						}
						if (JSON2[0].week8==1){
							codeStl += "8, ";
						}
						if (JSON2[0].week9==1){
							codeStl += "9, ";
						}
						if (JSON2[0].week10==1){
							codeStl += "10, ";
						}
						if (JSON2[0].week11==1){
							codeStl += "11, ";
						}
						if (JSON2[0].week12==1){
							codeStl += "12, ";
						}
						if (JSON2[0].week13==1){
							codeStl += "13, ";
						}
						if (JSON2[0].week14==1){
							codeStl += "14, ";
						}
						if (JSON2[0].week15==1){
							codeStl += "15, ";
						}
						codeStl = codeStl.substring(0,codeStl.length-2);
					}
				});	
				codeStl += "</td></tr>";

					codeStl += "<tr><td colspan='2'>";
					if(JSON[0].qualityroom==1){
						codeStl += "Quality Room, ";
					}
					if(JSON[0].wheelchairaccess==1){
						codeStl += "Wheelchair Access, ";
					}
					if(JSON[0].dataprojector==1){
						codeStl += "Data Projector, ";
					}
					if(JSON[0].doubleprojector==1){
						codeStl += "Double Projector, ";
					}
					if(JSON[0].visualiser==1){
						codeStl += "Visualiser, ";
					}
					if(JSON[0].videodvdbluray==1){
						codeStl += "Video/DVD/Bluray, ";
					}
					if(JSON[0].computer==1){
						codeStl += "Computer, ";
					}
					if(JSON[0].whiteboard==1){
						codeStl += "White Board, ";
					}
					if(JSON[0].chalkboard==1){
						codeStl += "Chalk Board, ";
					}
					if(JSON[0].nearestroom==1){
						codeStl += "Nearest Room, ";
					}
					codeStl = codeStl.substring(0,codeStl.length-2);
					codeStl += ".</tr>";
					
					$("#detailsBox").append(codeStl);
				}
			});
			            
			if (lastRow!=""){
				$("#"+lastRow).toggleClass('requestsRowClk');
				$("#"+lastRow +" > .butCells > .requestButtons").toggleClass('requestButtonsClk');
				$("#"+lastRow +" > .butCells > #formButs > .requestButtons").toggleClass('requestButtonsClk');
			}
			lastRow = $(button).parent().parent().attr('id');
			$(button).parent().parent().toggleClass('requestsRowClk');
			$(button).parent().parent().find(".butCells > .requestButtons").toggleClass('requestButtonsClk');
			$(button).parent().parent().find(".butCells > #formButs > .requestButtons").toggleClass('requestButtonsClk');
		}
		
		
		function deleteRequest(requestID){
			if (confirm("Are you sure you want to delete this entry?")){
				var status = 0;
				$.ajax({
					type: "GET",
					url: "GETrequeststatus.php", 
					data: {'id':requestID},
					dataType: "json",
					async: false,
					success: function(JSON){
						var statuses = JSON[0].requeststatus;
						if (statuses == "accepted"){
							status = 1;
						}
					}
				});
				$.ajax({
					type: "GET",
					url: "GETdeleterequest.php", 
					data: {'id': requestID, 'status': status},
					dataType: "json",
					async: false,
				
				});
				wrRequestsTable(prevBool);
				$("#detailsBox").empty();
			}
		}

		//Sort functions. Asc, Desc alternating. Bubble sort.
		function sortTable(colnumber,heads){
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
				codeStr += '	<th onclick="sortTable(' + countersort +',this)"id="h'+countersort+'" class="tableH">' + headersArray[viewHeaders[z]] + '</th>';
				countersort += 1;
			}
			codeStr += '    <th onclick="sortTable(6,this)"id="h6" class="tableH">Details</th>';
            codeStr += '    <th onclick="sortTable(7,this)"id="h7" class="tableH">Edit</th>';
			codeStr += '    <th onclick="sortTable(8,this)"id="h8" class="tableH">Delete</th>';
			codeStr += '    <th onclick="sortTable(9,this)"id="h9" class="tableH">Add Similar</th>';
			codeStr += '    <th onclick="sortTable(10,this)"id="h10" class="tableH">Status</th>';
            codeStr += '</tr>';
			for(var l=1;l<value.length;l++){
				codeStr += '	<tr class="requestsRow" id=r'+i+'>';
				codeStr += '    	<td class="requestCells">' + value[l][0] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][1] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][2] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][3] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][4] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][5] + '</td>';
				codeStr += '    	<td class="butCells">' + value[l][6] + '</td>';
				codeStr += '    	<td class="butCells">' + value[l][7] + '</td>';
				codeStr += '    	<td class="butCells">' + value[l][8] + '</td>';
				codeStr += '    	<td class="butCells">' + value[l][9] + '</td>';
				codeStr += '    	<td class="requestCells">' + value[l][10] + '</td>';
				codeStr += '	</tr>';
			}
			codeStr += '</table>';
			//Empty and refill table's div tag.
			document.getElementById("tableBox").innerHTML = "";
			document.getElementById("tableBox").innerHTML = codeStr;
			$(heads).toggleClass("tableHClick");

			
		}
		
		function rdRoundData(){
			$.ajax({
				type: "GET",
				url: "GETroundData.php", 
				dataType: "json",
				async: false,
				success: function(JSON){
					if(JSON.length!=0){
						roundsNumber=JSON[0].roundsnum;
					}
				}
			});
		}
		
		function wrRoundsTable(){	
			$.ajax({
				type: "GET",
				url: "GETRoundsDetails.php", 
				dataType: "json",
				async: false,
				success: function(JSON){
					var codeStp = "<table id='roundsInfoTable'>";
					codeStp += "<tr>";
					codeStp += "<th colspan=4>Rounds Table</th>";
					codeStp += "</tr>";
					codeStp += "<tr>";
					codeStp += "<th>" + "Semester" + "</th>";
					codeStp += "<th>" + "Rounds Num" + "</th>";
					codeStp += "<th>" + "Start Date" + "</th>";
					codeStp += "<th>" + "End Date" + "</th>";
					codeStp += "</tr>";
					
					for(var i=0;i<JSON.length;i++){
						codeStp += "<tr>";
						codeStp += "<td>" + JSON[i].semester + "</td>";
						codeStp += "<td>" + JSON[i].roundsnum + "</td>";
						codeStp += "<td>" + JSON[i].startdate + "</td>";
						codeStp += "<td>" + JSON[i].enddate + "</td>";
						codeStp += "</tr>";
					}

					if (roundsNumber==0){
						codeStp += "<tr>" ;
						codeStp += "<td colspan='4'>You are in adhoc round</td>";
						codeStp += "</tr>";
					}
					else{
						codeStp += "<tr>" ;
						codeStp += "<td colspan='4'>You are in round " + roundsNumber + "</td>";
						codeStp += "</tr>";
					}
					codeStp +="</table>";
					$("#roundsBox").append(codeStp);
				}
			});			
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
                <li><a href="accountPage.php">My Account</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">
            <div class="contentBox" id="searchBox">
				<table>
					<tr><td><label id="deptLabel" class="deptLabel"></label></td></tr>
					<tr><td><input type="text" name="search" id="search" onkeyup="wrRequestsTable(prevBool)" placeholder="Search by filter" /></td></tr>
					<tr><td>
					<label>Search by: </label>
					<select id="colSelect" name="colSelect" onclick="search.value=''">
						<option value="20">All</option>
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
					</td></tr>
					<tr><td><label id="wkLabel" class="wkInput">Semester</label>
					<input type="radio" name="Semester" id="semester1" onclick="wrRequestsTable(prevBool)" value="1" class="wkInput" /><label for="semester1">1</label>
					<input type="radio" name="Semester" id="semester2" onclick="wrRequestsTable(prevBool)" value="2" class="wkInput"/><label for="semester2">2</label>
					<input type="radio" name="Semester" id="semester0" onclick="wrRequestsTable(prevBool)" value="0" class="wkInput"/><label for="semester0">Both</label>
					</td></tr>
					<tr><td><label id="cTR"></label></td></tr>
					<tr><td><label id="acceptedreq"></label></td></tr>
					<tr><td><label id="rejectedreq"></label></td></tr>
					<tr><td><input type="button" onclick="wrRequestsTable(true)" value="Show Previous Year Requests"></input></td></tr>
					<tr><td><input type="button" onclick="wrRequestsTable(false)" value="Show Current Year Requests"></input></td></tr>
				</table>

			</div>
			
            <div class="contentBox" id="detailsBox"></div>
            <div class="contentBox" id="roundsBox"></div>
            <div class="contentBox" id="tableBox"></div>

        </div>
    </body>
</html>
