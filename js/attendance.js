$(document).ready(function(){
	
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


