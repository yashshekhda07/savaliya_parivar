<?php

namespace App\Models;

use CodeIgniter\Model;

class Devotionals_model extends Model
{
  protected $db;
  public $status = 'error';
  public $message = 'Error processing requested operation.';
  public $user = "";

  public function __construct()
    {
        parent::__construct();
        $builder = \Config\Database::connect();
    }

    function fetchMonthsDevotionals($month, $year){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_devotionals');
        $builder->where('month',$month);
        $builder->where('year',$year);
        $builder->orderBy('date','desc');
        $query = $builder->get();
        $result =  $query->getResult();
        foreach ($result as $res) {
          if($res->thumbnail!=""){
            $res->thumbnail = base_url()."/public/uploads/thumbnails/".$res->thumbnail;
          }
        }
        return $result;
    }

    public function getTotalItems(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_devotionals');
      $builder->select("COUNT(*) as num");
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }


    function adminDevotionalsListing($columnName,$columnSortOrder,$searchValue,$start, $length){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_devotionals');
      $builder->select('tbl_devotionals.*');
      if($searchValue!=""){
          $builder->like('title', $searchValue);
          $builder->orlike('content', $searchValue);
      }
      if($columnName!=""){
         $builder->orderby($columnName, $columnSortOrder);
      }
      $builder->limit($length,$start);

      $query = $builder->get();
      $result = $query->getResult();
      return $result;
    }

    public function get_total_devotionals($searchValue=""){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_devotionals');
      if($searchValue==""){
        $query = $builder->select("COUNT(*) as num")->get();
      }else{
        $builder->select("COUNT(*) as num");
        $builder->like('title', $searchValue);
        $builder->orlike('content', $searchValue);
        $query = $builder->get();
      }
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function checkDevotionalExists($date, $id = 0)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_devotionals');
       $builder->select("id");
       $builder->where("date", $date);
       if($id != 0){
           $builder->where("id !=", $id);
       }
       $query = $builder->get();
       //var_dump($query->result()); die;
       return $query->getResult();
   }


   function addNewDevotional($info){
     if(empty($this->checkDevotionalExists($info['date']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_devotionals');
       $builder->insert($info);
       $this->status = 'ok';
       $this->message = 'Devotional added successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Devotional already added for this date '.$info['date'];
     }
     return $this->db->insertID();
   }


   function editDevotional($info, $id){
     if(empty($this->checkDevotionalExists($info['date'],$id))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_devotionals');
       $builder->where('id', $id);
       $builder->update($info);
       $this->status = 'ok';
       $this->message = 'Devotional edited successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Date for this devotional already exists for another';
     }
   }


   function getDevotionalInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_devotionals');
       $builder->select('tbl_devotionals.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       if(count((array)$row) > 0 && $row->thumbnail!=""){
         $row->thumbnail = base_url()."/public/uploads/thumbnails/".$row->thumbnail;
       }
       return $row;
   }

   function deleteDevotional($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_devotionals');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Devotional deleted successfully.';
   }
}

?>
