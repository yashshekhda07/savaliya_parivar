<?php

namespace App\Models;
use CodeIgniter\Model;

class Donations_model extends Model{
    public $status = 'error';
    public $message = 'Error processing requested operation.';
    public $role = 0;
    public $branch = 0;

    function __construct(){
       parent::__construct();
       $session = session();
       $this->role = $session->get('role');
       $this->branch = $session->get('branch');
	  }

    public function getTotalItems(){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_donations');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }

   function getThisWeekDonationsAmount($date1, $date2){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_donations');
     $builder->selectSum("amount");
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     $builder->where("(DATE(date) BETWEEN '".$date1."' AND '".$date2."')");
     $query = $builder->get();
     $result = $query->getRow(0);
     if(isset($result)){
       if($result->amount == "")return 0;
       return $result->amount;
     }
     return 0;
   }

   function getDonationsAmount($month, $year){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_donations');
     $builder->selectSum("amount");
     if($this->role!=0){
       $builder->where('branch', $this->branch);
     }
     if($month!=0){
       $builder->where('month', $month);
     }
     if($year!=0){
       $builder->where('year', $year);
     }
     $query = $builder->get();
     $result = $query->getRow(0);
     if(isset($result)){
       if($result->amount == "")return 0;
       return $result->amount;
     }
     return 0;
   }

   function getRecentDonations(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_donations');
     $builder->select('tbl_donations.*');
     if($this->role!=0){
       $builder->where('tbl_donations.branch', $this->branch);
     }
     $builder->orderby('date', 'DESC');
     $builder->limit(10);
     $query = $builder->get();
     $result = $query->getResult();
     foreach ($result as $res) {
       $res->branchname = $this->getBranchName($res->branch);
     }
     return $result;
   }

    function donationsListing($columnName,$columnSortOrder,$searchValue,$start, $length){

      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_donations');
      $builder->select('tbl_donations.*');
      if($this->role!=0){
        $builder->where('tbl_donations.branch', $this->branch);
      }
      if($searchValue!=""){
        $builder->like('name', $searchValue);
        $builder->orlike('email', $searchValue);
        $builder->orlike('reference', $searchValue);
        $builder->orlike('amount', $searchValue);
      }
      if($columnName!=""){
         $builder->orderby($columnName, $columnSortOrder);
      }
      $builder->limit($length,$start);
      $query = $builder->get();
      $result = $query->getResult();
      foreach ($result as $res) {
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

    public function get_total_donations($searchValue=""){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_donations');
      $builder->select("COUNT(*) as num");
      if($this->role!=0){
        $builder->where('branch', $this->branch);
      }
      if($searchValue!=""){
        $builder->like('name', $searchValue);
        $builder->orlike('email', $searchValue);
        $builder->orlike('reference', $searchValue);
        $builder->orlike('amount', $searchValue);
      }
      $query = $builder->get();
      $result = $query->getRow(0);
      if(isset($result)) return $result->num;
      return 0;
   }


  public function recordDonation($ref){
   if($this->verifyPaymentRefExists($ref['email'],$ref['reference']) == FALSE){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_donations');
     $builder->insert($ref);
     $this->status = "ok";
      $this->message = "Donation was done successfully";
   }else{
     $this->status = "error";
     $this->message = "Cannot record the donation made at the moment";
   }
  }


  function verifyPaymentRefExists($email,$ref)
  {
    $db = \Config\Database::connect("default");
    $builder = $db->table('tbl_donations');
      $builder->select('tbl_donations.id');
      $builder->where('email',$email);
      $builder->where('reference',$ref);
      $query = $builder->get();
      if(count((array)$query->getResult())>0){
        return TRUE;
      }
      return FALSE;
  }
}
