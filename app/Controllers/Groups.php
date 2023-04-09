<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Groups_model as groupsmodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Groups extends BaseController
{
   protected $session;
   protected $groupsmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->groupsmodel = new groupsmodel();
    }

    public function index(){
        $data['groups'] = $this->groupsmodel->groupsListing();
        return $this->view("groups/listing", $data);
    }

    public function newGroup()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("groups/new", $data);
    }

    public function editGroup($id=0)
    {
      $data['group'] = $this->groupsmodel->getGroupInfo($id);
      if(count((array)$data['group'])==0)
      {
          return redirect()->to(base_url().'/groups');
      }
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
      return $this->view("groups/edit", $data);
    }

    function savenewgroup(){
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $leader = $this->request->getVar('leader');
      $description = $this->request->getVar('description');
      $location = $this->request->getVar('location');
      $time = $this->request->getVar('time');
      $info = array(
          'branch' => $branch,
          'leader' => $leader,
          'title' => $title,
          'description' => $description,
          'location' => $location,
          'time' => $time,
      );
      //var_dump($info); die;

      $groupid = $this->groupsmodel->addNewGroup($info);
      /*foreach ($members as $itm) {
        $info2 = array(
            'listid' => $listid,
            'email' => $itm,
        );
        $this->groupsmodel->addNewListMember($info2);
      }*/
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
          return redirect()->to(base_url().'/addMemberstoGroup/'.$groupid);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
          return redirect()->to(base_url().'/newGroup');
      }
    }


    function editGroupData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $leader = $this->request->getVar('leader');
      $description = $this->request->getVar('description');
      $location = $this->request->getVar('location');
      $time = $this->request->getVar('time');
      $info = array(
          'branch' => $branch,
          'leader' => $leader,
          'title' => $title,
          'description' => $description,
          'location' => $location,
          'time' => $time,
      );
      $this->groupsmodel->editGroup($info,$id);
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }

      return redirect()->to(base_url().'/editGroup/'.$id);
    }


    function deleteGroup($id=0){
      $this->groupsmodel->deleteGroupMembers($id);
      $this->groupsmodel->deleteGroup($id);
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }
      return redirect()->to(base_url().'/groups');
      //redirect('branchesListing');
    }

    function editGroupMemberStatus($id, $status){
      $info = array(
        'status' => $status,
      );
      $this->groupsmodel->editMemberStatus($info,$id);
      $group = $this->groupsmodel->getGroupMemberInfo($id);
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }
      return redirect()->to(base_url().'/viewGroupMembers/'.$group->groupid);
    }

    function removeFromGroup($id,$groupid){
      $this->groupsmodel->removeFromGroup($id);
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }
      return redirect()->to(base_url().'/viewGroupMembers/'.$groupid);
      //redirect('branchesListing');
    }

    public function viewGroupMembers($groupid){
      $data['group'] = $this->groupsmodel->getGroupInfo($groupid);
      if(count((array)$data['group'])==0)
      {
          return redirect()->to(base_url().'/groups');
      }
        $data['members'] = $this->groupsmodel->groupsMembersListing($groupid);
        return $this->view("groups/members", $data);
    }

    public function addMemberstoGroup($groupid){
        $data['group'] = $this->groupsmodel->getGroupInfo($groupid);
        if(count((array)$data['group'])==0)
        {
            return redirect()->to(base_url().'/groups');
        }
        $data['members'] = $this->groupsmodel->fetchMembersNotinGroup($data['group']);
        //var_dump($data['members']); die;
        return $this->view("groups/addmembers", $data);
    }

    function savenewmembersgroup(){
      $groupid = $this->request->getVar('id');
      $members = $this->request->getVar('members');
      if($members!= NULL && $members!="" && count($members)>0){
        foreach ($members as $itm) {
          $info2 = array(
              'groupid' => $groupid,
              'email' => $itm,
          );
          $this->groupsmodel->addNewGroupMember($info2);
        }
        $this->session->setFlashdata('success', "Members added to group");
      }

      return redirect()->to(base_url().'/viewGroupMembers/'.$groupid);
    }


    //group activities
    public function groupEvents($groupid){
        $data['group'] = $this->groupsmodel->getGroupInfo($groupid);
        if(count((array)$data['group'])==0)
        {
            return redirect()->to(base_url().'/groups');
        }
        $data['events'] = $this->groupsmodel->groupEventsListing($groupid);
        //var_dump($data['members']); die;
        $data['groupid'] = $groupid;
        return $this->view("groups/events/listing", $data);
    }


    public function newEvent($groupid)
    {
      $data['groupid'] = $groupid;
      return $this->view("groups/events/new", $data);
    }

    public function editEvent($id=0)
    {
      $data['event'] = $this->groupsmodel->getEventInfo($id);
      if(count((array)$data['event'])==0)
      {
          return redirect()->to(base_url().'/events');
      }
      $data['event']->time = str_replace("AM","",$data['event']->time);
      $data['event']->time = str_replace("PM","",$data['event']->time);
      $data['event']->time = trim($data['event']->time);
      return $this->view("groups/events/edit", $data);
    }

    function savenewevent(){
      $upload = $this->upload_thumbnail('./uploads/thumbnails/events');
      if($upload[0]=='ok'){
        $groupid = $this->request->getVar('groupid');
        $title = $this->request->getVar('title');
        $details = $this->request->getVar('details');
        $date = $this->request->getVar('date');
        $time = $this->request->getVar('time');
        $mer = intval($time) < 12 ? 'AM' : 'PM';
        $time = $time. " ".$mer;

        $_date = \DateTime::createFromFormat("Y-m-d", $date);
        $year =  $_date->format("Y") + 0;
        $month =  $_date->format("m") + 0;
        $day =  $_date->format("d") + 0;
        $info = array(
            'branch' => 1,
            'groupid' => $groupid,
            'title' => $title,
            'details' => $details,
            'date' => $date,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'time' => $time,
            'thumbnail' => $upload[1]
        );
        //var_dump($info); die;

        $this->groupsmodel->addNewEvent($info);
        if($this->groupsmodel->status == "ok")
        {
            $this->session->setFlashdata('success', $this->groupsmodel->message);
        }
        else
        {
            $this->session->setFlashdata('error', $this->groupsmodel->message);
        }
      }else{
        $this->session->setFlashdata('error', $upload[1]);
      }
      return redirect()->to(base_url().'/newGroupEvent/'.$groupid);
    }


    function editEventData(){
      $id = $this->request->getVar('id');
      $title = $this->request->getVar('title');
      $details = $this->request->getVar('details');
      $date = $this->request->getVar('date');
      $time = $this->request->getVar('time');
      $mer = intval($time) < 12 ? 'AM' : 'PM';
      $time = $time. " ".$mer;

      $_date = \DateTime::createFromFormat("Y-m-d", $date);
      $year =  $_date->format("Y") + 0;
      $month =  $_date->format("m") + 0;
      $day =  $_date->format("d") + 0;
      $info = array(
          'title' => $title,
          'details' => $details,
          'date' => $date,
          'year' => $year,
          'month' => $month,
          'day' => $day,
          'time' => $time,
      );
      //var_dump($info); die;

      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail('./uploads/thumbnails/events');

        if($upload[0]=='ok'){
           $info['thumbnail'] = $upload[1];
        }else{
          $this->session->setFlashdata('error', $upload[1]);
          return redirect()->to(base_url().'/editGroupEvent/'.$id);
          return;
        }
      }

      $this->groupsmodel->editEvent($info,$id);
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }

      return redirect()->to(base_url().'/editGroupEvent/'.$id);
    }


    function deleteEvent($id=0){
      $event = $this->groupsmodel->getEventInfo($id);
      if(!$event){
        return redirect()->to(base_url().'/groups');
      }
      $groupid = $event->groupid;
      $this->groupsmodel->deleteEvent($id);
      //var_dump($events); die;
      if($this->groupsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->groupsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->groupsmodel->message);
      }
      return redirect()->to(base_url().'/groupEvents/'.$groupid);
      //redirect('branchesListing');
    }


    function upload_thumbnail($uploadpath){
      helper(['form', 'url']);
          $input = $this->validate([
              'thumbnail' => [
                  'uploaded[thumbnail]',
                  'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
                  'max_size[thumbnail,10024]',
              ]
          ]);
          if (!$input) {
              //$data = ['errors' => $this->validator->getErrors()];
              return ['error',$this->validator->getErrors()];
          } else {
              $img = $this->request->getFile('thumbnail');
              $img->move($uploadpath);
              $data = [
                 'name' =>  $img->getName(),
                 'type'  => $img->getClientMimeType()
              ];
              return ['ok',$img->getName()];
          }
    }
}
