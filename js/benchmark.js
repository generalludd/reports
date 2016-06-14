$(document)
		.ready(function() {

				$('.delete_benchmark').live('click',function(event) {
					var myBenchmark = this.id.split("_")[1];
					var myUrl = base_url + "benchmark/delete";
					var form_data = {
							kBenchmark: myBenchmark,
							ajax: 1
					};
					choice = confirm("Are you absolutely sure you want to delete this benchmark entry? This cannot be undone!");
					if (choice) {
						$.ajax({
							url: myUrl,
							type:'POST',
							data: form_data,
							success: function(data){
							$("#benchmark_" + myBenchmark).fadeOut();
						}
						});//end ajax
						
					}// end if(choice);
					}// end function(event)
				);// end click

		
				

					$('.save_student_benchmark').live('click',
						function(event){
						
						var my_id = $(this).parents("tr").attr("id").split("_");
							var myBenchmark = my_id[1];
							var myStudent = my_id[2];
							var myTeach = my_id[3];
							var myGrade = $("#g_" + myBenchmark).val();
							var myComment = $("#c_" + myBenchmark).val();
							$(this).focus();
							var form_data = {
									kBenchmark: myBenchmark,
									kStudent: myStudent,
									kTeach: myTeach,
									grade: myGrade,
									comment: myComment,
									ajax: 1
							};
							var myUrl = base_url + "benchmark/update_for_student";
						
							$.ajax({
								type: "post",
								url: myUrl,
								data: form_data,
								success: function(data){
									console.log(data);
									$("#ssb_" + myBenchmark).hide();
									$("#save_" + myBenchmark).html(data).show();
								},
								error: function(data){
									console.log(data);
								}
							});
							
						}//end function(event)
					);//end update_student_benchmark.click
					
					$(".benchmark-string").live('keyup', function(event){
						var myBenchmark = this.id.split("_")[1];
						$("#save_" + myBenchmark).hide();
						$("#ssb_" + myBenchmark).show();
					});

			}
		);// end document-ready
