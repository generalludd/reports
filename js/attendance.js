$(document).ready(function(){
	
	$("#attendType").live("change", function(){
		$("#attend-length-notice").html("Please make sure to identify the length of the absence").addClass("notice");
		if($("#attendType").val() == "Appointment"){
			$("#attendLength-None").attr("checked",true);
		}else if($("#attendType").val() == "Tardy"){
		$("#attendLength-Half").attr("checked",true);
		}
	});

	
	$(".mark-present").live("click",function(e){
		e.preventDefault();
		me = this;
		my_id = me.id.split("_")[1];
		my_parent = $(me).parents(".checklist.row");
		$(my_parent).css("background-color","#999999");
	});
	
	$("#attendance-check #gradeEnd ").live("keyup",function(e){
		e.preventDefault();
		me = $(this).val();
		if(me == $("#gradeStart").val()){
			//hide everything but the grades and date. 
			$("#attendance-check .lower-school").fadeOut();
			$("#attendance-check .middle-school").fadeOut();
			$("#kTeach").val("");
			$("#humanitiesTeacher").val("");
			$("#stuGroup").val("");
		}else if(me < 5){
			//lower school not kindergarten. 
			$("#attendance-check .lower-school").fadeIn();
			$("#attendance-check .middle-school").fadeOut();
			get_teacher_menu("lower-school","kTeach-wrapper");
			$("#humanitiesTeacher").val("");
			$("#stuGroup").val("");
		}else if($("#gradeStart").val() == 5 && me == 8){
			get_teacher_menu("advisor","#kTeach-wrapper");
			$("#attendance-check .lower-school").fadeIn();
			$("#attendance-check .middle-school").fadeOut();
		}else{
			$("#attendance-check .middle-school").fadeIn();
			$("#attendance-check .lower-school").fadeOut();
			$("#kTeach").val("");

		}
	})
}// end ready

);// end $(document)


