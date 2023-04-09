<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Donations_model as donationsmodel;
use App\Models\Branches_model as branchesmodel;
use App\Models\Settings_model as settingsmodel;
//use App\Models\Home_model as homemodel;

class Donations extends BaseController
{
   protected $session;
   protected $devotionalsmodel;

	/**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->donationsmodel = new donationsmodel();
    }

    public function index(){
        //$data['userRecords'] = $this->devotionalsmodel->usersListing();
        return $this->view("donations/listing", []);
    }

    function donationslisting(){
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


        $users = $this->donationsmodel->donationsListing($columnName,$columnSortOrder,$searchValue,$start, $length);
				$total = $this->donationsmodel->get_total_donations($searchValue);
				//var_dump($users); die;
				$dat = array();

				 $count = $start + 1;
				foreach($users as $r) {
						 $dat[] = array(
									$count,
									$r->reason,
									$r->email,
									$r->name,
									$r->reference,
									$r->amount,
									$r->method,
									$r->date
						 );
						 $count++;
				}

        $output = array(
             "draw" => $draw,
               "recordsTotal" => $total,
               "recordsFiltered" => $total,
               "data" => $dat
          );
        echo json_encode($output);
    }

    public function donate()
    {
      $settingsmodel = new settingsmodel();
      $data['settings'] = $settingsmodel->getSettings();
      //$data['transid'] = $this->generate_string();
      //$data['hash'] = hash( "sha512","C0Dr8m|12345|1000|Shopping|Vinay|vinay@test.com|3sf0jURk");
      return view("donations/donate", $data);
    }


  function generate_string($strength = 16) {
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $input_length = strlen($input);
      $random_string = '';
      for($i = 0; $i < $strength; $i++) {
          $random_character = $input[mt_rand(0, $input_length - 1)];
          $random_string .= $random_character;
      }
      return $random_string;
  }

    public function savedonation(){
  		 $data = $this->get_data();
  		 //var_dump($data); die;
  		 if(!empty($data)){
         $branch = isset($data->branch)?filter_var($data->branch, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
  			 $reason = isset($data->reason)?filter_var($data->reason, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
  			 $method = isset($data->method)?filter_var($data->method, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
  			 $email = isset($data->email)?filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
  			 $name = isset($data->name)?filter_var($data->name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";
  			 $amount = isset($data->amount)?filter_var($data->amount, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):0;
  			 $reference = isset($data->reference)?filter_var($data->reference, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH):"";

         $pay_ref['branch'] = $branch;
  			 $pay_ref['email'] = $email;
  			 $pay_ref['name'] = $name;
  			 $pay_ref['reason'] = $reason;
  			 $pay_ref['reference'] = $reference;
  			 $pay_ref['amount'] = $amount;
  			 $pay_ref['method'] = $method;
         $pay_ref['day'] = date('d');
         $pay_ref['month'] = date('m');
         $pay_ref['year'] = date('Y');
  				$this->donationsmodel->recordDonation($pay_ref);
  			 echo json_encode(array("status" => $this->donationsmodel->status,"message" => $this->donationsmodel->message));
  			 exit;
  	 }else{
  		 echo json_encode(array("status" => "error","message" => "No data found for this transaction"));
  	 }

   }

    function thank_you(){
        return view("donations/thank_you");
		}
}
