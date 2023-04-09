<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Lists_model as listsmodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Lists extends BaseController
{
   protected $session;
   protected $listsmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->listsmodel = new listsmodel();
    }

    public function index(){
        $data['lists'] = $this->listsmodel->listsListing();
        return $this->view("lists/listing", $data);
    }

    public function fetchlists($branch){
        $lists = $this->listsmodel->listsListingbybranch($branch);
        echo json_encode(array("lists" => $lists));
        exit;
    }

    public function newList()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("lists/new", $data);
    }

    public function editList($id=0)
    {
      $data['lists'] = $this->listsmodel->getListInfo($id);
      if(count((array)$data['event'])==0)
      {
          return redirect()->to(base_url().'/lists');
      }
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
      return $this->view("lists/edit", $data);
    }

    function savenewlist(){
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      //$members = $this->request->getVar('members');
      //var_dump($_POST); die;
      $info = array(
          'branch' => $branch,
          'title' => $title,
      );
      //var_dump($info); die;

      $listid = $this->listsmodel->addNewList($info);
      /*foreach ($members as $itm) {
        $info2 = array(
            'listid' => $listid,
            'email' => $itm,
        );
        $this->listsmodel->addNewListMember($info2);
      }*/
      if($this->listsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->listsmodel->message);
          return redirect()->to(base_url().'/addMemberstoList/'.$listid);
      }
      else
      {
          $this->session->setFlashdata('error', $this->listsmodel->message);
          return redirect()->to(base_url().'/newList');
      }
    }


    function editListData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $info = array(
          'branch' => $branch,
          'title' => $title,
      );


      $this->listsmodel->editList($info,$id);
      if($this->listsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->listsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->listsmodel->message);
      }

      return redirect()->to(base_url().'/editList/'.$id);
    }


    function deleteList($id=0){
      $this->listsmodel->deleteListMembers($id);
      $this->listsmodel->deleteList($id);
      if($this->listsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->listsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->listsmodel->message);
      }
      return redirect()->to(base_url().'/lists');
      //redirect('branchesListing');
    }

    function removeFromList($id,$listid){
      $this->listsmodel->removeFromList($id);
      if($this->listsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->listsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->listsmodel->message);
      }
      return redirect()->to(base_url().'/viewListMembers/'.$listid);
      //redirect('branchesListing');
    }

    public function viewListMembers($listid){
      $data['lists'] = $this->listsmodel->getListInfo($listid);
      if(count((array)$data['lists'])==0)
      {
          return redirect()->to(base_url().'/lists');
      }
        $data['list'] = $this->listsmodel->getListInfo($listid);
        $data['members'] = $this->listsmodel->listsMembersListing($listid);
        return $this->view("lists/members", $data);
    }

    public function addMemberstoList($listid){
        $data['list'] = $this->listsmodel->getListInfo($listid);
        if(count((array)$data['list'])==0)
        {
            return redirect()->to(base_url().'/lists');
        }
        $data['members'] = $this->listsmodel->fetchMembersNotinList($data['list']);
        //var_dump($data['members']); die;
        return $this->view("lists/addmembers", $data);
    }

    function savenewmemberslist(){
      $listid = $this->request->getVar('id');
      $members = $this->request->getVar('members');
      foreach ($members as $itm) {
        $info2 = array(
            'listid' => $listid,
            'email' => $itm,
        );
        $this->listsmodel->addNewListMember($info2);
      }
      $this->session->setFlashdata('success', "Members added to list");
      return redirect()->to(base_url().'/viewListMembers/'.$listid);
    }
}
