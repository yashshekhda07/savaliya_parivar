<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Testimony_model as testimonymodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Testimony extends BaseController
{
   protected $session;
   protected $testimonymodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->testimonymodel = new testimonymodel();
    }

    public function index(){
        $data['testimonies'] = $this->testimonymodel->itemsListing();
        return $this->view("testimony/listing", $data);
    }

    public function newTestimony()
    {
      $branchesmodel = new branchesmodel();
      $data['branches'] = $branchesmodel->branchesListing();
        return $this->view("testimony/new", $data);
    }

    public function editTestimony($id=0)
    {
        $data['testimony'] = $this->testimonymodel->getItemInfo($id);
        if($data['testimony']==NULL)
        {
            return redirect()->to(base_url().'/testimonyListing');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("testimony/edit", $data);
    }

    function savenewtestimony(){
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $testifier = $this->request->getVar('testifier');
      $content = $this->request->getVar('content');

      $branchesmodel = new branchesmodel();
      /*$status = 0;
      if($branch!=0){
        $status = $branchesmodel->getBranchSettings($branch)->auto_approve_testimony;
      }*/

      $info = array(
        'title' => $title,
        'branch' => $branch,
        'content' => $content,
        'testifier' => $testifier,
        'status' => 0,
      );
      $this->testimonymodel->addNewItem($info);
      if($this->testimonymodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->testimonymodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->testimonymodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newTestimony');
    }


    function edittestimonydata(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $testifier = $this->request->getVar('testifier');
      $content = $this->request->getVar('content');
      $info = array(
        'title' => $title,
        'branch' => $branch,
        'content' => $content,
        'testifier' => $testifier,
      );


      $this->testimonymodel->editItem($info,$id);
      if($this->testimonymodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->testimonymodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->testimonymodel->message);
      }
      return redirect()->to(base_url().'/editTestimony/'.$id);
      //redirect('editBranch/'.$id);
    }

    function editTestimonyStatus($id, $status){
      $info = array(
        'status' => $status,
      );
      $this->testimonymodel->editItem($info,$id);
      if($this->testimonymodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->testimonymodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->testimonymodel->message);
      }
      return redirect()->to(base_url().'/testimonyListing');
    }


    function deleteTestimony($id=0){
      $this->testimonymodel->deleteItem($id);
      if($this->testimonymodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->testimonymodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->testimonymodel->message);
      }
      return redirect()->to(base_url().'/testimonyListing');
      //redirect('branchesListing');
    }
}
