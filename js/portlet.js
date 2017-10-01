$(document).ready(function(){
$( function() {
    $( ".class" ).sortable({
      connectWith: ".class",
      handle: ".portlet-header",
      cancel: ".portlet-toggle",
      placeholder: "portlet-placeholder ui-corner-all",
      cursor: "move",
      stop: function(event, ui){
    	  dragged(ui.item);
      }
    });
 
//    $( ".portlet" ).draggable({
//    	stop: dragged(this)
//    });
     
 
    $( ".portlet-toggle" ).on( "click", function() {
      var icon = $( this );
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
    });
  } );
});

function dragged(my_item){
	my_student = $(my_item).attr("id").split("_")[1];
	my_parent = $(my_item).parents(".class").attr("id");
	my_id = my_parent.split("_")[1];
	console.log(my_id);
	if(!my_id){
		console.log("hello?");
		my_id = 0;
	}
	my_type = my_parent.split("_")[2];
	console.log(my_type);
	if(my_type && my_id) {


    console.log(my_type);
    form_data = {
      kStudent: my_student,
      id: my_id,
      type: my_type
    }
    $.ajax({
      type: "post",
      data: form_data,
      url: base_url + "student/update_class",
      success: function (data) {
        if (my_type == "ab") {
          $("#" + $(my_item).attr("id") + " .ab-group").html(my_id);
        }
      }
    });
  }

}