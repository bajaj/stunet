
        <div id="content">
          <h1>Invite connections to the Group: {name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="createnewtopic"><a href="group/{group_ID}"><button><< Back</button></a></div><br/><br/>
	<div id="friends">			
          <form id="invitegroup" action="group/{group_ID}/invite" method="post">
          
              <label for="invitees"><b>Invite connections</b></label><br/><br/>
              
                <!-- START invitees --><input class="case1" type="checkbox" name="invitees[]" value="{ID}" />{fname} {lname}<br/>
                <!-- END invitees -->
                <input type="checkbox" class="checkall1" >Invite all<br/><br/>
        
                <input type="submit" id="create" name="create"  
            value="Invite" />
          </form>
 </div>
<script type="text/javascript">
$(function(){

// add multiple select / deselect functionality
$(".checkall1").click(function () {
$('.case1').attr('checked', this.checked);
});
});
$(document).ready(function(){
if(document.getElementById('friends').innerHTML.indexOf("invitees[]") == -1)
document.getElementById("friends").innerHTML='<div id="searchinfo">You have no connections to invite</div><br/>';
});
</script>
        </div>
      
      </div>