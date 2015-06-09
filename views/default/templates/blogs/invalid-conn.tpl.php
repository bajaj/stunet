
				<div id="content">
					<h1>Search Blogs Of Your Connections</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">No such blog found</div>
                                        <br/>
                                        <hr/>
                                        <br/>
                                         <center><h2>Search for another blog?</h2><br/></center>
                                 
					<form action="blogs/search-conn-results" method="get">
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