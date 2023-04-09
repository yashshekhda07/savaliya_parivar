<?php

namespace App\Models;

use CodeIgniter\Model;

class Articles_model extends Model
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

    function getLatestArticles(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select('tbl_articles.*');
      $builder->orderby('date','desc');
      $builder->limit(5);
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $row) {
        if($row->thumbnail!=""){
          $row->thumbnail = base_url()."/public/uploads/thumbnails/".$row->thumbnail;
        }
        $row->date = date('F j, Y', strtotime($row->date));
      }
      return $result;
    }

    public function fetch_articles($page = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select('tbl_articles.*');
      $builder->orderby('date','desc');
      if($page!=0){
          $builder->limit(20,$page * 20);
      }else{
        $builder->limit(20);
      }
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $row) {
        if($row->thumbnail!=""){
          $row->thumbnail = base_url()."/public/uploads/thumbnails/".$row->thumbnail;
        }
        $row->date = date('F j, Y', strtotime($row->date));
      }
      return $result;
    }

    public function get_total_articles_app(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select("COUNT(*) as num");
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

    public function getTotalItems(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }



    function adminarticlesListing($columnName,$columnSortOrder,$searchValue,$start, $length){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select('tbl_articles.*');
      if($searchValue!=""){
          $builder->like('title', $searchValue);
          $builder->orlike('content', $searchValue);
      }
      if($columnName!=""){
         $builder->orderby($columnName, $columnSortOrder);
      }
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      $builder->limit($length,$start);

      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $res) {
        $res->thumbnail = base_url()."/public/uploads/thumbnails/".$res->thumbnail;
        $res->branchname = $this->getBranchName($res->branch);
      }
      return $result;
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

    public function get_total_articles($searchValue=""){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_articles');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      if($searchValue!=""){
        $builder->like('title', $searchValue);
        $builder->orlike('content', $searchValue);
      }

      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }


   function addNewArticle($info){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_articles');
     $builder->insert($info);
     $this->status = 'ok';
     $this->message = 'Article added successfully';
     return $this->db->insertID();
   }


   function editArticle($info, $id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_articles');
     $builder->where('id', $id);
     $builder->update($info);
     $this->status = 'ok';
     $this->message = 'Article edited successfully';
   }


   function getArticleInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_articles');
       $builder->select('tbl_articles.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if(count((array)$row) > 0 && $row->thumbnail!=""){
         $row->thumbnail = base_url()."/public/uploads/thumbnails/".$row->thumbnail;
       }
       return $row;
   }

   function deleteArticle($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_articles');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Article deleted successfully.';
   }
}

?>
