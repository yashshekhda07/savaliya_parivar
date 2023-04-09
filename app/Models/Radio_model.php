<?php

namespace App\Models;

use CodeIgniter\Model;

class Radio_model extends Model
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

    public function fetch_radio($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_radio');
      $builder->select('tbl_radio.*');
      $builder->where('status',0);
      $builder->orderBy('title','ASC');

      if($page!=0){
            $builder->limit(20,$page * 20);
        }else{
          $builder->limit(20);
        }
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $row) {
          $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
        }
        return $result;
    }


    public function get_total_radio(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_radio');
      $builder->where('status',0);
      $query = $builder->select("COUNT(*) as num")->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function radioListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_radio');
       $builder->select('tbl_radio.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
       $builder->join('tbl_branches','tbl_branches.id=tbl_radio.branch');
       if($this->role!=0){
         $builder->where('tbl_radio.branch', $this->branch);
       }
       $builder->orderBy('title','ASC');
       $query = $builder->get();
       return $query->getResult();
       return $result;
   }

   function checkRadioExists($title, $id = 0)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_radio');
       $builder->select("title");
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $builder->where("title", $title);
       if($id != 0){
           $builder->where("id !=", $id);
       }
       $query = $builder->get();
       //var_dump($query->result()); die;
       return $query->getResult();
   }


   function addNewRadio($info)
   {
     if(empty($this->checkRadioExists($info['title']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_radio');
       $builder->insert($info);
       $this->status = 'ok';
       $this->message = 'New Radio added successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Radio already exists';
     }
   }


   function editRadio($info, $id){
     if(empty($this->checkRadioExists($info['title'],$id))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_radio');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $builder->update($info);
       $this->status = 'ok';
       $this->message = 'Radio edited successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Radio already exists';
     }
   }


   function getRadioInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_radio');
       $builder->select('tbl_radio.*');
       $builder->where('id', $id);
       if($this->role!=0){
         $builder->where('branch', $this->branch);
       }
       $query = $builder->get();
       $row = $query->getRow(0);
       if(count((array)$row)>0){
         $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
       }
       return $row;
   }

   function deleteRadio($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_radio');
     $builder->where('id', $id);
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Radio deleted successfully.';
   }

   private function get_thumbnail_source($source){
       if($this->isValidURL($source)){
         return $source;
       }
       return base_url()."/public/uploads/thumbnails/".$source;
   }

   function isValidURL($url){
      return filter_var($url, FILTER_VALIDATE_URL);
  }
}

?>
