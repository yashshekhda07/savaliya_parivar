<?php

namespace App\Models;

use CodeIgniter\Model;

class Prayer_model extends Model
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

    public function fetch_items($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_prayers');
      $builder->select('tbl_prayers.*');
      $builder->where('status',0);
      $builder->orderby('id','desc');
      if($page!=0){
          $builder->limit(20,$page * 20);
      }else{
        $builder->limit(20);
      }
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $row) {
        $row->date = date('F j, Y', strtotime($row->date));
      }
      return $result;
    }

    public function getTotalItems(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_prayers');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }


    public function get_total_items(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_prayers');
      $query = $builder->select("COUNT(*) as num")->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function itemsListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_prayers');
       $builder->select('tbl_prayers.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
       $builder->join('tbl_branches','tbl_branches.id=tbl_prayers.branch');
       if($this->role!=0){
         $builder->where('tbl_prayers.branch', $this->branch);
       }
       $builder->orderBy('title','ASC');
       $query = $builder->get();
       return $query->getResult();
       return $result;
   }


   function addNewItem($info)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_prayers');
     $builder->insert($info);
     $this->status = 'ok';
     $this->message = 'New Prayer Request added successfully';
   }


   function editItem($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_prayers');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'Prayer Request updated successfully';
   }


   function getItemInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_prayers');
     $builder->select('tbl_prayers.*');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $query = $builder->get();
     return $query->getRow(0);
   }

   function deleteItem($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_prayers');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Prayer Request deleted successfully.';
   }
}

?>
