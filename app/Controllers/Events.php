<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Events_model as eventsmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;
//use App\Models\Home_model as homemodel;

class Events extends BaseController
{
   protected $session;
   protected $eventsmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->eventsmodel = new eventsmodel();
    }

    public function index(){
        $data['events'] = $this->eventsmodel->eventsListing();
        return $this->view("events/listing", $data);
    }

    public function newEvent()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("events/new", $data);
    }

    public function editEvent($id=0)
    {
      $data['event'] = $this->eventsmodel->getEventInfo($id);
      if(count((array)$data['event'])==0)
      {
          return redirect()->to(base_url().'/events');
      }
      $data['event']->time = str_replace("AM","",$data['event']->time);
      $data['event']->time = str_replace("PM","",$data['event']->time);
      $data['event']->time = trim($data['event']->time);
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("events/edit", $data);
    }

    function savenewevent(){
      $upload = $this->upload_thumbnail('./uploads/thumbnails/events');
      if($upload[0]=='ok'){
        $branch = $this->request->getVar('branch');
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
            'branch' => $branch,
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

        $insertid = $this->eventsmodel->addNewEvent($info);
        if($insertid!=0){
  				$itm = $this->eventsmodel->getEventInfo($insertid);
  				//var_dump($article); die;
  				if(count((array)$itm)>0){
  					  $settingsmodel = new settingsmodel();
  						$server_key = $settingsmodel->getFcmServerKey();
  						$fcmmodel = new fcmmodel();
  						$fcmmodel->push_item_data($server_key,$itm, "Event");
  				}
  			}
        if($this->eventsmodel->status == "ok")
        {
            $this->session->setFlashdata('success', $this->eventsmodel->message);
        }
        else
        {
            $this->session->setFlashdata('error', $this->eventsmodel->message);
        }
      }else{
        $this->session->setFlashdata('error', $upload[1]);
      }
      return redirect()->to(base_url().'/newEvent');
    }


    function editEventData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
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
          'branch' => $branch,
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
          return redirect()->to(base_url().'/editEvent/'.$id);
          return;
        }
      }

      $this->eventsmodel->editEvent($info,$id);
      if($this->eventsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->eventsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->eventsmodel->message);
      }

      return redirect()->to(base_url().'/editEvent/'.$id);
    }


    function deleteEvent($id=0){
      $this->eventsmodel->deleteEvent($id);
      if($this->eventsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->eventsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->eventsmodel->message);
      }
      return redirect()->to(base_url().'/eventsListing');
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
