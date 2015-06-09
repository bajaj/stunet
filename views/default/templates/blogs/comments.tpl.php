<div id="commentlist"><!-- START blogcomments -->
            <div class="commentmain" id="{ID}">
                <div class="commentcreated"><div id="info"><img src="uploads/profilepics/small/{creator_photo_comment}" title="{creatorFName_comment} {creatorLName_comment}" alt="{creatorFName_comment} {creatorLName_comment}"/> &nbsp;<a href="profile/view/{creator_ID_comment}">{creatorFName_comment} {creatorLName_comment}</a>&nbsp; <span id="time">{createdFriendly_comment}</span></div></div>
         <div class="commentbuttons">{edit_comment} {delete_comment}</div><div class="actualcomment">{comment}</div>
          </div>
    
          <!-- END blogcomments -->
</div><br/><br/>
          <center><h4>Comment on this blog</h4></center>
          <form id="commenttext" action="blog/{blog_ID}/comment/"  method="post">
          <textarea id="post" name="comment" class="mceEditor" style="width: 100%" rows="6"></textarea><br/>
          <center><input type="submit" id="np" name="np" value="Add Comment" /></center>
          </form>