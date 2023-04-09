<?php

namespace App\Models;

use CodeIgniter\Model;

class Lists_model extends Model
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


   function listsListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
       $builder->select('tbl_lists.*');
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $builder->orderBy('date','DESC');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->branchname = $this->getBranchName($res->branch);
         $res->count = $this->getListMembersCount($res->id);
       }
       return $result;
   }

   function listsListingbybranch($branch){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
       $builder->select('tbl_lists.*');
       if($branch!=1){
         $builder->where('branch', $branch);
       }
       $builder->orderBy('date','DESC');
       $query = $builder->get();
       $result =  $query->getResult();
       foreach ($result as $res) {
         $res->branchname = $this->getBranchName($res->branch);
         $res->count = $this->getListMembersCount($res->id);
       }
       return $result;
   }

   public function getListMembersCount($listid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_list_members');
     $builder->select("COUNT(*) as num");
     $builder->where('listid', $listid);
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

   function addNewList($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
     $builder->insert($info);
     $this->status = 'ok';
     $this->message = 'List created successfully';
     return $this->db->insertID();
   }


   function editList($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'List edited successfully';
   }


   function getListInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
       $builder->select('tbl_lists.*');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function deleteList($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'List deleted successfully.';
   }

   function deleteListMembers($listid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_list_members');
     $builder->where('listid', $listid);
     $builder->delete();
   }

   function removeFromList($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_list_members');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Member removed from this list successfully.';
   }

   function addNewListMember($info)
   {
     if(empty($this->checkMemberListExists($info['email'], $info['listid']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_list_members');
       $builder->insert($info);
     }
   }

   function checkMemberListExists($email, $listid)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_list_members');
       $builder->select("id");
       $builder->where("email", $email);
       $builder->where("listid", $listid);
       $query = $builder->get();
       return $query->getResult();
   }

   function listsMembersListing($listid){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_list_members');
       $builder->select('tbl_list_members.*');
       $builder->where("listid", $listid);
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

   function fetchMembersNotinList($list){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_members');
       $builder->select('tbl_members.*');
       if($list->branch != 1){
         $builder->where("branch", $list->branch);
       }
       $subQuery = $db->table('tbl_list_members')->select('email')->where('listid', $list->id)->get();
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
}

?>
