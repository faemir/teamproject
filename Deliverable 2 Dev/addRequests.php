<?php
    ini_set("session.use_cookies",0);
    ini_set("session.use_only_cookies",0);
    ini_set("session.use_trans_sid",1);
    session_start();
	$_SESSION["editreqid"] = $_POST["reqid"];
	$_SESSION["editBool"] = $_POST["bool"];
	$_SESSION["addSim"] = $_POST["similar"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel='stylesheet' type='text/css' href='cssTimetable.css'>
        <title>Add New Requests</title>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">

		//GLOBALS -------------------------------------------------//
		var passedUsername = "";
		var seshId = "";
        var userDepartmentID = "";
        var hr24format = 0;
		var periodTime = 1;
		var startwk = 1;
		var endwk = 12;
		var prefLoc = "ANY";
		var redirectBool = false;
		var editBool = false;
		var roundsNumber = 0
		var semesterNumber = 0
		var editrequestid = "";
		var addSim = "";
		//pOrT stands for 'Period or Time' - to reflect user preferences
        var pOrTHeader1 = "Period";
		var pOrTHeader2 = "Times"
        var pOrTChildren = ["1","2","3","4","5","6","7","8","9"];
		var pOrTChildren2 = ["9-9:50","10-10:50","11-11:50","12-12:50"];
		var pOrTChildren3 = ["13-13:50","14-14:50","15-15:50","16-16:50","17-17:50"];
		var pOrTChildren4 = ["1-1:50","2-2:50","3-3:50","4-4:50","5-5:50"];
		
        var days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        var alreadyLoaded = false;
		var roomsQueue = [];
		var roomsNamesQueue = [];
		var roomsNames = [];
		var bookedRoomsArr = [];
		var roomsJSONchecker = true;
		var sort = false; // for sorting my capacity
		var ARooms = 0;
		var AClick = 0;
		var roomlen = 0;
		var currentYear = 13;
		var round = false;
		//Selected periods from table - false = not selected.
		//............................input table
		var mondaySele = [false,false,false,false,false,false,false,false,false];
		var tuesdaySele = [false,false,false,false,false,false,false,false,false];
		var wednesdaySele = [false,false,false,false,false,false,false,false,false];
		var thursdaySele = [false,false,false,false,false,false,false,false,false];
		var fridaySele = [false,false,false,false,false,false,false,false,false];
		var DPTArray = [];	//storing day, period, duration
		var specBoolArray =[0,0,0,0,0,0,0,0,0,0,0,0];

        //ONLOAD FUNCTIONS -----------------------------------------//
		$(document).ready(function(){theOnload()});
        //FUNCTIONS --------------------------------------------------//
		
		function theOnload(){
			getCurrentyear();
			rdRoundData();
			validateUser();
			getUser();
			GetPrefData();
			wrInputTable();
			loadDefaultWeeks();
			popModulesList(userDepartmentID);
			wrRoomsList();
			isEditreq();
			roundChanges();
		}
		
		function getUser(){
			passedUsername = "<?php echo $_SESSION['username']; ?>";
			seshId = "<?php echo session_id();?>";
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "GETdepartmentID.php",
				async: false,
				data:{'username': passedUsername},
				success: function(JSON){
					userDepartmentID = JSON[0].departmentid;
				}
			});
		}
		function GetPrefData(){	
			$.ajax({
				type: "GET",
				dataType: "json",
				url: "GETallPreferences.php",
				async: false,
				data: {'username': passedUsername},
				success: function(JSON){
					hr24format = JSON[0].hr24format;
					periodTime = JSON[0].period;
					startwk = JSON[0].defaultstartweek;
					endwk = JSON[0].defaultendweek;
					prefLoc = JSON[0].defaultlocation;
					
				}
			});
		}
		
		function validateUser(){
			var user= "<?php echo $_SESSION['username'] ?>";
			var sessionid= "<?php echo session_id(); ?>";
			$.get("GETuserpassdeets.php", {'username':user, 'sessionid':sessionid}, function(JSON){
				if (JSON.length==0)
				window.location.replace("login.php");
			}, 'json');
		}
		
		function rdRoundData(){
			$.get("GETroundData.php",function(JSON){
				if(JSON.length!=0){
				semesterNumber=JSON[0].semester;
				roundsNumber=JSON[0].roundsnum;
				}
			},'json');
		}
		
		function getCurrentyear(){
			$.get("GETcurrentYear.php",function(JSON){
				currentYear = JSON[0].year;
			},'json');
		}
		
		function roundChanges(){
			
			if (roundsNumber==1 && semesterNumber==1){
				document.getElementById('PRY').checked=true;
				document.getElementById('PRN').disabled=true;
				document.getElementById('sem1').checked=true;
				document.getElementById('sem2').disabled=true;
				round = false;
			}
			else if (roundsNumber==2 && semesterNumber==1){
				document.getElementById('sem1').checked=true;
				document.getElementById('sem2').disabled=true;
				round = true;
			}
			else if (roundsNumber==1 && semesterNumber==2){
				document.getElementById('PRY').checked=true;
				document.getElementById('PRN').disabled=true;
				round = false;
			}
			//else if (roundsNumber==2 && semesterNumber==2){
			//}
			else {
				document.getElementById('sem1').checked=true;
				document.getElementById('sem2').disabled=true;
				round = true;
			}
		}		 

		function isEditreq(){
			editBool = "<?php echo $_SESSION['editBool']; ?>";
			if(editBool == "true"){
				editrequestid = "<?php echo $_SESSION["editreqid"]; ?>";
				addSim = "<?php echo $_SESSION["addSim"]; ?>";
				var editwkid = 0;
				$.ajax({
					type: "GET",
					url: "GETeditRequest.php",
					dataType: "JSON",
					data:{'id': editrequestid},
					async: false,
					success: function(JSON){
						
						//module
						$("#modCodeSelect").val(String(JSON[0].modulecode));
						ModuleSelector(document.getElementById("modCodeSelect"));
						//capacity
						$("#CAP").val(JSON[0].noofstudents);
						//semester
						if(JSON[0].semester==1){$("#sem1").prop("checked",true);}
						else if(JSON[0].semester==2){$("#sem2").prop("checked",true);}
						//priority
						if(JSON[0].priority==1){$("#PRY").prop("checked",true);}
						else{$("#PRN").prop("checked",true);}
						//other
						if(JSON[0].other != "null"){$("#ORE").val(JSON[0].other);}
						//spec reqs
						if(JSON[0].qualityroom==1){$("#QUR").prop("checked",true);}
						if(JSON[0].wheelchairaccess==1){$("#WHC").prop("checked",true);}
						if(JSON[0].dataprojector==1){$("#DP1").prop("checked",true);}
						if(JSON[0].doubleprojector==1){$("#DP2").prop("checked",true);}
						if(JSON[0].visualiser==1){$("#VIS").prop("checked",true);}
						if(JSON[0].videodvdbluray==1){$("#VDB").prop("checked",true);}
						if(JSON[0].computer==1){$("#CMP").prop("checked",true);}
						if(JSON[0].whiteboard==1){$("#WHB").prop("checked",true);}
						if(JSON[0].chalkboard==1){$("#CHB").prop("checked",true);}
						if(JSON[0].nearestroom==1){$("#NER").prop("checked",true);}
						//park
						$("#PRK").val("ANY");
						GetRoom(false);
						editwkid = JSON[0].weekid;			
						//DPT
						var pdcnt = parseInt(JSON[0].period);
						var dycnt = "";
						if(JSON[0].day=="Monday"){dycnt=1;}
						if(JSON[0].day=="Tuesday"){dycnt=2;}
						if(JSON[0].day=="Wednesday"){dycnt=3;}
						if(JSON[0].day=="Thursday"){dycnt=4;}
						if(JSON[0].day=="Friday"){dycnt=5;}
						for(var i=0;i<(parseInt(JSON[0].duration));i++){
							var ref = "t" + String(dycnt) + String(pdcnt);
							tableSelect(ref);
							pdcnt++;
						}
						for(var j =0; j<JSON.length;j++){
							for(var k =0; k<roomlen;k++){
								if(($("#r"+k).val()) == JSON[j].roomid){
									document.getElementById("r"+k).checked = true;
									roomClick(document.getElementById("r"+k));
								}
							}
						}
						if(addSim == "true"){
							editBool = false;
						}
					}
				});
				$.ajax({
					type: "GET",
					url: "GETweek.php",
					dataType: "JSON",
					data:{'id': editwkid},
					async: false,
					success: function(JSON){
						$("#wk1").prop('checked',false);
						$("#wk2").prop('checked',false);
						$("#wk3").prop('checked',false);
						$("#wk4").prop('checked',false);
						$("#wk5").prop('checked',false);
						$("#wk6").prop('checked',false);
						$("#wk7").prop('checked',false);
						$("#wk8").prop('checked',false);
						$("#wk9").prop('checked',false);
						$("#wk10").prop('checked',false);
						$("#wk11").prop('checked',false);
						$("#wk12").prop('checked',false);
						$("#wk13").prop('checked',false);
						$("#wk14").prop('checked',false);
						$("#wk15").prop('checked',false);
						if(JSON[0].week1==1){$("#wk1").prop('checked',true);}
						if(JSON[0].week2==1){$("#wk2").prop('checked',true);}
						if(JSON[0].week3==1){$("#wk3").prop('checked',true);}
						if(JSON[0].week4==1){$("#wk4").prop('checked',true);}
						if(JSON[0].week5==1){$("#wk5").prop('checked',true);}
						if(JSON[0].week6==1){$("#wk6").prop('checked',true);}
						if(JSON[0].week7==1){$("#wk7").prop('checked',true);}
						if(JSON[0].week8==1){$("#wk8").prop('checked',true);}
						if(JSON[0].week9==1){$("#wk9").prop('checked',true);}
						if(JSON[0].week10==1){$("#wk10").prop('checked',true);}
						if(JSON[0].week11==1){$("#wk11").prop('checked',true);}
						if(JSON[0].week12==1){$("#wk12").prop('checked',true);}
						if(JSON[0].week13==1){$("#wk13").prop('checked',true);}
						if(JSON[0].week14==1){$("#wk14").prop('checked',true);}
						if(JSON[0].week15==1){$("#wk15").prop('checked',true);}	
					}
				});
			}
		}
		
		function wrInputTable(){

			var codeStr = "";

            codeStr += "<table id='inputTable'>";

            //headers
            codeStr += "<tr>";
            codeStr += "<th class ='daysHeader' rowspan='2'>Days</th>";

			if(periodTime == 1){
				codeStr += "<th class ='pOrTHeader' colspan='9'>" + pOrTHeader1 + "</th>";
			}
			else{
				codeStr += "<th class ='pOrTHeader' colspan='9'>" + pOrTHeader2 + "</th>";
			}

            codeStr += "</tr>";

            //pOrT children
            codeStr += "<tr>";


			if(periodTime == 1){
				for(var i = 0;i<pOrTChildren.length;i++){
					codeStr += "<th class ='pOrTChildren'>" + pOrTChildren[i] + "</th>"; 
				}
			}
			else if(hr24format == 1){
				var array = pOrTChildren2.concat(pOrTChildren3);
				for(var i = 0;i<array.length;i++){
					codeStr += "<th class ='pOrTChildren'>" + array[i] + "</th>"; 
				}	
			}
			else{
				var array2 = pOrTChildren2.concat(pOrTChildren4);
				for(var i = 0;i<array2.length;i++){
					codeStr += "<th class ='pOrTChildren'>" + array2[i] + "</th>"; 
				}
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
//****************************************CLEAR FUNCTIONS
        function ClrTab(){
			for(var k = 1;k<=pOrTChildren.length;k++){
				if (mondaySele[k-1]==true)
					{$("#t1"+ k).removeClass("gridClicked");}
					{$("#t1"+ k).addClass("grid");}
				if (tuesdaySele[k-1]==true)
					{$("#t2"+ k).removeClass("gridClicked");}
					{$("#t2"+ k).addClass("grid");}
				if (wednesdaySele[k-1]==true)
					{$("#t3"+ k).removeClass("gridClicked");}
					{$("#t3"+ k).addClass("grid");}
				if (thursdaySele[k-1]==true)
					{$("#t4"+ k).removeClass("gridClicked");}
					{$("#t4"+ k).addClass("grid");}
				if (fridaySele[k-1]==true)
					{$("#t5"+ k).removeClass("gridClicked");}
					{$("#t5"+ k).addClass("grid");}
			}
			mondaySele = [false,false,false,false,false,false,false,false,false];
			tuesdaySele = [false,false,false,false,false,false,false,false,false];
			wednesdaySele = [false,false,false,false,false,false,false,false,false];
			thursdaySele = [false,false,false,false,false,false,false,false,false];
			fridaySele = [false,false,false,false,false,false,false,false,false];
		}
		
		function ClrSpec(){
			document.getElementById("QUR").checked=false;
			document.getElementById("WHC").checked=false;
			document.getElementById("DP1").checked=false;
			document.getElementById("DP2").checked=false;
			document.getElementById("VIS").checked=false;
			document.getElementById("VDB").checked=false;
			document.getElementById("CMP").checked=false;
			document.getElementById("WHB").checked=false;
			document.getElementById("CHB").checked=false;
			document.getElementById("NER").checked=false;
			document.getElementById("CAP").value=0;
			document.getElementById("ORE").value="";
			document.getElementById("PRK").selectedIndex=0;
			document.getElementById("modTitleSelect").selectedIndex=0;
			document.getElementById("modCodeSelect").selectedIndex=0;
			//defaults?
		}
		function ClrRoom(){
			if (roomsJSONchecker){
				if (roomsQueue.length!=0){
					for(var j = 0; j < roomsQueue.length; j++){ //makes all rooms check = false
						document.getElementById("r"+roomsQueue[j]).checked = false;
					}
				}
				document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
				ARooms=0;
				document.getElementById("room1").checked = true;
			}
			
		}
		function EmptyRoom(){
			ClrRoom();
			roomsQueue = [];
			roomsNamesQueue = [];
			document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
			resetBooked()
			getBookedRooms();
			
		}
		function ClrAll(){
			if (confirm("Are you sure you are want to clear this?")){
				
				EmptyRoom();
				ClrTab();
				ClrSpec();
			}
		}
//****************************************CLEAR COMPLETE
		//---------------------------------------------------------------------------------------------//
        //onclick for table buttons
        function tableSelect(gridRef){
			if ($("#"+gridRef).attr('class')!='gridBooked'){
				if($("#"+gridRef).attr('class')=='gridClicked'){
					$("#"+ gridRef).removeClass("gridClicked");
					$("#"+ gridRef).addClass("grid");
				}else{
					$("#"+ gridRef).removeClass("grid");
					$("#"+ gridRef).addClass("gridClicked");
				}
			}
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
						else{
							if($("#t"+gridRef).attr('class')!='gridBooked'){mondaySele[periodSele]=true;}
							}
					}
					break;
					case 2:
					{
						if (tuesdaySele[periodSele]==true)
							{tuesdaySele[periodSele]=false;}
						else{
							if($("#t"+gridRef).attr('class')!='gridBooked'){tuesdaySele[periodSele]=true;}
							}
					}
					break;
					case 3:
					{
						if (wednesdaySele[periodSele]==true)
							{wednesdaySele[periodSele]=false;}
						else{
							if($("#t"+gridRef).attr('class')!='gridBooked'){wednesdaySele[periodSele]=true;}
							}
					}
					break;
					case 4:
					{
						if (thursdaySele[periodSele]==true)
							{thursdaySele[periodSele]=false;}
						else{
							if($("#t"+gridRef).attr('class')!='gridBooked'){thursdaySele[periodSele]=true;}
						}
					}
					break;
					case 5:
					{
						if (fridaySele[periodSele]==true)
							{fridaySele[periodSele]=false;}
						else{
							if($("#t"+gridRef).attr('class')!='gridBooked'){fridaySele[periodSele]=true;}
							}
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


        function loadDefaultWeeks(){
            for(var i = parseInt(startwk); i < parseInt(endwk)+1; i++){
				$("#wk" + i).prop('checked',true);
			}
            
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

		//gets and populates rooms list
        function wrRoomsList(){
			document.getElementById("roomSelectorBox").innerHTML = "";
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "GETroomsList.php",
				async: false,
				data: {'sqlrooms': SQLRoom},
                success: function(JSON){
                    var codeStr = "";
                    codeStr +="<div id='roomsListReq'>";
					if (JSON.length != 0){
						roomsJSONchecker=true;
						roomlen = JSON.length;
						for(var i =0;i<JSON.length;i++){
							codeStr += "<span title= 'Building: " + JSON[i].building+"'><input type='checkbox' id='r"+i+"' class='roomSele' onclick='roomClick(this)' value='"+JSON[i].roomid+"'><label for='r"+i+"'>" + JSON[i].roomid +" : "+ JSON[i].capacity+  "</label></span></br>";
							roomsNames[i] = JSON[i].roomid;
							
						}
					}else{
						roomsJSONchecker=false;
						codeStr += "<input type='checkbox' id='r1' class='roomSele' onclick=''><label for='r1'>There are no rooms for your requirements, please try again</label>";
					}
					codeStr +="</div>";
					codeStr +="<input type='button' value='Clear' onclick='EmptyRoom()'>"; //clearRooms


                    if (sort==false){
						codeStr +="<input type='button' value='Sort By Capacity' onclick='SortCap()'>"; //clearRooms
						
					}else{
						codeStr +="<input type='button' value='Sort By Building ID' onclick='SortCap()'>"; //clearRooms
					}
					codeStr +="<label id='cCR'>" + roomsNamesQueue.length + " Rooms Selected</label>";
					$("#roomSelectorBox").append(codeStr);
					for(var i =0;i<roomsNamesQueue.length;i++){
						for(var j=0; j<JSON.length;j++){
							if(roomsNamesQueue[i]==JSON[j].roomid){
								document.getElementById("r"+j).checked=true;
								roomsQueue[i]=j;
							}
						}
					}
                }  
            });
			document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
        }
		
		function SortCap(){
			if (sort==false){
				sort=true;}
			else{
				sort=false;}
			
			GetRoom(true);
		}
		
		function roomamount(valuess){
			if(valuess < ARooms){
				for(i=valuess; i <ARooms;i++){
					document.getElementById("r"+roomsQueue[i]).checked = false;
				}
				
				roomsQueue.length = valuess;
				roomsNamesQueue.length = valuess;
				ARooms = valuess;
			}
			getBookedRooms();
			AClick = valuess;
			document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
			
		}
		
		function roomClick(currentBox){
			
			if(currentBox.checked){
				if (ARooms <5){
					ARooms++;
				}
			}
			else{
				ARooms--
			}
			if(AClick==roomsQueue.length){AClick=0;}
			if((AClick != 0 && AClick <= ARooms) || AClick==0 && ARooms !=0){
				document.getElementById("room"+ARooms).checked=true;
			}
			
			var counter = 0
			var boxID = currentBox.id.substring(1,currentBox.length);
			
			if (currentBox.checked==true){
				if (roomsQueue.length < ARooms){
					roomsQueue[roomsQueue.length] = boxID;
				}else{
					document.getElementById("r"+roomsQueue[0]).checked = false;
					for (var i = 0; i < ARooms; i++){
						roomsQueue[i]=roomsQueue[i+1];
					}
					roomsQueue[ARooms-1]=boxID;
					/* roomsQueue[0]=roomsQueue[1];
					roomsQueue[1]=roomsQueue[2];
					roomsQueue[2]=roomsQueue[3];
					roomsQueue[3]=roomsQueue[4];
					roomsQueue[4] = boxID; */
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
			for(var i = 0; i<roomsQueue.length;i++){
				roomsNamesQueue[i] = roomsNames[roomsQueue[i]];
			}
			if(ARooms==0){
				roomsQueue=[];
				roomsNamesQueue = [];
			}
			document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
			
			
			getBookedRooms();
		}
		function getBookedRooms(){
			if(round){
				resetBooked();
				bookedRoomsArr= [];
				for(var i = 0; i <roomsNamesQueue.length; i++){
					$.ajax({
						type: "GET",
						url: "GETbookedRooms2.php",
						dataType: "json",
						data: {'roomid': roomsNamesQueue[i]},
						async: false,
						success: function(JSON){
							var timeSlotsArray = [];
							if(JSON.length !=0){
								for (var j = 0; j< JSON.length; j++){
									var timeSlots = [];
									timeSlots[0] = JSON[j].day ;
									timeSlots[1] = JSON[j].period ;
									timeSlots[2] = JSON[j].duration ;
									timeSlots[3] = JSON[j].week1 ;
									timeSlots[4] = JSON[j].week2 ;
									timeSlots[5] = JSON[j].week3 ;
									timeSlots[6] = JSON[j].week4 ;
									timeSlots[7] = JSON[j].week5 ;
									timeSlots[8] = JSON[j].week6 ;
									timeSlots[9] = JSON[j].week7 ;
									timeSlots[10] = JSON[j].week8 ;
									timeSlots[11] = JSON[j].week9 ;
									timeSlots[12] = JSON[j].week10 ;
									timeSlots[13] = JSON[j].week11 ;
									timeSlots[14] = JSON[j].week12 ;
									timeSlots[15] = JSON[j].week13 ;
									timeSlots[16] = JSON[j].week14 ;
									timeSlots[17] = JSON[j].week15 ;
									timeSlotsArray[timeSlotsArray.length]=timeSlots;
								}
								bookedRoomsArr[bookedRoomsArr.length]=[JSON[0].roomid,timeSlotsArray];
								//alert(bookedRoomsArr);
								displayBRooms();
							}
						}
					});
				}		
			}
			
		}
		function resetBooked(){
			for(i=1;i<=9;i++){
				for(j=1;j<=5;j++){
					$("#t"+j+''+i).removeClass('gridBooked');
					document.getElementById("t"+j+''+i).innerHTML="";
				}
			}
		}
		function displayBRooms(){
			resetBooked();
			for(i=0; i<bookedRoomsArr.length; i++){
				for(j=0; j<bookedRoomsArr[i][1].length;j++){
					var day =0;
					switch (bookedRoomsArr[i][1][j][0]){
						case "Monday":day = 1;break;
						case "Tuesday":day = 2;break;
						case "Wednesday":day = 3;break
						case "Thursday":day = 4;break;
						case "Friday":day = 5;break;
					}
					var checking = false;
					var weekcheck = 0;
					for(var t = 3; t <= 17; t++){
						weekcheck = 0;
						if(document.getElementById('wk'+(t-2)).checked){weekcheck = '1';}
						if(bookedRoomsArr[i][1][j][t]==1&&weekcheck==1){
							checking=true;
						}
					}
					if(checking){
						for (var k = 0; k < bookedRoomsArr[i][1][j][2]; k ++){
							var bubble = parseInt(bookedRoomsArr[i][1][j][1])+k;
							$("#t"+day+''+bubble).removeClass('grid');
							
							$("#t"+day+''+bubble).removeClass('grid');
							$("#t"+day+''+bubble).removeClass('gridClicked');
							$("#t"+day+''+bubble).addClass('gridBooked');
							tableSelect("t"+day+''+bubble);
							var weekshtml = "Weeks:";
							for(p=3; p <=17;p++){
								if(bookedRoomsArr[i][1][j][p]==1){
									weekshtml += p-2 + ", ";
								}
							}
							weekshtml=weekshtml.substring(0,weekshtml.length-2);
							if (document.getElementById("t"+day+''+bubble).innerHTML==""){
								document.getElementById("t"+day+''+bubble).innerHTML=bookedRoomsArr[i][0] + " " + weekshtml;
							}else{
								document.getElementById("t"+day+''+bubble).innerHTML = document.getElementById("t"+day+''+bubble).innerHTML + "<br>" + bookedRoomsArr[i][0] + " " + weekshtml;
							}
						}
					}
				}
			}		
		}
		//-------------Change Room list accordingly
		var SQLRoom = "SELECT roomid, building, capacity FROM RoomDetails ORDER BY roomid";// declares SQL for room
		function GetRoom(type){

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
			
			$("roomsList").empty();// empties current rooms list
			SQLRoom = "SELECT roomid, building, capacity FROM RoomDetails";
			if (specBoolArray[0]==0 && specBoolArray[1]==0 && specBoolArray[2]==0 && specBoolArray[3]==0 &&specBoolArray[4]==0 &&specBoolArray[5]==0 &&specBoolArray[6]==0 &&specBoolArray[7]==0 &&specBoolArray[8]==0 && specBoolArray[11]=="ANY"){
			
			}
			else{
				SQLRoom  +=" WHERE";
			}
			//counter to record number of "ANDS" to add
			counter = 0;
			for(var i=0;i<9;i++){
				if(specBoolArray[i]==1){
					counter ++;
				}
			}
			if (specBoolArray[11]!="ANY"){
				counter ++;
			}
			if(specBoolArray[0] ==1){
				SQLRoom += " (qualityroom = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[1] ==1){
				SQLRoom += " (wheelchairaccess = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[2] ==1){
				SQLRoom += " (dataprojector = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[3] ==1){
				SQLRoom += " (doubleprojector = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[4] ==1){
				SQLRoom += " (visualiser = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[5] ==1){
				SQLRoom += " (videodvdbluray = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[6] ==1){
				SQLRoom += " (computer = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[7] ==1){
				SQLRoom += " (whiteboard = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if(specBoolArray[8] ==1){
				SQLRoom += " (chalkboard = " + 1 + ") ";
				if(counter>1){
					SQLRoom += "AND";
					counter--;
				}
			}
			if (specBoolArray[11] != "ANY"){ 
				SQLRoom += " (location = '" + specBoolArray[11] + "')";
			}
			if (sort==true){
				SQLRoom +=" ORDER BY capacity"
			}else{
				SQLRoom +=" ORDER BY roomid"
			}
			wrRoomsList();
			if (!type){
				EmptyRoom();
			}
			document.getElementById("cCR").innerHTML  = roomsNamesQueue.length + " Rooms Selected";
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
			if (capTemp==""){
				document.getElementById("CAP").value =0;
			}else{
				document.getElementById("CAP").value = parseInt(capTemp);}
		}

		
		// function getBookedRooms(selectedRooms){
			// $.get("GETbookedRooms.php",{roomsarray: selectedRooms},function(JSON){});
		// }
		
		function countText(){
			document.getElementById("charToGo").innerHTML = (280 - document.getElementById("ORE").value.length) + " Characters remaining"
			if (document.getElementById("ORE").value.length >= 280){
				document.getElementById("ORE").value=document.getElementById("ORE").value.substring(0,280);
			}
		}


        //------------------------------------------------------------------------------------------------//

        function popModulesList(userDepartmentID){
            //check if lists already loaded
            if(alreadyLoaded == false){
                //if not then send php
                $.get("GETmodulesList.php", {'id': userDepartmentID}, function(JSON){
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
                    codeStr += "<td><input type='checkbox' class='specReq' id='QUR' onchange='GetRoom(false)'><label for='QUR'>Quality Room</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='WHC' onchange='GetRoom(false)'><label for='WHC'>Wheelchair</label></td>";
                    codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='DP1' onchange='GetRoom(false)'><label for='DP1'>Data Projector</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='DP2' disabled='true' onchange='GetRoom(false)'><label for='DP2'>Data Projector * 2</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='VIS' onchange='GetRoom(false)'><label for='VIS'>Visualiser</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='VDB' onchange='GetRoom(false)'><label for='VDB'>Video/DVD/BluRay</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='CMP' onchange='GetRoom(false)'><label for='CMP'>Computer</label></td>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='WHB' onchange='GetRoom(false)'><label for='WHB'>Whiteboard</label></td>";
					codeStr += "</tr><tr>";
                    codeStr += "<td><input type='checkbox' class='specReq' id='CHB' onchange='GetRoom(false)'><label for='CHB'>Chalkboard</label></td>";
					codeStr += "<td><input type='checkbox' class='specReq' id='NER' onchange='GetRoom(false)'><label for='NER'>Near Previous Room</label></td>";
					codeStr += "</tr>";
					codeStr +="<tr><td>No of Students:</td><td><input type='textbox' class='specReqText' id='CAP' value='50' onclick='CapacityChange()' onchange='CapacityChange()' onkeypress='CapacityChange()' onkeyup='CapacityChange()'></td></tr>";
					codeStr +="<tr><td>Park:</td><td><select id='PRK' onchange='GetRoom(false)' class='modChooser'>";
					if(prefLoc == "ANY"){
						codeStr +="<option value='ANY' selected>Any</option><option value='E'>East</option><option value='C'>Central</option><option value='W'>West</option>"
					}
					if(prefLoc == "E"){
						codeStr +="<option value='ANY'>Any</option><option value='E' selected>East</option><option value='C'>Central</option><option value='W'>West</option>"
					}
					if(prefLoc == "C"){
						codeStr +="<option value='ANY'>Any</option><option value='E'>East</option><option value='C' selected>Central</option><option value='W'>West</option>"
					}
					if(prefLoc == "W"){
						codeStr +="<option value='ANY'>Any</option><option value='E'>East</option><option value='C'>Central</option><option value='W' selected>West</option>"
					}
					codeStr +="</select></td></tr>";
					codeStr +="<tr><td>Other Requirements:</td><td><input type='textbox' class='specReqText' onkeyup='countText()' id='ORE' placeholder='Type here...'></td></tr>";
					codeStr +="<tr><td></td><td><label id='charToGo'> </label></td></tr>";
					codeStr +="<tr><td>Amount of rooms:</td><td><input type='radio' class='specReqR' id='room1' value='1' name='RoomCount' onclick='roomamount(1)' checked><label for='room1'>1</label>";
					codeStr +="<input type='radio' class='specReqR' id='room2' value='2' name='RoomCount' onclick='roomamount(2)' ><label for='room2'>2</label>";
					codeStr +="<input type='radio' class='specReqR' id='room3' value='3' name='RoomCount' onclick='roomamount(3)' ><label for='room3'>3</label>";
					codeStr +="<input type='radio' class='specReqR' id='room4' value='4' name='RoomCount' onclick='roomamount(4)' ><label for='room4'>4</label>";
					codeStr +="<input type='radio' class='specReqR' id='room5' value='5' name='RoomCount' onclick='roomamount(5)' ><label for='room5'>5</label></td></tr>";
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

		function Submit(redirectBool,eBool){
			if (confirm("Are you sure you are ready to submit this?")){
				var checkRoom=1;
				for(var i = 1; i <= 5; i++){
					if (document.getElementById("room"+i).checked == true)
					checkRoom = i;
				}
				if(roomsQueue.length != checkRoom && roomsQueue.length!=0){
					var num = checkRoom-roomsQueue.length;
					if(num==1){
						alert("Please enter " + num + " more room OR no rooms");
					}
					else{
						alert("Please enter " + num + " more rooms OR no rooms");
					}
				}
				else{
					timetableGetter();
					if(DPTArray.length==0){
						alert("Please enter a time slot");
					}
					else{
						var weekArr = [];
						//change all data to variables of correct type.
						var yearID = 13;

						if(document.getElementById("PRY").checked) {
							var pri=1;
						}
						else{
							var pri=0;
						}
						if(document.getElementById("sem1").checked){
							var sem = 1;
						}
						else{
							var sem = 2;
						}
						for(var i = 0; i < 15; i++){
							if(document.getElementById("wk" +(i+1)).checked){weekArr[i] = 1;}
							else{weekArr[i] = 0;}
						}
						if (roomsQueue.length ==0){
							var preferredRoom = 0;
						}
						else{
							var preferredRoom = 1;
						}
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
								data: {'editrequestid': editrequestid,'editBool': eBool,'year':yearID, 'modulecode':(document.getElementById("modCodeSelect").value), 'priority':pri, 'semester':sem, 'day':DPTArray[i][0], 'period':DPTArray[i][1], 'duration':DPTArray[i][2], 'weekid':weekID , 'noofstudents':specBoolArray[10], 'noofrooms':checkRoom , 'preferredroom':preferredRoom , 'qualityroom':specBoolArray[0], 'wheelchair':specBoolArray[1] , 'dataprojector':specBoolArray[2] , 'doubleprojector': specBoolArray[3], 'visualiser':specBoolArray[4] , 'videodvdbluray':specBoolArray[5], 'computer':specBoolArray[6] , 'whiteboard':specBoolArray[7], 'chalkboard':specBoolArray[8] , 'nearestroom':specBoolArray[9], 'other':(document.getElementById("ORE").value), 'year': currentYear},
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
							
							if(editBool){
								$.get("POSTdeleteBooking.php", {'editrequestid': editrequestid});
								lReq = editrequestid;
							}
							if (preferredRoom ==1){
								for(var j =0; j < checkRoom;j++){
									$.ajax({
										type: "GET",
										url: "POSTroomBooking.php",
										async: false,
										data: {'editBool': eBool, 'requestid':lReq, 'room':roomsNamesQueue[j], 'modulecode':(document.getElementById("modCodeSelect").value)}
									});
								}
							}
							else{
								for(var j =0; j < checkRoom;j++){
									$.ajax({
										type: "GET",
										url: "POSTroomBooking.php",
										async: false,
										data: {'requestid':lReq, 'room':"NULL", 'modulecode':(document.getElementById("modCodeSelect").value)}
									});
								}
							}
							
						}while(i<DPTArray.length);
					
						if(redirectBool){
							window.location.replace("viewRequests.php?PHPSESSID=" + seshId);
						}else{
							window.location.replace("addRequests.php?PHPSESSID=" +seshId);
						}	
				
					}
				}
			}
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
            <div class="contentBox" id="basicBox">
                <select id="modTitleSelect" name="modTitleSelect" class="modChooser" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected></option></select></br>
                <select id="modCodeSelect" name="modCodeSelect" class="modChooser" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected></option></select></br>
            </div>
			
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
								<input type="checkbox" class="wkInput" id="wk1" onclick="getBookedRooms()"><label for="wk1">1</label>
								<input type="checkbox" class="wkInput" id="wk2" onclick="getBookedRooms()"><label for="wk2">2</label>
								<input type="checkbox" class="wkInput" id="wk3" onclick="getBookedRooms()"><label for="wk3">3</label>
								<input type="checkbox" class="wkInput" id="wk4" onclick="getBookedRooms()"><label for="wk4">4</label>
								<input type="checkbox" class="wkInput" id="wk5" onclick="getBookedRooms()"><label for="wk5">5</label>
								<input type="checkbox" class="wkInput" id="wk6" onclick="getBookedRooms()"><label for="wk6">6</label>
								<input type="checkbox" class="wkInput" id="wk7" onclick="getBookedRooms()"><label for="wk7">7</label>
								<input type="checkbox" class="wkInput" id="wk8" onclick="getBookedRooms()"><label for="wk8">8</label>
								<input type="checkbox" class="wkInput" id="wk9" onclick="getBookedRooms()"><label for="wk9">9</label>
								<input type="checkbox" class="wkInput" id="wk10" onclick="getBookedRooms()"><label for="wk10">10</label>
								<input type="checkbox" class="wkInput" id="wk11" onclick="getBookedRooms()"><label for="wk11">11</label>
								<input type="checkbox" class="wkInput" id="wk12" onclick="getBookedRooms()"><label for="wk12">12</label>
								<input type="checkbox" class="wkInput" id="wk13" onclick="getBookedRooms()"><label for="wk13">13</label>
								<input type="checkbox" class="wkInput" id="wk14" onclick="getBookedRooms()"><label for="wk14">14</label>
								<input type="checkbox" class="wkInput" id="wk15" onclick="getBookedRooms()"><label for="wk15">15</label>
							</td>
						</tr>
					</table>
				</center>
            </div>

            <div class="contentBox" id="inputTableBox">

			</div>
            <div class="contentBox" id="formControlsBox">
				<form>

                    <input type="button" value="Submit" onclick="Submit(true,editBool)">  
                    <input type="button" value="Submit & Add Another" onclick="Submit(false,editBool)">

                    <input type="button" value="Clear Form" onclick="ClrAll()">
                </form>
            </div>
        </div>
    </body>
</html>
