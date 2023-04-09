<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Chat_model as chatmodel;
use App\Models\Fcm_model as fcmmodel;
use App\Models\Settings_model as settingsmodel;

class Chat extends BaseController{

  protected $chatmodel;
  protected $fcmmodel;
  protected $settingsmodel;

  /**
     * constructor
     */
    public function __construct(){
        $this->chatmodel = new chatmodel();
        $this->fcmmodel = new fcmmodel();
        $this->settingsmodel = new settingsmodel();
    }

   public function fetch_user_chats(){
     $data = $this->get_data();
     $results = [];
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $count = 0;
     if(isset($data->count)){
       $count = $data->count;
     }
     $results = $this->chatmodel->getUsersChat($email,$count);
     echo json_encode(array("chatsList" => $results));
     exit;
   }

   public function fetch_user_partner_chat(){
     $data = $this->get_data();
     //var_dump($data);
     $results = [];
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $results = $this->chatmodel->fetch_user_partner_chat($email,$partner);
     if($results){
       echo json_encode(array("status" => "ok","chat" => $results));
     }else{
       echo json_encode(array("status" => "none"));
     }
     exit;
   }

   public function checkfornewmessages(){
    $data = $this->get_data();
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $date = isset($data->date)?filter_var($data->date, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):1602063634;
     $results = $this->chatmodel->checkfornewmessages($email,$date);
     echo json_encode(array("status" => "ok","chats" => $results));
     exit;
   }

   public function load_more_chats(){
    $data = $this->get_data();
     $chat_id = isset($data->chatId)?filter_var($data->chatId, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $count = isset($data->count)?filter_var($data->count, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):20;
     //print($count); die;
     if($chat_id == 0){
         //first we check if a chat have been initiated before
         //between both users and get the chat id
         $chat_id = $this->chatmodel->get_user_chatID_if_exists($email,$partner);
     }
     $results = $this->chatmodel->get_chat_messages($chat_id,$email,intval($count));
     $have_more_content = $this->chatmodel->chats_have_more_content($chat_id, $email,intval($count));
     echo json_encode(array("status" => "ok","chats" => $results,"have_more_content" => $have_more_content));
     exit;
   }

   public function on_seen_conversation(){
     $data = $this->get_data();
     //var_dump($data);
     $chatid = isset($data->chatid)?filter_var($data->chatid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     if($chatid == 0){
         //first we check if a chat have been initiated before
         //between both users and get the chat id
         $chatid = $this->chatmodel->get_user_chatID_if_exists($email,$partner);
     }
     if($chatid!=0){
       $this->chatmodel->on_seen_conversation($chatid,$email);
       //notify user of conversation read
       $server_key = $this->settingsmodel->getFcmServerKey();
    	 $this->fcmmodel->userSeenConversationNotification($server_key, $partner, $email, $chatid);
     }
     echo json_encode(array("status" => "ok"));
     exit;
   }

   public function on_user_typing(){
     $data = $this->get_data();
     //var_dump($data);
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

     $server_key = $this->settingsmodel->getFcmServerKey();
     $this->fcmmodel->userTypingNotification($server_key, $partner, $email);
     echo json_encode(array("status" => "ok"));
     exit;
   }

   public function update_user_online_status(){
     $data = $this->get_data();
     //var_dump($data);
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $status = isset($data->status)?filter_var($data->status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):1;
     $this->chatmodel->updateUserOnlineStatus($email, $status);
     $server_key = $this->settingsmodel->getFcmServerKey();
     $this->fcmmodel->notifyUserOnlinePresence($server_key,$email,$status);
     echo json_encode(array("status" => "ok"));
     exit;
   }

   public function save_user_conversation(){
     $date = time();
     $chat_id = null !== $this->request->getVar('chat_id')?filter_var($this->request->getVar('chat_id'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
     $sender = null !== $this->request->getVar('sender')?filter_var($this->request->getVar('sender'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $recipient = null !== $this->request->getVar('recipient')?filter_var($this->request->getVar('recipient'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $msg_reciept = null !== $this->request->getVar('msg_reciept')?filter_var($this->request->getVar('msg_reciept'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):time();
     $msg_owner = null !== $this->request->getVar('msg_owner')?filter_var($this->request->getVar('msg_owner'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $message = null !== $this->request->getVar('content')?filter_var($this->request->getVar('content'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     if($chat_id == 0){
         //first we check if a chat have been initiated before
         //between both users and get the chat id
         $chat_id = $this->chatmodel->get_user_chatID_if_exists($sender,$recipient);
         if($chat_id==0){
           $info = array(
               'email1' => $sender,
               'email2' => $recipient,
               'last_message_time' => $date
           );
          $chat_id = $this->chatmodel->createUsersChatID($info);
         }

     }else{
       $this->chatmodel->updateChatIDLastMessageTime($chat_id, $date);
     }
     $attachment = "";
     if(!empty($_FILES['photo'])){
       $upload = $this->upload_file();
       //var_dump($upload); die;
       if($upload[0]=='ok'){
         $attachment =  $upload[1];
       }else{
         echo json_encode(array("status" => "error","msg" => $upload[1]));
         exit;
       }
     }

     //check if this user is blocked from sending messages
     $isUserBlocked1 = $this->chatmodel->verifyIfPartnerIsBlocked($sender,$recipient);
     $isUserBlocked2 = $this->chatmodel->verifyIfPartnerIsBlocked($recipient,$sender);

     //save message for sender
     $msg1 = array(
         'chat_id' => $chat_id,
         'message' => $message,
         'attachment' => $attachment,
         'sender' => $sender,
         'msg_reciept' => $msg_reciept,
         'msg_owner' => $msg_owner,
         'date' => $date
     );
      //save for sender
      $this->chatmodel->saveUserChatConversation($msg1);

      //if none of the users blocked the other, we save and send notification
      if($isUserBlocked1 != 0 && $isUserBlocked2 != 0){
        //save message for reciever
        $msg2 = array(
            'chat_id' => $chat_id,
            'message' => $message,
            'attachment' => $attachment,
            'sender' => $sender,
            'msg_reciept' => $msg_reciept,
            'msg_owner' => $recipient,
            'date' => $date
        );
        //save for recipient
        $converseID = $this->chatmodel->saveUserChatConversation($msg2);
        $unseen = $this->chatmodel->get_unseen_messages($chat_id,$recipient);
        //send notification to recipient
        $chatsender = $this->chatmodel->getRecipientDetails($sender);
        /*$notificationmessage = "Sent a photo";
        if($message!=""){
          $notifcationmessage = substr(base64_decode($message),100);
        }*/
        $chat = $this->chatmodel->getUserLastConversation($converseID);
        $server_key = $this->settingsmodel->getFcmServerKey();
        $this->fcmmodel->userConversationNotification($server_key, $recipient, $chatsender, $unseen, $chat);
      }
     echo json_encode(array("status" => "ok","chatid" => $chat_id));
     exit;
   }

   function delete_selected_chat_messages(){
     $data = $this->get_data();
     //var_dump($data);
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $chatid = isset($data->chatid)?filter_var($data->chatid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
     $msgReciepts = $data->msgReciepts;
     if($chatid == 0){
         //first we check if a chat have been initiated before
         //between both users and get the chat id
         $chatid = $this->chatmodel->get_user_chatID_if_exists($email,$partner);
     }
     $chat = $this->chatmodel->delete_selected_chat_messages($email, $chatid, $msgReciepts);
     echo json_encode(array("status" => "ok"));
     exit;
   }

   function clear_user_conversation(){
     $data = $this->get_data();
     //var_dump($data);
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $chatid = isset($data->chatid)?filter_var($data->chatid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
     if($chatid == 0){
         //first we check if a chat have been initiated before
         //between both users and get the chat id
         $chatid = $this->chatmodel->get_user_chatID_if_exists($email,$partner);
     }
     $chat = $this->chatmodel->clear_user_chat_messages($email, $chatid);
     echo json_encode(array("status" => "ok"));
     exit;
   }

   function blockUnblockUser(){
     $data = $this->get_data();
     //var_dump($data);
     $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $partner = isset($data->partner)?filter_var($data->partner, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
     $status = isset($data->status)?filter_var($data->status, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):1;

     if($status == 0){
       $info = array(
           'blocked_user' => $partner,
           'blocked_by' => $email
       );
       $this->chatmodel->blockUser($info);
     }else{
       $this->chatmodel->unblockUser($partner, $email);
     }

     echo json_encode(array("status" => "ok"));
     exit;
   }


   public function upload_file(){
 		$path = $_FILES["photo"]['name'];
 		$ext = pathinfo($path, PATHINFO_EXTENSION);
    $new_name = uniqid()."_photo_".time().".".$ext;
    //var_dump($new_name); die;
    helper(['form', 'url']);
        $input = $this->validate([
            'photo' => [
                'uploaded[photo]',
                'mime_in[photo,image/jpg,image/jpeg,image/png,image/JPG,image/PNG]',
                'max_size[photo,100024]',
            ]
        ]);
        if (!$input) {

            //$data = ['errors' => $this->validator->getErrors()];
            return ['error',$this->validator->getErrors()['photo']];
        } else {
            $img = $this->request->getFile('photo');
            $img->move('./uploads/socials/chats', $new_name);
            $data = [
               'name' =>  $img->getName(),
               'type'  => $img->getClientMimeType()
            ];
            return ['ok',$img->getName()];
        }
 	}

}
