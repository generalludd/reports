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

					$(".benchmark-grade, .benchmark-comment").live('keydown',function(event){
						var my_id = $(this).parents("tr").attr("id").split("_");
						var myBenchmark = my_id[1];
						$("#save_" + myBenchmark).fadeOut(1000);
					});
					
				
					
					$(".benchmark-grade").live('change', function(event){
						var my_id = $(this).parents("tr").attr("id").split("_");
						update_benchmark(my_id);
						
					});
					
					$(".benchmark-comment").live('blur',function(event){
						var my_id = $(this).parents("tr").attr("id").split("_");
						update_benchmark(my_id);
					});
					
					$(".benchmark-fill-down").live('click',function(event){
						$(".benchmark-grade").each(function(){
							if($(this).val()==""){
							$(this).val("M");
							var my_id = $(this).parents("tr").attr("id").split("_");
							update_benchmark(my_id);
							}
							
						});
					});

			}
		);// end document-ready

function update_benchmark (my_id){
	var myBenchmark = my_id[1];
	var myStudent = my_id[2];
	var myTeach = my_id[3];
	var myYear = $("#year").val();
	var myTerm = $("#term").val();
	var myQuarter = $("#quarter").val();
	var myGrade = $("#g_" + myBenchmark).val();
	var myComment = $("#c_" + myBenchmark).val();
	var form_data = {
			kBenchmark: myBenchmark,
			kStudent: myStudent,
			kTeach: myTeach,
			grade: myGrade,
			comment: myComment,
			quarter: myQuarter,
			term: myTerm,
			year: myYear,
			ajax: 1
	};
	var myUrl = base_url + "student_benchmark/update";
	$.ajax({
		type: "post",
		url: myUrl,
		data: form_data,
		success: function(data){
			console.log(data);
			$("#save_" + myBenchmark).html(data).show();

		},
		error: function(data){
			console.log(data);

		}
	});
	
}
