<?php

namespace App\Models;

use CodeIgniter\Model;

class Members_model extends Model
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
        $builder = $db->table('tbl_members');
        $builder->select("COUNT(*) as num");
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    function adminMembersListing($columnName, $columnSortOrder, $searchValue, $start, $length)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        if ($searchValue != "") {
            $builder->like('email', $searchValue);
            $builder->orlike('firstname', $searchValue);
            $builder->orlike('lastname', $searchValue);
        }
        if ($columnName != "") {
            $builder->orderby($columnName, $columnSortOrder);
        }
        $builder->limit($length, $start);

        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            if ($res->thumbnail != "") {
                $res->thumbnail = base_url() . "/public/uploads/members/" . $res->thumbnail;
            }
            $res->branchname = $this->getBranchName($res->branch);
        }
        return $result;
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

    public function get_total_members($searchValue = "")
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select("COUNT(*) as num");
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        if ($searchValue != "") {
            $builder->like('email', $searchValue);
            $builder->orlike('firstname', $searchValue);
            $builder->orlike('lastname', $searchValue);
        }
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    function getMembers($branch)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.email, tbl_members.phonenumber');
        if ($branch != 1) {
            $builder->where('branch', $branch);
        }
        $query = $builder->get();
        return $query->getResult();
    }

    function getMembersByListid($branch, $list)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.email, tbl_members.phonenumber');
        if ($branch != 1) {
            $builder->where("branch", $branch);
        }
        $subQuery = $db->table('tbl_list_members')->select('email')->where('listid', $list)->get();
        $items = $subQuery->getResult();
        $_itms = [];
        foreach ($items as $ress) {
            array_push($_itms, $ress->email);
        }
        //var_dump($_itms); die;
        if (count($items) > 0) {
            $builder->whereIn('email', $_itms);
        }

        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    function checkMembersExists($phonenumber, $id = 0)
    {
        //echo $name . " and ". $group;
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select("id");
        $builder->where("phonenumber", $phonenumber);
        if ($id != 0) {
            $builder->where("id !=", $id);
        }
        $query = $builder->get();
        return $query->getResult();
    }


    function addNewMember($info)
    {
        if (empty($this->checkMembersExists($info['email']))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_members');
            $builder->insert($info);
            $this->status = 'ok';
            $this->message = 'Member added successfully';
        } else {
            $this->status = 'error';
            $this->message = 'Member already exists with this email: ' . $info['email'];
        }
        return $this->db->insertID();
    }


    function editMember($info, $id)
    {
        if (empty($this->checkMembersExists($info['phonenumber'], $id))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_members');
            $builder->where('id', $id);
            if ($this->role != 0) {
                $builder->where('branch', $this->branch);
            }
            $builder->update($info);
            $this->status = 'ok';
            $this->message = 'Member details edited successfully';
        } else {
            $this->status = 'error';
            $this->message = 'Member already exists with this email: ' . $info['email'];
        }
    }


    function getMemberInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->select('tbl_members.*');
        $builder->where('id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $row = $query->getRow(0);
        if (count((array)$row) > 0 && $row->thumbnail != "") {
            if ($row->thumbnail != "") {
                $row->thumbnail = base_url() . "/public/uploads/members/" . $row->thumbnail;
            }
        }
        return $row;
    }

    function deleteMember($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_members');
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Member deleted successfully.';
    }

    public function readdata(){
        $query=$this->db->select('*')->get('tbl_members');
        return $query->result();
    }
}

?>
