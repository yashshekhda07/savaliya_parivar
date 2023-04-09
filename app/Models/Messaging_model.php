<?php

namespace App\Models;

use CodeIgniter\Model;

class Messaging_model extends Model
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

    public function fetch_app_messages($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_messaging');
      $builder->select('tbl_messaging.*');

      $builder->orderBy('date','DESC');

      /*  if($page!=0){
            $builder->limit(20,$page * 20);
        }else{
          $builder->limit(20);
        }*/
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }


    public function get_total_messages(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_messaging');
      $query = $builder->select("COUNT(*) as num")->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function messageListing(){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_messaging');
       $builder->select('tbl_messaging.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
       $builder->join('tbl_branches','tbl_branches.id=tbl_messaging.branch');
       if($this->role!=0){
         $builder->where('tbl_messaging.branch', $this->branch);
       }
       $builder->orderBy('date_created','DESC');
       $query = $builder->get();
       $result = $query->getResult();
       foreach ($result as $res) {
         if($res->listid == 0){
           $res->listname = "All Members";
         }else{
           $res->listname = $this->getListName($res->listid);
         }
       }
       return $result;
   }

   function getListName($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_lists');
       $builder->select('tbl_lists.title');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if($row){
         return $row->title;
       }else{
         return "---";
       }
   }

   function addNewMessage($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_messaging');
     $builder->insert($info);
     $this->status = 'ok';
   }


   function editMessage($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_messaging');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->update($info);
     $this->status = 'ok';
   }


   function getMessageInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_messaging');
       $builder->select('tbl_messaging.*');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function deleteMessage($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_messaging');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Message deleted successfully.';
   }
}

?>
