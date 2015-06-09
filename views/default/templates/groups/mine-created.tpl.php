
        <div id="content">
          <h1>Groups created by you</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="mycreatedgroups">
                                    
          <!-- START my-created-groups -->
          <div id="list"><ul><li><a href="group/{ID}">{name}</a> - {description}</li><li>{button} Created - {createdFriendly}</li></ul></div>
          <!-- END my-created-groups -->
                                </div>
                                 <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('mycreatedgroups').innerHTML.indexOf("ul") == -1)
                                        document.getElementById("mycreatedgroups").innerHTML='<div id="searchinfo">You haven\'t created any group yet</div>';
                                    });
                                </script>
        </div>
      </div>