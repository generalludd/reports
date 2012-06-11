﻿$(document).ready(function(){
//	$('#content').css({height:'500px'});
	
	$(".datefield").live("focus", function(){
		$(".datefield").datepicker();
	});
	
		
	 $("#sortable").sortable({
	      handle : '.handle',
	      update : function () {
		var order = $('#sortable').sortable('serialize');
			  alert(order);
	      }
    });
		$("table thead").addClass("theader");
//	$("table tr:nth-child(even)").addClass("striped");

	 
	$('.home').live('click',
		function(event){
			document.location="index.php";
		}//end function(event);
	);//end home.click
	
	$('.submit_feedback').live('click',function(event){
		$.get('ajax.switch.php',{target:'feedback'},function(data){
			showPopup('Submit Feedback',data,'auto');
		});
	});
	
	$('.searchYear').live('change',function(){
		var myYear=$(this).val();
		if(myYear != 0){
			var endYear=parseInt(myYear) + 1;
			$('.yearEnd').val(endYear);
		}else{
			$('.yearEnd').val("");
		}
	});

	$(".email_create").live("click",function(){
		var form_data = {
				ajax: '1'
		};
		var myUrl = base_url + "index.php/email/create";
		$.ajax({
			type:"get",
			url: myUrl,
			data: form_data,
			success: function(data){
				showPopup("Create New System Email",data,"auto");
			}
		});
		return false;
	});
	
	$(".email_edit").live("click",function(){
		var myId = this.id.split("_")[1];
		var form_data = {
				kEmail:myId,
				ajax: '1'
		};
		
		var myUrl = base_url + "index.php/email/edit/" + myId;
		$.ajax({
			type:"post",
			url: myUrl,
			data: form_data,
			success: function(data){
				showPopup("Edit System Email Record", data,"auto");
			}
		});
		
	});
	
	
/*** MISCELLANEOUS SCRIPTS ****/	
	
	$('.closeThis').live('click',
			function(event){
				$("#popupContainer").fadeOut();
				$("#popupSidebar").fadeOut();
   });//end function(event);
	
	$('#close-sidebar').live('click',function(){
		$("#sidebar").fadeOut();
		$("#content").animate({width: "100%"});
		$("narrText").css({width:"100%"});
//		closeSidebar();
	});
	
	
	$('.help').live('click',
			function(event){
				var keys=this.id.split("_");//expect the id to be in the format "helpTopic_helpSubtopic"
				var myTopic=keys[0];
				var mySubtopic=keys[1];
				var myUrl = base_url + "index.php/help/get";
				var form_data = {
						helpTopic: myTopic,
						helpSubtopic: mySubtopic,
						ajax: '1'
				};
				$.ajax({
					type: "get",
					url: myUrl,
					data: form_data,
					success: function(data){
						var title="Help with "+ myTopic + "->"+ mySubtopic;
						showPopup(title, data, "300px");
					}
				});
		});//end function(event)
	
	
	$('.edit_preference').mouseup( function(event){
		var myTeach=$('#kTeach').val();
		var myType=this.id;
		var myValue=$('#'+this.id).val();
		var myTarget="stat"+myType;
		$('#'+myTarget).html("").show();
		var myUrl = base_url + "index.php/preference/update/";
		var form_data = {
				kTeach: myTeach,
				type: myType,
				value: myValue,
				ajax: 1
		};
		$.ajax({
			url: myUrl,
			type: "POST",
			data: form_data,
			success: function(data){
				$('#'+myTarget).html(data).fadeOut(3000);
			}
		});
	});
	
	$('.edit_preference_type').live("click", function(event){
		var myType = this.id.split("-")[1];
		var myUrl = base_url + "preference_type/edit";
		form_data = {
				type: myType,
				ajax: '1'
		};
		$.ajax({
			url: myUrl,
			type: "GET",
			data: form_data,
			success: function(data){
				showPopup("Edit Preference Type", data, "auto");
			}
			
		});
		
	});
	
	$('.create_preference_type').live("click", function(event){
		var myUrl = base_url + "preference_type/create";
		form_data = {
				ajax: '1'
		};
		
		$.ajax({
			url: myUrl,
			type: "GET",
			data: form_data,
			success: function(data){
				showPopup("Create Preference Type", data, "auto");
			}
		});
	});
	

	$("select.required").live('change', function(event) {
		var fieldName = $(this).attr('name');
		var fieldValue = $(this).val();
		validateField(fieldName, fieldValue);
	});
	
	$("input.required").live('blur', function(event) {
		if ($(this).val() == '') {
			$(this).css( {
				'background-color' : '#ff8538'
			});
		} else {
			$(this).css( {
				'background-color' : 'white'
			});
		}
	});
	// $("select.required[value='']").next().html("Required
	// Field!");

	
	$("#browser_warning").live('click',
		function(){
			$(".notice").fadeOut();
		}
	);
	
	}//end document function
);//end ready


function showPopup(myTitle,data,popupWidth,x,y){
	if(!popupWidth){
		popupWidth=300;
	}
	var myDialog=$('<div id="popup">').html(data).dialog({
		autoOpen:false,
		title: myTitle,
		modal: true,
		width: popupWidth
	});
	
	if(x) {
		myDialog.dialog({position:x});
	}


	myDialog.fadeIn().dialog('open',{width: popupWidth});

	return false;
}


function showSidebar(title,data,containerWidth,contentWidth,sidebarWidth){
	var sidebarLeft=parseInt(contentWidth)+2+"%";
	//$('#title').html(title);
	$('#content').animate( {
		width : '65%'
	}, 'fast');
	var narrTextWidth = $("#narrText_ifr").width();
	var contentWidth = $("#content").width();
	var percent = narrTextWidth / contentWidth;
	if(percent > .7) {
		$("#narrText_ifr").css({width: '70%'});
	}
	$('#sidebar').css({height:'95%'});
	$('#sidebar').html(data);
	$('#sidebar').animate({width:sidebarWidth,left:sidebarLeft},'normal').fadeIn();
}

function closeSidebar(){
	var myWidth=$('#popupSidebar').width();
	$('#popupSidebar').fadeOut();
	var mainWidth=$('#popupContainer').width();
	$('#popupContent').animate({width:'95%'},'normal');
	$('#popupContainer').animate({width:mainWidth-myWidth},'normal');
}
/*
    @function getYear
    @params myTerm string
    @params myGrade string
    @dependencies NARRATIVE_EDIT_INC
   description:  parses the year from the information supplied and determines the student's current grade. 
*/
function getYear(myTerm,myGrade){
	var termName=myTerm.replace(/ [0-9]{4}/gi,"");
	
	var myYear=myTerm.replace(/Mid-Year |Year-End /gi, "");
	myYear=parseInt(myYear);
	if(termName=="Year-End"){
		myYear=myYear-1;
	}
	var now=new Date;
	now=now.getFullYear();
	var diff=parseInt(now)-myYear;
	var reportGrade=parseInt(myGrade)-diff;
	if(reportGrade==0){
		reportGrade="K";
	}
	$('#stuGrade').val(reportGrade);
	//document.getElementById('stuGrade').value=reportGrade;
}


function format_grade(grade){
	var output = grade;
	if(grade == 0){
		output = "K";
	}
	return output;
}

function validateField(fieldName, fieldValue) {
	var errField = fieldName + "Err";
	var errorList = $('#errors').val();
	if (fieldValue == "") {
		$("#" + errField).html("Required Field!");
	} else {
		$("#" + errField).html("");
	}
}