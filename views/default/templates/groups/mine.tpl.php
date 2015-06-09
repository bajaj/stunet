
        <div id="content">
          <h1>Your groups</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="mygroups">
                                    
          <!-- START my-groups -->
          <div id="list"><ul><li><a href="group/{ID}">{name}</a> - {description}</li><li>{button} Created - {createdFriendly}</li></ul></div>
          <!-- END my-groups -->
                                </div>
                                 <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('mygroups').innerHTML.indexOf("ul") == -1)
                                        document.getElementById("mygroups").innerHTML='<div id="searchinfo">You are not a member of any group yet</div>';
                                    });
                                </script>
        </div>
      </div>