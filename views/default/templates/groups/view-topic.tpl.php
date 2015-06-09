
       
        <div id="content">
          <h1><a href="group/{group_ID}">{group_name}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo"><a href="group/{group_ID}/view-topic/{topic_ID}">{topic_name}</a></div>
                                
                                <div id="createnewtopic"><a href="group/{group_ID}"><button><< Back</button></a></div><br/><br/>
                                <!-- START posts -->
          <div class="posts" id="{ID}">
              <div class="postscreator" id="postscreatorjs"><div id="time">{createdFriendly_post}</div>
                  <div class="creator"><a href="profile/view/{creator_ID}">{creatorFName_post} {creatorLName_post}</a><br/><img src="uploads/profilepics/small/{creator_photo}"></img></div>
              </div>
              <div class="postscontent" id="postscontentjs"><div class="postcontent">{post}</div><div class="postsbutton">{edit} {delete}</div></div>
          
          
          </div>
          <!-- END posts -->               
          <br/>
          <center><h4>Comment on this topic</h4></center>
          <form id="topic" action="group/{group_ID}/reply-to-topic/{topic_ID}"  method="post">
          <textarea id="post" name="post" class="mceEditor" style="width: 100%" rows="6"></textarea><br/>
          <center><input type="submit" id="np" name="np" value="Add Comment" /></center>
          </form>
        </div>

      </div>