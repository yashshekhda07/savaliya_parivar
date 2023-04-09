<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Inbox_model as inboxmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
use App\Models\Fcm_model as fcmmodel;

class Inbox extends BaseController {
	protected $role = 0;
  protected $branch = 0;

	public function __construct()
    {
				$session = session();
	      $this->role = $session->get('role');
	      $this->branch = $session->get('branch');
    }

		public function index(){
		  $inboxmodel = new inboxmodel();
      $data['messages'] = $inboxmodel->inboxListing();
			return $this->view("inbox/listing", $data);
    }

		public function newInbox()
    {
			  $branchesmodel = new branchesmodel();
			  $data['branches'] = $branchesmodel->branchesListing(0);

        return $this->view("inbox/new", $data);
    }

		public function editInbox($id=0)
    {
			  $inboxmodel = new inboxmodel();
        $data['inbox'] = $inboxmodel->getInboxInfo($id);
        if(count((array)$data['inbox'])==0)
        {
            return redirect()->to(base_url().'/inbox');
        }
				$branchesmodel = new branchesmodel();
			  $data['branches'] = $branchesmodel->branchesListing(0);
        return $this->view("inbox/edit", $data);
    }

    public function resendInbox($id=0)
    {
			  $inboxmodel = new inboxmodel();
        $data['inbox'] = $inboxmodel->getInboxInfo($id);
        if(count((array)$data['inbox'])==0)
        {
            return redirect()->to(base_url().'/inbox');
        }
				$branchesmodel = new branchesmodel();
			  $data['branches'] = $branchesmodel->branchesListing(0);
        return $this->view("inbox/resend", $data);
    }

    public function sendnewinbox()
    {
			$branch = $this->request->getVar('branch');
      $title = $this->request->getVar('title');
			$message = $this->request->getVar('message');

      //$members = $this->request->getVar('members');
      //var_dump($_POST); die;
      $info = array(
          'branch' => $branch,
          'title' => $title,
          'message' => $message,
					'date' => time(),
      );
			$inboxmodel = new inboxmodel();
			$inbox_id = $inboxmodel->addNewInbox($info);

			if($inbox_id!=0){
				$inbox = $inboxmodel->getInboxInfo($inbox_id);
				//var_dump($article); die;
				if(count((array)$inbox)>0){
					  $settingsmodel = new settingsmodel();
						$server_key = $settingsmodel->getFcmServerKey();
						$fcmmodel = new fcmmodel();
						$fcmmodel->push_inbox_data($server_key,$inbox);
				}
			}

			$session = session();
			$session->setFlashdata('success', "Message sent successfully.");
			return redirect()->to(base_url().'/inbox');
    }

    function editInboxData()
    {
			$id = $this->request->getVar('id');
			$title = $this->request->getVar('title');
			$message = $this->request->getVar('message');
      $info = array(
          'title' => $title,
          'message' => $message,
      );
			$inboxmodel = new inboxmodel();
			$inboxmodel->editInbox($info, $id);
			$session = session();
			$session->setFlashdata('success', "Message updated successfully.");
			return redirect()->to(base_url().'/editInbox/'.$id);
    }

    function deleteInbox($id=0)
    {
			$inboxmodel = new inboxmodel();
      $inboxmodel->deleteInbox($id);
			$session = session();
      if($inboxmodel->status == "ok")
      {
          $session->setFlashdata('success', $inboxmodel->message);
      }
      else
      {
          $session->setFlashdata('error', $inboxmodel->message);
      }
      return redirect()->to(base_url().'/inbox');
    }
}
