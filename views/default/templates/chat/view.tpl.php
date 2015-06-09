<link rel="stylesheet" type="text/css" href="controllers/chat/main.css"/>

<script type="text/javascript" src="controllers/chat/chat.js"></script>
<script type="text/javascript" src="controllers/chat/settings.js"></script>
<script type="text/javascript">
    	var chat = new Chat('{grpid}.txt');
    	chat.init();
    	chat.getUsers('{grpid}','{username}');
    	var name = '{username}';
    </script>


    <div id="page-wrap"> 
    

        
    	
            <h2>{grpname}</h2>
                     
            <div id="chat-wrap">
                <div id="chat-area"></div>
            </div>
            
            <div id="userlist">
			Online Users
			
			<!-- START userlist -->
			<br/>{fullname}
			<!-- END userlist -->
			</div>
			
                
                <form id="send-message-area" action="">
                    <textarea id="sendie" maxlength='100'></textarea>
                </form>
            
        </div>
        
    </div>
        

