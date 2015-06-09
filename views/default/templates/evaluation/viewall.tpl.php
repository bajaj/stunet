<!-- START group_info -->
				<div id="content">
				<h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">Edit evaluation</div>
                                <div id="createnewtopic"><a href="group/{group_id}"><button><< Back To Group</button></a></div><br/><br/>
				
				
					 <div id="searchinfo">Prepare grade</div>
					
                                         <div id="print"><a href="javascript:window.print()"><img src="views/default/images/print.jpg" width="50px" height="50px" /></a></div>
					</br>
					</br><br/>
					 <div id="evaluation">
					<table id="time">
						<tr id="header">
							<th>Roll NO</th><th>Name</th><th>Subject</th><th>Grade</th>
						</tr>
						
						<!-- START grade -->
						<tr>
							<td><a href="evaluation/editgrade/{group_id}/{ID}">{roll_no}</a></td><td>{fname}&nbsp;{lname}</a></td>
							<td>{subject}</td><td>{secured}</td>
				</tr>
						<!-- END group_info -->
						<!-- END grade -->
					</table>
			
				</div>
			
			</div>