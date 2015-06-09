
				
				<div id="content">
					<h1>Pending connections</h1>
						<br/>
						<hr/><br/>
						<hr/><br/>
                                                <div id="pending">
					<!-- START pending -->
                                         <div id="member">
                                    <div id="profilepic"><img src="uploads/profilepics/small/{usera_photo}" title="{usera_fname}{usera_lname}" alt="{usera_fname}{usera_lname}"/><br/><a href="profile/view/{ID}">{usera_fname} {usera_lname}</a></div>
                                    <div id="info"><p> wants to be your {relationship}</p></div><br/>
                                    <div id="button"><div id="approve{ID}"><button onClick="approveRelationship({ID})">Approve</button> or <button onClick="rejectRelationship({ID})">Reject</button></div></div>
                                  </div>
					<!-- END pending -->
                                                </div>
                                        <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('pending').innerHTML.indexOf("member") == -1)
                                        document.getElementById("pending").innerHTML='<div id="searchinfo">You have no pending connections</div>';
                                    });
                                </script>
					
				</div>
			
			</div>