<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Users_model as usersmodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class User extends BaseController
{
   protected $session;
   protected $usersmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->usersmodel = new usersmodel();
        if($this->session->get('role') != 0){
          header("Location: ".base_url());
          exit();
        }
    }

    public function index(){
        $data['userRecords'] = $this->usersmodel->usersListing();
        return $this->view("admin/listing", $data);
    }

    public function newAdmin()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("admin/new", $data);
    }

    public function editAdmin($id=0)
    {
        $data['admin'] = $this->usersmodel->getAdminInfo($id);
        if(count((array)$data['admin'])==0)
        {
          return redirect()->to(base_url().'/adminListing');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("admin/edit", $data);
    }

    function savenewadmin(){
      $branch = $this->request->getVar('branch');
      $name = $this->request->getVar('name');
      $email = $this->request->getVar('email');
      $password = $this->request->getVar('password');
      $hashed = password_hash($password,PASSWORD_DEFAULT);
      /*echo $hashed;
      echo "<br><br>";
      echo password_verify($password, $hashed);
      die;*/
      $role = $this->request->getVar('role');
      /*if($role == 0 && $branch != 1){
        $this->session->setFlashdata('success', "A super Admin User cannot be assigned to a church branch");
        return redirect()->to(base_url().'/newAdmin');
        exit;
      }
      if($role == 1 && $branch == 1){
        $this->session->setFlashdata('error', "A church branch admin need to be assigned to a church branch");
        return redirect()->to(base_url().'/newAdmin');
        exit;
      }*/
      $info = array(
        'role' => $role,
        'branch' => $branch,
        'fullname'       => $name,
        'email'      => $email,
        'password'   => $hashed
      );
      $this->usersmodel->addNewAdmin($info);
      if($this->usersmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->usersmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->usersmodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newAdmin');

    }


    function editadmindata(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $name = $this->request->getVar('name');
      $email = $this->request->getVar('email');
      $password = $this->request->getVar('password');

      /*if($branch == 1){
        $this->session->setFlashdata('error', "A church branch admin need to be assigned to a church branch");
        return redirect()->to(base_url().'/editAdmin/'.$id);
        exit;
      }*/
      $info = array(
        'branch' => $branch,
        'fullname'       => $name,
        'email'      => $email,
        //'password'   => getHashedPassword($password)
      );

      if($password!=""){
        $info['password'] = password_hash($password,PASSWORD_DEFAULT);
      }

      $this->usersmodel->editAdmin($info,$id);
      if($this->usersmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->usersmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->usersmodel->message);
      }
      return redirect()->to(base_url().'/editAdmin/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteAdmin($id=0){
      $this->usersmodel->deleteAdmin($id);
      if($this->usersmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->usersmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->usersmodel->message);
      }
      return redirect()->to(base_url().'/adminListing');
      //redirect('branchesListing');
    }
}
