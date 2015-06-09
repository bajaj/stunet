
        <div id="content">
          <h1>Create a new group</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				
          <form id="creategroup" action="groups/create" method="post">
            <label for="name"><b>Name</b></label><br/>
            <input type="text" name="name"/><br/><br/>
            <label><b>College</b></label><br/>
            <input type="text" name="college" value=""/><br/><br/>
            <label><b>Type of group</b></label><br/>
                <select name="type">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
                </select><br/><br/>
            <label><b>Description</b></label><br/>
                <textarea name="description" class="uniform" rows="6"></textarea><br/>
            <label><b>Invite Connections</b></label><br/>
            <div id="friends">
                <!-- START invitees --><input class="case1" type="checkbox" name="invitees[]" value="{ID}" />{fname} {lname}<br/>
                <!-- END invitees -->
                <input type="checkbox" class="checkall1" >Invite all<br/></div><br/>
                <input type="submit" id="create" name="create"  
            value="Create group" />
  
          </form>
<script type="text/javascript">
$(function(){

// add multiple select / deselect functionality
$(".checkall1").click(function () {
$('.case1').attr('checked', this.checked);
});
});
$(document).ready(function(){
if(document.getElementById('friends').innerHTML.indexOf("invitees[]") == -1)
document.getElementById("friends").innerHTML='<div id="searchinfo" style="float:left;">You have no connections to invite</div><br/>';
});
</script>
        </div>
      
      </div>