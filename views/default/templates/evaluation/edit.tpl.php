<!-- START group_info -->
				<div id="content">
				<h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">Edit evaluation</div>
                                <div id="createnewtopic"><a href="group/{group_id}"><button><< Back To Group</button></a></div><br/><br/>
					<!-- START eval_info -->
					<form id="enterevaluation" action="evaluation/edit/{group_id}/{eval_id}" method="post">
					<!-- END group_info -->
                                        <label id="date">Last Edited on: {date}</label>
				<label for="name">Evaluation Name</label><br />
					<input type="text" id="name" name="name" value="{name}" /><br /><br />
					<label for="name">Description</label><br />
					<input type="text" id="name" name="description" value="{description}" /><br /><br />
					<label for="out_of_marks">Out of Marks</label><br />
					<input type="text" id="name" name="out_of_marks" value="{out_of_marks}" /><br /><br />
					
					<!-- END eval_info -->
					
					<label>Students</label><br/>
                                        <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Roll Number</th><th>Name</th><th>Marks</th><th>Remarks</th>
						</tr>
						
						<!-- START evaluation -->
						<tr>
							<td>{roll_no}</td><td><a href="profile/view/{user_id}">{fname} {lname}</a></td><td><input type="text" name="marks[{user_id}]" value="{marks}" /></td><td><input type="text"  name="remarks[{user_id}]" value="{remarks}" /></td>
						</tr>
						<!-- END evaluation -->
					</table>
					</div>
                                        <br/>
<input type="submit" name="" value="Update Evaluation"/>
</form>
				</div>
			
			</div>