<?php
class Relationships{
    
    private $registry;
    
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
        $this->user=$this->registry->getObject('authenticate')->getUser()->getUserID();
        $this->type=$this->registry->getObject('authenticate')->getUser()->getType();
    }
    
    public function getRelationships($usera,$userb,$accepted=0)
    {//Used to get pending relationships
        $sql="select r.ID as ID, pa.photo as usera_photo, pb.photo as userb_photo, pa.fname as usera_fname, pa.lname as usera_lname, pb.lname as userb_lname, pb.fname as userb_fname, if(pb.type='professor',if(pa.type='student','Pupil','Mate'),'Mate') as relationship         
        from relationships r, users ua, users ub, profile pa, profile pb where r.usera=ua.ID and r.userb=ub.ID and ua.ID=pa.ID and ub.ID=pb.ID and ua.banned=0 and ua.admin=0 and ua.active=1 and ua.deleted=0 and ub.banned=0 and ub.admin=0 and ub.active=1 and ub.deleted=0
        and r.accepted={$accepted}";
        if($usera!=0)
        {
           $sql.= " and r.usera={$usera}";
        }
        
       if($userb!=0)
       {
           $sql.= " and r.userb={$userb}";
       }
       $cache=$this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
    
    public function getByUser($id,$type='same',$rand=false,$limit=0)
    {//Used to get connections of logged in user
        if($type=='same')
        {
        $sql="select u.ID, p.photo, p.fname as users_fname, p.lname as users_lname from users u, relationships r, profile p 
        where p.ID=u.ID and r.accepted=1 and p.type='{$this->type}' and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";       
        }
        else if($type=='different')
        {
            $typeof=($this->type=='student')?'professor':'student';
           $sql="select u.ID, p.photo, p.fname as users_fname, p.lname as users_lname from users u, relationships r, profile p 
        where p.ID=u.ID and r.accepted=1 and p.type='{$typeof}' and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";
        }
        else if ($type=='both')
        {
            $sql="select u.ID, p.photo, p.fname as users_fname, p.lname as users_lname from users u, relationships r, profile p 
        where p.ID=u.ID and r.accepted=1 and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";
        }
        /*$sql="select t.plural_name as plural_name, u.ID, u.username as users_name from users u, relationships r, relationship_types t 
        where r.type=t.ID and r.accepted=1 and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera)";*/
        if($rand==true){
            $sql.=" order by rand()";
        }
        if($limit!=0){
            $sql.=" limit ".$limit;
        }
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
    
     public function getAll($id,$usertype,$type='same',$rand=false,$limit=0)
    {//Used to get connections of another user!
        
        if($type=='same')
        {
        $sql="select u.ID, p.photo, p.fname as users_fname, p.lname as users_lname from users u, relationships r, profile p 
        where p.ID=u.ID and r.accepted=1 and p.type='{$usertype}' and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";       
        }
        else if($type=='different')
        {
            $typeof=($usertype=='student')?'professor':'student';
           $sql="select u.ID, p.photo, p.fname as users_fname, p.lname as users_lname from users u, relationships r, profile p 
        where p.ID=u.ID and r.accepted=1 and p.type='{$typeof}' and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";
        }
        /*$sql="select t.plural_name as plural_name, u.ID, u.username as users_name from users u, relationships r, relationship_types t 
        where r.type=t.ID and r.accepted=1 and (r.usera={$id} or r.userb={$id}) and if(r.usera={$id},u.ID=r.userb,u.ID=r.usera)";*/
        if($rand==true){
            $sql.=" order by rand()";
        }
        if($limit!=0){
            $sql.=" limit ".$limit;
        }
        $cache=$this->registry->getObject('db')->cacheQuery($sql);
        return $cache;
    }
    
    public function getNetwork($user){
        $sql="select u.ID from users u, relationships r where r.accepted=1 and (r.usera={$user} or r.userb={$user})
        and if(r.usera={$user},u.ID=r.userb,u.ID=r.usera) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";
        $this->registry->getObject('db')->executeQuery($sql);
        $network=array();
        if($this->registry->getObject('db')->numRows()>0){
            while($result=$this->registry->getObject('db')->getRows()){
                $network[]=$result['ID'];
            }
        }
        
        return $network;
        
        }
        
    public function getIDsByUser($user,$cache=false)
    {
        $sql="select u.ID from users u, profile p, relationships r where u.ID=p.ID and r.accepted=1 and if(r.usera={$user},u.ID=r.userb,u.ID=r.usera) and (r.usera={$user} or r.userb={$user}) and u.banned=0 and u.admin=0 and u.active=1 and u.deleted=0";
            if($cache==false)
            {
                return $sql;
            }
            else
            {
                $cache=$this->registry->getObject('db')->cacheQuery($sql);
                return $cache;
            }
    }
}