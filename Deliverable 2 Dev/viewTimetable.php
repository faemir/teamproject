<?php
  ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel='stylesheet' type='text/css' href='cssTimetable.css'>
        <title>View Timetable</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
			var days = ["Monday", "Tuesday", "Wednesday", "Thurday", "Friday"];
			var reqID = [];
			var passedUsername = "";
			$(document).ready(function(){validateUser();});
			$(document).ready(function(){getUser();});
			$(document).ready(function(){MkTable()});
			$(document).ready(function(){MkActions()});
			$(document).ready(function(){getTable("")});
			function getUser(){
				passedUsername = "<?php echo $_SESSION['username'] ?>";
			}
			function SelectWeek(week){
				clearTbl();
				getTable(week);
			}
			function clearTbl(){
				for(var i =1;i<=9;i++){
					for(var j =1; j<=5;j++){
						$('#t'+i+''+j).removeClass("changeVT");
						$('#t'+i+''+j).addClass("tabletdVT");
						document.getElementById("t"+i+''+j).innerHTML="";
					}
				}
			}
			
			function validateUser(){
				var user= "<?php echo $_SESSION['username'] ?>";
				var sessionid= "<?php echo session_id(); ?>";
				$.get("GETuserpassdeets.php", {'username':user, 'sessionid':sessionid}, function(JSON){
					if (JSON.length==0)
					window.location.replace("login.php");
				}, 'json');
			}	
			function MkActions(){
				var tbl="";
				tbl +="<select id='weeksele' onchange='SelectWeek(this.value)' onclick='SelectWeek(this.value)' onkeypress='SelectWeek(this.value)' onkeyup='SelectWeek(this.value)'>";
				tbl +="<option value='0'>Select Week</option>";
				for(var k=1; k<=15;k++){
					tbl +=	"<option value='"+k+"'>"+k+"</option>";
				}
				tbl +="</select>";
				tbl +="<select id='yearsele' onchange='semesters()' onclick='semesters()' onkeypress='semesters()' onkeyup='semesters()'>";
				tbl +="<option value='0'>Select Year</option>";
				tbl +="<option value='F'>F</option>";
				tbl +="<option value='A'>A</option>";
				tbl +="<option value='B'>B</option>";
				tbl +="<option value='I'>I</option>";
				tbl +="<option value='C'>C</option>";
				tbl +="<option value='D'>D</option>";
				tbl +="<option value='P'>P</option>";
				tbl +="</select>";
				tbl +="<label class='wkInput' id='wkLabel'>Semester</label>";
				tbl +="<input type='radio' class='wkInput' id='sem1' name='semester' onclick='semesters()'><label for='sem1'>1</label>";
				tbl +="<input type='radio' class='wkInput' id='sem2' name='semester' onclick='semesters()'><label for='sem2'>2</label>";
				document.getElementById("actiontimeBox").innerHTML=tbl;
			}
			function semesters(){	
				var check = document.getElementById("weeksele").value
				clearTbl();
				getTable(check);
			}
			function MkTable(){
				var tbl="";
				tbl +="<table class='tableVT'>";
				tbl += 		"<tr><td></td>";
				for(var i =1;i<=9;i++){
					tbl += 		"<td>"+i+"</td>";
				}
				tbl += 		"</tr>";
				for(var i =1;i<=days.length;i++){
					tbl +=	"<tr>";
					tbl +=		"<td class='tabledaysVT' >";	
					tbl +=			days[i-1];	
					tbl +=		"</td>";	
					for(var j =1; j<=9;j++){
						tbl +=	"<td class='tabletdVT' onclick='clickedtable(this)' id='t"+j+""+i+"'>";
						tbl +=	"</td>";
					}
					tbl +=	"</tr>";
				}
				tbl += 	"</table>";
				document.getElementById("timetableViewerBox").innerHTML=tbl;
			}
			function clickedtable(value){
				if($(value).attr('class')=='changeVT'){
					if(reqID.length != 0){
						var seleID =[];
						var RoomArr=[];
						var RoomIdArr = [];
						for(var count = 0; count < reqID.length; count++){
							if (reqID[count][0] == value.id){
								seleID[seleID.length] = reqID[count][1];
							}
						}
						codeStl = "";
						for(var i = 0; i < seleID.length; i++){
							$.ajax({
								type: "GET",
								dataType: "json",
								url: "GETdetailedRequestsTimetable.php",
								async: false,
								data: {'id':seleID[i]},
								success: function(JSON){
									RoomIdArr = [];
									for(var j = 0; j < JSON.length; j++){
										var subRoomArr =[];
										subRoomArr[0] = JSON[j].requestid;
										subRoomArr[1] = JSON[j].year;
										subRoomArr[2] = JSON[j].modulecode;
										subRoomArr[3] = JSON[j].moduletitle;
										subRoomArr[4] = JSON[j].period;
										subRoomArr[5] = JSON[j].day;
										subRoomArr[6] = JSON[j].noofstudents;
										subRoomArr[7] = JSON[j].noofrooms;
										subRoomArr[8] = JSON[j].qualityroom;
										subRoomArr[9] = JSON[j].wheelchairaccess;
										subRoomArr[10] = JSON[j].dataprojector;
										subRoomArr[11] = JSON[j].doubleprojector;
										subRoomArr[12] = JSON[j].visualiser;
										subRoomArr[13] = JSON[j].videodvdbluray;
										subRoomArr[14] = JSON[j].computer;
										subRoomArr[15] = JSON[j].whiteboard;
										subRoomArr[16] = JSON[j].chalkboard;
										subRoomArr[17] = JSON[j].nearestroom;
										if(RoomIdArr[j]==0){
											RoomIdArr[RoomIdArr.length]=JSON[j].roomid;
										}else{
											var boolcheck = true;
											for (var i = 0; i < RoomIdArr.length; i++){
												if(RoomIdArr[i]==JSON[j].roomid){
													boolcheck = false;
												}
											}
											if(boolcheck){
												RoomIdArr[RoomIdArr.length] = JSON[j].roomid;
											}
										}
										if(RoomArr.length==0){
											subRoomArr[18] = RoomIdArr;
											RoomArr[RoomArr.length] = subRoomArr;
										}else{
											var boolcheck = true;
											for (var l = 0; l < RoomArr.length; l++){
												if(RoomArr[l][0]==subRoomArr[0] && RoomArr[l][4]==subRoomArr[4] && RoomArr[l][5]==subRoomArr[5]){
													boolcheck = false;
												}
											}
											if(boolcheck){
												subRoomArr[18] = RoomIdArr;
												RoomArr[RoomArr.length] = subRoomArr;
											}
										}
									}
									wrTables(RoomArr);
								}
							});
						}
					}
				}
			}
			function wrTables(RoomArr){
				var codeStl = "";
				for(var i = 0; i < RoomArr.length; i++){
					codeStl += "<table id='detailsTable'>";
					codeStl += "<tr>";
					codeStl += "<td>Request ID: " + RoomArr[i][0] + "</td>";
					codeStl += "<td>Year: " + RoomArr[i][1] + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>Module Code: " + RoomArr[i][2] + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td colspan='2'>" + RoomArr[i][3] + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>Period: " + RoomArr[i][4] + "</td>";
					codeStl += "<td>Duration: ADD IN </td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					
					/* if (timeFormat==1){
						var Starttime = startTimeList1.concat(startTimeList2);
						var Endtime = endTimeList1.concat(endTimeList2)
					}
					else{
						var Starttime = startTimeList1.concat(startTimeList3);
						var Endtime = endTimeList1.concat(endTimeList3);
					}
					var starttime = Starttime[JSON[0].period-1];
					var endtime = Endtime[(parseInt(JSON[0].period) + parseInt(JSON[0].duration)-2)];
					
					codeStl += "<td colspan='2'>Requested Time: " + starttime + "-" + endtime + "</td>"; */
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td>No. Students: " + RoomArr[i][6] + "</td>";
					codeStl += "<td>No. Rooms: " + RoomArr[i][7] + "</td>";
					codeStl += "</tr>";
					codeStl += "<tr>";
					codeStl += "<td colspan='2'>Booked rooms: "
					for (var k = 0; k< RoomArr[i][18].length; k++){
						codeStl += RoomArr[i][18][k] + " ";
					}
					codeStl += "</td>";
					codeStl += "<tr><td colspan='2'>";
					if(RoomArr[i][8]==1){
						codeStl += "Quality Room, ";
					}
					if(RoomArr[i][9]==1){
						codeStl += "Wheelchair Access, ";
					}
					if(RoomArr[i][10]==1){
						codeStl += "Data Projector, ";
					}
					if(RoomArr[i][11]==1){
						codeStl += "Double Projector, ";
					}
					if(RoomArr[i][12]==1){
						codeStl += "Visualiser, ";
					}
					if(RoomArr[i][13]==1){
						codeStl += "Video/DVD/Bluray, ";
					}
					if(RoomArr[i][14]==1){
						codeStl += "Computer, ";
					}
					if(RoomArr[i][15]==1){
						codeStl += "White Board, ";
					}
					if(RoomArr[i][16]==1){
						codeStl += "Chalk Board, ";
					}
					if(RoomArr[i][17]==1){
						codeStl += "Nearest Room, ";
					}
					codeStl = codeStl.substring(0,codeStl.length-2);
					codeStl += ".</tr>";
					codeStl += ".</table>";		
				}
				document.getElementById("roomSelectorBox2").innerHTML = (codeStl);
			}
			function getTable(week){
				reqID= [];
				var user = "CO";
				var SQL = "SELECT modulecode, semester, EntryRequestTable.requestid, day , period, duration, roomid ";
				SQL += "FROM EntryRequestTable ";
				SQL += "INNER JOIN WeekTable ON EntryRequestTable.weekid = WeekTable.weekid ";
				SQL += "INNER JOIN ConfirmedBooking ON EntryRequestTable.requestid = ConfirmedBooking.requestid ";
				SQL += "WHERE modulecode LIKE '"+user;
				if (document.getElementById('yearsele').value != 0){
					SQL += document.getElementById('yearsele').value;
				}
				SQL +="%' ";
				if (document.getElementById('sem1').checked == true){
					SQL += " AND semester = 1";
				}else{
					SQL += " AND semester = 2";
				}
				if (week!=0 && week!=""){
					SQL += " AND week" + week +" =1";
				}
				SQL += ";";
				//alert(SQL);
				$.ajax({
					type: "GET",
					dataType: "json",
					url: "GETbookedRooms.php",
					async: false,
					data: {'sql':SQL},
					success: function(JSON){
						for(var i = 0; i <JSON.length; i++){
							var day =0;
							switch (JSON[i].day){
								case "Monday":day = 1;break;
								case "Tuesday":day = 2;break;
								case "Wednesday":day = 3;break
								case "Thursday":day = 4;break;
								case "Friday":day = 5;break;
							}
							var count=0;
							do{
								var bubble = parseInt(JSON[i].period)+count;
								$('#t'+bubble+''+day).removeClass('tabletdVT');
								$('#t'+bubble+''+day).addClass('changeVT');
								var subarray = ['t'+bubble+''+day , JSON[i].requestid];
								if (document.getElementById("t"+bubble+''+day).innerHTML==""){
									document.getElementById("t"+bubble+''+day).innerHTML= JSON[i].modulecode;
								}else{
									document.getElementById("t"+bubble+''+day).innerHTML= document.getElementById("t"+bubble+''+day).innerHTML+ "<br>" + JSON[i].modulecode;		
								}								
								reqID[reqID.length] = subarray;
								//alert(reqID);
								count++;
							}while(count<JSON[i].duration)
						}
					}
				});
			}
		</script>
        <title>View Timetable</title>
    </head>

    <body>
        <div id="navwrap">
            <ul id="topnav">
                <li><a href="viewRequests.php"><img src="LU-mark-rgb.png" alt="Home"></a></li>
                <li><a href="addRequests.php">Add New Requests</a></li>
                <li><a href="viewTimetable.php">View Timetable</a></li>
                <li><a href="helpPage.php">Help</a></li>
                <li><a href="accountPage.php">Username(pref)</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">

            <div class="contentBox" id="roomSelectorBox2"></div>

            <div class="contentBox" id="actiontimeBox"></div>

            <div class="contentBox" id="timetableViewerBox"></div>
        </div>
    </body>
</html>
