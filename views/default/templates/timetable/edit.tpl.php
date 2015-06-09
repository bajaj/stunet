<div id="content">
    <!-- START group_info -->
          <h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				
                                <div id="searchinfo">{typecaps} Time Table</div>
                                        <div id="createnewtopic"><a href="timetable/view/{type}/{group_id}"><button><< Back</button></a></div><br/><br/>
                                        <form id="idforclear" action="timetable/edit/{type}/{group_id}" method="post">
                                            <!-- END group_info -->
                                        <div id="timetable">
					<table id="time">
						<tr id="header">
							<th>Timings</th><th>Mon</th><th>Tues</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
						</tr>
				

<!-- START time -->							
<tr>
<td><input type="text" name="row{row_no}[timing]" value="{timing}" /></td>	
<td><input type="text" name="row{row_no}[mon]" value="{mon}" /></td><td><input type="text" name="row{row_no}[tues]" value="{tues}" /></td><td><input type="text" name="row{row_no}[wed]" value="{wed}" /></td>
<td><input type="text" name="row{row_no}[thu]" value="{thu}" /></td><td><input type="text" name="row{row_no}[fri]" value="{fri}" /></td><td><input type="text" name="row{row_no}[sat]" value="{Sat}" /></td>
</tr>
<!-- END time -->
						
					</table>

</br>
<table id="time">
    <tr id="header"><th>Note:</th></tr>
<!-- START note -->	
<tr><td><textarea class="uniform" name="note">{note}</textarea>	</td></tr>
<!-- END note -->		
</table>			
</div>	
<br/>
<input type="submit" name="" value="Submit"/>&nbsp;<input type="button" class="not" name="reset_form" value="Clear Form" onClick="var clear=confirm('Are you sure you want the clear the timetable? You will lose all data');if(clear==true){clearForm(this.form);console.log('done');}" />
</form>
				</div>
			
			</div>