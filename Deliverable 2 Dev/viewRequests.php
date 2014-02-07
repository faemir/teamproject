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
        //onload
        $(document).ready(function(){wrRequestsTable();});

        function wrRequestsTable(){
            //writes and populates Requests table. needs preferences input
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "GETallRequests.php",
                success: function(JSON){

                    var codeStr = "";
                    codeStr += '<table id="RequestsTable">';
                    codeStr += '<tr>';
                    //Added sorting of th's
                    codeStr += '    <thonclick="sortTable(0)">Module Code</th>';
                    codeStr += '    <thonclick="sortTable(1)">Priority</th>';
                    codeStr += '    <thonclick="sortTable(2)">Day</th>';
                    codeStr += '    <thonclick="sortTable(3)">Period</th>';
                    codeStr += '    <thonclick="sortTable(4)">Details</th>';
                    codeStr += '    <thonclick="sortTable(5)">Status</th>';
                    codeStr += '</tr>';

                    for(var i=0;i<JSON.length;i++){
                        codeStr += '<tr>';
                        codeStr += '    	<td>' + JSON[i].modulecode + '</td>';
                        codeStr += '    	<td>' + JSON[i].priority + '</td>';
                        codeStr += '    	<td>' + JSON[i].day + '</td>';
                        codeStr += '    	<td>' + JSON[i].period + '</td>';
                        codeStr += '    	<td><input type="button" class="detailsButton" value="Details" onclick="showDetails(' + JSON[i].requestid + ')"></input></td>';
                        codeStr += '    	<td>' + JSON[i].requeststatus + '</td>';
                        codeStr += '	</tr>';
                    }
                    codeStr += "</table">

                    //clears and writes table into container
                    // UPDATE: empty function produced 'false' onscreen when already empty
                    //$("#tableBox").empty();
                    $("#tableBox").append(codeStr);
                }
            });
        }

        function showDetails(requestID){

            $.get("GETdetailedRequests.php", {id: requestID}, function(JSON){
                alert("test!!\n requestsID: " + JSON[0].requestid + "\n" + "Duration: " + JSON[0].duration + "\n" + "NO. Students: " + JSON[0].noofstudents + "\n etc....");
            }, 'json');


        }

		//Sort functions. Asc, Desc alternating. Bubble sort.
		var way = "1";
		function sortTable(colnumber){
			//Fill 2D array with each row of table.
			var value=new Array();
			var rows = RequestsTable.getElementsByTagName('tr');
			for (i = 1; i < rows.length; i++){
				var row = rows[i];
				var cols = row.getElementsByTagName('td');
				var row_data = [];
				value[i]=new Array();
				for (j = 0; j < cols.length; j++){
					value[i][j]=cols[j].innerHTML;
				}
			}
			//Sort descending.
			var temp=new Array();
			if (way == "1"){
				var swapped = true;
				while(swapped){
					swapped = false;
					for (k = 1; k < value.length-1; k++){
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
					for (m = 1; m < value.length-1; m++){
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
			//Recreate table after sorting
			var codeStr = "";
			codeStr += '<table id="RequestsTable">';
			codeStr += '<tr>';
			codeStr += '    <th onclick="sortTable(0)">Module Code</th>';
			codeStr += '    <th onclick="sortTable(1)">Priority</th>';
			codeStr += '    <th onclick="sortTable(2)">Day</th>';
			codeStr += '    <th onclick="sortTable(3)">Period</th>';
			codeStr += '    <th onclick="sortTable(4)">Details</th>';
			codeStr += '    <th onclick="sortTable(5)">Status</th>';
			codeStr += '</tr>';
			for(var l=1;l<value.length;l++){
				codeStr += '	<tr>';
				codeStr += '    	<td>' + value[l][0] + '</td>';
				codeStr += '    	<td>' + value[l][1] + '</td>';
				codeStr += '    	<td>' + value[l][2] + '</td>';
				codeStr += '    	<td>' + value[l][3] + '</td>';
				codeStr += '    	<td>' + value[l][4] + '</td>';
				codeStr += '    	<td>' + value[l][5] + '</td>';
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
            <div class="contentBox" id="actionsBox"></div>

            <div class="contentBox" id="searchBox"></div>

            <div class="contentBox" id="roundsBox"></div>


            <div class="contentBox" id="tableBox"></div>

        </div>
    </body>
</html>
