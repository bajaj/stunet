
  
  <!-- <div id="content"><h1>{p_fname} {p_lname}: Edit Profile</h1>
   <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
      <form action="profile/view/{p_ID}/edit" method="post" enctype="multipart/form-data">
         <label for="fname">First Name</label>
         <input type="text" id="fname" name="fname" value="{p_fname}"/><br /><br />
         
         <label for="lname">Last Name</label>
         <input type="text" id="lname" name="lname" value="{p_lname}"/><br /><br />
         
         <label for="profile_picture">Photograph</label>
         <input type="file" id="profile_picture" name="profile_picture" /> <br /><br />
         
         <label for="bio">Biography</label>
         <textarea id="bio" name="bio" cols="42" rows="12">{p_bio}</textarea><br /><br/>
         
         <label for="student_college">College</label>
         <input type="text" id="student_college" name="student_college" value="{p_student_college}" /><br /><br />
         
         <label for="student_field">Student Field</label>
         <input type="text" id="student_field" name="student_field" value="{p_student_field}" /><br /><br />
         
         <label for="student_dob">Date of Birth</label>
         <input type="text" id="student_dob" class="selectdate" name="student_dob" value="{p_student_dob}" /><br /><br />
         
         <label for="student_gender">Gender</label>
         <select id="student_gender" name="student_gender">
            <option value="">Please select</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
         </select>
         <br /><br />
        <label>&nbsp;</label> <input type="submit" id="" name="" value="Save profile" />
      </form>
     
   </div>-->
   
   
   <div id="content"><h1>{p_fname} {p_lname}: Edit Profile</h1>
   <br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
                                <form id="register" name="register" action="profile/view/edit" enctype="multipart/form-data" method="post" onsubmit="return validate_form({p_ID},'{p_type}');"> 
				<label for="fname">First Name</label> 
<input type="text" id="fname" name="fname" value="{p_fname}" onblur="return validate_fname();"/><br /><div id="fnamevalidate"></div><br />

<label for="lname">Last Name</label> 
<input type="text" id="lname" name="lname" value="{p_lname}" onblur="return validate_lname();"/><br /><div id="lnamevalidate"></div><br/>
 
<label for="username">Username</label> 
<input type="text" id="username" name="username" value="{p_username}" disabled/><br /><div id="usernamevalidate"><div class="error">You cannot change your username</div></div><br/>
 
<label for="password">New Password</label> 
<input type="password" id="password" class="disablepaste" name="password" onblur="return validate_password();"/><br /><div id="passwordvalidate"></div><br/><br/>
 
<label for="password_confirm">Confirm New Password</label>
<input type="password" id="password_confirm" class="disablepaste"  name="password_confirm" onblur="return validate_password_confirm();"/><br /><div id="password_confirmvalidate"></div><br/>
 
<label for="email">Email</label>
<input type="text" id="email" name="email" value="{p_email}" onblur="return validate_email({p_ID});"/><br /><div id="emailvalidate"></div><br/>

<label for="college">College name</label>
<input type="text" id="college" name="college" value="{p_college}" onblur="return validate_college();"/><br /><div id="collegevalidate"></div><br/>

<label for="type" disabled>Member type</label>
<input type="text" value="{p_type}" disabled/><br/><div id="type_validate"><div class="error">You cannot change your member type</div></div><br/>

<div id="student">
<label for="info1" id="label_info1">Branch</label>
<input type="text" name="info1" value="{p_info1}" onblur="return validate_info1();"/><br /><div id="info1validate"></div><br/><br/>

<label for="info2" id="label_info2">Class</label>
<input type="text" name="info2" value="{p_info2}" onblur="return validate_info2();"/><br /><div id="info2validate"></div><br/>
</div>

<label for="roll_no" id="label_roll_no">Roll Number</label>
<input type="text" name="roll_no" value="{p_roll_no}" id="roll_no_value" onblur="return validate_roll_no();"/><br /><div id="roll_novalidate"></div><br/>

<label for="mobile_no">Mobile Number</label>
<input type="text" name="mobile_no" value="{p_mobile_no}" id="mobile_no" onblur="return validate_mobile_no();"/><br /><div id="mobile_novalidate"></div><br/>

<label for="photo">Photograph</label>
<input type="file" name="photo" /> <br /><br />
         
<label for="bio">Biography</label>
<textarea id="bio" name="bio" rows="6" onblur="return validate_bio();">{p_bio}</textarea><br /><div id="biovalidate"></div><br/>

<label for="gender">Gender</label>
<select id="gender" name="gender">
<option value="Male">Male</option>
<option value="Female">Female</option>
</select><br /><div id="gendervalidate"></div><br/>

<label for="dob">Date of Birth</label> 
 
 <select name="dob_day" id="dob_day" class=""><option value="-1">Day:</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> 
 <select name="dob_month" id="dob_month" ><option value="-1">Month:</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
 <select name="dob_year" id="dob_year"()"><option value="-1">Year:</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option></select>
<br /><div id="dobvalidate"></div><br/>

<label for="password">Current Password</label> 
<input type="password"  class="disablepaste" name="current_password" value="" onblur="return validate_current_password({p_ID});"/><br /><div id="current_passwordvalidate"><div class="error">Enter your current password to update your profile</div></div><br/>
 <label for="submit">&nbsp;</label>
<input type="submit" id="process_registration" name="process_registration" value="Update Profile" />
                            {script}    </form>
 
      <!--<form name="register" action="profile/view/edit" method="post" enctype="multipart/form-data">
         <label for="fname">First Name</label>
         <input type="text" id="fname" name="fname" value="{p_fname}"/><br /><br />
         
         <label for="lname">Last Name</label>
         <input type="text" id="lname" name="lname" value="{p_lname}"/><br /><br />
         
         <label for="photo">Photograph</label>
         <input type="file" id="photo" name="photo"/> <br /><br />
         
         <label for="bio">Biography</label>
         <textarea id="bio" name="bio" cols="42" rows="12">{p_bio}</textarea><br /><br/>
         
         <label for="college">College</label>
         <input type="text" id="student_college" name="college" value="{p_college}" /><br /><br />
         
         <label for="info1">{p_info1_tag}</label>
         <input type="text" id="info1" name="info1" value="{p_info1}" /><br /><br />
         
         <label for="info2">{p_info2_tag}</label>
         <input type="text" id="info2" name="info2" value="{p_info2}" /><br /><br />
                                      
         <label for="dob">Date of Birth</label> 
 <select name="dob_day" id="dob_day" class=""><option value="-1">Day:</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> 
 <select name="dob_month" id="dob_month" ><option value="-1">Month:</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
 <select name="dob_year" id="dob_year"><option value="-1">Year:</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option></select>
<br /><br/>

         <label for="gender">Gender</label>
         <select id="gender" name="gender">
            <option value="Male" selected="selected">Male</option>
            <option value="Female">Female</option>
         </select>
         <br /><br />
        <label>&nbsp;</label> <input type="submit" id="" name="" value="Save profile" />
        {script}
      </form>-->
     
   </div>
</div>