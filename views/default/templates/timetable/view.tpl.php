<div id="content">
    <!-- START group_info -->
          <h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				
                                <div id="searchinfo">{typecaps} Time Table</div>
                                <div id="createnewtopic"><a href="group/{group_id}"><button><< Back</button></a></div><br/><br/>
					<!-- END group_info -->
					</br>
					<div id="timetable">
					<table id="time">
						<tr id="header">
							<th>Timings</th><th>Mon</th><th>Tues</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
						</tr>

<!-- START time -->						
<tr>
<td>{timing}</td><td>{mon}</td><td>{tues}</td><td>{wed}</td><td>{thu}</td><td>{fri}</td><td>{Sat}</td>
</tr>
<!-- END time -->
</table>

</br>
<table id="timenote">
    <tr id="header"><th>Note:</th></tr>
<!-- START note -->	
<tr><td>{note}</td></tr>
<!-- END note -->		
</table>			
</div>	
 <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('timenote').getElementsByTagName('td')[0].innerHTML.length == 0)
                                        document.getElementById('timenote').getElementsByTagName('td')[0].innerHTML='--';
                                    });
                                </script>
								</div>	</div>