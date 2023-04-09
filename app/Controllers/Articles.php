<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Articles_model as articlesmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;
//use App\Models\Home_model as homemodel;

class Articles extends BaseController
{
   protected $session;
   protected $articlesmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->articlesmodel = new articlesmodel();
    }

    public function index(){
        //$data['userRecords'] = $this->articlesmodel->usersListing();
        return $this->view("articles/listing", []);
    }

    function getArticles(){
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


        $feeds = $this->articlesmodel->adminarticlesListing($columnName,$columnSortOrder,$searchValue,$start, $length);
        $total_feeds = $this->articlesmodel->get_total_articles($searchValue);
        //var_dump($feeds); die;
        $dat = array();

         $count = $start + 1;
        foreach($feeds as $r) {
          //var_dump($r); die;
          //$title = substr($r->title,0,10 );
          //$content = substr($r->content,0,50 );

             $dat[] = array(
                  $count,
                  $r->date,
                  $r->title,
                  '
	                <div class="dropdown">
	                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
	                    <i class="dw dw-more"></i>
	                  </a>
	                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
	                    <a class="dropdown-item" href="'.base_url().'/editArticle/'.$r->id.'"><i class="dw dw-edit2"></i> Edit</a>
	                    <a data-type="articles" data-id="'.$r->id.'" class="dropdown-item" onclick="delete_item(event)">
	                    <i data-type="articles" data-id="'.$r->id.'" class="dw dw-delete-3"></i> Delete</a>
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

    public function newArticle()
    {
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("articles/new", $data);
    }

    public function editArticle($id=0)
    {
      $data['article'] = $this->articlesmodel->getArticleInfo($id);
      if(count((array)$data['article'])==0)
      {
          return redirect()->to(base_url().'/articlesListing');
      }
      $this->branchesmodel = new branchesmodel();
      $data['branches'] = $this->branchesmodel->branchesListing(0);
      return $this->view("articles/edit", $data);
    }

    function saveNewArticle(){
      $branch = $this->request->getVar('branch');
      $date = $this->request->getVar('date');
      $title = $this->request->getVar('title');
      $author =$this->request->getVar('author');
      $content = $this->request->getVar('content');


      $info = array(
          'branch' => $branch,
          'date' => $date,
          'title' => $title,
          'author' => $author,
          'content' => $content
      );

      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail();
        if($upload[0]=='ok'){
          $info['thumbnail'] =  $upload[1];
        }
      }

      $insertid = $this->articlesmodel->addNewArticle($info);
      if($insertid!=0){
        $itm = $this->articlesmodel->getArticleInfo($insertid);
        //var_dump($article); die;
        if(count((array)$itm)>0){
            $settingsmodel = new settingsmodel();
            $server_key = $settingsmodel->getFcmServerKey();
            $fcmmodel = new fcmmodel();
            $fcmmodel->push_item_data($server_key,$itm, "Article");
        }
      }
      if($this->articlesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->articlesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->articlesmodel->message);
      }
      //redirect('newBranch');
      return redirect()->to(base_url().'/newArticle');

    }


    function editArticleData(){
      $id = $this->request->getVar('id');
      $branch = $this->request->getVar('branch');
      $date = $this->request->getVar('date');
      $title = $this->request->getVar('title');
      $author =$this->request->getVar('author');
      $content = $this->request->getVar('content');


      $info = array(
          'branch' => $branch,
          'date' => $date,
          'title' => $title,
          'author' => $author,
          'content' => $content
      );


      if(!empty($_FILES['thumbnail']['name'])){
        $upload = $this->upload_thumbnail();
        if($upload[0]=='ok'){
          $info['thumbnail'] =  $upload[1];
        }
      }

      $this->articlesmodel->editArticle($info,$id);
      if($this->articlesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->articlesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->articlesmodel->message);
      }
      return redirect()->to(base_url().'/editArticle/'.$id);
      //redirect('editBranch/'.$id);
    }


    function deleteArticle($id=0){
      $this->articlesmodel->deleteArticle($id);
      if($this->articlesmodel->status == "ok")
      {
          $this->session->setFlashdata('success', $this->articlesmodel->message);
      }
      else
      {
          $this->session->setFlashdata('error', $this->articlesmodel->message);
      }
      return redirect()->to(base_url().'/articlesListing');
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
