			
				<div id="content">
					<h1>Groups List</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo">Search results for "{public_name}"</div>
                                        <div id="groupslist">
                                <!-- START groups -->
                                <div id="groups">
                                    <div id="profilepic">Group Creator<img src="uploads/profilepics/small/{creator_photo}" title="{creator_fname}{creator_lname}" alt="{creator_fname}{creator_lname}"/><a href="profile/view/{creator_ID}"><br/>{creator_fname} {creator_lname}</a></div>
                                    
                                    <div id="title"><center><strong>'{name}'</strong> - {description}</center></div>
                                    <br/><div id="button">{button}</div>
                                  
                                </div><br/>
                                  <!-- END groups -->
                                </div>
                                <hr/> 
					<br/>
                                       <hr />
									  
									   
					<p>Viewing page {current_page} of {num_pages}</p>
					<p>{first} {previous} {next} {last}</p>
					<br/>
                                        <center><h2>Search for another group?</h2><br/></center>
					<form action="groups/search-results" method="get">
					<label for="searchby">Search by: </label>
					<select name="searchby">
					<option value="college" selected="selected">College</option>
					<option value="name">Name</option>
                                        <option value="creator">Creator Name</option>
					<option value="description">Description</option>
					</select><br/><br/>
					<label for="searchname">Search for: </label><input type="text" id="searchname" name="searchname" value="" /><br /><br/>
					<label for="submit">&nbsp;</label>
					<input type="submit" id="search" value="Search" />
					</form>
				</div>
			
			</div>