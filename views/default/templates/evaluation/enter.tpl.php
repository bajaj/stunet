<!-- START group_info -->
				<div id="content">
				<h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                
                                <div id="searchinfo">Enter an evaluation</div>
                                <div id="createnewtopic"><a href="group/{group_id}"><button><< Back To Group</button></a></div><br/><br/>
                                <div id="fulldiv">
					<form id="enterevaluation" action="evaluation/enter/{group_id}" method="post">
					<!-- END group_info -->
					
					<label for="name">Evaluation Name</label><br />
					<input type="text" id="name" name="name" value="" /><br /><br />
					<label for="name">Description</label><br />
					<input type="text" id="name" name="description" value="" /><br /><br />
					<label for="out_of_marks">Out of Marks</label><br />
					<input type="text" id="name" name="out_of_marks" value="" /><br /><br />
					
                                        <label>Students</label><br/>
                                        <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Roll Number</th><th>Name</th><th>Marks</th><th>Remarks</th>
						</tr>
						
						<!-- START evaluation -->
						<tr>
							<td>{roll_no}</td><td><a href="profile/view/{ID}" >{fname} {lname}</a></td><td><input type="text" name="marks[{ID}]" value="" /></td><td><input type="text"  name="remarks[{ID}]" value="" /></td>
						</tr>
						<!-- END evaluation -->
					</table>
                                        </div>
					<br/>
<input type="submit" name="" value="Submit"/>
</form>
				</div>
			 <script>
 $(document).ready(function(){
  if(document.getElementById('fulldiv').innerHTML.indexOf("td") == -1)
 document.getElementById("fulldiv").innerHTML='<div id="searchinfo">You cannot enter an evaluation, as there are no students in this group yet</div>';
  });
</script>
			</div></div>