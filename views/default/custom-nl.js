var RecaptchaOptions = {
    theme : 'clean'
 };

function routine(this1)
{
    document.getElementById('hide').style.display='inline';
    equalize();
}

function setOptions(chosen)
        {
            if(chosen=="professor")
            {
                document.getElementById("label_info1").innerHTML='Field of experience';
                document.getElementById("label_info2").innerHTML='Years of experience';
                document.getElementById("roll_no_value").disabled=true;
                document.getElementById("label_roll_no").innerHTML='';
                document.getElementById("roll_novalidate").innerHTML='';
                document.getElementById("info1validate").innerHTML='';
                document.getElementById("info2validate").innerHTML='';
                document.getElementById("roll_no_value").style.display='none';
            }
            if(chosen=="student")
            {
                document.getElementById("label_info1").innerHTML='Branch';
                document.getElementById("label_info2").innerHTML='Class';
                document.getElementById("roll_no_value").disabled=false;
                document.getElementById("label_roll_no").innerHTML='Roll Number';
                 document.getElementById("info1validate").innerHTML='';
                document.getElementById("info2validate").innerHTML='';
                document.getElementById("roll_no_value").style.display='inline';
            }
        }
        
 
        
        
      function equalize(){
		
                 var heightmain=$('#main').height();
                 var heightpage=$(window).height() - $('#headerbar').height();
                 var height=(heightmain>heightpage)?heightmain:heightpage;
     $('#sidepane, #rightside, #main').css('min-height',height);
		}
                

 
 /*$(window).resize(function(){F7F6CA
     var height= $(this).height() - $("#headerbar").height();
     $('#main, #sidepane, #rightside, #contentwrapper').css('min-height',height);
 });*/




$(document).ready(function(){
    $("input, select, textarea").not("input[type='submit'], button, a.button, input[type='button']").uniform();
$("input.disablepaste").bind('paste',function(e){e.preventDefault();});});

 function validate_fname()
 {
     var fname=document.register.fname;
     var letters=/^[a-zA-Z]+$/;
     if(fname.value.length==0)
	{
		document.getElementById("fnamevalidate").innerHTML="<div class='error'>Please enter your first name</div><br/>";
		equalize(); return false;
	}
	else if(!fname.value.match(letters))
	{
		document.getElementById("fnamevalidate").innerHTML="<div class='error'>First Name should contain only alphabets</div><br/>";
		equalize(); return false;
	}
        else
            {
                
                document.getElementById("fnamevalidate").innerHTML="";
                equalize(); return true;
            }
	
 }
 
 function validate_lname()
 {
     var lname=document.register.lname;
     var letters=/^[a-zA-Z]+$/;
      if(lname.value.length==0)
	{
		document.getElementById("lnamevalidate").innerHTML="<div class='error'>Please enter your last name</div><br/>";
		equalize(); return false;
	}
	else if(!lname.value.match(letters))
	{
            document.getElementById("lnamevalidate").innerHTML="<div class='error'>Last Name should contain only alphabets</div><br/>";
            equalize(); return false;
	}
	document.getElementById('lnamevalidate').innerHTML=''; return true;
 }
 
 function validate_username()
 {
     var username=document.register.username;
     var letters=/^[_a-zA-Z0-9]+$/;
     if(username.value.length==0)
	{
		document.getElementById("usernamevalidate").innerHTML="<div class='error'>Please enter an username</div><br/>";
		equalize(); return false;
	}
        else if(username.value.length>25)
        {
               document.getElementById("usernamevalidate").innerHTML="<div class='error'>Username can of maximum 25 characters</div><br/>";
               equalize(); return false;
        }
	else if(!username.value.match(letters))
	{
		document.getElementById("usernamevalidate").innerHTML="<div class='error'>Username can contain only alphabets, digits or underscore</div><br/>";
		equalize(); return false;
	}
        else{
            var usernam=document.register.username.value;
            var xmlhttp;
            if(window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                    {
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
               xmlhttp.onreadystatechange=function()
               {
                   if(xmlhttp.readyState==4 && xmlhttp.status==200)
                       {
                           $response=xmlhttp.responseText.split("\n");
                           if($response[0]!=1)
                           {
                               document.getElementById("usernamevalidate").innerHTML='<div class="error">This username is already in use, please select a different one</div><br/>';
                               return false;
                           }
                           else
                               {
                                   document.getElementById('usernamevalidate').innerHTML=''; return true;
                               }
                       }
               }
               xmlhttp.open('POST','/ajax/username.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("username="+usernam);
        }
	
 }
 
 function validate_mobile_no()
 {
     var mobile=document.register.mobile_no;
     if(mobile.value.length==0)
         {
              document.getElementById('mobile_novalidate').innerHTML='<div class="error">Please enter your mobile number</div><br/>';
             equalize(); return false;
         }
         else if(mobile.value.length!=10)
             {
              document.getElementById('mobile_novalidate').innerHTML='<div class="error">Please enter a 10 digit mobile number</div><br/>';
             equalize(); return false;   
             }
     else if(!mobile.value.match(/^[0-9]{10,10}$/))
         {
            document.getElementById('mobile_novalidate').innerHTML='<div class="error">Mobile number should contain only digits</div><br/>';
             equalize(); return false; 
         }  
         document.getElementById('mobile_novalidate').innerHTML='';
         return true;
 }
 
 function validate_bio()
 {
     var bio=document.register.bio;
     if(bio.value.length==0)
         {
              
                  document.getElementById('biovalidate').innerHTML='<div class="error">Please write a few lines descrbing yourself</div><br/>';
             equalize(); return false;
         }
         document.getElementById('biovalidate').innerHTML='';
         return true;
         
 }
 function validate_password()
 {
     var password=document.register.password;
     if(password.value.length==0)
         {
             document.getElementById('passwordvalidate').innerHTML='<div class="error">Please enter a password</div><br/>';
             equalize(); return false;
         }
      else if(password.value.length<8)
        {
                document.getElementById('passwordvalidate').innerHTML='<div class="error">Password should be atleast 8 characters long</div><br/>';
             equalize(); return false;
        }
      else if(!(password.value.match(/[a-z]+/)&&password.value.match(/[A-Z]+/)&&password.value.match(/[0-9]+/)))
          {
             document.getElementById('passwordvalidate').innerHTML='<div class="error">Password should contain atleast one uppercase letter, one lowercase letter and one digit</div><br/>';
             equalize(); return false;
          }
          
         document.getElementById('passwordvalidate').innerHTML=''; return true;
 }
 
 function validate_password_confirm()
 {
     var pw=document.register.password;
     if(pw.value.length!=0)
         {
             if(document.register.password_confirm.value!=document.register.password.value)
                 {
                    document.getElementById('password_confirmvalidate').innerHTML='<div class="error">The two passwords you entered did not match</div><br/>';
                    equalize(); return false; 
                 }
         }
         document.getElementById('password_confirmvalidate').innerHTML=''; return true;
 }
 
 function validate_email()
 {
     var email=document.register.email;
     if(email.value.length==0)
         {
              document.getElementById('emailvalidate').innerHTML='<div class="error">Please specify your email address</div><br/>';
              equalize(); return false; 
         }
      else if(!email.value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]*)*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})$/))   
          {
              document.getElementById('emailvalidate').innerHTML='<div class="error">The email address specified is invalid</div><br/>';
              equalize(); return false; 
          }
          else{
            var emailcheck=document.register.email.value;
            var xmlhttp;
            if(window.XMLHttpRequest)
                {
                    xmlhttp=new XMLHttpRequest();
                }
                else
                    {
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
               xmlhttp.onreadystatechange=function()
               {
                   if(xmlhttp.readyState==4 && xmlhttp.status==200)
                       {   
                           $response=xmlhttp.responseText.split("\n");
                           if($response[0]!=1)
                               {
                                 
                               console.log('no');
                               document.getElementById("emailvalidate").innerHTML='<div class="error">The email address specified is already in use</div><br/>';
                               return false;
                           }
                           else
                               {
                                   document.getElementById('emailvalidate').innerHTML=''; return true;
                               }
                       }
                      }
               xmlhttp.open('POST','/ajax/email.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("email="+emailcheck);
        }
}

function validate_email_confirm()
 {
     var email=document.register.email;
     if(email.value.length!=0)
         {
             if(document.register.email_confirm.value!=document.register.email.value)
                 {
                    document.getElementById('email_confirmvalidate').innerHTML='<div class="error">The two email addresses you entered did not match</div><br/>';
                    equalize(); return false; 
                 }
         }
         document.getElementById('email_confirmvalidate').innerHTML=''; return true;
 }
 
 function validate_college()
 {
     var college=document.register.college;
     if(college.value.length==0)
         {
             document.getElementById('collegevalidate').innerHTML='<div class="error">Please enter your college name</div><br/>';
              equalize(); return false;
         }
     else if(!college.value.match(/^[\._a-zA-Z0-9-]+$/))
         {
             document.getElementById('collegevalidate').innerHTML='<div class="error">Invalid college name</div><br/>';
              equalize(); return false;
         }
         document.getElementById('collegevalidate').innerHTML=''; return true;
 }
 
 function validate_type()
 {
     var type=document.register.type;
     if(type.value!='student' && type.value!='professor')
         {
             document.getElementById('typevalidate').innerHTML='<div class="error">Please specify whether you are a student or a professor</div><br/>';
              equalize(); return false;
         }
         document.getElementById('typevalidate').innerHTML=''; return true;
 }
 
 function validate_info1()
 {
     var info1=document.register.info1;
     var letters=/^[\._a-zA-Z0-9-]+$/;
     if(info1.value.length==0)
         {
             if(document.register.type.value=='student')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Please specify your branch of studies</div><br/>';
                 }
             else if(document.register.type.value=='professor')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Please specify your field of experience</div><br/>';
                 }
                 equalize(); return false;
         }
         else if(!info1.value.match(letters))
             {
                if(document.register.type.value=='student')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Invalid branch name</div><br/>';
                 }
             else if(document.register.type.value=='professor')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Invalid field name</div><br/>';
                 }
                 equalize(); return false; 
             }
             document.getElementById('info1validate').innerHTML=''; return true;
 }
 
 function validate_info2()
 {
     var info2=document.register.info2;
     if(info2.value.length==0)
         {
             if(document.register.type.value=='student')
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Please specify the class you are currently studying in</div><br/>';
                 }
             else if(document.register.type.value=='professor')
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Please specify your years of experience</div><br/>';
                 }
                 equalize(); return false;
         }
         else if(document.register.type.value=='student' &&  !info2.value.match(/^[\._a-zA-Z0-9- ]+$/))
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Invalid class name</div><br/>';
                     equalize(); return false;
                 }
             else if(document.register.type.value=='professor' && !info2.value.match(/^[0-9]+$/))
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Invalid years, please specify a whole number</div><br/>';
                     equalize(); return false;
                 }
                 document.getElementById('info2validate').innerHTML=''; return true;
 }
 
 function validate_roll_no()
 {
     var roll_no=document.register.roll_no_value;
     if(document.register.type.value=='student')
         {
             if(roll_no.value.length==0)
                 {
                    document.getElementById('roll_novalidate').innerHTML='<div class="error">Please specify your roll number</div><br/>'; 
                 equalize(); return false;
             }
               document.getElementById('roll_novalidate').innerHTML='';
     equalize();return true;  
         }
     
     document.getElementById('roll_novalidate').innerHTML='';
     equalize();return true;
 }
 
 function validate_gender()
 {
     var gender=document.register.gender;
     if(gender.value!='Male' && gender.value!='Female')
         {
              document.getElementById('gendervalidate').innerHTML='<div class="error">Please specify your gender</div><br/>';
                equalize(); return false;
         }
         document.getElementById('gendervalidate').innerHTML=''; return true;
 }
 
 function validate_dob()
 {
     var d=document.register.dob_day.value;
     var m=document.register.dob_month.value;
     var y=document.register.dob_year.value;
     if(d=='-1' || m=='-1' || y=='-1')
         {
             document.getElementById('dobvalidate').innerHTML='<div class="error">Please specify your complete date of birth</div><br/>';
                equalize(); return false;
         }
      else if(!validateDOB(d,m,y))
      {
          document.getElementById('dobvalidate').innerHTML='<div class="error">Invalid date of birth</div><br/>';
                equalize(); return false;
      }
      else if((new Date().getFullYear()-y)<13)
      {
          document.getElementById('dobvalidate').innerHTML='<div class="error">You must be atleast 13 years of age to use our site</div><br/>';
                equalize(); return false;
      }
      document.getElementById('dobvalidate').innerHTML=''; return true;
 }
 
 function validate_terms()
 {
     var terms=document.register.terms;
     if(!terms.checked)
         {
             document.getElementById('termsvalidate').innerHTML='<div class="error">You must accept our terms and conditions</div><br/>';
                equalize(); return false;
         }
         else
             {
             }
         document.getElementById('termsvalidate').innerHTML=''; return true;
 }
 
 function validateDOB(d,m,y)
 {
      if(m<1||m>12||d<0||d>31||y>new Date().getFullYear())
        {
            equalize(); return false;
        }
        else
        {
            if((m==4||m==6||m==9||m==11))
            {
                if(d<=30)
                    return true;
                else
                    {equalize(); return false;}
            }
            else if(m!=2)
            {
                if(d<=31)
                    return true;
                else
                    {equalize(); return false;}
            }
            else
            {
                if(y%4!=0)
                {
                    if(d<=28)
                        return true;
                    else
                        {equalize(); return false;}
                }
                else
                {
                    if(y%100!=0)
                    {
                        if(d<=29)
                            return true;
                        else
                            {equalize(); return false;}
                    }
                    else
                    {
                        if(y%400!=0)
                        {
                            if(d<=28)
                                return true;
                            else
                                {equalize(); return false;}
                        }
                        else
                        {
                            if(d<=29)
                                return true;
                            else
                                {equalize(); return false;}
                        }
                    }
                }
            }
        }
 }
 

 
 function validate_form()
{
	
	/*if(validate_fname())
	{*/   
            var a = new Array(14);
            a[0]=validate_fname();
            a[1]=validate_lname();
            if(document.register.username.value.length==0)
                {
                    document.getElementById("usernamevalidate").innerHTML="<div class='error'>Please enter an username</div><br/>";
		equalize(); a[2]=false;
                }
            else
                {
                    a[2]=true;
                }
            if(document.register.email.value.length==0)
                {
                    document.getElementById("emailvalidate").innerHTML="<div class='error'>Please enter an email</div><br/>";
		equalize(); a[3]=false;
                }
            else
                {
                    a[3]=true;
                }
            a[4]=validate_email_confirm();
            a[5]=validate_password();
            a[6]=validate_password_confirm();
            a[7]=validate_college();
            a[8]=validate_type();
            a[9]=validate_info1();
            a[10]=validate_info2();
            a[11]=validate_gender();
            a[12]=validate_dob();
            a[13]=validate_terms();
            a[14]=validate_roll_no();
            a[15]=validate_mobile_no();
            a[16]=validate_bio();
		/*if(validate_lname())
		{
			if(validate_username())
			{
				if(validate_password())
				{
					if(validate_password_confirm())
					{
						if(validate_email())
						{
							if(validate_email_confirm())
							{
                                                            if(validate_college())
                                                                {
                                                                    if(validate_type())
                                                                        {
                                                                            if(validate_info1())
                                                                                {
                                                                                    if(validate_info2())
                                                                                        {
                                                                                            if(validate_gender())
                                                                                                {
                                                                                                    if(validate_dob())
                                                                                                        {
                                                                                                            if(validate_terms())
                                                                                                                {
                                                                                                                    console.log('true');
                                                                                                                    return true;
                                                                                                                }
                                                                                                        }
                                                                                                }
                                                                                        }
                                                                                }
                                                                        }
                                                                }
								
							}
						}
					}
				}
			}
		}
	}*/
        equalize();
        
	var bool= a[0]&&a[1]&&a[2]&&a[3]&&a[4]&&a[5]&&a[6]&&a[7]&&a[8]&&a[9]&&a[10]&&a[11]&&a[12]&&a[13]&&a[14]&&a[15]&&a[16];
        
        return bool;
}
