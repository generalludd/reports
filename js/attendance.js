$(document).ready(function(){


	$('.show_attendance_search').live('click',function(event){
		var myStudent = null;
		if(this.id) {
			myStudent = this.id.split("_")[1];	
		}
		var form_data = { 
				ajax: 1,
				kStudent: myStudent
		};
		var myUrl = base_url + ("index.php/attendance/show_search");
		$.ajax({
			type: "POST",
			url: myUrl,
			data: form_data,
			success: function(data){
			showPopup("Attendance Search",data,'auto');
			$("#attendType").focus();
		}//end success
		});//end ajax
	});
	

	$('.delete_attendance').live('click',
		function(event){
				$("#action").val("delete");
				choice=confirm("Are you absolutely sure you want to delete this concept entry? This cannot be undone!");
				if(choice) {
					document.forms["attendanceEditor"].submit();
				}else{
					return false;
				}
			}//end function(event)
	);//end click
	
	
	$('.list_attendance').live('click',
			function(event){
				listAttendance(this);
			}// end function(event);
	);// end home.click
	
	$('.edit_attendance').live('click',
			function(event){
				var myUrl = base_url + "index.php/attendance/edit";
				var myAttendance = this.id.split("_")[1];
				var form_data = {
						ajax: 1,
						kAttendance: myAttendance
				};
				
				$.ajax({
					type: "POST",
					url: myUrl,
					data: form_data,
					success: function(data){
						showPopup("Edit Attendance Record",data,"auto");
				}//end success
				});//end ajax
				
			}// end function(event)
	);// end click
	
	$('.add_attendance').live('click',
			function(event){
				var myStudent = this.id.split("_")[1];
				var myUrl = base_url + "index.php/attendance/create";
				var form_data = {
						ajax: 1,
						kStudent: myStudent
				};
				$.ajax({
					type: "POST",
					url: myUrl,
					data: form_data,
					success: function(data){
						showPopup("Add Attendance Record",data,"auto");
						// @TODO this doesn't seem right. I shouldn't need to
						// add the datefield script here!
					}// end success
				});// end ajax
			}// end function(event)
	);// end click
	

	
	$('.search_attendance').live('click',function(event){
		 var startDate=$('#startDate').val();
		 var endDate=$('#endDate').val();
		 if(startDate=="" || endDate==""){
			 alert("Start and End dates are Required!");
		 }else{
			 document.forms['attendance_search'].submit();
		 }
	});
	$("#attendType").live("change", function(){
		$("#attend-length-notice").html("Please make sure to identify the length of the absence").addClass("notice");
		if($("#attendType").val() == "Appointment"){
			$("#attendLength-None").attr("checked",true);
		}else if($("#attendType").val() == "Tardy"){
		$("#attendLength-Half").attr("checked",true);
		}
	});

}// end ready

);// end $(document)


function listAttendance(myID) {
	if(myID.id){
		var kStudent=myID.id.split("_")[1];
	}else{
		var kStudent=$("#kStudent").val();
	}
	myUrl = base_url + "index.php/attendance/search/" + kStudent;
	document.location = myUrl;
}

