			
				
				<script type="text/javascript">
				
				function subm(f,act)
				{
				document.mess.action=act;
				f.submit();
				}
				
				</script>
				<div id="content">
					<h1>Compose message</h1>
                                        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="messages">
					<form action="" method="post" target="" name="mess">
					<label for="recipient">To</label>
					<select id="recipient" name="recipient">
					<option value="0">Select Member</option>
						
						<!-- START recipients -->
						<option value="{ID}" {opt}>{users_fname} {users_lname}</option>
						<!-- END recipients -->
						<option value="1" {opt}>Admin</option>
					</select><br /><br/>
					
					<div id="messagegroups">
					<label for="group">To Groups</label><br/>
					<!-- START grp_invitees --><label>&nbsp;</label><input class="case2"  type="checkbox" name="grp_invitees[]" value="{grp_ID}" />{grp_name}<br/>
                <!-- END grp_invitees --><br/><br/>
				</div>
					
					 <script>
 $(document).ready(function(){
  if(document.getElementById('messagegroups').innerHTML.indexOf("class=") == -1)
 document.getElementById("messagegroups").innerHTML='<div id="searchinfo">You have no groups to message</div>';
  });
</script>
				
					<label for="subject">Subject:</label>
					<input type="text" id="subject" name="subject" value="{subject}" /><br /><br/>
					<label for="message">Message:</label>
					<textarea id="message" name="message" value="{message}" cols="42" rows="12"></textarea><br /><br/>
                                        <label for="submit">&nbsp;</label>
					<input type="submit" id="create" onclick="subm(this.form,'messages/create');" name="create" value="Send message" />
					<input type="submit" id="draft" onclick="subm(this.form,'messages/draft');" name="draft" value="save as Draft" />
					</form>
					
				</div>
                              <br/><br/><hr/><br/>
                                <div class="messages">
                                <ul>
                                    <li>
<div class="email_img">
 <a href="messages/inbox"><img src="views/default/images/inbox.jpg" alt="inbox" width="80" height="50"></a>
 <a href="messages/inbox">Inbox</a>
</div>
</li>
<li>
<div class="email_img">
 <a href="messages/sent"><img src="views/default/images/sent.jpg" alt="sent" width="80" height="50"></a>
 <a href="messages/sent">Sent Items</a>
</div>
    </li>
<li>
<div class="email_img">
 <a href="messages/draft"><img src="views/default/images/draft.png" alt="draft" width="80" height="50"></a>
 <a href="messages/draft">Drafts</a>
</div>
</li>
<li>
<div class="email_img">
 <a href="messages/create"><img src="views/default/images/compose.png" alt="compose" width="80" height="50"></a>
  <a href="messages/create">Compose</a>
</div>
</li>
</ul>  
                                    </div>
                                </div>
			</div>