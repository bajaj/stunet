                       
	<div id="content">              
	<h1>{event_name}</h1>
        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>      
        <table id="event">
            <tr><th>Description</th><td><p>{event_description}</p></td></tr>   
                                <tr><th>Date of event</th><td><p>{event_friendly_date}</p></td></tr>   
                                <tr><th>Start time</th><td><p>{event_start_time}</p></td></tr>   
                                <tr><th>End time</th><td><p>{event_end_time}</p></td></tr>              
	<tr><th><p>You are currently recorded as:</p></th>
	<td><form action="event/change-attendance/{event_ID}" method="post">            
	<select name="status">  
	<option value="" {unknown_select}>Unknown - Please select...</option> 
	<option value="attending" {attending_select}>Attending</option>
	<option value="not attending" {notattending_select}>Not attending</option>
	<option value="maybe" {maybeattending_select}>Maybe attending</option>
	</select> <br/><br/><input type="submit" name="" value="Update attendance" /></form></td></tr>
        
	             
	  
        <tr id="attending">
        <th>Attending</th><td>
	<ul> 
	<!-- START attending --><li><a href="profile/view/{ID}">{fname} {lname}</a></li>
	<!-- END attending -->
	</ul>
        </td>
        </tr>
        <tr id="invited">
	<th>Invited / Awaiting Reply</th><td>
	<ul> 
	<!-- START invited --><li><a href="profile/view/{ID}">{fname} {lname}</a></li> 
	<!-- END invited --> 
	</ul></td>
        </tr>
        <tr id="maybeattending">
        <th>Maybe attending</th><td>
	<ul>
	<!-- START maybeattending --><li><a href="profile/view/{ID}">{fname} {lname}</a></li> 
	<!-- END maybeattending --></ul> </td> 
        </tr>
        <tr id="notattending">
	<th>Not attending</th><td> 
	<ul>  
	<!-- START notattending --><li><a href="profile/view/{ID}">{fname} {lname}</a></li> 
	<!-- END notattending -->               
	</ul></td> 
        </tr>
         <tr><th></th><td><a href="event/edit/{event_ID}"><button>Edit Event</button></a></td></tr>    
         </table>
         <script>
                                    $(document).ready(function(){
                                        console.log(document.getElementById("attending").innerHTML.length);
                                         console.log(document.getElementById("invited").innerHTML.length)
                                        if(document.getElementById("attending").innerHTML.length<=100)
                                        document.getElementById("attending").innerHTML='';
                                    if(document.getElementById("invited").innerHTML.length<=100)
                                        document.getElementById("invited").innerHTML='';
                                    if(document.getElementById("maybeattending").innerHTML.length<=100)
                                        document.getElementById("maybeattending").innerHTML='';
                                    if(document.getElementById("notattending").innerHTML.length<=100)
                                        document.getElementById("notattending").innerHTML='';
                                    });
                                </script>
	</div>    
    </div>