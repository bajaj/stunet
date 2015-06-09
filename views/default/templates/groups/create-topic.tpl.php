 <div id="content">
                <ul>
                    <li><h1><a href="group/{group_ID}">{group_name}</a></h1></li>
          </ul>
             <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <div id="createnewtopic"><a href="group/{group_ID}"><button><< Back</button></a></div><br/><br/>
          <form id="topic" action="group/{group_ID}/create-topic" method="post">
              <div id="searchinfo">Create a new topic</div><br/>
          <label for="name">Title</label><br />
          <input type="text" id="name" name="name" value="" /><br /><br/>
          <label for="post">Message</label><br />
          <textarea id="post" name="post" rows="17" class="mceEditor" style="width: 100%"></textarea><br />
          <input type="submit" id="create" name="create"  value="Submit new topic" />
          </form>
        </div>
     
      </div>