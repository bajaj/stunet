
				<div id="content">
					<h1>Upcoming events in your network</h1>
                                        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="events">
					
					<!-- START events -->
                                        <div id="list"><ul><li><a href="event/view/{ID}">{name}</a></li><li>{friendly_date}</li></ul></div>
					<!-- END events -->
					
				</div>
                                
			<script>
			$(document).ready(function(){
			console.log(document.getElementById('events').innerHTML.indexOf('list'));
			if(document.getElementById('events').innerHTML.indexOf("list") == -1)
			{ 
			document.getElementById("events").innerHTML=='<div id="searchinfo">You have no upcoming events in your network</div><br/>';}
			});
			</script>
			</div>
			</div>