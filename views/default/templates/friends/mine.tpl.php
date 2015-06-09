
				<div id="content">
					<h1>Your connections</h1>
					<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="mine">
                                    <div id="same">
                                        <div id="searchinfo">Your Mates are ...</div><br/>
                                        <div id="member">
                                            <ul>   
					<!-- START sameconnections -->
					<li><div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{users_fname}{users_lname}" alt="{users_fname}{users_lname}"/><br/><a href="profile/view/{ID}">{users_fname} {users_lname}</a></div></li>
                                      <!-- END sameconnections -->
                                        </ul>
                                        </div>
                                    </div>
                                        <div id="different">
                                            <div id="searchinfo">Your {different} are ...</div><br/>
                                            <div id="member">
                                        <ul>
					<!-- START differentconnections -->
					<li><div id="profilepic"><img src="uploads/profilepics/small/{photo}" title="{users_fname}{users_lname}" alt="{users_fname}{users_lname}"/><br/><a href="profile/view/{ID}">{users_fname} {users_lname}</a></div></li>
                                      <!-- END differentconnections -->
                                        </ul>
					</div>
                                </div>
                                </div>
                                    <script>
                                    $(document).ready(function(){
                                        
                                        if(document.getElementById('same').innerHTML.indexOf("profilepic") == -1)
                                        document.getElementById("same").innerHTML='<div id="searchinfo">You have no Mates</div>';
                                     if(document.getElementById('different').innerHTML.indexOf("profilepic") == -1)
                                        document.getElementById("different").innerHTML='<div id="searchinfo">You have no {different}</div>';
                                    });
                                </script>
                                
                                
                                </div>
			</div>