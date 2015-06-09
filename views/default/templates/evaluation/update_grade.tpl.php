		
<!-- START group_info -->
				<div id="content">
				<h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">Edit grade</div>
                                <div id="createnewtopic"><a href="group/{group_id}"><button><< Back To Group</button></a></div><br/><br/>
				
					<!-- START grade -->
                                        <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Roll NO</th><th>Name</th>
						</tr>
						<tr><td>{roll_no}</td>
							<td><a href="profile/view/{user_id}" style="text-decoration: none">{stu_fname}&nbsp;{stu_lname}</a></td>
							
						</tr>
					</table>
                                        </div>
					<br/>
					<div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Date</th><th>Evaluation Name</th><th>Description</th><th>marks</th><th>Remarks</th><th>Out of Marks</th>
						</tr>
						
						<!-- START evaluation -->
						<tr>
							<td>{date}</td><td>{eval_name}</td><td>{description}</td><td>{marks}</td><td>{remarks}</td><td>{out_of_marks}</td>
						</tr>
						<!-- END evaluation -->
					</table>
                                            </div>
					
						
	<form action="evaluation/grade/{group_id}/{user_id}" method="post">
			<!-- END group_info -->
</br>

			<div id="evaluation">
					<table id="time">
						<tr id="header">
						<tr><th>Total Marks Obtained</th><td>{marks_scored}</td></tr>
						<tr><th>Total Marks</th><td>{total_marks}</td></tr>
<!-- END grade -->		
<!-- START percent -->				
						<tr><th>Percentage %</th><td>{percentage}</td></tr>
<!-- END percent -->					
<!-- START update_grade -->
						<tr><th>Subject</th><td><input type="text" name="subject" value="{subject}" /></td></tr>
						<tr><th>Grade</th><td><input type="text" name="grade" value="{secured}" /></td></tr>
                                                <!-- END update_grade -->
			</table>			
                        </div>		<br/>
<input type="submit" name="" value="Submit"/>
</form>
				</div>
			
			</div>