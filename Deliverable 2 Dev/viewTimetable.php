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
			var passedUsername = "";
			$(document).ready(function(){validateUser();});
			$(document).ready(function(){getUser();});
			$(document).ready(function(){MkTable()});
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
					for(var j =1; j<=9;j++){
						$('#t'+i+''+j).removeClass("change");
						$('#t'+i+''+j).addClass("tabledays");
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
		
			function MkTable(){
				var tbl="";
				tbl +="<select id='weeksele' onchange='SelectWeek(this.value)' onclick='SelectWeek(this.value)' onkeypress='SelectWeek(this.value)' onkeyup='SelectWeek(this.value)'>";
				tbl +="<option value='0'>Select Week</option>";
				for(var k=1; k<=15;k++){
					tbl +=	"<option value='"+k+"'>"+k+"</option>";
				}
				tbl +="</select>";
				tbl +="<table class='table'>";
				tbl += 		"<tr><td></td>";
				for(var i =1;i<=9;i++){
					tbl += 		"<td>"+i+"</td>";
				}
				tbl += 		"</tr>";
				for(var i =1;i<=days.length;i++){
					tbl +=	"<tr>";
					tbl +=		"<td class='tabledays' >";	
					tbl +=			days[i-1];	
					tbl +=		"</td>";	
					for(var j =1; j<=9;j++){
						tbl +=	"<td id='t"+j+""+i+"'>";
						tbl +=	"</td>";
					}
					tbl +=	"</tr>";
				}
				tbl += 	"</table>";
				document.getElementById("timetableViewerBox").innerHTML=tbl;
			}
			
			function getTable(week){
				var user = "CO";
				var SQL = "SELECT ModuleCode, Day , Period, Duration, Week1, Week2, Week3, Week4, Week5, Week6, Week7, Week8, Week9, Week10, Week11, Week12, Week13, Week14, Week15	";
				SQL += " FROM EntryRequestTable INNER JOIN WeekTable ON EntryRequestTable.weekid = WeekTable.weekid WHERE ";
				SQL += "modulecode LIKE '"+user+"%'";
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
							while(count<JSON[i].duration){
								var bubble = parseInt(JSON[i].period)+count;
								$('#t'+bubble+''+day).removeClass('tabledays');
								$('#t'+bubble+''+day).addClass('change');
								count++;
							}
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

            <div class="contentBox" id="timetableActionsBox"></div>

            <div class="contentBox" id="timetableViewerBox"></div>
        </div>
    </body>
</html>
