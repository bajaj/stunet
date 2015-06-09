
				<div id="content">
					<h1>View message</h1>
                                        <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="messages">
					<table>
						<tr>
							<th>Subject</th>
							<td>{inbox_subject}</td>
						</tr>
						<tr>
							<th>To</th>
							<td>{inbox_grpname}</td>
						</tr>
						<tr>
							<th>Sent</th>
							<td>{inbox_sentFriendlyTime}</td>
						</tr>
						<tr>
							<th>Message</th>
							<td>{inbox_message}</td>
						</tr>
					</table>
                                     <br/><br/>
                                    <label for="submit">&nbsp;</label><a href="messages/create/{inbox_id}"><button>Reply</button></a><a href="messages/delete/{inbox_id}"><button>Delete</button></a>
                                   
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