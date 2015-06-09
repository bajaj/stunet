	
       
        <div id="content">
          <h1>Blogs created by you</h1>
		  <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="mycreatedblogs">
                                    
          <!-- START my-created-blogs -->
          <div id="list"><ul><li><a href="blog/{ID}">{category}</a> - {title}</li><li>Created - {createdFriendly}</li></ul></div>
          <!-- END my-created-blogs -->
                                </div>
                               <script>
                                    $(document).ready(function(){
                                        if(document.getElementById('mycreatedblogs').innerHTML.indexOf("ul") == -1)
                                        document.getElementById("mycreatedblogs").innerHTML='<div id="searchinfo">You have made no blogs yet</div>';
                                    });
                                </script>
        </div>
      </div>