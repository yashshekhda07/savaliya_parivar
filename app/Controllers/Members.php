<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Members_model as membersmodel;
use App\Models\Branches_model as branchesmodel;

class Members extends BaseController
{
    protected $session;
    protected $membersmodel;

    /**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->membersmodel = new membersmodel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM tbl_members");
        $results = $query->getResultArray();
        return $this->view("members/listing", ['results' => $results]);
    }

//    function getMembers(){
//        // Datatables Variables
//        $draw = intval($_POST['draw']);
//        $start = intval($_POST['start']);
//        $length = intval($_POST['length']);
//        $columnIndex = $_POST['order'][0]['column']; // Column index
//        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
//        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
//        $searchValue="";
//        if(isset($_POST['search']['value'])){
//          $searchValue = $_POST['search']['value']; // Search value
//        }
//
//        $columnName="";
//        if(isset($_POST['columns'][$columnIndex]['data'])){
//          $columnSortOrder = $_POST['columns'][$columnIndex]['data']; // Search value
//        }
//
//        $columnSortOrder = "ASC";
//        if(isset($_POST['order'][0]['dir'])){
//          $columnSortOrder = $_POST['order'][0]['dir']; // Search value
//        }
//
//
//        $feeds = $this->membersmodel->adminMembersListing($columnName,$columnSortOrder,$searchValue,$start, $length);
//        $total_feeds = $this->membersmodel->get_total_members($searchValue);
//        //var_dump($feeds); die;
//        $dat = array();
//
//         $count = $start + 1;
//        foreach($feeds as $r) {
//          //var_dump($r); die;
//          //$title = substr($r->title,0,10 );
//          //$content = substr($r->content,0,50 );
//
//             $dat[] = array(
//                  $count,
//                  $r->email,
//                  $r->firstname,
//                  $r->lastname,
//                  $r->age,
//                  '
//	                <div class="dropdown">
//	                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
//	                    <i class="dw dw-more"></i>
//	                  </a>
//	                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
//	                    <a class="dropdown-item" href="'.base_url().'/editMember/'.$r->id.'"><i class="dw dw-edit2"></i> Edit</a>
//	                    <a data-type="members" data-id="'.$r->id.'" class="dropdown-item" onclick="delete_item(event)">
//	                    <i data-type="members" data-id="'.$r->id.'" class="dw dw-delete-3"></i> Delete</a>
//	                  </div>
//	                </div>
//	                '
//             );
//             $count++;
//        }
//
//        $output = array(
//             "draw" => $draw,
//               "recordsTotal" => $total_feeds,
//               "recordsFiltered" => $total_feeds,
//               "data" => $dat
//          );
//        echo json_encode($output);
//    }

    function getMembers() {
        $this->db = \Config\Database::connect();
        $this->db->select('users');
        $this->db->from('Users');
        $this->db->where('users_groups.user_id', 1);
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $query = $this->db->get();
        $result = $query->result_array();

        var_dump($result); // display data in array();
        return $result;
    }

    public function newMember()
    {
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("members/new", $data);
    }

    public function editMember($id = 0)
    {
        $data['member'] = $this->membersmodel->getMemberInfo($id);
        if (count((array)$data['member']) == 0) {
            return redirect()->to(base_url() . '/membersListing');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing(0);
        return $this->view("members/edit", $data);
    }

    function saveNewMember()
    {
        $branch = $this->request->getVar('branch');
        $firstname = $this->request->getVar('firstname');
        $lastname = $this->request->getVar('lastname');
        $gender = $this->request->getVar('gender');
        $occupation = $this->request->getVar('occupation');
        $phonenumber = $this->request->getVar('phonenumber');
        $email = $this->request->getVar('email');
        $address = $this->request->getVar('address');
        $facebook = $this->request->getVar('facebook');
        $twitter = $this->request->getVar('twitter');
        $linkedln = $this->request->getVar('linkedln');
        $dob = $this->request->getVar('dob');

        $_date = \DateTime::createFromFormat("Y-m-d", $dob);
        $year = $_date->format("Y") + 0;
        $month = $_date->format("m") + 0;
        $day = $_date->format("d") + 0;


        $info = array(
            'age' => $this->getAge($dob),
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'dob' => $dob,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'gender' => $gender,
            'occupation' => $occupation,
            'phonenumber' => $phonenumber,
            'email' => $email,
            'address' => $address,
            'facebook' => $facebook,
            'twitter' => $twitter,
            'linkedln' => $linkedln,
            'branch' => $branch,
        );

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = $this->upload_thumbnail();
            if ($upload[0] == 'ok') {
                $info['thumbnail'] = $upload[1];
            }
        }

        $this->membersmodel->addNewMember($info);
        if ($this->membersmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->membersmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->membersmodel->message);
        }
        //redirect('newBranch');
        return redirect()->to(base_url() . '/newMember');

    }


    function editMemberData()
    {
        $id = $this->request->getVar('id');
        $branch = $this->request->getVar('branch');
        $name = $this->request->getVar('name');
        $gender = $this->request->getVar('gender');
        $phonenumber = $this->request->getVar('phonenumber');
        $whatsappnumber = $this->request->getVar('whatsappnumber');
        $occupation = $this->request->getVar('occupation');
        $villagename = $this->request->getVar('villagename');
        $jillo = $this->request->getVar('jillo');
        $taluko = $this->request->getVar('taluko');

        $info = array(
            'name' => $name,
            'gender' => $gender,
            'occupation' => $occupation,
            'phonenumber' => $phonenumber,
            'whatsappnumber' => $whatsappnumber,
            'villagename' => $villagename,
            'jillo' => $jillo,
            'taluko' => $taluko,
            'branch' => $branch,
        );

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = $this->upload_thumbnail();
            if ($upload[0] == 'ok') {
                $info['thumbnail'] = $upload[1];
            }
        }

        $this->membersmodel->editMember($info, $id);
        if ($this->membersmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->membersmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->membersmodel->message);
        }
        return redirect()->to(base_url() . '/editMember/' . $id);
        //redirect('editBranch/'.$id);
    }


    function deleteMember($id = 0)
    {
        $this->membersmodel->deleteMember($id);
        if ($this->membersmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->membersmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->membersmodel->message);
        }
        return redirect()->to(base_url() . '/membersListing');
        //redirect('branchesListing');
    }

    function getAge($dateofbirth)
    {
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateofbirth), date_create($today));
        return $diff->format('%y');
    }

    function upload_thumbnail()
    {
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
            return ['error', $this->validator->getErrors()];
        } else {
            $img = $this->request->getFile('thumbnail');
            $img->move('./uploads/members');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }
}
