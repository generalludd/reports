$(document).ready(function(){
	

	$('.delete_attendance').live('click',
		function(e){
		e.preventDefault();
				$("#action").val("delete");
				choice=confirm("Are you absolutely sure you want to delete this attendance entry? This cannot be undone!");
				if(choice) {
					document.forms["attendanceEditor"].submit();
				}else{
					return false;
				}
			}//end function(event)
	);//end click
	
	$('.search_attendance').live('click',function(e){
		e.preventDefault();
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


