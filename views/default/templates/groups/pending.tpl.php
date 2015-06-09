
        <div id="content">
          <h1>Pending requests for the Group: {name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="createnewtopic"><a href="group/{group_ID}"><button><< Back</button></a></div><br/><br/>
	<div id="pendinggrouprequests">
                                <!-- START pendinggrouprequests -->
                                <div id="member">
                                    <div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{fname}{lname}" alt="{fname}{lname}"/><a href="profile/view/{ID}">{fname} {lname}</a></div>
                                    <div id="info"><p><strong>{description}</strong></p></div>
                                    <div id="button"><a href="group/{group_ID}/approve/{ID}"><button>Approve</button></a> <a href="group/{group_ID}/reject/{ID}"><button>Reject</button></a></div>
                                   
                                </div><br/><br/><br/>
                                  <!-- END pendinggrouprequests -->
                                   
                                </div>
                                <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('pendinggrouprequests').innerHTML.indexOf("member") == -1)
                                        document.getElementById("pendinggrouprequests").innerHTML='<div id="searchinfo">No pending requests for this group</div>';
                                    });
                                </script>
        </div>
      
      </div>