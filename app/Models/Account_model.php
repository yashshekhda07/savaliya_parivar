<?php

namespace App\Models;

use CodeIgniter\Model;

class Account_model extends Model
{
    public $status = 'error';
    public $message = 'Error processing requested operation.';

    public function __construct()
    {
        parent::__construct();
    }

    //authenticate user email and password
    public function authenticateUser($phonenumber, $password)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('phonenumber', $phonenumber);
        $query = $builder->get();
        $user = $query->getRow(0);
        if (!$user) {
            $this->status = "error";
            $this->message = "Phone number or password does not exist";
        } else {
            //then we verify if password matches the saved hashed password
            if (password_verify($password, $user->password)) {
                if ($user->verified != 1) {
                    //if user have not verified his account, we display message for user to verify his email address
                    $this->status = "error";
                    $this->message = "A verification link was sent to your mail, follow the link to verify your email address.";
                } else {
                    $this->status = "ok";
                    $this->message = "User Authenticated";
                }
            } else {
                $this->status = "error";
                $this->message = "Email or Password is not correct.";
            }
        }
        if ($user) {
            if ($user->thumbnail != "") {
                $user->thumbnail = base_url() . "/public/uploads/members/" . $user->thumbnail;
            }
            if ($user->coverphoto != "") {
                $user->coverphoto = base_url() . "/public/uploads/members/" . $user->coverphoto;
            }
        }
        return $user;
    }

    //create email or password for user
    public function createAccount($phonenumber, $password)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('phonenumber', $phonenumber);
        $query = $builder->get();
        $user = $query->getRow(0);
        if ($user) {
            if ($user->password != "") {
                $this->status = "error";
                $this->message = "An account already exists for this phonenumber.";
            } else {
                $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
                $builder->where('phonenumber', $phonenumber);
                $builder->update($info);
                $this->status = "ok";
            }
        } else {
            $info = array('phonenumber' => $phonenumber, 'password' => password_hash($password, PASSWORD_DEFAULT),'verified'=>1);
            $builder->insert($info);
            $this->status = "ok";
            $this->message = "Account create successfully.";
        }
    }

    //update user verification status
    public function updateUserVerfication($email)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $info = array('verified' => 0);
        $builder->where('email', $email);
        $builder->update($info);
        $this->status = "ok";
    }

    //update user password
    public function updateUserPassword($email, $password)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $info = array('password' => password_hash($password, PASSWORD_DEFAULT));
        $builder->where('email', $email);
        $builder->update($info);
        $this->status = "ok";
    }

    //update user profile
    public function updateUserProfile($info, $phonenumber)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->where('phonenumber', $phonenumber);
        $builder->update($info);
        $this->status = "ok";
    }

    public function verifyEmailExists($email)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('email', $email);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //authenticate user email and password
    public function getUpdatedUserProfile($phonenumber)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('phonenumber', $phonenumber);
        $query = $builder->get();
        $user = $query->getRow(0);
        if ($user) {
            if ($user->thumbnail != "") {
                $user->thumbnail = base_url() . "/public/uploads/members/" . $user->thumbnail;
            }
            if ($user->coverphoto != "") {
                $user->coverphoto = base_url() . "/public/uploads/members/" . $user->coverphoto;
            }
        }
        return $user;
    }

    function deletemyaccount($phonenumber)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->where('phonenumber', $phonenumber);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Account deleted successfully.';
    }


}

?>
