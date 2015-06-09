<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<base href="{siteurl}" />
	<title></title> 
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" /> 
	<meta name="description" content="Dash Board - The Student's Network" /> 
	<meta name="keywords" content="dash,board,student,network,assignment,events" /> 
	

</head> 
 <body onLoad="routine(this);" onResize="equalize();" > 
     <script type="text/javascript" src="views/default/jquery.js"></script>
     <link rel="stylesheet" href="views/default/uniform.default.css" media="screen"/> 
	 
<meta http-equiv="refresh" content="60" />

	<script type="text/javascript" src="views/default/jquery.uniform.js"></script>
	<script type="text/javascript" src="views/default/custom.js"></script>
        <link rel="stylesheet" type="text/css" href="views/default/style.css" />
        <link rel="stylesheet" type="text/css" href="views/default/style2.css" />
<script type="text/javascript" src="views/default/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
theme : "advanced",
mode : "textareas",
editor_selector :"mceEditor",
plugins :"youtube,preview,paste,emotions,insertdatetime,fullscreen,print,spellchecker,wordcount,style,searchreplace",
 theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontsizeselect,|,sub,sup",
 theme_advanced_buttons2: "cut,copy,paste,pastetext,|,search,replace,|,undo,redo,|,link,unlink,image,youtube,code,emotions,|,outdent,indent",
               theme_advanced_buttons3: "removeformat,visualaid,|,insertdate,inserttime,preview,fullscreen,|,spellchecker,print,wordcount,|,styleprops,search,replace,|,bullist,numlist"
});


</script>

             <div id="hide">
	<div id="wrapper">
	<div id="headerbar">
			{userbar}
			</div>
            	<div id="main">
				<div id="sidepane">
			<ul id="menu">
				<li><a href="home"><img src="views/default/images/arrow.png"/> &nbsp;Dashboard</a></li>
				<li><a href="members/search"><img src="views/default/images/arrow.png"/> &nbsp;Search Members</a></li>
				<li><a><img src="views/default/images/arrow.png"/> &nbsp;Messages</a>
                                <ul class="submenu">
                                        <li><a href="messages/inbox"><img src="views/default/images/arrow.png"/> &nbsp;Inbox</a></li>
                                        <li><a href="messages/sent"><img src="views/default/images/arrow.png"/> &nbsp;Sent Items</a></li>
                                        <li><a href="messages/draft"><img src="views/default/images/arrow.png"/> &nbsp;Drafts</a></li>
                                        <li><a href="messages/create"><img src="views/default/images/arrow.png"/> &nbsp;Compose</a></li>
                                    </ul>
                                </li>
				<li><a href="relationships/pending"><img src="views/default/images/arrow.png"/> &nbsp;Requests</a></li>
				<li><a><img src="views/default/images/arrow.png"/> &nbsp;Events</a><ul class="submenu">
                                        <li><a href="event/create"><img src="views/default/images/arrow.png"/> &nbsp;Create An Event</a></li>
                                        <li><a href="event/"><img src="views/default/images/arrow.png"/> &nbsp;Upcoming Events In Network</a></li>
                                    </ul>
                                </li>
                                <li><a><img src="views/default/images/arrow.png"/> &nbsp;Groups</a><ul class="submenu">
                                        <li><a href="groups/my-groups"><img src="views/default/images/arrow.png"/> &nbsp;Your Groups</a></li>
                                        <li><a href="groups/my-created-groups"><img src="views/default/images/arrow.png"/> &nbsp;Your Created Groups</a></li>
                                        <li><a href="groups/create"><img src="views/default/images/arrow.png"/> &nbsp;Create a group</a></li>
                                        <li><a href="groups/search"><img src="views/default/images/arrow.png"/> &nbsp;Search groups</a></li>
                                    </ul>
                                </li>
                                <li><a><img src="views/default/images/arrow.png"/> &nbsp;Blogs</a><ul class="submenu">
                                        <li><a href="blogs/create"><img src="views/default/images/arrow.png"/> &nbsp;Create a blog</a></li>
                                        <li><a href="blogs/my-created-blogs"><img src="views/default/images/arrow.png"/> &nbsp;Your blogs</a></li>
                                        <li><a href="blogs/search-conn"><img src="views/default/images/arrow.png"/> &nbsp;Search blogs by your connection</a></li>
                                        <li><a href="blogs/search"><img src="views/default/images/arrow.png"/> &nbsp;Search public blogs</a></li>
                                    </ul>
                                </li>
                               <li><a href="pdm"><img src="views/default/images/arrow.png"/> &nbsp;Document Manager</a></li>
                                {evaluationlink}
			</ul>
		</div>				
				<div id="rightside">
                                    <br/>
                                <div id="calendarajax">
                                                 

				</div>
                                <script type="text/javascript">
            function calendar(this1)
            {
                 $span=this1.getElementsByTagName('span');
    $element=$span[0];
    document.getElementById('calendardata').innerHTML=$element.innerHTML;
    equalize();
            }
            </script>
                                 
<hr/>
 <br/>
					<table id="suggestionstext">
                                            
					</table>	
<script>
$(document).ready(function(){
   suggestions({logged_in_ID},'{logged_in_type}'); 
  
});
</script>
				</div>
		