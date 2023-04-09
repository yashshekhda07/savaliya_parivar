<?php

namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model
{
  protected $db;

  public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function authenticate($email, $password)
    {
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_users');
      $builder->select('*');
      $builder->where('email', $email);
      //$builder->where('password', MD5($password));
      //$builder->from('tbl_clients');
      $query = $builder->get();
      $user = $query->getRow(0);
      if($user){
        if(password_verify($password, $user->password)){
            return $user;
        } else {
            return NULL;
        }
      }
      return NULL;
    }

}

?>
