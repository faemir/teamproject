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
		//Selected periods from table - false = not selected.
		//............................input table
		var mondaySele = [false,false,false,false,false,false,false,false,false];
		var tuesdaySele = [false,false,false,false,false,false,false,false,false];
		var wednesdaySele = [false,false,false,false,false,false,false,false,false];
		var thursdaySele = [false,false,false,false,false,false,false,false,false];
		var fridaySele = [false,false,false,false,false,false,false,false,false];
		var DPTArray = [];	//storing day, period, duration


        //ONLOAD FUNCTIONS -----------------------------------------//

        $(document).ready(function(){wrInputTable()});
        $(document).ready(function(){loadDefaultWeeks()});
        $(document).ready(function(){wrRoomsList()});
     //$(document).ready(function(){popModulesList(userDepartmentID)});


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
                    codeStr += "<td class='grid' onclick='tableSelect(this.id)' id='" + j + k + "'></td>";
                }
                codeStr += "</tr>";
            }

            codeStr += "</table>";
            //insert into inputTableBox
            $("#inputTableBox").append(codeStr);
        }

        //onclick for table buttons
        function tableSelect(gridRef){

            //2-D array to hold boolean value for any field currently pressed.
            //checker searches 2-d array to see if a previous gridref has been clicked.
            //if new grid ref is to right of previous, allow connection of two (i.e. 2hr slot) - (2 true values in array)
            //Else erase previous grid ref boolean and write new gridref boolean (so only one true value in array

            $("#" + gridRef).toggleClass("gridClicked");
			TFTable(gridRef)
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
			alert(DPTArray.join("//"));
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

		//gets and populates rooms list
        function wrRoomsList(){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "GETroomsList.php",
                success: function(JSON){

                    var codeStr = "";
                    codeStr +="<ul id='roomsList'>";
                    for(var i =0;i<JSON.length;i++){
                        codeStr += "<li class='' onclick=''>" + JSON[i].roomid + " | " + JSON[i].building + "</li>";
                    }
                    codeStr +="</ul>";

                    $("#roomSelectorBox").append(codeStr);
                }
            });
        }


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
                    codeStr += "<input type='checkbox' id='QR'>Quality Room<br>";
                    codeStr += "<input type='checkbox' id='WC'>Wheelchair<br>";
                    codeStr += "<input type='checkbox' id='DP'>Data Projector<br>";
                    codeStr += "<input type='checkbox' id='DP2'>Data Projector * 2<br>";
                    codeStr += "<input type='checkbox' id='Vi'>Visualiser<br>";
                    codeStr += "<input type='checkbox' id='VDB'>Video/DVD/BluRay<br>";
                    codeStr += "<input type='checkbox' id='CP'>Computer<br>";
                    codeStr += "<input type='checkbox' id='WB'>Whiteboard<br>";
                    codeStr += "<input type='checkbox' id='CB'>Chalkboard<br>";
                    $("#basicBox").append(codeStr);

                }, 'json');
                alreadyLoaded = true;
            }
        }
		function ModuleSelector(modList){
			var modIndex = modList.selectedIndex;
			document.getElementById("modTitleSelect").selectedIndex=modIndex;
			document.getElementById("modCodeSelect").selectedIndex=modIndex;
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
            <div class="contentBox" id="basicBox">
                <select id="modTitleSelect" name="modTitleSelect" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected>Please Choose Module Title</option></select></br>
                <select id="modCodeSelect" name="modCodeSelect" onclick="popModulesList(userDepartmentID)" onchange="ModuleSelector(this)"><option selected>Please Choose Module Code</option></select></br>
            </div>

            <div class="contentBox" id="roomActionsBox"></div>

            <div class="contentBox" id="roomSelectorBox"></div>

            <div class="contentBox" id="inputWeeksBox">
                <!-- unsure if css is css3  -->
                <label id="wkLabel">Weeks</label></td>
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
            </div>

            <div class="contentBox" id="inputTableBox">

			</div>
            <div class="contentBox" id="formControlsBox">
				<form>
                    <input type="button" value="Submit" onclick="selectedPeriods()">  <!--changed to button from submit  for testing purposes-->
                    <input type="button" value="Submit & Add Another" onclick="timetableGetter()"> <!-- changed to test aswell -->
                    <input type="button" value="Clear Form">
                </form>
            </div>
        </div>
    </body>
</html>
