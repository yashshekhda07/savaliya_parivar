<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Livestream_model as livestreammodel;
use App\Models\Branches_model as branchesmodel;
//use App\Models\Home_model as homemodel;

class Livestream extends BaseController
{
   protected $session;
   protected $livestreammodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->livestreammodel = new livestreammodel();
    }

    public function index(){
        $data['livestreams'] = $this->livestreammodel->livestreamsListing();
        return $this->view("livestream/listing", $data);
    }

    public function newLivestream()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("livestream/new", $data);
    }

    public function editLivestream($id=0)
    {
        $data['livestream'] = $this->livestreammodel->getLivestreamInfo($id);
        if(count((array)$data['livestream'])==0)
        {
            return redirect()->to(base_url().'/livestream');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("livestream/edit", $data);
    }

    function savenewlivestream(){
      if(empty($_FILES['thumbnail']['name'])){
        $this->session->setFlashdata('error', "Thumbnail is empty");
        return redirect()->to(base_url().'/newLivestream');
      }
      $thumb_upload = $this->upload_thumbnail();
      if($thumb_upload[0]=='error'){
          $this->session->setFlashdata('error', "\nThumbnail upload error: ".$thumb_upload[1]['thumbnail']);
          return redirect()->to(base_url().'/newLivestream');
        exit;
      }

      $source = $this->request->getVar('source');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $description = $this->request->getVar('description');
      $link = $this->request->getVar('link');
      $status = $this->request->getVar('status');
      $info = array(
        'branch' => $branch,
        'title' => $title,
        'source' => $source,
        'description' => $description,
        'link' => $link,
        'status' => $status,
        'cover_photo' => $thumb_upload[1],
      );
      $this->livestreammodel->addNewLivestream($info);
      if($this->livestreammodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->livestreammodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->livestreammodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newLivestream');

    }


    function editLivestreamData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
      $description = $this->request->getVar('description');
      $link = $this->request->getVar('link');
      $status = $this->request->getVar('status');
      $source = $this->request->getVar('source');
      $info = array(
         'branch' => $branch,
         'source' => $source,
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
          return redirect()->to(base_url().'/editLivestream/'.$id);
          return;
        }
      }

      $this->livestreammodel->editLivestream($info,$id);
      if($this->livestreammodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->livestreammodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->livestreammodel->message);
      }
      return redirect()->to(base_url().'/editLivestream/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteLivestream($id=0){
      $this->livestreammodel->deleteLivestream($id);
      if($this->livestreammodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->livestreammodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->livestreammodel->message);
      }
      return redirect()->to(base_url().'/livestreams');
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
