  <div id="content">
        
        <h1>{p_fname} {p_lname}</h1>
		<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <table id="profile">
      <tr>
         <th><img src="uploads/profilepics/small/{p_photo}" width="120" height="150"/></th>
         <td><p>{p_bio}</p><br/></td>
      </tr>                              
      <tr>
         <th>Full Name</th>
         <td>{p_fname} {p_lname}<br/></td>
      </tr>
      <tr>
          
          <tr>
         <th>Username</th>
         <td>{p_username}<br/></td>
      </tr>
      
      {email_entry}   
          
          <tr>
         <th>College</th>
         <td>{p_college}<br/></td>
      </tr>
      
      <tr>
          <th>Member Type</th>
         <td>{p_type}<br/></td>
      </tr>
      
      <tr>
         <th>{p_info1_tag}</th>
         <td>{p_info1}<br/></td>
      </tr>
      
	  <tr>
         <th>{p_info2_tag}</th>
         <td>{p_info2}<br/></td>
      </tr>
      
      {roll_no_entry}
         
      {mobile_no_entry}
      
      <tr>
          
      <tr>
         <th>Gender</th>
         <td>{p_gender}<br/></td>
      </tr>
      
      <tr>
         <th>Date of Birth</th>
         <td>{p_dob_friendly}<br/></td>
      </tr>
      
      
      {edit}
   </table>
   <br/><br/>
					
					<div id="myprofile">
					<ul>
					<li><a href="relationships/all/{profile_ID}">{profile_connections}</a></li>
					<li>{blogs}</li>
					<ul>
					</div>

    </div>
    
</div>