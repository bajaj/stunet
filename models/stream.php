<?php

class Stream{
    private $registry;
    private $empty=true;
    private $IDs=array();
    private $stream=array();
    
    public function __construct(Registry $registry){
        $this->registry=$registry;
    }
    
    public function buildStream($user,$offset=0)
    {
        $network=array();
        require_once(FRAMEWORK_PATH.'models/relationships.php');
        $relationships=new Relationships($this->registry);
        $network=$relationships->getNetwork($user);//Get the ID of all users the logged in user is connected to
        $network[]=0;
        $network=implode(',',$network);
        
        $sql="select s.*, t.type_name, t.type_reference,UNIX_TIMESTAMP(s.posted) as timestamp, p.fname as poster_fname, p.lname as poster_lname,
            r.fname as profile_fname, r.lname as profile_lname from profile p, profile r, statuses s, status_types t where s.type=t.ID and
             p.ID = s.poster and r.ID=s.profile and (r.ID={$user} or p.ID={$user} or r.ID in ({$network}) or p.ID in ({$network})) 
             order by s.ID desc limit {$offset},20";
             $this->registry->getObject('db')->executeQuery($sql);
             if($this->registry->getObject('db')->numRows()>0){
                 $this->empty=false;
                 while($row=$this->registry->getObject('db')->getRows()){
                     $row['friendly_time']=$this->getFriendlyTime($row['timestamp']);
                     $this->IDs[]=$row['ID'];
                     $this->stream[]=$row;
                 }
                }
    }
    
    public function getFriendlyTime($time){
        $current=time();
        if($current<($time+60)){
            return $current-$time." seconds ago";
        }
        elseif($current<($time+120)){
            return "just over a minute ago";
        }
        elseif($current<($time+(60*60))){
            return round(($current-$time)/60)." minutes ago";
        }
        elseif($current<($time+(60*120))){
            return "just over an hour ago";
        }
        elseif($current<($time+(60*60*24))){
            return round(($current-$time)/(60*60))." hours ago";
        }
        else{
            return "at " . date( 'h:ia \o\n l \t\h\e jS \o\f M',$time);
        }
    }
    
    public function getIDs(){
        return $this->IDs;
    }
    
    public function isEmpty(){
        return $this->empty;
    }
    
    public function getStream(){
        return $this->stream;
    }
}
?>