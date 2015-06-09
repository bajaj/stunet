
        <div id="content">
          <h1><a href="blog/{blog_ID}">{blog_title}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                 
          <br/>
          <center><h4>Edit your comment</h4></center>
          <form id="commenttext" action="blog/{blog_ID}/comment/{comment_ID}/edit"  method="post">
          <textarea id="post" class="mceEditor" style="width: 100%;" name="comment" rows="6">{comment_comment}</textarea><br/>
          <center><input type="submit" id="np" name="np" value="Update Comment" /></center>
          </form>
        </div>
     
      </div>