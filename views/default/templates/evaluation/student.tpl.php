
				<div id="content">
				<h1>All your evaluations</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                          <div id="evaluationjavascript">     
					   <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Date</th><th>Evaluation Name</th><th>Description</th><th>Your Marks</th><th>Out of Marks</th><th>Remarks</th><th>Evaluated by</th>
						</tr>
						
						<!-- START evaluation -->
						<tr>
							<td>{date}</td><td>{eval_name}</td><td>{description}</td><td>{marks}</td><td>{out_of_marks}</td><td>{remarks}</td><td><a href="profile/view/{creator}" style="text-decoration: none">Prof {fname}&nbsp;{lname}</a></td>
						</tr>
						<!-- END evaluation -->
					</table>
					</div>
                                           </div>
					</br>
					
					
					
					<div id="gradesjavascript">
					   <div id="evaluation">
					   <div id="searchinfo">Your grades</div>
					<table id="time">
						<tr id="header">
							<th>Date</th><th>Subject</th><th>Grade</th><th>Evaluated by</th>
						</tr>
						
						<!-- START grade -->
						<tr>
							<td>{date}</td><td>{subject}</td><td>{secured}</td><td><a href="profile/view/{ID}" style="text-decoration: none">Prof {fname}&nbsp;{lname}</a></td>
						</tr>
						<!-- END grade -->
						
					</table>
					</div>
                                           </div>
					
 <script>
 $(document).ready(function(){
  if(document.getElementById('gradesjavascript').innerHTML.indexOf("<td>") == -1)
 document.getElementById("gradesjavascript").innerHTML='<div id="searchinfo">No grades have been entered for you yet</div>';
 
 
  if(document.getElementById('evaluationjavascript').innerHTML.indexOf("<td>") == -1)
 document.getElementById("evaluationjavascript").innerHTML='<div id="searchinfo">No evaluation has been entered for you yet</div>';
  });
</script>
					
 					

				</div>
			
			</div>