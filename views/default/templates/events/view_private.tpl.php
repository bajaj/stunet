                        
            
	<div id="content">              
	<h1>{event_name}</h1>
        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>      
        <div id="searchinfo"><small>This is a private event and serves as a reminder. It can be seen only by you</small></div><br/>
                                <table id="event">
            <tr><th>Description</th><td><p>{event_description}</p></td></tr>   
                                <tr><th>Date of event</th><td><p>{event_friendly_date}</p></td></tr>   
                                <tr><th>Start time</th><td><p>{event_start_time}</p></td></tr>   
                                <tr><th>End time</th><td><p>{event_end_time}</p></td></tr>  
 <tr><th></th><td><a href="event/edit/{event_ID}"><button>Edit Event</button></a></td></tr>    								
         </table>
                                
         <script>
                                    $(document).ready(function(){
                                         if(document.getElementById('attending').innerHTML.indexOf("<li>") == -1)
                                         document.getElementById("attending").innerHTML='';
                                        if(document.getElementById('maybeattending').innerHTML.indexOf("<li>") == -1)
                                         document.getElementById("maybeattending").innerHTML='';
                                      if(document.getElementById('notattending').innerHTML.indexOf("<li>") == -1)
                                         document.getElementById("notattending").innerHTML='';
                                      if(document.getElementById('invited').innerHTML.indexOf("<li>") == -1)
                                         document.getElementById("invited").innerHTML='';
                                    });
                                </script>
	</div>    
    </div>