$(document)
		.ready(function() {

				$('.delete_benchmark').live('click',function(event) {
					var myBenchmark = this.id.split("_")[1];
					var myUrl = base_url + "benchmark/delete";
					var form_data = {
							kBenchmark: myBenchmark,
							ajax: 1
					};
					choice = confirm("Are you absolutely sure you want to delete this benchmark entry? This will ALSO delete all student grades entered for this benchmark. This cannot be undone!");
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

					$(".benchmark-grade, .benchmark-comment").blur(function(event){
						update_benchmark($(this));
					});
					
					$(".benchmark-fill-down").click(function(event){
						$(".benchmark-grade").each(function(){
							if($(this).val()==""){
							    $(this).val("M");
							update_benchmark($(this));
							}
							
						});
					});

			}
		);// end document-ready

function update_benchmark (me){

    $("table tr td input").each(function(){
        $(this).removeClass("ok").removeClass("bad");
    });
	let my_field = me.attr('name');
	let my_sibling = me.siblings("td input").val();
let my_comment = "";
let my_grade = "";
	if(my_field == "grade"){
	     my_comment = my_sibling;
	     my_grade = me.val();
    }else{
	     my_comment = me.val();
	     my_grade = my_sibling;
    }

	let myBenchmark = me.data("benchmark");
	let myStudent = me.data("student");
	let myQuarter = me.data('quarter');
	let form_data = {
			kBenchmark: myBenchmark,
			kStudent: myStudent,
     		quarter: myQuarter,
			grade: my_grade,
			comment: my_comment,
			ajax: 1
	};
	console.log(form_data);
	let myUrl = base_url + "student_benchmark/update";
	$.ajax({
		type: "post",
		url: myUrl,
		data: form_data,
		success: function(data){
		    me.addClass("ok").removeClass("bad");
		},
		error: function(data){
            me.addClass("bad").removeClass("ok");
		}
	});
	
}
