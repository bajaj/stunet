<div id="content">
    <!-- START group_info -->
          <h1>{group_name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				
                                <div id="searchinfo">{typecaps} Time Table</div>
                                
					</br>
                                        <div id="createnewtopic"><a href="group/{group_id}"><button><< Back</button></a></div><br/><br/>
                                        <form id="idforclear" action="timetable/create/{type}/{group_id}" method="post">
                                            <!-- END group_info -->
                                        <div id="timetable">
					<table id="time">
						<tr id="header">
							<th>Timings</th><th>Mon</th><th>Tues</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
						</tr>
								
<tr>
<td><input type="text" name="row1[timing]" value="" /></td>	
<td><input type="text" name="row1[mon]" value="" /></td><td><input type="text" name="row1[tues]" value="" /></td><td><input type="text" name="row1[wed]" value="" /></td>
<td><input type="text" name="row1[thu]" value="" /></td><td><input type="text" name="row1[fri]" value="" /></td><td><input type="text" name="row1[sat]" value="" /></td>

</tr>
<tr>
<td><input type="text" name="row2[timing]" value="" /></td>	
<td><input type="text" name="row2[mon]" value="" /></td><td><input type="text" name="row2[tues]" value="" /></td><td><input type="text" name="row2[wed]" value="" /></td>
<td><input type="text" name="row2[thu]" value="" /></td><td><input type="text" name="row2[fri]" value="" /></td><td><input type="text" name="row2[sat]" value="" /></td>

</tr>
<tr>
<td><input type="text" name="row3[timing]" value="" /></td>	
<td><input type="text" name="row3[mon]" value="" /></td><td><input type="text" name="row3[tues]" value="" /></td><td><input type="text" name="row3[wed]" value="" /></td>
<td><input type="text" name="row3[thu]" value="" /></td><td><input type="text" name="row3[fri]" value="" /></td><td><input type="text" name="row3[sat]" value="" /></td>

</tr>
<tr>
<td><input type="text" name="row4[timing]" value="" /></td>	
<td><input type="text" name="row4[mon]" value="" /></td><td><input type="text" name="row4[tues]" value="" /></td><td><input type="text" name="row4[wed]" value="" /></td>
<td><input type="text" name="row4[thu]" value="" /></td><td><input type="text" name="row4[fri]" value="" /></td><td><input type="text" name="row4[sat]" value="" /></td>
</tr>
<tr>
<td><input type="text" name="row5[timing]" value="" /></td>	
<td><input type="text" name="row5[mon]" value="" /></td><td><input type="text" name="row5[tues]" value="" /></td><td><input type="text" name="row5[wed]" value="" /></td>
<td><input type="text" name="row5[thu]" value="" /></td><td><input type="text" name="row5[fri]" value="" /></td><td><input type="text" name="row5[sat]" value="" /></td>

</tr>
<tr>	
<td><input type="text" name="row6[timing]" value="" /></td>
<td><input type="text" name="row6[mon]" value="" /></td><td><input type="text" name="row6[tues]" value="" /></td><td><input type="text" name="row6[wed]" value="" /></td>
<td><input type="text" name="row6[thu]" value="" /></td><td><input type="text" name="row6[fri]" value="" /></td><td><input type="text" name="row6[sat]" value="" /></td>

</tr>
<tr>	
<td><input type="text" name="row7[timing]" value="" /></td>
<td><input type="text" name="row7[mon]" value="" /></td><td><input type="text" name="row7[tues]" value="" /></td><td><input type="text" name="row7[wed]" value="" /></td>
<td><input type="text" name="row7[thu]" value="" /></td><td><input type="text" name="row7[fri]" value="" /></td><td><input type="text" name="row7[sat]" value="" /></td>

</tr>
<tr>
<td><input type="text" name="row8[timing]" value="" /></td>	
<td><input type="text" name="row8[mon]" value="" /></td><td><input type="text" name="row8[tues]" value="" /></td><td><input type="text" name="row8[wed]" value="" /></td>
<td><input type="text" name="row8[thu]" value="" /></td><td><input type="text" name="row8[fri]" value="" /></td><td><input type="text" name="row8[sat]" value="" /></td>

</tr>
<tr>	
<td><input type="text" name="row9[timing]" value="" /></td>
<td><input type="text" name="row9[mon]" value="" /></td><td><input type="text" name="row9[tues]" value="" /></td><td><input type="text" name="row9[wed]" value="" /></td>
<td><input type="text" name="row9[thu]" value="" /></td><td><input type="text" name="row9[fri]" value="" /></td><td><input type="text" name="row9[sat]" value="" /></td>

</tr>
<tr>	
<td><input type="text" name="row10[timing]" value="" /></td>
<td><input type="text" name="row10[mon]" value="" /></td><td><input type="text" name="row10[tues]" value="" /></td><td><input type="text" name="row10[wed]" value="" /></td>
<td><input type="text" name="row10[thu]" value="" /></td><td><input type="text" name="row10[fri]" value="" /></td><td><input type="text" name="row10[sat]" value="" /></td>

</tr>

						
					</table>
</br>
<table id="time">
    <tr id="header"><th>Note:</th></tr>
    <tr><td><textarea class="uniform" name="note"></textarea>	</td></tr>				
					</table>			
</div>	
<br/>
<input type="submit" name="" value="Submit"/>&nbsp;<input type="button" class="not" name="reset_form" value="Clear Form" onClick="var clear=confirm('Are you sure you want the clear the timetable? You will lose all data');if(clear==true){clearForm(this.form);console.log('done');}" />
</form>
				</div>
			</div>