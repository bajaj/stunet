	
				<div id="content">
					<h1>Blog List</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">Search results for "{public_name}"</div>
                                        <div id="blogslist">
                                <!-- START blogs -->
                                <div id="blog">
                                    <div id="title"><a href="blog/{ID}">{title}</a></div>
                                  <div id="blogcreated"><div id="category"><b>Category</b>: '{category}'</div>
                  <div id="info"><img src="uploads/profilepics/small/{photo}" title="{fname} {lname}" alt="{fname} {lname}"/> &nbsp;<a href="profile/view/{creator}">{fname} {lname}</a>&nbsp; <span id="time">{createdFriendly}</span></div>
                                </div></div>
                                
                                  <!-- END blogs -->
                                </div>
                                <hr/> 
					<br/>
                                       <hr />
									  
									   
					<p>Viewing page {current_page} of {num_pages}</p>
					<p>{first} {previous} {next} {last}</p>
					<br/>
                                        <center><h2>Search for another blog?</h2><br/></center>
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