<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Radio_model as radiomodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Radio extends BaseController
{
   protected $session;
   protected $radiomodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->radiomodel = new radiomodel();
    }

    public function index(){
        $data['radio'] = $this->radiomodel->radioListing();
        return $this->view("radio/listing", $data);
    }

    public function newRadio()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("radio/new", $data);
    }

    public function editRadio($id=0)
    {
        $data['radio'] = $this->radiomodel->getRadioInfo($id);
        if(count((array)$data['radio'])==0)
        {
            return redirect()->to(base_url().'/radio');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("radio/edit", $data);
    }

    function savenewradio(){
      if(empty($_FILES['thumbnail']['name'])){
        $this->session->setFlashdata('error', "Thumbnail is empty");
        return redirect()->to(base_url().'/newRadio');
      }
      $thumb_upload = $this->upload_thumbnail();
      if($thumb_upload[0]=='error'){
          $this->session->setFlashdata('error', "\nThumbnail upload error: ".$thumb_upload[1]['thumbnail']);
          return redirect()->to(base_url().'/newRadio');
        exit;
      }

       $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $description = $this->request->getVar('description');
      $link = $this->request->getVar('link');
      $status = $this->request->getVar('status');
      $info = array(
        'title' => $title,
        'branch' => $branch,
        'description' => $description,
        'link' => $link,
        'status' => $status,
        'cover_photo' => $thumb_upload[1],
      );
      $this->radiomodel->addNewRadio($info);
      if($this->radiomodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->radiomodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->radiomodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newRadio');

    }


    function editRadioData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $description = $this->request->getVar('description');
      $link = $this->request->getVar('link');
      $status = $this->request->getVar('status');
      $info = array(
        'branch' => $branch,
        'title' => $title,
        'description' => $description,
        'link' => $link,
        'status' => $status,
      );
      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail();

        if($upload[0]=='ok'){
           $info['cover_photo'] = $upload[1];
        }else{
          $this->session->setFlashdata('error', "\nThumbnail upload error: ".$upload[1]['thumbnail']);
          return redirect()->to(base_url().'/editRadio/'.$id);
          return;
        }
      }

      $this->radiomodel->editRadio($info,$id);
      if($this->radiomodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->radiomodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->radiomodel->message);
      }
      return redirect()->to(base_url().'/editRadio/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteRadio($id=0){
      $this->radiomodel->deleteRadio($id);
      if($this->radiomodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->radiomodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->radiomodel->message);
      }
      return redirect()->to(base_url().'/radio');
      //redirect('branchesListing');
    }

    function upload_thumbnail(){
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
              $img->move('./uploads/thumbnails');
              $data = [
                 'name' =>  $img->getName(),
                 'type'  => $img->getClientMimeType()
              ];
              return ['ok',$img->getName()];
          }
    }
}
