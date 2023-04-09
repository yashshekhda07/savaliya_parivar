<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Branches extends BaseController
{
   protected $session;
   protected $branchesmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->branchesmodel = new branchesmodel();
        if($this->session->get('role') != 0){
          //return redirect()->to(base_url().'/dashboard');
          header("Location: ".base_url());
          exit();
        }
    }

    //fetch audios/videos
    function church_branches(){
        $data = $this->get_data();
        $results = [];
        $isLastPage = false;
        $page = 0;
        if(isset($data->page)){
          $page = $data->page;
        }

        $results = $this->branchesmodel->fetch_branches($page);
        $total_items = $this->branchesmodel->get_total_branches();
        $isLastPage = (($page + 1) * 20) >= $total_items;

        echo json_encode(array("status" => "ok","branches" => $results,"isLastPage" => $isLastPage));
    }

    public function index(){
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("branches/listing", $data);
    }

    public function loadbranches(){
      $branches = $this->branchesmodel->branchesListing();
      echo json_encode(array("status" => "ok","branches" => $branches));
    }

    public function newBranch()
    {
        return $this->view("branches/new", []);
    }

    public function editBranch($id=0)
    {
        $data['branch'] = $this->branchesmodel->getBranchInfo($id);
        if(count((array)$data['branch'])==0)
        {
            return redirect()->to(base_url().'/branchesListing');
        }
        return $this->view("branches/edit", $data);
    }

    function savenewbranch(){
      $name = $this->request->getVar('name');
      $phone = $this->request->getVar('phone');
      $email = $this->request->getVar('email');
      $address = $this->request->getVar('address');
      $pastor = $this->request->getVar('pastor');
      $latitude = $this->request->getVar('latitude');
      $longitude = $this->request->getVar('longitude');
      $info = array(
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'pastor' => $pastor,
        'latitude' => $latitude,
        'longitude' => $longitude
      );
      $this->branchesmodel->addNewBranch($info);
      if($this->branchesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->branchesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->branchesmodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newBranch');

    }


    function editBranchData(){
      $id = $this->request->getVar('id');
      $name = $this->request->getVar('name');
      $phone = $this->request->getVar('phone');
      $email = $this->request->getVar('email');
      $address = $this->request->getVar('address');
      $pastor = $this->request->getVar('pastor');
      $latitude = $this->request->getVar('latitude');
      $longitude = $this->request->getVar('longitude');
      $info = array(
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'address' => $address,
        'pastor' => $pastor,
        'latitude' => $latitude,
        'longitude' => $longitude
      );

      $this->branchesmodel->editBranch($info,$id);
      if($this->branchesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->branchesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->branchesmodel->message);
      }
      return redirect()->to(base_url().'/editBranch/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteBranch($id=0){
      $this->branchesmodel->deleteBranch($id);
      if($this->branchesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->branchesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->branchesmodel->message);
      }
      return redirect()->to(base_url().'/branchesListing');
      //redirect('branchesListing');
    }
}
