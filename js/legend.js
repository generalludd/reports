$(document)
		.ready(function() {



				

	$('.delete_legend').live('click',function(event) {
		var mylegend = this.id.split("_")[1];
		var myUrl = base_url + "benchmark_legend/delete";
		var form_data = {
				klegend: mylegend,
				ajax: 1
		};
		choice = confirm("Are you absolutely sure you want to delete this legend? This cannot be undone!");
		if (choice) {
			$.ajax({
				url: myUrl,
				type:'POST',
				data: form_data,
				success: function(data){
				$("#legend_" + mylegend).fadeOut();
			}
			});//end ajax
			
		}// end if(choice);
		}// end function(event)
	);// end click





		}
);// end document-ready
