
				<div id="content">
					<h1>Connections of {connecting_name}</h1>
					<br/>
						<hr/><br/>
						<hr/><br/>
					
					
					
					     <div id="mine">
                                    <div id="same">
                                        <div id="searchinfo">{connecting_name}'s Mates are ...</div><br/>
                                        <div id="member">
                                         <ul>   
					<!-- START allsame -->
					<li><div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{users_fname}{users_lname}" alt="{users_fname}{users_lname}"/><br/><a href="profile/view/{ID}">{users_fname} {users_lname}</a></div></li>
                                      <!-- END allsame -->
                                        </ul>
                                        </div>
                                    </div>
                                        <div id="different">
                                            <div id="searchinfo">{connecting_name}'s {different} are ...</div><br/>
                                            <div id="member">
                                        <ul>
					<!-- START alldifferent -->
					<li><div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{users_fname}{users_lname}" alt="{users_fname}{users_lname}"/><br/><a href="profile/view/{ID}">{users_fname} {users_lname}</a></div></li>
                                      <!-- END alldifferent -->
                                        </ul>
                                            </div>
					</div>
                                </div>
                                               <script>
                                    $(document).ready(function(){
                                        
                                        if(document.getElementById('same').innerHTML.indexOf("profilepic") == -1)
                                        document.getElementById("same").innerHTML='<div id="searchinfo">{connecting_name} has no Mates</div>';
                                     if(document.getElementById('different').innerHTML.indexOf("profilepic") == -1)
                                        document.getElementById("different").innerHTML='<div id="searchinfo">{connecting name} has no {different}</div>';
                                    });
                                </script>
				</div>
			
			</div>