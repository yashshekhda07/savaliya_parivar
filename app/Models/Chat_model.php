<?php

namespace App\Models;

use CodeIgniter\Model;

class Chat_model extends Model{
    public $status = 'error';
    public $message = 'Error processing requested operation';
    public $user = "";

    function __construct(){
       parent::__construct();
	  }

    public function getUsersChat($email="null", $count = 0){
      $db = \Config\Database::connect("default");
      $builder = $db->table('tbl_chat');
      $builder->select('tbl_chat.*');
      $builder->where('email1',$email);
      $builder->orwhere('email2', $email);
      $builder->orderby('last_message_time','desc');
        if($count!=0){
            $builder->limit(20,$count);
        }else{
          $builder->limit(20);
        }

        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
          $res->chats = $this->get_chat_messages($res->id, $email,$count);
          $res->unseen = $this->get_unseen_messages($res->id, $email);
          $res->isOnline = $this->getUserOnlineStatus($res->email1==$email?$res->email2:$res->email1);
          $res->partner = $this->getPartner($res->email1==$email?$res->email2:$res->email1);
          $res->lastSeenDate = $this->getUserLastSeenDate($res->email1==$email?$res->email2:$res->email1);
          $res->is_blocked = $this->verifyIfPartnerIsBlocked($email,$res->email1==$email?$res->email2:$res->email1);
          $res->have_more_content = $this->chats_have_more_content($res->id, $email,$count);
        }
        return $result;
     }

     public function get_chat_messages($chat_id, $email,$count){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_chat_messages');
       $builder->select('tbl_chat_messages.*');
       $builder->where('chat_id',$chat_id);
       $builder->where('msg_owner', $email);
       $builder->orderby('date','desc');
         if($count!=0){
             $builder->limit(20,$count);
         }else{
           $builder->limit(20);
         }

         $query = $builder->get();
         $result = $query->getResult();
         foreach ($result as $res) {
           if($res->attachment!=""){
              $res->attachment = base_url()."/public/uploads/socials/chats/".$res->attachment;
           }
           if($res->message!=""){
             $res->message = base64_decode($res->message);
           }

         }
         //var_dump($result); die;
         return $result;
     }


     public function checkfornewmessages($email,$date){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_chat_messages');
       $builder->select('tbl_chat_messages.*');
       $builder->where('sender !=',$email);
       $builder->where('msg_owner', $email);
       $builder->where('date > ', $date);
        $builder->limit(20);
         $query = $builder->get();
         $result = $query->getResult();
         foreach ($result as $res) {
           if($res->attachment!=""){
              $res->attachment = base_url()."/public/uploads/socials/chats/".$res->attachment;
           }
           if($res->message!=""){
             $res->message = base64_decode($res->message);
           }

         }
         //var_dump($result); die;
         return $result;
     }

     public function get_unseen_messages($chat_id,$email){
       $db = \Config\Database::connect("default");
       $builder = $db->table('tbl_chat_messages');
       $builder->select('COUNT(*) as num');
       $builder->where('chat_id',$chat_id);
       $builder->where('sender !=', $email);
       $builder->where('msg_owner', $email);
       $builder->where('seen', 1);
       $query = $builder->get();
       $row = $query->getRow(0);
       if(isset($row)) return $row->num;
       return 0;
      }

      public function chats_have_more_content($chatid, $email,$count){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->select('COUNT(*) as num');
        $builder->where('chat_id',$chatid);
        $builder->where('msg_owner', $email);
        $query = $builder->get();
        $row = $query->getRow(0);
        if(isset($row)){
           $total = $row->num;
           if($total > ($count + 20)){
             return 0;
           }else{
             return 1;
           }
        }
        return 1;
      }


      function getPartner($email){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('tbl_members.email',$email);
        $query = $builder->get();
        $user = $query->getRow(0);
        if($user){
           $user->activated = 1;
           $user->photo = $user->thumbnail==""?"":base_url()."/public/uploads/members/".$user->thumbnail;
           $user->coverphoto = $user->coverphoto==""?"":base_url()."/public/uploads/members/".$user->coverphoto;
           //$res->following = 1;
          return $user;
        }else{
          return null;
        }
      }

      public function fetch_user_partner_chat($email, $partner){
        $sql = "SELECT * FROM tbl_chat WHERE (email1 = '".$email."' AND email2 = '".$partner."') OR (email1 = '".$partner."' AND email2 = '".$email."')";
        $db = \Config\Database::connect("default");
        $query = $db->query($sql);
        //var_dump($query); die;
        $result = $query->getResult();
        if($result){
          $res = new stdClass();
          $res = $result[0];
          $res->chats = $this->get_chat_messages($res->id, $email,0);
          $res->unseen = $this->get_unseen_messages($res->id, $email);
          $res->isOnline = $this->getUserOnlineStatus($res->email1==$email?$res->email2:$res->email1);
          $res->partner = $this->getPartner($res->email1==$email?$res->email2:$res->email1);
          $res->lastSeenDate = $this->getUserLastSeenDate($res->email1==$email?$res->email2:$res->email1);
          $res->is_blocked = $this->verifyIfPartnerIsBlocked($email,$res->email1==$email?$res->email2:$res->email1);
          $res->have_more_content = $this->chats_have_more_content($res->id, $email,$count);
          return $res;
        }else{
          return null;
        }
      }

      public function verifyIfPartnerIsBlocked($email, $partner){
        $sql = "SELECT * FROM tbl_blocked_users WHERE blocked_user = '".$partner."' AND blocked_by = '".$email."' ";
        $db = \Config\Database::connect("default");
        $query = $db->query($sql);
        $result = $query->getResult();
        if($result){
          return 0;
        }else{
          return 1;
        }
      }

      public function get_user_chatID_if_exists($email, $partner){
        $sql = "SELECT * FROM tbl_chat WHERE (email1 = '".$email."' AND email2 = '".$partner."') OR (email1 = '".$partner."' AND email2 = '".$email."')";
        $db = \Config\Database::connect("default");
        $query = $db->query($sql);
        //var_dump($query);
        $result = $query->getResult();
        if($result){
          $res = new \stdClass();
          $res = $result[0];
          return $res->id;
        }else{
          return 0;
        }
      }

      function createUsersChatID($info){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat');
        $builder->insert($info);
        return $db->insertID();
      }

      public function updateChatIDLastMessageTime($id,$date){
        $data = ['last_message_time' => $date];
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat');
        $builder->where('id', $id);
        $builder->update($data);
      }

      function saveUserChatConversation($info){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->insert($info);
        return $db->insertID();
      }

      public function on_seen_conversation($chatid,$email){
        $data = ['seen' => 0];
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->where('chat_id', $chatid);
        $builder->where('msg_owner', $email);
        $builder->update($data);
      }


      function getRecipientDetails($email){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('tbl_members.email',$email);
        $query = $builder->get();
        $user = $query->getRow(0);
        if($user){
           $user->activated = 1;
           $user->photo = $user->thumbnail==""?"":base_url()."/public/uploads/members/".$user->thumbnail;
           $user->coverphoto = $user->coverphoto==""?"":base_url()."/public/uploads/members/".$user->coverphoto;
           //$res->following = 1;
          return $user;
        }else{
          return null;
        }
      }

      function getUserLastSeenDate($email){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.last_seen_date');
        $builder->where('tbl_members.email',$email);
        $query = $builder->get();
        $user = $query->getRow(0);
        if($user){
          return $user->last_seen_date;
        }else{
          return 0;
        }
      }

      function getUserOnlineStatus($email){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.online_status');
        $builder->where('tbl_members.email',$email);
        $query = $builder->get();
        $user = $query->getRow(0);
        if($user){
          return $user->online_status;
        }else{
          return 1;
        }
      }

      public function updateUserOnlineStatus($email, $status){
        $data = ['online_status' => $status, 'last_seen_date' => time()];
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->where('email', $email);
        $builder->update($data);
      }


      public function getUserLastConversation($id){
        //print($chat_id);
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->select('tbl_chat_messages.*');
        $builder->where('id',$id);

        $query = $builder->get();
        $res = $query->getRow(0);
        if($res){
          if($res->attachment!=""){
             $res->attachment = base_url()."/public/uploads/socials/chats/".$res->attachment;
          }
          if($res->message!=""){
            $res->message = base64_decode($res->message);
          }
        }
          //var_dump($result); die;
        return $res;
      }

      function delete_selected_chat_messages($email, $chatid, $msgReciepts){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->where('msg_owner', $email);
        $builder->where('chat_id', $chatid);
        $builder->wherein('msg_reciept', $msgReciepts);
        $builder->delete();
      }

      function clear_user_chat_messages($email, $chatid){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_chat_messages');
        $builder->where('msg_owner', $email);
        $builder->where('chat_id', $chatid);
        $builder->delete();
      }

      function blockUser($info){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_blocked_users');
        $builder->insert($info);
        return $db->insertID();
      }

      function unblockUser($blockedUser,$blockedBy){
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_blocked_users');
        $builder->where('blocked_user', $blockedUser);
        $builder->where('blocked_by', $blockedBy);
        $builder->delete();
      }

}
