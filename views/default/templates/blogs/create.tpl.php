
        <div id="content">
          <h1>Create a new blog</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				
          <form id="createblog" action="blogs/create" method="post">
            <label for="name"><b>Title</b></label><br/>
            <input type="text" name="title" placeholder="Specify a title for your blog"/><br/><br/>
            <label><b>Category</b></label><br/>
            <input type="text" name="category" value="" placeholder="Specify category, seperate categories by ','"/><br/><br/>
            <label><b>Type of blog</b></label><br/>
                <select name="type">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
                </select><br/><small>*Public blogs are visible to all members of the site</small><br/><small>*Private blogs are visible only to your connections</small><br/><br/>
            <label><b>Content</b></label><br/>
                <textarea name="content" rows="6" style="width: 100%"class="mceEditor"></textarea><br/><br/>
                <input type="checkbox" name="allowcomments" value="1">Allow comments<br/><br/>
                <input type="submit" id="create" name="create"  
            value="Create blog" />
          </form>
        </div>
      
      </div>