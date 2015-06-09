					<!-- START group_info -->
				
				 <div id="content">
		
          <h1><a href="group/{group_id}">{group_name}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
					<div id="searchinfo">All Evaluations for this group</div>
                                        <div id="createnewtopic"><a href="group/{group_id}"><button><< Back To Group</button></a></div><br/><br/>
                                        <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Evaluation Name</th><th>Description</th><th>Out of Marks</th><th>Date</th>
						</tr>
						
						<!-- START evaluation -->
						<tr>
							<td><a href="evaluation/edit/{group_id}/{eval_id}">{name}</td><td>{description}</td><td>{out_of_marks}</td><td>{date}</td>
						</tr>
						<!-- END evaluation -->
					</table>

						

  					
</div>
                                        <br/><div class="left"><a href="evaluation/enter/{group_id}"><button>Enter an Evaluation</button></a></div>
<script><!-- END group_info --> 
 $(document).ready(function(){
  if(document.getElementById('evaluation').innerHTML.indexOf("td") == -1)
 document.getElementById("evaluation").innerHTML='<div id="searchinfo">No evaluation has been submitted yet</div>';
  });
</script>
				</div>
			
			</div>