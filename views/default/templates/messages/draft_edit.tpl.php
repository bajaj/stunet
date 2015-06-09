		
				<script type="text/javascript">
				
				function subm(f,act)
				{
				document.mess.action=act;
				f.submit();
				}
				
				</script>
				<div id="content">
					<h1>Edit and Forward</h1>
                                        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="messages">
						<form action="" method="post" target="" name="mess">
					<label for="recipient">To:</label>
					<select id="recipient" name="recipient">
						<!-- START recipients -->
						<option value="{ID}" {opt}>{users_fname} {users_lname}</option>
						<!-- END recipients -->
						<option value="1">Admin</option>
					</select><br /><br />
					<label for="subject">Subject:</label>
					<input type="text" id="subject" name="subject" value="{subject}" /><br /><br />
					<label for="message">Message:</label>
					<textarea id="message"  name="message" cols="42" rows="12">{message}</textarea><br /><br />
					<label for="submit">&nbsp;</label>
                                        <input type="hidden" name="msgtype" value="draft"/>
					<input type="submit" id="create" onclick="subm(this.form,'messages/create');" name="create" value="Send message" />
					<input type="submit" id="draft" onclick="subm(this.form,'messages/draft/save/{delete_id}');" name="draft" value="save as Draft" />
                                        <input type="submit" id="draft" onclick="subm(this.form,'messages/delete/{delete_id}');" name="draft" value="delete" />
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
                                </div>
			</div>