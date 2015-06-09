        <div id="content">
          <h1><a href="blog/{blog_ID}">{blog_title}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="blogcontent">{content}</div>
                                <div id="blogcreated"><div class="postscreator"><div id="time">{blog_createdFriendly}</div><hr/>
                  <div class="creator"><a href="profile/view/{blog_creator_ID}">{blog_creatorFName} {blog_creatorLName}</a><br/><img src="uploads/profilepics/small/{blog_creator_photo}"></img></div>
              </div>{blog_category}</div> 
                                
                                <!-- START posts -->
          <div class="posts" id="{ID}">
              <div class="postscreator"><div id="time">{createdFriendly_post}</div><hr/>
                  <div class="creator"><a href="profile/view/{creator_ID}">{creatorFName_post} {creatorLName_post}</a><br/><img src="uploads/profilepics/small/{creator_photo}"></img></div>
              </div>
              <div class="postscontent"><div class="postcontent">{post}</div><div class="postsbutton">{edit}{delete}</div></div>
          
          
          </div>
          <!-- END posts -->               
          <br/>
          <center><h4>Comment on this topic</h4></center>
          <form id="topic" action="blog/{blog_ID}/reply-to-topic/{topic_ID}"  method="post">
          <textarea id="post" name="post" rows="17">
          </textarea><br/>
          <center><input type="submit" id="np" name="np" value="Add Comment" /></center>
          </form>
        </div>
     
      </div>