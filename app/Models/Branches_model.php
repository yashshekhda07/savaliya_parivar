<?php

namespace App\Models;

use CodeIgniter\Model;

class Branches_model extends Model
{
    protected $db;
    public $status = 'error';
    public $message = 'Error processing requested operation.';
    public $role = 0;
    public $branch = 0;

    public function __construct()
    {
        parent::__construct();
        $builder = \Config\Database::connect();
        $session = session();
        $this->role = $session->get('role');
        $this->branch = $session->get('branch');
    }

    public function getTotalItems()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select("COUNT(*) as num");
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    public function fetch_items($page = 0)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select('tbl_branches.*');
        $builder->where('id !=', 1);
        $builder->orderby('name', 'asc');
        /*if($page!=0){
            $builder->limit(20,$page * 20);
        }else{
          $builder->limit(20);
        }*/
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    public function get_total_items()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select("COUNT(*) as num");
        $builder->where('id !=', 1);
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    public function fetch_branches($page = 0)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select('tbl_branches.*');

        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }


    public function get_total_branches()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $query = $builder->select("COUNT(*) as num")->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    function branchesListing($admin = 1)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select('tbl_branches.*');
        if ($this->role != 0) {
            $builder->where('tbl_branches.id', $this->branch);
        }
        $builder->orderBy('name', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    function checkNameExists($name, $id = 0)
    {
        //echo $name . " and ". $group;
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select("name");
        $builder->where("name", $name);
        if ($id != 0) {
            $builder->where("id !=", $id);
        }
        $query = $builder->get();
        //var_dump($query->result()); die;
        return $query->getResult();
    }


    function addNewBranch($info)
    {
        if (empty($this->checkNameExists($info['name']))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_branches');
            $builder->insert($info);
            $this->status = 'ok';
            $this->message = 'New Church Branch added successfully';
        } else {
            $this->status = 'error';
            $this->message = 'Church Branch already exists';
        }
    }


    function editBranch($info, $id)
    {
        if (empty($this->checkNameExists($info['name'], $id))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_branches');
            $builder->where('id', $id);
            $builder->update($info);
            $this->status = 'ok';
            $this->message = 'Church Branch edited successfully';
        } else {
            $this->status = 'error';
            $this->message = 'Church Branch already exists';
        }
    }


    function getBranchInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select('tbl_branches.*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query->getRow(0);
    }

    function deleteBranch($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Church branch deleted successfully.';
    }

    function getBranchSettings($branch)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branch_settings');
        $builder->select('tbl_branch_settings.*');
        $builder->where('branch', $branch);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row && $row->features == "") {
            $row->features = "audiomessages, videomessages, donations, livestreams, events, articles, hymns, radio, photos, groups, prayer, testimony, devotionals, notes, books, gosocial";
        }
        if (!$row) {
            $row = new \stdClass;
            $row->join_groups = 0;
            $row->post_prayer = 0;
            $row->post_testimony = 0;
            $row->auto_approve_testimony = 0;
            $row->auto_approve_prayer = 0;
            $row->auto_approve_group_membership = 0;
            $row->facebook = "";
            $row->youtube = "";
            $row->twitter = "";
            $row->instagram = "";
            $row->website = "";
            $row->mail_username = "";
            $row->mail_password = "";
            $row->mail_smtp_host = "";
            $row->mail_protocol = "";
            $row->mail_port = "";

            $row->twilio_account_sid = "";
            $row->twilio_auth_token = "";
            $row->twilio_phonenumber = "";
            $row->termi_sender_id = "";
            $row->termi_apikey = "";

            $row->flutterwaves_api_key = "";
            $row->paystack_api_key = "";
            $row->currency_code = "";
            $row->donations_link = "";
            $row->fcm_server_key = "";
        }
        $row->branchname = $this->getBranchName($branch);
        return $row;
    }

    function getBranchName($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branches');
        $builder->select('tbl_branches.name');
        $builder->where('id', $id);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row) {
            return $row->name;
        } else {
            return "---";
        }
    }

    function updateSettings($info, $branch)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_branch_settings');
        $builder->select('tbl_branch_settings.id');
        $builder->where('branch', $branch);
        $query = $builder->get();
        $row = $query->getRow(0);
        if ($row) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_branch_settings');
            $builder->where('branch', $branch);
            $builder->update($info);
        } else {
            $info['branch'] = $branch;
            $builder->insert($info);
        }

    }
}

?>
