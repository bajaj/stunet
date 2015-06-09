<div id="content">

<h1>Create an event</h1>
<br/>
				<hr/><br/>
				<hr/><br/>
				<br/>

<form name="createeventform" action="event/create" method="post">
    <table id="event">
        <tr>
            <th>Name</th>
            <td><input type="text" name="name"/></td>
        </tr>

        <tr>
            <th>Type of event</th>
            <td>
                <select name="type" id="type">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
				<small>*Private events serve as a reminder and can only be seen by you</small>
				<small>*Public events serve can only be seen by your invited connections</small>
                </select>
            </td>
        </tr>
        
        <tr>
            <th>Date of Event</th>
            <td>
                <select name="event_day" id="event_day" class=""><option value="-1">Day:</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> 
                <select name="event_month" id="event_month" ><option value="-1">Month:</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> 
                <select name="event_year" id="event_year"()"><option value="-1">Year:</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select>
            </td>
        </tr>

        <tr>
            <th>Start Time</th>
            <td><input type="text" id="selecttime" name="start_time"/></td>
        </tr>

        <tr>
            <th>End Time</th>
            <td><input type="text" id="selecttime2" name="end_time"/></td>
        </tr>

        <tr>
            <th>Description</th>
            <td>
                <textarea name="description" class="uniform" cols="45" rows="6"></textarea>
            </td>
        </tr>

        <tr>
            <th>Invite Connections</th>
            <td id="friends">
                <!-- START invitees --><input class="case1" type="checkbox" name="invitees[]" value="{ID}" />{fname} {lname}<br/>
                <!-- END invitees -->
                <input type="checkbox" class="checkall1" >Invite all<br/>
            </td>
        </tr>
        
        <tr>
            <th>Invite Group</th>
            <td id="groups">
                <!-- START grp_invitees --><input class="case2"  type="checkbox" name="grp_invitees[]" value="{grp_ID}" />{grp_name}<br/>
                <!-- END grp_invitees -->
                    <input type="checkbox" class="checkall2" >Invite all<br/>
            </td>    
        </tr>
        
        <tr>
            <th></th>
            <td>
                <input type="submit" name="" value="create event"/>
            </td>
        </tr>
        
    </table>

<SCRIPT language="javascript">
$(function(){

// add multiple select / deselect functionality
$(".checkall1").click(function () {
$('.case1').attr('checked', this.checked);
});

// if all checkbox are selected, check the selectall checkbox
// and viceversa
$(".case1").click(function(){

if($(".case1").length == $(".case1:checked").length) {
$(".checkall1").attr("checked", "checked");
} else {
$(".checkall1").removeAttr("checked");
}

});

$(".checkall2").click(function () {
$('.case2').attr('checked', this.checked);
});

// if all checkbox are selected, check the selectall checkbox
// and viceversa
$(".case2").click(function(){

if($(".case2").length == $(".case2:checked").length) {
$(".checkall2").attr("checked", "checked");
} else {
$(".checkall2").removeAttr("checked");
}

});
});
</SCRIPT>
<script type="text/javascript">

$(function(){
$('#selecttime').timeEntry();});
$(function(){
$('#selecttime2').timeEntry();});
$(document).ready(function(){
if(document.getElementById('friends').innerHTML.indexOf("invitees[]") == -1)
document.getElementById("friends").innerHTML='<div id="searchinfo">You have no connections to invite</div><br/>';
if(document.getElementById('groups').innerHTML.indexOf("grp_invitees[]") == -1)
document.getElementById("groups").innerHTML='<div id="searchinfo">You have no groups to invite</div><br/>';
});
</script>

</form>

</div>

</div>








