
        <div id="content">
          <h1>Edit Group: {name}</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				<div id="createnewtopic"><a href="group/{group_ID}"><button><< Back</button></a></div><br/><br/>
          <form id="editgroup"action="groups/edit/{group_ID}" method="post">
          
          <label for="name"><b>Name</b></label><br/>
            <input type="text" name="name" value="{name}"/><br/><br/>
            <label><b>College</b></label><br/>
            <input type="text" name="college" value="{college}"/><br/><br/>
            <label><b>Type of group</b></label><br/>
                <select name="type" id="type">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
                </select><br/><br/>
            <label><b>Description</b></label><br/>
                <textarea name="description" class="mceEditor" style="width: 100%" rows="6">{description}</textarea><br/>
            <label><b>Invite friends</b></label><br/>
            <div id="friends">
                <!-- START invitees --><input class="case1" type="checkbox" name="invitees[]" value="{ID}" />{fname} {lname}<br/>
                <!-- END invitees -->
                <input type="checkbox" class="checkall1" >Invite all<br/></div><br/>
                <input type="submit" id="edit" name="edit"  
            value="Update group" />
 
          </form>
                                {script}
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