<?php

namespace App\Models;

use CodeIgniter\Model;

class Users_model extends Model
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


   function usersListing(){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_users');
       $builder->select('tbl_users.*');
       //$builder->orderBy('id','ASC');
       $query = $builder->get();
       $result =  $query->getResult();
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

   function checkEmailExists($email, $id = 0)
   {
       //echo $name . " and ". $group;
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_users');
       $builder->select("email");
       $builder->where("email", $email);
       if($id != 0){
           $builder->where("id !=", $id);
       }
       $query = $builder->get();
       //var_dump($query->result()); die;
       return $query->getResult();
   }


   function addNewAdmin($info)
   {
     if(empty($this->checkEmailExists($info['email']))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_users');
       $builder->insert($info);
       $this->status = 'ok';
       $this->message = 'New Admin User added successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Email already exists';
     }
   }


   function editAdmin($info, $id){
     if(empty($this->checkEmailExists($info['email'],$id))){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_users');
       $builder->where('id', $id);
       $builder->update($info);
       $this->status = 'ok';
       $this->message = 'Admin User edited successfully';
     }else{
       $this->status = 'error';
       $this->message = 'Email already exists';
     }
   }


   function getAdminInfo($id)
   {
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_users');
       $builder->select('tbl_users.*');
       $builder->where('id', $id);
       $query = $builder->get();
       $row = $query->getRow(0);
       return $row;
   }

   function deleteAdmin($id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_users');
     $builder->where('id', $id);
     $builder->delete();
     $this->status = 'ok';
     $this->message = 'Admin User deleted successfully.';
   }
}

?>
