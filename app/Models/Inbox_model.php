<?php

namespace App\Models;

use CodeIgniter\Model;

class Inbox_model extends Model
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

    public function get_last_seen_notification_count($last_seen_inbox=0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_inbox');
      $builder->select("COUNT(*) as num");
      $builder->where('id >',$last_seen_inbox);
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

    public function fetch_app_inbox($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_inbox');
      $builder->select('tbl_inbox.*');
      $builder->orderBy('date','DESC');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    function fetchInbox($page=0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_inbox');
        $builder->select('tbl_inbox.*');
        $builder->orderby('date','desc');
        if($page!=0){
            $builder->limit(20,$page * 20);
        }else{
          $builder->limit(20);
        }

        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }


    public function get_total_inbox(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_inbox');
      $query = $builder->select("COUNT(*) as num")->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function inboxListing(){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_inbox');
       $builder->select('tbl_inbox.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
       $builder->join('tbl_branches','tbl_branches.id=tbl_inbox.branch');
       if($this->role!=0){
         $builder->where('tbl_inbox.branch', $this->branch);
       }
       $builder->orderBy('date_created','DESC');
       $query = $builder->get();
       $result = $query->getResult();
       return $result;
   }

   function addNewInbox($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_inbox');
     $builder->insert($info);
     $this->status = 'ok';
     return $this->db->insertID();
   }


   function editInbox($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_inbox');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->update($info);
     $this->status = 'ok';
   }


   function getInboxInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_inbox');
       $builder->select('tbl_inbox.*');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function deleteInbox($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_inbox');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Inbox message deleted successfully.';
   }
}

?>
