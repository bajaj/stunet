
        <div id="content">
            <h1><a href="group/{group_ID}">{group_name}</a></h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
                                <div id="searchinfo"><a href="group/{group_ID}/view-topic/{topic_ID}">{topic_name}</a></div>     
          <br/>
          <center><h4>Edit your comment</h4></center>
          <form action="group/{group_ID}/view-topic/{topic_ID}/{ID}/edit"  method="post">
          <textarea id="post" name="post" rows="17" class="mceEditor" style="width: 100%">{post}</textarea><br/>
          <center><input type="submit" id="savecomment" name="savecomment" value="Save comment" /></center>
          </form>
        </div>
     
      </div>