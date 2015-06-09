					
				<div id="content">
					<h1>Your Drafts</h1>
                                        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="messages">
					<table id="messages1">
						<tr id="header">
							<th>To</th>
							<th>Subject</th>
							<th>Saved On</th>
						</tr>
							<!-- START messages -->
						<tr class="read">
							<td><a href="messages/draft/view/{ID}">{recipient_fname} {recipient_lname}</a></td>
							<td><a href="messages/draft/view/{ID}">{subject}</a></td>
							<td><a href="messages/draft/view/{ID}">{sent_friendly}</a></td>
						</tr>
						<!-- END messages -->
						
						<!-- START empty_messages -->
						<tr class="read">
							<td><a href="messages/draft/view/{ID}"></a></td>
							<td><a href="messages/draft/view/{ID}">{subject}</a></td>
							<td><a href="messages/draft/view/{ID}">{sent_friendly}</a></td>
						</tr>
						<!-- END empty_messages -->
						
						<!-- START grp_messages -->
						<tr class="read">
							<td><a href="messages/view/{ID}">{group_name}</a></td>
							<td><a href="messages/view/{ID}">{subject}</a></td>
							<td><a href="messages/view/{ID}">{sent_friendly}</a></td>
						</tr>
						<!-- END grp_messages -->
					</table>
				</div>
                                <script>
                                      $(document).ready(function(){
                                        if(document.getElementById('messages').innerHTML.indexOf("<a href=") == -1)
                                        document.getElementById("messages").innerHTML='<div id="searchinfo">You have no messages in your drafts</div>';
                                    });  
                            </script>
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