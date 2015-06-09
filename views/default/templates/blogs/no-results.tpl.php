
				<div id="content">
					<h1>Search Blogs</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
					<form action="blogs/search-results" method="get">
					<label for="searchby">Search by: </label>
					<select name="searchby">
                                        <option value="title" selected="selected">Title</option>
					<option value="category">Category</option>
                                        <option value="content">Blog Content</option>
					<option value="creator">Creator</option>
					</select><br/><br/>
					<label for="searchname">Search for: </label><input type="text" id="searchname" name="searchname" value="" /><br /><br/>
					
					<label for="submit">&nbsp;</label>
					<input type="submit" id="search" value="Search" />
					</form>
				</div>
			
			</div>