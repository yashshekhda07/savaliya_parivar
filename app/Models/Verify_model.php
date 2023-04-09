<?php

namespace App\Models;

use CodeIgniter\Model;

class Verify_model extends Model
{
  public $status = 'error';
  public $message = 'Error processing requested operation.';

  public function __construct()
    {
        parent::__construct();
    }

    function insertData($info)
    {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_verification');
      $builder->insert($info);
    }

    //check if verification details exists, when user clicks on the link sent to mail
   public function checkActivationDetails($activation_id){
     $db = \Config\Database::connect("default");
     $builder = $db->table('tbl_verification');
     $builder->where('activation_id',$activation_id);
     $query = $builder->get();
     $row = $query->getRow(0);
     //var_dump($activation_id); die;
     return $row;
   }

   //delete details when user have been verified
    public function deleteActivationDetails($activation_id){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_verification');
      $builder->where('activation_id',$activation_id);
      $builder->delete();
      $this->status = 'ok';
    }


}

?>
