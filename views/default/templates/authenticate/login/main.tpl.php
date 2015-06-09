<div id="main">
<div id="sidepane">
</div>	
<div id="rightside">
</div>
				
				<div id="content">
				<h1>Login to {sitename}</h1>
				<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				<center>{error}</center>
                                
				<form action="authenticate/login" method="post"> 
 
<label for="sn_auth_user">Username</label>
<input type="text" id="sn_auth_user" name="sn_auth_user"/><a href="{siteurl}authenticate/username" id="forgotusername"> Forgot username?</a>
<br/><br/>
			
				
<label for="register_password">Password</label>
<input type="password" id="sn_auth_pass" name="sn_auth_pass"/><a href="{siteurl}authenticate/password" id="forgotpassword">Forgot password?</a>
<br/><br/>
 
 <input type="hidden" id="referer" name="referer" value="{referer}"/>

<label for="submit">&nbsp;</label><input type="submit" id="login" name="login" value="Log In" /> 
</form> 
	
				</div>
			
			</div>