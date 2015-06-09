		
				
				<div id="content">
					<h1>Members List</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="searchinfo"><p>Search results for "{public_name}"</p></div>
                                        <div id="memberslist">
                                <!-- START members -->
                                <div id="member">
                                    <div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{fname}{lname}" alt="{fname}{lname}"/><a href="profile/view/{ID}"><br/>{fname} {lname}</a></div>
                                    <div id="info"><p>{description}</p></div>
                                    <br/><div id="button">{button}</div>
                                </div>
                                  <!-- END members -->
                                </div>
                                <hr/> 
					<br/>
                                       <hr />
									  
									   
					<p>Viewing page {current_page} of {num_pages}</p>
					<p>{first} {previous} {next} {last}</p>
					<br/>
                                        <center><h2>Search for another member?</h2><br/></center>
					<form action="members/search-results" method="get">
					<label for="searchby">Search by: </label>
					<select name="searchby">
					<option value="name" selected="selected">Name</option>
					<option value="email">Email</option>
					<option value="college">College</option>
					<option value="field">Field/Branch</option>
					<option value="class">Class/Years of experience</option>
					</select><br/><br/>
					<label for="searchname">Search for: </label><input type="text" id="searchname" name="searchname" value="" /><br /><br/>
					
					<label for="submit">&nbsp;</label>
					<input type="submit" id="search" value="Search" />
					</form>
				</div>
			
			</div>