
        <div id="content">
          <h1><a href="blog/{blog_ID}">{blog_title}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="viewblog">
                                <div id="blogcontent">{blog_content}</div>
                                <div id="blogcreated"><div id="category"><b>Category</b>: '{blog_category}'</div>
                  <div id="info"><img src="uploads/profilepics/small/{blog_creator_photo}" title="{blog_creatorFName} {blog_creatorLName}" alt="{blog_creatorFName} {blog_creatorLName}"/> &nbsp;<a href="profile/view/{blog_creator_ID}">{blog_creatorFName} {blog_creatorLName}</a>&nbsp; <span id="time">{blog_createdFriendly}</span></div>
                                </div></div>
                                <div id="blogeditbuttons">{blog_edit} {blog_delete}</div>
                                          
          <br/>
               
          {comments}
          <script>
              $('.deletecomment').click(
          function()
      {
          return confirm('Are you sure you want to delete this comment?');
      });
       $('.deleteblog').click(
          function()
      {
          return confirm('Are you sure you want to delete this blog?');
      });
          </script>
                  </div>
     </div>