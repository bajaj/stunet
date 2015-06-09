  <div id="content">
        
        <h1>Banned User</h1>
		<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                {error}
                    <div id="topiclist">            <table>
								
								
      <tr id="header"><th>Full Name</th><th>College</th><th>Action</th>
		
		<!-- START banneduser -->
		
		<tr><td><a href="profile/view/{ID}/">{fname}&nbsp{lname}</a></td><td>{college}</td><td><a href="admin/unbanuser/{ID}">Remove Ban</a></td>
		
		<!-- END banneduser -->
	

	  </table></div>
	  
 <script>
 $(document).ready(function(){
  if(document.getElementById('topiclist').innerHTML.indexOf("<td>") == -1)
 document.getElementById("topiclist").innerHTML='<div id="searchinfo">There are no banned users</div>';
  });
</script>
   <br/><br/>
					
					

    </div>
    
