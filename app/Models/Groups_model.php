<?php

namespace App\Models;

use CodeIgniter\Model;

class Groups_model extends Model
{
  protected $db;
  public $status = 'error';
  public $message = 'Error processing requested operation.';
  public $role = 0;
  public $branch = 0;

  public function __construct()
    {
        parent::__construct();
        $builder = \Config\Database::connect();
        $session = session();
        $this->role = $session->get('role');
        $this->branch = $session->get('branch');
    }

    public function fetchmygroups($email, $page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_groups');
      $builder->select('tbl_groups.*');
      $builder->join('tbl_group_members','tbl_group_members.groupid=tbl_groups.id');
      $builder->where('tbl_group_members.email',$email);
      $builder->where('tbl_group_members.status',0);
      $builder->orderby('id','desc');
      if($page!=0){
          $builder->limit(20,$page * 20);
      }else{
        $builder->limit(20);
      }
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $row) {
        $row->members = $this->getGroupMembersCount($row->id);
        $row->ismember = 0;
      }
      return $result;
    }

    public function get_my_total_groups($email){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_group_members');
      $builder->select("COUNT(*) as num");
      $builder->where('tbl_group_members.email',$email);
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

    public function fetch_items($email, $page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_groups');
      $builder->select('tbl_groups.*');
      $builder->orderby('id','desc');
      if($page!=0){
          $builder->limit(20,$page * 20);
      }else{
        $builder->limit(20);
      }
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $row) {
        $row->members = $this->getGroupMembersCount($row->id);
        $row->ismember = empty($this->checkMemberGroupExists($email, $row->id))?1:0;
      }
      return $result;
    }

    public function get_total_items(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_groups');
      $builder->select("COUNT(*) as num");
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

    public function getTotalItems(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_groups');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }


   function groupsListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_groups');
       $builder->select('tbl_groups.*');
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $builder->orderBy('date','DESC');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->branchname = $this->getBranchName($res->branch);
         $res->count = $this->getGroupMembersCount($res->id);
       }
       return $result;
   }

   public function getGroupMembersCount($groupid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
     $builder->select("COUNT(*) as num");
     $builder->where('groupid', $groupid);
     $query = $builder->get();
     $result = $query->getRow(0);
     if(isset($result)) return $result->num;
     return 0;
  }

   function getBranchName($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_branches');
       $builder->select('tbl_branches.name');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if($row){
         return $row->name;
       }else{
         return "---";
       }
   }

   function addNewGroup($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_groups');
     $builder->insert($info);
     $this->status = 'ok';
     $this->message = 'Group created successfully';
     return $this->db->insertID();
   }


   function editGroup($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_groups');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'Group edited successfully';
   }


   function getGroupInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_groups');
       $builder->select('tbl_groups.*');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function deleteGroup($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_groups');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Group deleted successfully.';
   }

   function deleteGroupMembers($listid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
     $builder->where('groupid', $listid);
     $builder->delete();
   }

   function removeFromGroup($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Member removed from this group successfully.';
   }

   function editMemberStatus($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
     $builder->where('id', $id);
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'Group Member Status edited successfully';
   }

   function addNewGroupMember($info)
   {
     if(empty($this->checkMemberGroupExists($info['email'], $info['groupid']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_group_members');
       $builder->insert($info);
     }
   }

   function getGroupMemberInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
       $builder->select('tbl_group_members.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function checkMemberGroupExists($email, $groupid)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_group_members');
       $builder->select("id");
       $builder->where("email", $email);
       $builder->where("groupid", $groupid);
       $query = $builder->get();
       return $query->getResult();
   }

   function groupsMembersListing($groupid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_members');
       $builder->select('tbl_group_members.*');
       $builder->where("groupid", $groupid);
       $builder->orderBy('date','DESC');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->name = $this->getMemberName($res->email);
       }
       return $result;
   }

   function getMemberName($email)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_members');
       $builder->select('tbl_members.firstname, tbl_members.lastname');
       $builder->where('email', $email);
       $query = $builder->get();
       $row = $query->getRow(0);
       if($row){
         return $row->firstname." ".$row->lastname;
       }else{
         return "---";
       }
   }

   function fetchMembersNotinGroup($group){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_members');
       $builder->select('tbl_members.*');
       if($group->branch != 1){
         $builder->where("branch", $group->branch);
       }
       $subQuery = $db->table('tbl_group_members')->select('email')->where('groupid', $group->id)->get();
       $items = $subQuery->getResult();
       $_itms = [];
       foreach ($items as $ress) {
         array_push($_itms, $ress->email);
       }
       //var_dump($_itms); die;
       if(count($items) > 0){
         $builder->whereNotIn('email', $_itms);
       }

       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->name = $this->getMemberName($res->email);
       }
       return $result;
   }

   function getBranchMembers($branch){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_members');
    $builder->select('tbl_members.*');
     if($list->branch != 1){
       $builder->where("branch", $branch);
     }
     $query = $builder->get();
     $result =  $query->getResult();
     foreach ($result as $res) {
       $res->name = $this->getMemberName($res->email);
     }
     return $result;
   }


   //group events
   function groupEventsListing($groupid){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_group_events');
       $builder->select('tbl_group_events.*');
       $builder->where('groupid', $groupid);
       $builder->orderBy('date','DESC');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->thumbnail = base_url()."/public/uploads/thumbnails/events/".$res->thumbnail;
       }
       return $result;
   }


   function addNewEvent($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_events');
     $builder->insert($info);
     $this->status = 'ok';
     $this->message = 'Group Event added successfully';
     return $this->db->insertID();
   }


   function editEvent($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_events');
     $builder->where('id', $id);
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'Group Event edited successfully';
   }


   function getEventInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_events');
       $builder->select('tbl_group_events.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if(count((array)$row) > 0){
         $row->thumbnail = base_url()."/public/uploads/thumbnails/events/".$row->thumbnail;
       }
       return $row;
   }

   function deleteEvent($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_group_events');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Group Event deleted successfully.';
   }

   function fetchMonthsEvents($groupid, $month, $year){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_group_events');
       $builder->where('groupid', $groupid);
       $builder->where('month',$month);
       $builder->where('year',$year);
       $builder->orderBy('date','desc');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->thumbnail = base_url()."/public/uploads/thumbnails/events/".$res->thumbnail;
       }
       return $result;
   }
}

?>
