<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Hymns_model as hymnsmodel;
//use App\Models\Home_model as homemodel;

class Hymns extends BaseController
{
   protected $session;
   protected $hymnsmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->hymnsmodel = new hymnsmodel();
    }

    public function index(){
        //$data['userRecords'] = $this->hymnsmodel->usersListing();
        return $this->view("hymns/listing", []);
    }

    function getHymns(){
      // Datatables Variables

        $draw = intval($_POST['draw']);
        $start = intval($_POST['start']);
        $length = intval($_POST['length']);
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue="";
        if(isset($_POST['search']['value'])){
          $searchValue = $_POST['search']['value']; // Search value
        }

        $columnName="";
        if(isset($_POST['columns'][$columnIndex]['data'])){
          $columnSortOrder = $_POST['columns'][$columnIndex]['data']; // Search value
        }

        $columnSortOrder = "ASC";
        if(isset($_POST['order'][0]['dir'])){
          $columnSortOrder = $_POST['order'][0]['dir']; // Search value
        }


        $feeds = $this->hymnsmodel->adminHymnsListing($columnName,$columnSortOrder,$searchValue,$start, $length);
        $total_feeds = $this->hymnsmodel->get_total_hymns($searchValue);
        //var_dump($feeds); die;
        $dat = array();

         $count = $start + 1;
        foreach($feeds as $r) {
          //var_dump($r); die;
          //$title = substr($r->title,0,10 );
          //$content = substr($r->content,0,50 );

             $dat[] = array(
                  $count,
                  $r->title,
                  '
	                <div class="dropdown">
	                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
	                    <i class="dw dw-more"></i>
	                  </a>
	                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
	                    <a class="dropdown-item" href="'.base_url().'/editHymn/'.$r->id.'"><i class="dw dw-edit2"></i> Edit</a>
	                    <a data-type="hymns" data-id="'.$r->id.'" class="dropdown-item" onclick="delete_item(event)">
	                    <i data-type="hymns" data-id="'.$r->id.'" class="dw dw-delete-3"></i> Delete</a>
	                  </div>
	                </div>
	                '
             );
             $count++;
        }

        $output = array(
             "draw" => $draw,
               "recordsTotal" => $total_feeds,
               "recordsFiltered" => $total_feeds,
               "data" => $dat
          );
        echo json_encode($output);
    }

    public function newHymn()
    {
        return $this->view("hymns/new", []);
    }

    public function editHymn($id=0)
    {
      $data['hymn'] = $this->hymnsmodel->getHymnInfo($id);
      if(count((array)$data['hymn'])==0)
      {
          return redirect()->to(base_url().'/hymnsListing');
      }
      return $this->view("hymns/edit", $data);
    }

    function saveNewHymn(){
      $title = $this->request->getVar('title');
      $content = $this->request->getVar('content');
      $info = array(
          'title' => $title,
          'content' => $content,
      );

      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail();
        if($upload[0]=='ok'){
          $info['thumbnail'] =  $upload[1];
        }
      }

      $this->hymnsmodel->addNewHymn($info);
      if($this->hymnsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->hymnsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->hymnsmodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newHymn');

    }


    function editHymnData(){
      $id = $this->request->getVar('id');
      $title = $this->request->getVar('title');
      $content = $this->request->getVar('content');


      $info = array(
          'title' => $title,
          'content' => $content
      );

      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail();
        if($upload[0]=='ok'){
          $info['thumbnail'] =  $upload[1];
        }
      }

      $this->hymnsmodel->editHymn($info,$id);
      if($this->hymnsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->hymnsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->hymnsmodel->message);
      }
      return redirect()->to(base_url().'/editHymn/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteHymn($id=0){
      $this->hymnsmodel->deleteHymn($id);
      if($this->hymnsmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->hymnsmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->hymnsmodel->message);
      }
      return redirect()->to(base_url().'/hymnsListing');
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
