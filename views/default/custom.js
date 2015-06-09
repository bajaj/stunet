function routine(this1)
{
    $('.submenu').hide();
    document.getElementById('hide').style.display='inline';
    equalize();
}



      function equalize(){
		 var heightright=$('#rightside').height();
                 var heightmain=$('#main').height();
                 heightmain=(heightmain>heightright)?heightmain:heightright;
                 var heightpage=$(window).height() - $('#headerbar').height();
                 var height=(heightmain>heightpage)?heightmain:heightpage;
                 var heightright=$('#rightside').height();
                 var height=(height>heightright)?height:heightright;
				 var height=(height>800)?height:800;
                 
     $('#sidepane, #rightside, #main').css('min-height',height);
		}

 
$(document).ready(function(){
    $("input, select,form#register textarea,textarea#message,textarea#sendie").not("input[type='submit'],input[type='button'], .not").uniform();
    changemonth(0,0);
    
    $('#menu > li').hover(function () { $(this).find("ul:first").fadeIn('normal'); },
                      function () { $(this).find("ul:first").fadeOut('normal'); }
);
    $('#menu > li').hover(function () { $(this).find("img:lt(1)").show('fast'); },
                      function () { $(this).find("img:lt(1)").hide('fast'); }
                      );
$('.submenu > li ').hover(function () { $(this).find("img").show('fast'); },
     function () { $(this).find("img").hide('fast'); }
);

});

function createRelationship(id, relationship)
{
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
                           if($response[0]=='request')
                           {
                               document.getElementById("create"+id+relationship).innerHTML=relationship+' request sent';
                               this.disabled = true;
                           }
                           else if($response[0]=='added')
                               {
                                   document.getElementById("create"+id+relationship).innerHTML=relationship+' added';
                                   this.disabled = true;
                               }
                           else if($response[0]=='approved')
                               {
                                   document.getElementById("create"+id+relationship).innerHTML='Already your '+relationship;
                                   this.disabled = true;
                               }
                           else
                               {
                                   document.getElementById("create"+id+relationship).innerHTML=relationship+' request has already been sent';
                                   this.disabled = true;
                               }
                       }
               }
              xmlhttp.open('POST','http://'+window.location.hostname+'/relationship/create',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("ID="+id+"&relationship="+relationship);
        }
		
function create1Relationship(id, relationship)
{
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
                           if($response[0]=='request')
                           {
                               document.getElementById("create1"+id+relationship).innerHTML=relationship+' request sent';
                               this.disabled = true;
                           }
                           else if($response[0]=='added')
                               {
                                   document.getElementById("create1"+id+relationship).innerHTML=relationship+' added';
                                   this.disabled = true;
                               }
                           else if($response[0]=='approved')
                               {
                                   document.getElementById("create1"+id+relationship).innerHTML='Already your '+relationship;
                                   this.disabled = true;
                               }
                           else
                               {
                                   document.getElementById("create1"+id+relationship).innerHTML=relationship+' request has already been sent';
                                   this.disabled = true;
                               }
                       }
               }
              xmlhttp.open('POST','http://'+window.location.hostname+'/relationship/create',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("ID="+id+"&relationship="+relationship);
        }
        
     function approveRelationship(id)
{
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
                           if($response[0]==1)
                           {
                               document.getElementById("approve"+id).innerHTML='<button disabled="disabled">Approved</button>';
                           }
                       }
               }
              xmlhttp.open('POST','http://'+window.location.hostname+'/relationship/approve',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("ID="+id);
        }
        
           function rejectRelationship(id)
{
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
                           if($response[0]==1)
                           {
                               document.getElementById("approve"+id).innerHTML='<button disabled="disabled">Rejected</button>';
                           }
                       }
               }
              xmlhttp.open('POST','http://'+window.location.hostname+'/relationship/reject',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("ID="+id);
        }
        
        function changemonth(m, y)
        {
            var xmlhttp,link;
            if(m==0 && y==0)
                link='http://'+window.location.hostname+'/calendar';
            else
                link='http://'+window.location.hostname+'/calendar?&month='+m+'&year='+y;
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
                            document.getElementById('calendarajax').innerHTML=xmlhttp.responseText;
                        }
                }
                xmlhttp.open('GET',link,true);
               xmlhttp.send();
        }
        
             function request(gid,myid)
{
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
                           if($response[0]=='yes')
                           {
                               document.getElementById("requestbutton"+gid+myid).innerHTML='<button disabled="disabled">Request Sent</button>';
                           }
                       }
               }
              xmlhttp.open('POST','http://'+window.location.hostname+'/group/'+gid+'/request',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("mid="+myid);
        }
        
        function setOptions(chosen)
        {
            if(chosen=="professor" || chosen=='Professor')
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
            if(chosen=="student" || chosen=='Student')
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
                               document.getElementById("usernamevalidate").innerHTML='<div class="error">This username is already in use</div><br/>';
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
     var password1=document.register.password;
     if(password1.value.length==0) {document.getElementById('passwordvalidate').innerHTML=''; return true;}
     if(password1.value.length<8)
        {
                document.getElementById('passwordvalidate').innerHTML='<div class="error">Password should be atleast 8 characters long</div><br/>';
             equalize(); return false;
        }
      else if(!(password1.value.match(/[a-z]+/)&&password1.value.match(/[A-Z]+/)&&password1.value.match(/[0-9]+/)))
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
 
 function validate_email(id)
 {
     var email=document.register.email;
     if(email.value.length==0)
         {
              document.getElementById('emailvalidate').innerHTML='<div class="error">Please specify your email address</div><br/>';
              equalize(); return false; 
         }
      else if(!email.value.match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*@[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-])*(\.[a-z]{2,4})$/))   
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
               xmlhttp.open('POST','/ajax/emailupdate.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("email="+emailcheck+"&id="+id);
        }
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

 function validate_info1()
 {
     var info1=document.register.info1;
     var letters=/^[\._a-zA-Z0-9-]+$/;
     if(info1.value.length==0)
         {
             if(type=='student')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Please specify your branch of studies</div><br/>';
                 }
             else if(type=='professor')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Please specify your field of experience</div><br/>';
                 }
                 equalize(); return false;
         }
         else if(!info1.value.match(letters))
             {
                if(type=='student')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Invalid branch name</div><br/>';
                 }
             else if(type=='professor')
                 {
                     document.getElementById('info1validate').innerHTML='<div class="error">Invalid field name</div><br/>';
                 }
                 equalize(); return false; 
             }
             document.getElementById('info1validate').innerHTML=''; return true;
 }
 
 function validate_info2(type)
 {
     var info2=document.register.info2;
     if(info2.value.length==0)
         {
             if(type=='student')
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Please specify the class you are currently studying in</div><br/>';
                 }
             else if(type=='professor')
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Please specify your years of experience</div><br/>';
                 }
                 equalize(); return false;
         }
         else if(type=='student' &&  !info2.value.match(/^[\._a-zA-Z0-9- ]+$/))
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Invalid class name</div><br/>';
                     equalize(); return false;
                 }
             else if(type=='professor' && !info2.value.match(/^[0-9]+$/))
                 {
                     document.getElementById('info2validate').innerHTML='<div class="error">Invalid years, please specify a whole number</div><br/>';
                     equalize(); return false;
                 }
                 document.getElementById('info2validate').innerHTML=''; return true;
 }
 
 function validate_roll_no(type)
 {
     var roll_no=document.register.roll_no_value;
     if(type=='student')
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
 
function validate_current_password(id)
{
     var password=document.register.current_password;
     if(password.value.length==0)
         {
             document.getElementById('current_passwordvalidate').innerHTML='<div class="error">Please enter your current password</div><br/>';
             equalize(); return false;
         }
      else if(password.value.length<8)
        {
                document.getElementById('current_passwordvalidate').innerHTML='<div class="error">Incorrect password</div><br/>';
             equalize(); return false;
        }
      else if(!(password.value.match(/[a-z]+/)&&password.value.match(/[A-Z]+/)&&password.value.match(/[0-9]+/)))
          {
             document.getElementById('current_passwordvalidate').innerHTML='<div class="error">Incorrect password</div><br/>';
             equalize(); return false;
          }
     var currentpw=document.register.current_password.value;
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
                                 
                               console.log(xmlhttp.responseText);
                               document.getElementById("current_passwordvalidate").innerHTML='<div class="error">Incorrect password</div><br/>';
                               return false;
                           }
                           else
                               {
                                   document.getElementById('current_passwordvalidate').innerHTML=''; return true;
                               }
                       }
                      }
               xmlhttp.open('POST','/ajax/pwcheck.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("pwcheck="+currentpw+"&id="+id);
        }

 
 function validate_form(id,type)
{
	if(type=='Professor') type='professor';
        if(type=='Student') type='student';
	/*if(validate_fname())
	{*/   
            var a = new Array(14);
            a[0]=validate_fname();
            a[1]=validate_lname();
           a[2]=true;
            if(document.register.email.value.length==0)
                {
                    document.getElementById("emailvalidate").innerHTML="<div class='error'>Please enter an email</div><br/>";
		equalize(); a[3]=false;
                }
            else
                {
                    a[3]=true;
                }
                a[4]=true;
                var pw=0;
                var pwc=0;
            if(typeof document.register.password.value!='undefined')
            {
                pw=document.register.password.value.length;
                pwc=document.register.password_confirm.value.length;
            }
            
            if(pw==pwc && pw==0)
               {
                   a[5]=true;
                   a[6]=true;
               }
            else
                {
                    a[5]=validate_password();
                    a[6]=validate_password_confirm();
                }
            a[7]=validate_college();
            a[8]=true;
            a[9]=validate_info1();
            a[10]=validate_info2();
            a[11]=validate_gender();
            a[12]=validate_dob();
            a[13]=true;
            a[14]=validate_roll_no();
            a[15]=validate_mobile_no();
            a[16]=validate_bio();
if(document.register.current_password.value.length==0)
                {
                    document.getElementById("current_passwordvalidate").innerHTML="<div class='error'>Please enter your current password</div><br/>";
		equalize(); a[17]=false;
                }
            else
                {
                    a[17]=true;
                }
        equalize();
        console.log(a);
	var bool= a[0]&&a[1]&&a[2]&&a[3]&&a[4]&&a[5]&&a[6]&&a[7]&&a[8]&&a[9]&&a[10]&&a[11]&&a[12]&&a[13]&&a[14]&&a[15]&&a[16]&&a[17];
        console.log(bool);
        return bool;
}

function makedefault(gid, uid)
{
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
                           if($response[0]=='yes')
                           {
                               document.getElementById("makedefault"+gid).innerHTML='Done';
                           }
                           else if($response[0]=='no')
                           {
                               document.getElementById("makedefault"+gid).innerHTML='An error occured';
                           }
                           else if($response[0]=='already')
                           {
                               document.getElementById("makedefault"+gid).innerHTML='Already your default group';    
                           }
                       }
               }
              xmlhttp.open('POST','/ajax/makedefault.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("gid="+gid+"&uid="+uid);
        }
 
 function suggestions(id,type)
 {
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
                           $response=xmlhttp.responseText;
                           if($response!='no')
                           {
                               document.getElementById("suggestionstext").innerHTML=$response;
                           }
                           else if($response[0]=='no')
                           {
                               document.getElementById("suggestionstext").innerHTML='';
                           }
                       }
               }
              xmlhttp.open('POST','/ajax/suggestions.php',true);
               xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
               xmlhttp.send("id="+id+"&type="+type);
                equalize();
 }