<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Socialcomments_model as commentsmodel;

class Postcomments extends BaseController{

  protected $commentsmodel;

  public function __construct(){
      $this->commentsmodel = new commentsmodel();
  }

  public function makecomment(){
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if(!empty($data)){
        $user = isset($data->user)?filter_var($data->user, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $content = isset($data->content)?filter_var($data->content, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $post = isset($data->post)?filter_var($data->post, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($email!="" && $post !="" && $content != ""){
           $comment = $this->commentsmodel->makeComment($post,$email,$content);
           $total_count = $this->commentsmodel->get_total_comments($post);
           $this->check_notify_user($post, "comment", $user, $email);
        }
     }
     echo json_encode(array("status" => $this->commentsmodel->status,"message" => $this->commentsmodel->message,
   "comment" => $comment, "total_count" => $total_count));
   exit;
  }

  public function editcomment(){
    $data = $this->get_data();
    $comment = [];
    if(!empty($data)){
        $content = isset($data->content)?filter_var($data->content, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $id = isset($data->id)?filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($content != "" && $id != ""){
           $comment = $this->commentsmodel->editComment($id,$content);
        }
     }
     echo json_encode(array("status" => $this->commentsmodel->status,"message" => $this->commentsmodel->message,
   "comment" => $comment));
   exit;
  }

  public function deletecomment(){
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if(!empty($data)){
        $id = isset($data->id)?filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $post = isset($data->post)?filter_var($data->post, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($id != ""){
           $this->commentsmodel->deleteComment($id);
           $total_count = $this->commentsmodel->get_total_comments($post);
        }
     }
     echo json_encode(array("status" => $this->commentsmodel->status,"message" => $this->commentsmodel->message, "total_count" => $total_count));
     exit;
  }

  function loadcomments(){
      $data = $this->get_data();
      $results = [];
      $total_count = 0;
      http_response_code(404);
      $id = 0;
      if(isset($data->id)){
        $id = $data->id;
      }

      $post = 0;
      if(isset($data->post)){
        $post = $data->post;
      }

      $results = $this->commentsmodel->loadcomments($post,$id);
      $has_more = $this->commentsmodel->checkIfpostHasMoreComments($post,$id);
      $total_count = $this->commentsmodel->get_total_comments($post);
      if(count((array)$results)>0){
        http_response_code(200);
      }
     echo json_encode(array("status" => "ok","comments" => $results,"has_more" => $has_more, "total_count" => $total_count));
     exit;
  }

  public function reportcomment(){
    $data = $this->get_data();
    if(!empty($data)){
        $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $type = isset($data->type)?filter_var($data->type, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $reason = isset($data->reason)?filter_var($data->reason, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $id = isset($data->id)?filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        if($email!="" && $type !="" && $id != ""){
          $this->commentsmodel->reportComment($id,$email,$type,$reason);
        }
     }
     echo json_encode(array("status" => $this->commentsmodel->status,"message" => $this->commentsmodel->message));
     exit;
  }

}
