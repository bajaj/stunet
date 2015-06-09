  
        <div id="content">
		
          <h1><a href="group/{group_ID}">{group_name}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/><div id="groups">
                                    <div id="searchinfo">{group_description}</div>
		   <br/>
          
                                 <div id="createnewtopic"><a href="group/{group_ID}/create-topic"><button>+ Create new topic</button></a></div><br/><br/>
          <div id="topiclist">
          <table>
		  <col width="230">
		  <col width="100">
		  <col width="150">
		  <col width="50">
            <tr id="header">
              <th class="left">Topic</th><th>Creator</th> 
              <th>Created</th><th>Posts</th>
            </tr>
            <!-- START topics -->
            <tr>
              <td class="left"><a href="group/{group_ID}/view-topic/{ID}">{name}</a></td><td><a href="profile/view/{creator}">{creatorFName} {creatorLName}</a></td>
                <td>{createdFriendly}</td><td>{posts}</td>
            </tr>
            <!-- END topics -->
          </table></div><br/>
                                <div class="groupbuttons">{createclasstt}{createexamtt}{viewclasstt}{viewexamtt}{enterevaluation}{viewevaluation}{allgrade}{entergrade}{chat}</div>
                               
                                <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('topiclist').innerHTML.indexOf("td") == -1)
                                        document.getElementById("topiclist").innerHTML='<div id="searchinfo">No topics in this group, be the first to start one</div>';
                                    });
                                </script>
        </div>
      </div>
      </div>