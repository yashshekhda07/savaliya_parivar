<?php

namespace App\Models;

use CodeIgniter\Model;

class Livestream_model extends Model
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

    public function fetch_livestreams_app($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->select('tbl_livestreams.*');
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
        $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
      }
      return $result;
    }

    public function fetch_livestreams($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->select('tbl_livestreams.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
      $builder->join('tbl_branches','tbl_branches.id=tbl_livestreams.branch');

      $builder->orderBy('title','ASC');

      /*  if($page!=0){
            $builder->limit(20,$page * 20);
        }else{
          $builder->limit(20);
        }*/
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }


    public function get_total_livestreams(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_livestreams');
      $builder->where('status',0);
      $query = $builder->select("COUNT(*) as num")->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function livestreamsListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_livestreams');
     $builder->select('tbl_livestreams.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
     $builder->join('tbl_branches','tbl_branches.id=tbl_livestreams.branch');
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
       $builder->orderBy('title','ASC');
       $query = $builder->get();
       return $query->getResult();
       return $result;
   }

   function checkLivestreamExists($title, $id = 0)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_livestreams');
       $builder->select("title");
       $builder->where("title", $title);
       if($id != 0){
           $builder->where("id !=", $id);
       }
       $query = $builder->get();
       //var_dump($query->result()); die;
       return $query->getResult();
   }


   function addNewLivestream($info)
   {
     if(empty($this->checkLivestreamExists($info['title']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_livestreams');
       $builder->insert($info);
       $this->status = 'ok';
       $this->message = 'New Livestream added successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Livestream already exists';
     }
   }


   function editLivestream($info, $id){
     if(empty($this->checkLivestreamExists($info['title'],$id))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_livestreams');
       $builder->where('id', $id);
       $builder->update($info);
       $this->status = 'ok';
       $this->message = 'Livestream edited successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Livestream already exists';
     }
   }


   function getLivestreamInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_livestreams');
       $builder->select('tbl_livestreams.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if(count((array)$row)>0){
         $row->cover_photo = $this->get_thumbnail_source($row->cover_photo);
       }
       return $row;
   }

   function deleteLivestream($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_livestreams');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Livestream deleted successfully.';
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
