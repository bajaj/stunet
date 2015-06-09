
        <div id="content">
          <h1>Edit Blog: {blog_title}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
            <form id="editblog" action="blogs/edit/{blog_ID}" method="post">
            <label for="name"><b>Title</b></label><br/>
            <input type="text" name="title" value="{blog_title}"/><br/><br/>
            <label><b>Category</b></label><br/>
            <input type="text" name="category" value="{blog_category}" /><br/><br/>
            <label><b>Type of blog</b></label><br/>
                <select name="type">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
                </select><br/><small>*Public blogs are visible to all members of the site</small><br/><small>*Private blogs are visible only to your connections</small><br/><br/>
            <label><b>Content</b></label><br/>
                <textarea name="content" class="mceEditor" style="width: 100%" rows="6">{blog_content}</textarea><br/><br/>
                <input type="checkbox" name="allowcomments" value="1" />Allow comments<br/><br/>
                <input type="submit" id="create" name="create"  value="Update blog" />
          </form>
                                {script}
        </div>
      
      </div>