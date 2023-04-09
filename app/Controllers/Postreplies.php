<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Socialreplies_model as repliesmodel;

class Postreplies extends BaseController{

  protected $repliesmodel;

  public function __construct(){
      $this->repliesmodel = new repliesmodel();
  }

  public function replycomment(){
    $data = $this->get_data();
    $reply = [];
    $total_count = 0;
    if(!empty($data)){
        $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $content = isset($data->content)?filter_var($data->content, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $comment = isset($data->comment)?filter_var($data->comment, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($email!="" || $media !="" || $content != ""){
           $reply = $this->repliesmodel->replyComment($comment,$email,$content);
           $total_count = $this->repliesmodel->get_total_replies($comment);
        }
     }
     echo json_encode(array("status" => $this->repliesmodel->status,"message" => $this->repliesmodel->message,
   "comment" => $reply, "total_count" => $total_count));
   exit;
  }

  public function editreply(){
    $data = $this->get_data();
    $comment = [];
    $total_count = 0;
    if(!empty($data)){
        $content = isset($data->content)?filter_var($data->content, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $id = isset($data->id)?filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($content != "" || $id != ""){
           $comment = $this->repliesmodel->editReply($id,$content);
        }
     }
     echo json_encode(array("status" => $this->repliesmodel->status,"message" => $this->repliesmodel->message,
   "comment" => $comment));
   exit;
  }

  public function deletereply(){
    $data = $this->get_data();
    $comments = [];
    if(!empty($data)){
        $id = isset($data->id)?filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
        $comment_id = isset($data->comment)?filter_var($data->comment, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

        if($id != ""){
           $this->repliesmodel->deleteReply($id);
           $total_count = $this->repliesmodel->get_total_replies($comment_id);
        }
     }
     echo json_encode(array("status" => $this->repliesmodel->status,"message" => $this->repliesmodel->message, "total_count" => $total_count));
    exit;
  }

  function loadreplies(){
      $data = $this->get_data();
      $results = [];
      $total_count = 0;
      http_response_code(404);
      $id = 0;
      if(isset($data->id)){
        $id = $data->id;
      }

      $comment = 0;
      if(isset($data->comment)){
        $comment = $data->comment;
      }
      $results = $this->repliesmodel->loadreplies($comment,$id);
      $has_more = $this->repliesmodel->checkIfCommentHaveMoreReplies($comment,$id);
      $total_count = $this->repliesmodel->get_total_replies($comment);
     echo json_encode(array("status" => "ok","comments" => $results,"has_more" => $has_more, "total_count" => $total_count));
     exit;
  }


}
