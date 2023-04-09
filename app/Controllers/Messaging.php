<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Messaging_model as messagingmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Lists_model as listsmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Members_model as membersmodel;

use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

use ManeOlawale\Termii\Client as Termii;

// Load Composer's autoloader
//require '../vendor/autoload.php';

class Messaging extends BaseController {
	protected $role = 0;
  protected $branch = 0;

	public function __construct()
    {
				$session = session();
	      $this->role = $session->get('role');
	      $this->branch = $session->get('branch');
    }

		public function index(){
		  $messagingmodel = new messagingmodel();
      $data['messages'] = $messagingmodel->messageListing();
			return $this->view("messaging/listing", $data);
    }

		public function newMessage()
    {
			  $branchesmodel = new branchesmodel();
			  $data['branches'] = $branchesmodel->branchesListing(0);
				$listsmodel = new listsmodel();
				$data['lists'] = $listsmodel->listsListing();
				$istwilioenabled = 1;
				$istermiienabled = 1;
				$isemailenabled = 1;
				if($this->role == 0){
          $settingsmodel = new settingsmodel();
          $settings = $settingsmodel->getSettings();
					//if any of the settings are available
					if($settings->twilio_account_sid != ""
					&& $settings->twilio_auth_token != ""
					&& $settings->twilio_phonenumber != ""){
            $istwilioenabled = 0;
					}
					if($settings->termi_apikey != ""
					&& $settings->termi_sender_id != ""){
            $istermiienabled = 0;
					}
					if($settings->mail_username != ""
					&& $settings->mail_password != ""
				  && $settings->mail_smtp_host != ""
			    && $settings->mail_protocol != ""
				  && $settings->mail_port != 0){
            $isemailenabled = 0;
					}
        }else{
          $branchesmodel = new branchesmodel();
          $settings = $branchesmodel->getBranchSettings($this->branch);
					//if any of the settings are available
					if($settings->twilio_account_sid != ""
					&& $settings->twilio_auth_token != ""
					&& $settings->twilio_phonenumber != ""){
            $istwilioenabled = 0;
					}
					if($settings->termi_apikey != ""
					&& $settings->termi_sender_id != ""){
            $istermiienabled = 0;
					}
					if($settings->mail_username != ""
					&& $settings->mail_password != ""
				  && $settings->mail_smtp_host != ""
			    && $settings->mail_protocol != ""
				  && $settings->mail_port != 0){
            $isemailenabled = 0;
					}

					$settingsmodel = new settingsmodel();
          $settings2 = $settingsmodel->getSettings();
					//if branch settings not set, we use admin settings
					if($istwilioenabled == 1){
						if($settings2->twilio_account_sid != ""
						&& $settings2->twilio_auth_token != ""
						&& $settings2->twilio_phonenumber != ""){
	            $istwilioenabled = 0;
						}
					}
          if($istermiienabled == 1){
						if($settings2->termi_apikey != ""
						&& $settings2->termi_sender_id != ""){
	            $istermiienabled = 0;
						}
					}

					if($isemailenabled == 1){
						if($settings2->mail_username != ""
						&& $settings2->mail_password != ""
					  && $settings2->mail_smtp_host != ""
				    && $settings2->mail_protocol != ""
					  && $settings2->mail_port != 0){
	            $isemailenabled = 0;
						}
					}
        }
				$data['istwilioenabled'] = $istwilioenabled;
				$data['istermiienabled'] = $istermiienabled;
				$data['isemailenabled'] = $isemailenabled;
        return $this->view("messaging/new", $data);
    }

    public function resendMessage($id=0)
    {
			  $messagingmodel = new messagingmodel();
        $data['message'] = $messagingmodel->getMessageInfo($id);
        if(count((array)$data['message'])==0)
        {
            return redirect()->to(base_url().'/messaging');
        }
				$branchesmodel = new branchesmodel();
			  $data['branches'] = $branchesmodel->branchesListing(0);
				$listsmodel = new listsmodel();
				$data['lists'] = $listsmodel->listsListing();
				$istwilioenabled = 1;
				$istermiienabled = 1;
				$isemailenabled = 1;
				if($this->role == 0){
          $settingsmodel = new settingsmodel();
          $settings = $settingsmodel->getSettings();
					//if any of the settings are available
					if($settings->twilio_account_sid != ""
					&& $settings->twilio_auth_token != ""
					&& $settings->twilio_phonenumber != ""){
            $istwilioenabled = 0;
					}
					if($settings->termi_apikey != ""
					&& $settings->termi_sender_id != ""){
            $istermiienabled = 0;
					}
					if($settings->mail_username != ""
					&& $settings->mail_password != ""
				  && $settings->mail_smtp_host != ""
			    && $settings->mail_protocol != ""
				  && $settings->mail_port != 0){
            $isemailenabled = 0;
					}
        }else{
          $branchesmodel = new branchesmodel();
          $settings = $branchesmodel->getBranchSettings($this->branch);
					//if any of the settings are available
					if($settings->twilio_account_sid != ""
					&& $settings->twilio_auth_token != ""
					&& $settings->twilio_phonenumber != ""){
            $istwilioenabled = 0;
					}
					if($settings->termi_apikey != ""
					&& $settings->termi_sender_id != ""){
            $istermiienabled = 0;
					}
					if($settings->mail_username != ""
					&& $settings->mail_password != ""
				  && $settings->mail_smtp_host != ""
			    && $settings->mail_protocol != ""
				  && $settings->mail_port != 0){
            $isemailenabled = 0;
					}

					$settingsmodel = new settingsmodel();
          $settings2 = $settingsmodel->getSettings();
					//if branch settings not set, we use admin settings
					if($istwilioenabled == 1){
						if($settings2->twilio_account_sid != ""
						&& $settings2->twilio_auth_token != ""
						&& $settings2->twilio_phonenumber != ""){
	            $istwilioenabled = 0;
						}
					}
          if($istermiienabled == 1){
						if($settings2->termi_apikey != ""
						&& $settings2->termi_sender_id != ""){
	            $istermiienabled = 0;
						}
					}

					if($isemailenabled == 1){
						if($settings2->mail_username != ""
						&& $settings2->mail_password != ""
					  && $settings2->mail_smtp_host != ""
				    && $settings2->mail_protocol != ""
					  && $settings2->mail_port != 0){
	            $isemailenabled = 0;
						}
					}
        }
				$data['istwilioenabled'] = $istwilioenabled;
				$data['istermiienabled'] = $istermiienabled;
				$data['isemailenabled'] = $isemailenabled;
        return $this->view("messaging/edit", $data);
    }

    public function sendnewmessage()
    {
			$branch = $this->request->getVar('branch');
			$list = $this->request->getVar('list');
      $title = $this->request->getVar('title');
			$message = $this->request->getVar('message');
			$smsgateway = $this->request->getVar('smsgateway');
			$sms = "NO";
			$email = "NO";
			$app_notification = "NO";
			$formats = $this->request->getVar('formats');
			$draft = 1;
			if($formats != NULL){
				$draft = 0;
				foreach ($formats as $val) {
	        $itm = (array_values($val)[0]);
					if($itm == "sms"){
						$sms = "YES";
					}
					if($itm == "email"){
						$email = "YES";
					}
	      }
			}
      //$members = $this->request->getVar('members');
      //var_dump($_POST); die;
      $info = array(
          'branch' => $branch,
          'title' => $title,
					'listid' => $list,
          'message' => $message,
          'sms' => $sms,
					'email' => $email,
          'app_notification' => $app_notification,
					'date' => time(),
      );
			$messagingmodel = new messagingmodel();
			$msg_id = $messagingmodel->addNewMessage($info);

			if($email == "YES" || $sms == "YES"){
				$membersmodel = new membersmodel();
				$members = [];
				if($branch == 1 && $list == 0){
          $members = $membersmodel->getMembers(1);
				}else if($branch != 1 && $list == 0){
          $members = $membersmodel->getMembers($branch);
				}else{
          $members = $membersmodel->getMembersByListid($branch,$list);
				}

				$settingsmodel = new settingsmodel();
				$adminsettings = $settingsmodel->getSettings();
				$branchesmodel = new branchesmodel();
				$churchsettings = $branchesmodel->getBranchSettings($branch);
				//send email
				if($email == "YES"){
					$emailconfig = $settingsmodel->getEmailConfig($churchsettings, $adminsettings);
					$branchname = $branch == 1?$adminsettings->churchname:$branchesmodel->getBranchName($branch);
          foreach ($members as $res) {
						if($res->email!=""){
							$this->sendEmail($branchname, $emailconfig, $res->email, $title, $message);
						}

          }
				}
				//send sms
				if($sms == "YES"){
          $smsconfig = $settingsmodel->getSMSConfig($churchsettings, $adminsettings,$smsgateway);
					foreach ($members as $res) {
						if($res->phonenumber!=""){
							$this->sendSMS($smsgateway, $smsconfig, $res->phonenumber, $message);
						}
          }
				}
			}
			$session = session();
			$session->setFlashdata('success', $draft == 0?"Message sent successfully.":"Message saved as draft.");
			return redirect()->to(base_url().'/messaging');
			/*
			 $inbox_id = $this->inbox_model->addNewInbox($info);
			if($inbox_id!=0){
				$inbox = $this->inbox_model->getInboxData($inbox_id);
				//var_dump($article); die;
				if(count((array)$inbox)>0){
						$this->load->model('settings_model');
						$server_key = $this->settings_model->getFcmServerKey();
						//echo $server_key; die;
						$this->load->model('fcm_model');
						$this->fcm_model->push_inbox_data($server_key,$inbox);
				}
			*/

    }

    function editMessageData()
    {
			$id = $this->input->post('id');
			$title = $this->request->getVar('title');
			$message = $this->request->getVar('message');
      //$members = $this->request->getVar('members');
      //var_dump($_POST); die;
      $info = array(
          'title' => $title,
          'message' => $message,
      );
			$messagingmodel = new messagingmodel();
			$messagingmodel->editMessage($info, $id);
			$session = session();
			$session->setFlashdata('success', "Message updated successfully.");
			return redirect()->to(base_url().'/editMessage/'.$id);
    }

    function deleteMessage($id=0)
    {
			$messagingmodel = new messagingmodel();
      $messagingmodel->deleteMessage($id);
			$session = session();
      if($messagingmodel->status == "ok")
      {
          $session->setFlashdata('success', $messagingmodel->message);
      }
      else
      {
          $session->setFlashdata('error', $messagingmodel->message);
      }
      return redirect()->to(base_url().'/messaging');
    }

		private function sendSMS($smsgateway, $smsconfig, $phonenumber, $content){
			//var_dump($smsgateway); die;
     if($smsgateway == "twilio"){
			 try {
				 $twilio = new Client($smsgateway->twilio_account_sid, $smsgateway->twilio_auth_token);
				 $twiliomsg = $twilio->messages
										 ->create($phonenumber, // to
															["from" => $smsgateway->twilio_phonenumber, "body" => $content]
										 );
			 } catch (\Exception $e) {
					 //die( $e->getCode() . ' : ' . $e->getMessage() );
			 }
		 }
		 if($smsgateway == "termii"){
			 try {
				 $client = new Termii($smsconfig->termi_apikey,
				 ['sender_id' => $smsconfig->termi_sender_id, 'channel' => 'generic',]
					);
				 $client->sms->send($phonenumber, $content);
			 } catch (\Exception $e) {
					 //die( $e->getCode() . ' : ' . $e->getMessage() );
			 }

		 }
		}
}
