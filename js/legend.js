$(document)
		.ready(function() {

			
			$('.legend_search').live('click', function(event){
				var myUrl = base_url + "benchmark_legend/search";
				var form_data = {
						ajax: 1
				};

				$.ajax({
					url: myUrl,
					type: "POST",
					data: form_data,
					success: function(data){
					showPopup("Search for legends", data, 'auto');

				}
				
				});

				
			});
			
			
			$('.new_legend').live('click', function(event) {
				var myUrl = base_url + "benchmark_legend/create";
				var form_data = {
						ajax: 1
				};
				$.ajax({
					url: myUrl,
					type: 'POST',
					data: form_data,
					success: function(data){
					showPopup("New legend", data, "auto");
				}//end success function
				});//end ajax
				
				}// end function(event)
			);// end new_legend

				$('.edit_legend').live('click',function(event) {
							var mylegend = this.id.split("_")[1];
							var form_data = {
									klegend: mylegend,
									ajax: 1
							};
							var myUrl = base_url + "benchmark_legend/edit";
							
							$.ajax({
								url: myUrl,
								type: 'POST',
								data: form_data,
								success: function(data){
								showPopup("Edit legend", data, 'auto');
							} //end success function
							}); //end ajax

						}// end function(event)
				);// end edit_legend

				

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
