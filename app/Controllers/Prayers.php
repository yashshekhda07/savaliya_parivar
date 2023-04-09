<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Prayer_model as prayermodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Prayers extends BaseController
{
   protected $session;
   protected $prayermodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->prayermodel = new prayermodel();
    }

    public function index(){
        $data['prayers'] = $this->prayermodel->itemsListing();
        return $this->view("prayers/listing", $data);
    }

    public function newPrayer()
    {
      $branchesmodel = new branchesmodel();
      $data['branches'] = $branchesmodel->branchesListing();
        return $this->view("prayers/new", $data);
    }

    public function editPrayer($id=0)
    {
        $data['prayer'] = $this->prayermodel->getItemInfo($id);
        if($data['prayer']==NULL)
        {
            return redirect()->to(base_url().'/prayers');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("prayers/edit", $data);
    }

    function savenewprayer(){
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $requester = $this->request->getVar('requester');
      $content = $this->request->getVar('content');

      $branchesmodel = new branchesmodel();
      /*$status = 0;
      if($branch!=0){
        $status = $branchesmodel->getBranchSettings($branch)->auto_approve_prayer;
      }*/

      $info = array(
        'title' => $title,
        'branch' => $branch,
        'content' => $content,
        'requester' => $requester,
        'status' => 0,
      );
      $this->prayermodel->addNewItem($info);
      if($this->prayermodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->prayermodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->prayermodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newPrayer');
    }


    function editprayerdata(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $requester = $this->request->getVar('requester');
      $content = $this->request->getVar('content');
      $info = array(
        'title' => $title,
        'branch' => $branch,
        'content' => $content,
        'requester' => $requester,
      );


      $this->prayermodel->editItem($info,$id);
      if($this->prayermodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->prayermodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->prayermodel->message);
      }
      return redirect()->to(base_url().'/editPrayer/'.$id);
      //redirect('editBranch/'.$id);
    }

    function editPrayerStatus($id, $status){
      $info = array(
        'status' => $status,
      );
      $this->prayermodel->editItem($info,$id);
      if($this->prayermodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->prayermodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->prayermodel->message);
      }
      return redirect()->to(base_url().'/prayersListing');
    }


    function deletePrayer($id=0){
      $this->prayermodel->deleteItem($id);
      if($this->prayermodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->prayermodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->prayermodel->message);
      }
      return redirect()->to(base_url().'/prayersListing');
      //redirect('branchesListing');
    }
}
