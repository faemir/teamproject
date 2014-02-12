<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel='stylesheet' type='text/css' href='cssTimetable.css'>
        <title>Add New Requests</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">

		//GLOBALS -------------------------------------------------//
        
        //needs preferences here!!!!
        var userDepartmentID = "CO";
        
		
		//pOrT stands for 'Period or Time' - to reflect user preferences
        var pOrTHeader = "Period";
        var pOrTChildren = ["1","2","3","4","5","6","7","8","9"];
        var days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        var alreadyLoaded = false;
		var roomsQueue = [];
		var roomsNamesQueue = [];
		var roomsNames = [];
		var sort = false; // for sorting my capacity
		//Selected periods from table - false = not selected.
		//............................input table
		var mondaySele = [false,false,false,false,false,false,false,false,false];
		var tuesdaySele = [false,false,false,false,false,false,false,false,false];
		var wednesdaySele = [false,false,false,false,false,false,false,false,false];
		var thursdaySele = [false,false,false,false,false,false,false,false,false];
		var fridaySele = [false,false,false,false,false,false,false,false,false];
		var DPTArray = [];	//storing day, period, duration
		var specBoolArray =[0,0,0,0,0,0,0,0,0,0,0,0];
		
		var redirectBool = false;

        //ONLOAD FUNCTIONS -----------------------------------------//
      
        $(document).ready(function(){wrInputTable()});
        $(document).ready(function(){loadDefaultWeeks()});
        $(document).ready(function(){wrRoomsList()});
		$(document).ready(function(){popModulesList(userDepartmentID)});
		
        
        //FUNCTIONS --------------------------------------------------//
        function wrInputTable(){
        
			var codeStr = "";
			
            codeStr += "<table id='inputTable'>";
            
            //headers
            codeStr += "<tr>";
            codeStr += "<th class ='daysHeader' rowspan='2'>Days</th>";
            codeStr += "<th class ='pOrTHeader' colspan='9'>" + pOrTHeader + "</th>";
            codeStr += "</tr>";
            
            //pOrT children
            codeStr += "<tr>";
            for(var i = 0;i<pOrTChildren.length;i++){
                codeStr += "<th class ='pOrTChildren'>" + pOrTChildren[i] + "</th>"; 
            }
             codeStr += "</tr>";
            
            //days and grid
            for(var j = 1;j<=days.length;j++){
                codeStr += "<tr>";
                codeStr += "<th class ='daysChildren'>" + days[j-1] + "</th>";
                for(var k = 1;k<=pOrTChildren.length;k++){
                    codeStr += "<td class='grid' onclick='tableSelect(this.id)' id='t" + j + k + "'></td>";
                }
                codeStr += "</tr>";
            }
            
            codeStr += "</table>";
            //insert into inputTableBox
			codeStr += "<input type='button' value='Clear Timetable' onclick='ClrTab()'>"
            $("#inputTableBox").append(codeStr);
        }
        function ClrTab(){
			for(var k = 1;k<=pOrTChildren.length;k++){
				if (mondaySele[k-1]==true)
					{$("#t1"+ k).toggleClass("gridClicked");}
				if (tuesdaySele[k-1]==true)
					{$("#t2"+ k).toggleClass("gridClicked");}
				if (wednesdaySele[k-1]==true)
					{$("#t3"+ k).toggleClass("gridClicked");}
				if (thursdaySele[k-1]==true)
					{$("#t4"+ k).toggleClass("gridClicked");}
				if (fridaySele[k-1]==true)
					{$("#t5"+ k).toggleClass("gridClicked");}
			}
			mondaySele = [false,false,false,false,false,false,false,false,false];
			tuesdaySele = [false,false,false,false,false,false,false,false,false];
			wednesdaySele = [false,false,false,false,false,false,false,false,false];
			thursdaySele = [false,false,false,false,false,false,false,false,false];
			fridaySele = [false,false,false,false,false,false,false,false,false];
		}
		
		
		//---------------------------------------------------------------------------------------------//
        //onclick for table buttons
        function tableSelect(gridRef){
            //2-D array to hold boolean value for any field currently pressed.         
            //checker searches 2-d array to see if a previous gridref has been clicked.
            //if new grid ref is to right of previous, allow connection of two (i.e. 2hr slot) - (2 true values in array)
            //Else erase previous grid ref boolean and write new gridref boolean (so only one true value in array

            $("#"+ gridRef).toggleClass("gridClicked");
			TFTable(gridRef.substring(1,gridRef.length));
        }	
        
		
        //toggles boolean value of each square in input table for other functions to use
		function TFTable(gridRef){
			var daySele = parseInt(gridRef.substring(0,1));
			var periodSele = parseInt(gridRef.substring(1,gridRef.length)) -1;
			switch (daySele){
				case 1:
				{
					if (mondaySele[periodSele]==true)
						{mondaySele[periodSele]=false;}
					else{mondaySele[periodSele]=true;}
				}
				break;
				case 2:
				{
					if (tuesdaySele[periodSele]==true)
						{tuesdaySele[periodSele]=false;}
					else{tuesdaySele[periodSele]=true;}
				}
				break;
				case 3:
				{
					if (wednesdaySele[periodSele]==true)
						{wednesdaySele[periodSele]=false;}
					else{wednesdaySele[periodSele]=true;}
				}
				break;
				case 4:
				{
					if (thursdaySele[periodSele]==true)
						{thursdaySele[periodSele]=false;}
					else{thursdaySele[periodSele]=true;}
				}
				break;
				case 5:
				{
					if (fridaySele[periodSele]==true)
						{fridaySele[periodSele]=false;}
					else{fridaySele[periodSele]=true;}
				}
				break;
			}
		}
        
        //collect day time and period information for all days
		function timetableGetter(){
			timetableCollector(mondaySele,"Monday");
			timetableCollector(tuesdaySele,"Tuesday");
			timetableCollector(wednesdaySele,"Wednesday");
			timetableCollector(thursdaySele,"Thursday");
			timetableCollector(fridaySele,"Friday");
			//alert(DPTArray.join("//"));
		}
        
        //collects all day time and period information from input table by day
		function timetableCollector(dayArray,day){
		
			for(i=0;i<dayArray.length;i++){
			
				if(i==0){
					if(dayArray[0]==true){
						var subArray = [day,i+1,1];
						DPTArray[DPTArray.length] = subArray;	
					}
				}
				else if((dayArray[i-1]==false) && (dayArray[i]==true)){
					var subArray = [day,i+1,1];
					DPTArray[DPTArray.length] = subArray;
				}
				else if((dayArray[i-1]==true)&&(dayArray[i]==true)){
					DPTArray[DPTArray.length-1][2]++; 
				}
			}
			
		}
		

        function loadDefaultWeeks(){//will change...
            $("#wk1").prop('checked',true);
            $("#wk2").prop('checked',true);
            $("#wk3").prop('checked',true);
            $("#wk4").prop('checked',true);
            $("#wk5").prop('checked',true);
            $("#wk6").prop('checked',true);
            $("#wk7").prop('checked',true);
            $("#wk8").prop('checked',true);
            $("#wk9").prop('checked',true);
            $("#wk10").prop('checked',true);
            $("#wk11").prop('checked',true);
            $("#wk12").prop('checked',true);
            $("#wk13").prop('checked',false);
            $("#wk14").prop('checked',false);
            $("#wk15").prop('checked',false);
        }
        
        function getWeeksSelectionArray(){
            var wksArray = [];
            if($("#wk1").is(':checked')){wksArray.push(1);}
            if($("#wk2").is(':checked')){wksArray.push(2);}
            if($("#wk3").is(':checked')){wksArray.push(3);}
            if($("#wk4").is(':checked')){wksArray.push(4);}
            if($("#wk5").is(':checked')){wksArray.push(5);}
            if($("#wk6").is(':checked')){wksArray.push(6);}
            if($("#wk7").is(':checked')){wksArray.push(7);}
            if($("#wk8").is(':checked')){wksArray.push(8);}
            if($("#wk9").is(':checked')){wksArray.push(9);}
            if($("#wk10").is(':checked')){wksArray.push(10);}
            if($("#wk11").is(':checked')){wksArray.push(11);}
            if($("#wk12").is(':checked')){wksArray.push(12);}
            if($("#wk13").is(':checked')){wksArray.push(13);}
            if($("#wk14").is(':checked')){wksArray.push(14);}
            if($("#wk15").is(':checked')){wksArray.push(15);}
            return wksArray;    
        }
        //----------------------------------------------------------------------------------------------//		
		//gets and populates rooms list
        function wrRoomsList(){
			document.getElementById("roomSelectorBox").innerHTML = "";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "GETroomsList.php",
				data: {'sqlrooms': SQLRoom},
                success: function(JSON){
                    roomsNames= [];
                    var codeStr = "";
                    codeStr +="<div id='roomsList'>";
                    for(var i =0;i<JSON.length;i++){
                        codeStr += "<span id='spanning' title= 'Building: " + JSON[i].building+"'><input type='checkbox' id='r"+i+"' class='roomSele' onclick='roomClick(this)'><label for='r"+i+"'>" + JSON[i].roomid +" : "+ JSON[i].capacity+  "</label></span></br>";
						roomsNames[i] = JSON[i].roomid;
                    }
                    codeStr +="</div>";
					codeStr +="<input type='button' value='Clear' onclick='ClrRoom()'>"; //clearRooms
                    if (sort==false){
						codeStr +="<input type='button' value='Sort By Capacity' onclick='SortCap()'>"; //clearRooms
						
					}else{
						codeStr +="<input type='button' value='Sort By Building ID' onclick='SortCap()'>"; //clearRooms
					}
					$("#roomSelectorBox").append(codeStr);
                }  
            });
        }
		function SortCap(){
			if (sort==false){
				sort=true;}
			else{
				sort=false;}
			GetRoom();
		}
		function roomClick(currentBox){
			roomsNameQueue = [];
			var counter = 0
			var boxID = currentBox.id.substring(1,currentBox.length);
			if (currentBox.checked==true){
				if (roomsQueue.length <= 4){
					roomsQueue[roomsQueue.length] = boxID;
				}else{
					document.getElementById("r"+roomsQueue[0]).checked = false;
					roomsQueue[0]=roomsQueue[1];
					roomsQueue[1]=roomsQueue[2];
					roomsQueue[2]=roomsQueue[3];
					roomsQueue[3]=roomsQueue[4];
					roomsQueue[4] = boxID;
				}
			}else{
				while (roomsQueue[counter] != boxID){
					counter = counter +1
				}
				for(var j = counter; j<roomsQueue.length; j++){
					roomsQueue[j]=roomsQueue[j+1];
				}
				roomsQueue.length = roomsQueue.length-1;
				roomsNamesQueue.length = roomsNamesQueue.length-1;
			}
			for (var i = 0; i < roomsQueue.length; i++){
				roomsNamesQueue[i] = roomsNames[roomsQueue[i]]
			}
			getBookedRooms(roomsNamesQueue);
			
		}
		function ClrRoom(){
			for(var j = 0; j < roomsQueue.length; j++){ //makes all rooms check = false
					document.getElementById("r"+roomsQueue[j]).checked = false;
			}
			roomsQueue = [];
		}
		//-------------Change Room list accordingly
		var SQLRoom = "SELECT roomid, building, capacity FROM RoomDetails";// declares SQL for room
		function GetRoom(){
			specBoolArray = [0,0,0,0,0,0,0,0,0,0,0,0];
			if (document.getElementById("QUR").checked == true){specBoolArray[0] = 1;}
			if (document.getElementById("WHC").checked == true){specBoolArray[1] = 1;}
			if (document.getElementById("DP1").checked == true){
				specBoolArray[2] = 1;
				document.getElementById("DP2").disabled = false;
			}else{
				document.getElementById("DP2").disabled = true;
				document.getElementById("DP2").checked = false;
				specBoolArray[3] = 0;
			}
			if (document.getElementById("DP2").checked == true){specBoolArray[3] = 1;}
			if (document.getElementById("VIS").checked == true){specBoolArray[4] = 1;}
			if (document.getElementById("VDB").checked == true){specBoolArray[5] = 1;}
			if (document.getElementById("CMP").checked == true){specBoolArray[6] = 1;}
			if (document.getElementById("WHB").checked == true){specBoolArray[7] = 1;}
			if (document.getElementById("CHB").checked == true){specBoolArray[8] = 1;}
			if (document.getElementById("NER").checked == true){specBoolArray[9] = 1;}
			specBoolArray[10]=document.getElementById("CAP").value;
			specBoolArray[11]=document.getElementById("PRK").value;
			ClrRoom();
			$("roomsList").empty();// empties current rooms list
			SQLRoom = "SELECT roomid, building, capacity FROM RoomDetails WHERE ";
			//SQLRoom += "(qualityroom = " + specBoolArray[0] + ") AND "; 
			if(specBoolArray[1]==1){SQLRoom  += "(wheelchair = " + specBoolArray[1] + ") AND "; }
			if(specBoolArray[2]==1){SQLRoom += "(dataprojector = " + specBoolArray[2] + ") AND "; }
			if(specBoolArray[3]==1){SQLRoom += "(doubleprojector = " + specBoolArray[3] + ") AND "; }
			if(specBoolArray[4]==1){SQLRoom += "(visualiser = " + specBoolArray[4] + ") AND "; }
			if(specBoolArray[5]==1){SQLRoom += "(videodvdbluray = " + specBoolArray[5] + ") AND "; }
			if(specBoolArray[6]==1){SQLRoom += "(computer = " + specBoolArray[6] + ") AND "; }
			if(specBoolArray[7]==1){SQLRoom += "(whiteboard = " + specBoolArray[7] + ") AND "; }
			if(specBoolArray[8]==1){SQLRoom += "(chalkboard = " + specBoolArray[8]+ ") AND "; }
			SQLRoom += "(capacity >= " + specBoolArray[10] + ")";
			if (specBoolArray[11] != "ANY"){ SQLRoom += " AND (location = '" + specBoolArray[11] + "')";}
			//if (sort==true){SQLRoom +=" ORDER BY capacity"}
			wrRoomsList();	
		}
		//-------validation
		function CapacityChange(){
			var capStr = document.getElementById("CAP").value;
			var capTemp = "";
			for(var i = 0; i < capStr.length; i++){
				if (capStr.charCodeAt(i) >= 48 && capStr.charCodeAt(i) <= 57){
					capTemp = capTemp + capStr.charAt(i);
				}
			}
			document.getElementById("CAP").value = parseInt(capTemp);
			GetRoom();
		}
		
		
		function getBookedRooms(selectedRooms){
			$.get("GETbookedRooms.php",{roomsarray: selectedRooms},function(JSON){});
		}
		
        //------------------------------------------------------------------------------------------------//
        function popModulesList(userDepartmentID){
            //check if lists already loaded
            if(alreadyLoaded == false){
                //if not then send php
                $.get("GETmodulesList.php", {id: userDepartmentID}, function(JSON){
                    titleOpt = "";
                    codeOpt = "";
                    codeStr ="";
                    
                    //mod title populator
                    for(var i=0;i<JSON.length;i++){
                        titleOpt += "<option value='" + JSON[i].moduletitle + "'>" + JSON[i].moduletitle + "</option>"
                    }
                    $("#modTitleSelect").empty();
                    $("#modTitleSelect").append(titleOpt);
                    
                    //mod code populator
                    for(var i=0;i<JSON.length;i++){
                        codeOpt += "<option value='" + JSON[i].modulecode + "'>" + JSON[i].modulecode + "</option>"
                    }
                    $("#modCodeSelect").empty();
                    $("#modCodeSelect").append(codeOpt);
                    
                    //spec requirements populator
					codeStr += "<table class='modTable'><tr>"
                    codeStr += "<td><input type='checkbox' class='specReq' id='QUR' onchange='GetRoom()'><label for='QUR'>Quality Room</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='WHC' onchange='GetRoom()'><label for='WHC'>Wheelchair</label></td>";
                    codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='DP1' onchange='GetRoom()'><label for='DP1'>Data Projector</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='DP2' disabled='true' onchange='GetRoom()'><label for='DP2'>Data Projector * 2</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='VIS' onchange='GetRoom()'><label for='VIS'>Visualiser</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='VDB' onchange='GetRoom()'><label for='VDB'>Video/DVD/BluRay</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='CMP' onchange='GetRoom()'><label for='CMP'>Computer</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='WHB' onchange='GetRoom()'><label for='WHB'>Whiteboard</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='CHB' onchange='GetRoom()'><label for='CHB'>Chalkboard</label></td>";
					codeStr += "<td><input type='checkbox' class='specReq' id='NER' onchange='GetRoom()'><label for='NER'>Near Previous Room</label></td>";
					codeStr += "</tr>";
					codeStr +="<tr><td>Capacity:</td><td><input type='textbox' class='specReqText' id='CAP' value='50' onchange='CapacityChange()'></td></tr>";
					codeStr +="<tr><td>Park:</td><td><select id='PRK' onchange='GetRoom()' class='modChooser'><option selected>ANY</option><option>E</option><option>C</option><option>W</option></select></td></tr>";
					codeStr +="<tr><td>Other Requirements:</td><td><input type='textbox' class='specReqText' id='ORE' placeholder='Type here...'></td></tr>";
					codeStr +="<tr><td>Priority:</td><td>";
					codeStr +="<input type='radio' class='specReqP' id='PRY' name='Priority' ><label for='PRY'>Yes</label>";
					codeStr +="<input type='radio' class='specReqP' id='PRN' name='Priority' ><label for='PRN'>No</label></td></tr></table>";
                    $("#basicBox").append(codeStr);

                }, 'json');
                alreadyLoaded = true;
            }
        }
		
		//-------------makes the mod code = mod title
		function ModuleSelector(modList){
			var modIndex = modList.selectedIndex;
			document.getElementById("modTitleSelect").selectedIndex=modIndex;
			document.getElementById("modCodeSelect").selectedIndex=modIndex;
		}

		
		function Submit(redirectBool){
		
			timetableGetter();
			var weekArr = [];
			//change all data to variables of correct type.
			var yearID = 13;
			if(document.getElementById("PRY").checked) {
				var pri=1;}
			else{var pri=0;}
			if(document.getElementById("sem1").checked) {
				var sem = 1;}
			else{var sem = 2;}
			for(var i = 0; i < 15; i++)
			{
				if(document.getElementById("wk" +(i+1)).checked){
					weekArr[i] = 1;}
				else{weekArr[i] = 0;}
			}
			if (roomsQueue.length ==0){
				var preferredRoom = 0;}
			else{var preferredRoom = 1;}
			var weekID = 0;
			var weekBool = true;
			$.ajax({
				type: "GET", 
				dataType: "json",
				url:"GETweeksIdExistence.php", 
				data: {'weeks1': weekArr[0],'weeks2': weekArr[1],'weeks3': weekArr[2],'weeks4': weekArr[3],'weeks5': weekArr[4],'weeks6': weekArr[5],'weeks7': weekArr[6],'weeks8': weekArr[7],'weeks9': weekArr[8],'weeks10': weekArr[9],'weeks11': weekArr[10],'weeks12': weekArr[11],'weeks13': weekArr[12],'weeks14': weekArr[13],'weeks15': weekArr[14]},  
				async: false,
				success: function(JSON){
					if(JSON.length == 1){
						weekID = JSON[0].weekid;
						weekBool = false;
					}
				}
			});
			if (weekBool){
				$.ajax({
					type: "GET",
					url: "POSTnewWeek.php",
					async: false,
					data: {'weeks1': weekArr[0],'weeks2': weekArr[1],'weeks3': weekArr[2],'weeks4': weekArr[3],'weeks5': weekArr[4],'weeks6': weekArr[5],'weeks7': weekArr[6],'weeks8': weekArr[7],'weeks9': weekArr[8],'weeks10': weekArr[9],'weeks11': weekArr[10],'weeks12': weekArr[11],'weeks13': weekArr[12],'weeks14': weekArr[13],'weeks15': weekArr[14]},
				});
				$.ajax({
					type: "GET",
					url: "GETlatestWeekId.php",
					dataType: "json",
					async: false,
					success: function(JSON){
						weekID = JSON[0].weekid;
					}
				});
			}		

			var i = 0;
			do{
				//post new request
				$.ajax({
					type: "GET",
					url: "POSTnewRequest.php",
					async: false,
					data: {'year':yearID, 'modulecode':(document.getElementById("modCodeSelect").value), 'priority':pri, 'semester':sem, 'day':DPTArray[i][0], 'period':DPTArray[i][1], 'duration':DPTArray[i][2], 'weekid':weekID , 'noofstudents':specBoolArray[10], 'noofrooms':roomsQueue.length , 'preferredroom':preferredRoom , 'qualityroom':specBoolArray[0], 'wheelchair':specBoolArray[1] , 'dataprojector':specBoolArray[2] , 'doubleprojector': specBoolArray[3], 'visualiser':specBoolArray[4] , 'videodvdbluray':specBoolArray[5], 'computer':specBoolArray[6] , 'whiteboard':specBoolArray[7], 'chalkboard':specBoolArray[8] , 'nearestroom':specBoolArray[9], 'other':(document.getElementById("ORE").value)},
				});
				i++;
				// //get latest request id
				var lReq = 0;
				$.ajax({
					type: "GET",
					url: "GETlatestRequestID.php",
					dataType: "json",
					async: false,
					success: function(JSON){
						lReq = JSON[0].requestid;
					}
				});

				if (preferredRoom ==1){
					for(var j =0; j < roomsNamesQueue.length;j++){
						$.ajax({
							type: "GET",
							url: "POSTroomBooking.php",
							async: false,
							data: {'requestid':lReq, 'room':roomsNamesQueue[j], 'modulecode':(document.getElementById("modCodeSelect").value)}
						});
					}
				}
			}
			while(i<DPTArray.length);
			
			if(redirectBool){
				alert("bye bye");
				window.location.replace("viewRequests.php");
			}
			else{
				alert("same");
				window.location.replace("addRequests.php");
			}
		}
		
        </script>
    </head>
    
    <body> 
        <div id="navwrap">
            <ul id="topnav">
                <li><a href="viewRequests.htm"><img src="LU-mark-rgb.png" alt="Home"></a></li>
                <li><a href="addRequests.htm">Add New Requests</a></li>
                <li><a href="viewTimetable.htm">View Timetable</a></li>
                <li><a href="helpPage.htm">Help</a></li>
                <li><a href="accountPage.htm">Username(pref)</a></li>
                <li><a href="login.htm">Logout</a></li>
            </ul>
        </div>
        <div id="pagewrap">
            <div class="contentBox" id="basicBox">
                <select id="modTitleSelect" name="modTitleSelect" class="modChooser" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected></option></select></br>
                <select id="modCodeSelect" name="modCodeSelect" class="modChooser" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected></option></select></br>
            </div>
            
            <!--<div class="contentBox" id="roomActionsBox"></div>-->
            
            <div class="contentBox" id="roomSelectorBox"></div>
            
            <div class="contentBox" id="inputWeeksBox">
                <!-- unsure if css is css3  -->
				<center>
					<table id="SemWekTab">
						<tr>
							<td>
								<label class="wkInput" id="wkLabel">Semester</label>
								<input type="radio" class="wkInput" id="sem1" name="semester" ><label for="sem1">1</label>
								<input type="radio" class="wkInput" id="sem2" name="semester" ><label for="sem2">2</label>
							</td>
							<td>
								<label class="wkInput" id="wkLabel">Weeks</label>
								<input type="checkbox" class="wkInput" id="wk1" ><label for="wk1">1</label>
								<input type="checkbox" class="wkInput" id="wk2" ><label for="wk2">2</label>
								<input type="checkbox" class="wkInput" id="wk3" ><label for="wk3">3</label>
								<input type="checkbox" class="wkInput" id="wk4" ><label for="wk4">4</label>
								<input type="checkbox" class="wkInput" id="wk5" ><label for="wk5">5</label>
								<input type="checkbox" class="wkInput" id="wk6" ><label for="wk6">6</label>
								<input type="checkbox" class="wkInput" id="wk7" ><label for="wk7">7</label>
								<input type="checkbox" class="wkInput" id="wk8" ><label for="wk8">8</label>
								<input type="checkbox" class="wkInput" id="wk9" ><label for="wk9">9</label>
								<input type="checkbox" class="wkInput" id="wk10" ><label for="wk10">10</label>
								<input type="checkbox" class="wkInput" id="wk11" ><label for="wk11">11</label>
								<input type="checkbox" class="wkInput" id="wk12" ><label for="wk12">12</label>
								<input type="checkbox" class="wkInput" id="wk13"><label for="wk13">13</label>
								<input type="checkbox" class="wkInput" id="wk14"><label for="wk14">14</label>
								<input type="checkbox" class="wkInput" id="wk15"><label for="wk15">15</label>
							</td>
						</tr>
					</table>
				</center>
            </div>
            
            <div class="contentBox" id="inputTableBox">

			</div>
            <div class="contentBox" id="formControlsBox">
				<form>
                    <input type="button" value="Submit" onclick="Submit(true)">  <!--changed to button from submit  for testing purposes-->
                    <input type="button" value="Submit & Add Another" onclick="Submit(false)"> <!-- changed to test aswell -->
                    <input type="button" value="Clear Form">
                </form>
            </div>
        </div>
    </body>
</html>