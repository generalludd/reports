$(document)
		.ready(function() {

			
			$('.show_benchmark_search').live('click', function(event){
				var myUrl = base_url + "index.php/benchmark/show_search";
				var form_data = {
						ajax: 1
				};

				$.ajax({
					url: myUrl,
					type: "POST",
					data: form_data,
					success: function(data){
					showPopup("Search for Benchmarks", data, 'auto');

				}
				
				});

				
			});
			
			
			$('.new_benchmark').live('click', function(event) {
				var myUrl = base_url + "index.php/benchmark/create";
				var form_data = {
						ajax: 1
				};
				$.ajax({
					url: myUrl,
					type: 'POST',
					data: form_data,
					success: function(data){
					showPopup("New Benchmark", data, "auto");
				}//end success function
				});//end ajax
				
				}// end function(event)
			);// end new_benchmark

				$('.edit_benchmark').live(
						'click',
						function(event) {
							var myBenchmark = this.id.split("_")[1];
							var form_data = {
									kBenchmark: myBenchmark,
									ajax: 1
							};
							var myUrl = base_url + "index.php/benchmark/edit";
							
							$.ajax({
								url: myUrl,
								type: 'POST',
								data: form_data,
								success: function(data){
								showPopup("Edit Benchmark", data, 'auto');
							} //end success function
							}); //end ajax

						}// end function(event)
				);// end edit_benchmark

				$('.duplicate_benchmark').live(
						'click',
						function(event) {
							var myBenchmark = this.id.split("_")[1];
							var form_data = {
									kBenchmark: myBenchmark,
									ajax: 1
							};
							var myUrl = base_url + "index.php/benchmark/duplicate";
							$.ajax({
								url: myUrl,
								type: 'POST',
								data: form_data,
								success: function(data){
								showPopup('Duplicated Benchmark', data, 'auto');
							}//end success function
							});//end ajax

						}// end function(event)
				);// end duplicate benchmark

				$('.delete_benchmark').live('click',function(event) {
					var myBenchmark = this.id.split("_")[1];
					var myUrl = base_url + "index.php/benchmark/delete";
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

				$('.list_current')
						.live(
								'click',
								function(event) {
									var myTerm = $('#term').val();
									var myYear = $('#year').val();
									var myTeach = $('#kTeach').val();
									var url = "index.php?target=benchmark&action_task=list&kTeach="
											+ myTeach
											+ "&term="
											+ myTerm
											+ "&year=" + myYear;
									document.location = url;
								}// end function(event)
						);// end click

				$('.list_all')
						.live(
								'click',
								function(event) {
									document.location = "index.php?target=benchmark&action_task=search";
								}// end function(event)
						);// end list_all.click
				
				
				$('.edit_student_benchmarks').live('click', function(event) {
					// saveNarrative();
						var myNarrative = this.id.split("_")[1];	
						
						var form_data = {
								kNarrative: myNarrative,
								ajax: 1
						};
						
						var myUrl = base_url + "index.php/benchmark/edit_for_student";
						$.ajax({
							type: "POST",
							url: myUrl,
							data: form_data,
							success: function(data){
								showPopup("Edit Benchmarks", data, "auto");
							}// end function(data)
						});// end ajax
					});// end edit_student_benchmarks.click
				
				
					$('.save_benchmark').live('click',
							function(event){
								document.forms['benchmark'].submit();
							}//end function(event)
					);//end click

					$('.view_narrative').live('click',
							function(event){
								var myNarrative=this.id.split("_")[1];
								document.location="index.php?target=narrative&action_task=view&kNarrative="+myNarrative;
							}	
					);//end click

					$('.save_student_benchmark').live('click',
						function(event){
							var myStudent=$('#kStudent').val();
							var myTeach=$('#kTeach').val();
							var myBenchmark=this.id.split("_")[1];
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
							
							var myUrl = base_url + "index.php/benchmark/update_for_student";
						
							$.ajax({
								type: "post",
								url: myUrl,
								data: form_data,
								success: function(data){
									$("#ssb_" + myBenchmark).hide();
									$("#save_" + myBenchmark).html(data).show();
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
