<!--<p>Tell your network what you are up to</p><!--onkeydown="if (event.keyCode == 13 && !event.shiftKey) document.getElementById('postmessage').click()"-->
<form action="profile/statuses/{profile_ID}" method="post" enctype="multipart/form-data">
<center><textarea  name="content" rows="5" cols="65" placeholder="What are you upto, {profile_fname} {profile_lname} ?"></textarea>
<br /><br/>
<input type="radio" name="status_type" id="status_checker_update"  
  class="status_checker" value="update"  />Update
<input type="radio" name="status_type" id="status_checker_video"  
  class="status_checker" value="video"  />Video
<input type="radio" name="status_type" id="status_checker_image"  
  class="status_checker" value="image"  />Image
<input type="radio" name="status_type" id="status_checker_link"  
  class="status_checker" value="link"  />Link
<br />
<div class="video_input  extra_field">
<br/>
<label for="video_url" class="">YouTube URL</label>
<input type="text" id="" name="video_url" class="" /><br />
</div>
<div class="image_input  extra_field">
<br/>
    <label for="image_file" class="">Upload image</label>
<input type="file" id="" name="image_file" class="" /><br />
</div>
<div class="link_input  extra_field">
<br/>
<label for="link_url" class="">Link</label>
<input type="text" id="" name="link_url" class="" /><br /><br />
<label for="link_description" class="">Description</label>
<input type="text" id="" name="link_description" class="" /><br />
</div>
<br/>
&nbsp;<input type="submit" id="updatestatus" name="updatestatus"
value="Update" /><!--style="position: absolute; left: -9999px"-->
</center>
</form>
<br/><br/>
<script>
$(function(){
    $('.extra_field').hide();//hide all divs intially
    $("input[name='status_type']").change(function(){//on change of the radio buttons!
        $('.extra_field').hide();//hide all divs again
        $('.'+$("input[name='status_type']:checked").val()+'_input').show();//show the div corresponding to the selected radio button
    })
});
</script>