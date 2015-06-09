<div id="main">
<div id="sidepane">
</div>	
<div id="rightside">
</div>
				
				<div id="content">
				<h1>Welcome to {sitename}</h1>
				<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>
				<p>Thank you for joining {sitename}. You are one step away from availing the services of {sitename}. 
			A SMS containing a confirmation code has been sent to the mobile number you registered with. Please check your mobile and enter the code below</p>
			<br/>
			{error}
			<form action="" method="post">
			<label for="verify_email"></label>
			<input type="text" name="verify_email" id="verify_email" value="{verify_email}"/>
			<input type="submit" id="confirm" name="confirm" value="Confirm" />
			
		<!--	 <input type="button" name="do" value="Resend E-mail" onclick="location.href='authenticate/resendemail'" />-->

				
				</form>
				
				</div>
			
			</div>