<?php

namespace App\Models;

use CodeIgniter\Model;

class Events_model extends Model
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

    function getUpcomingEvents()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->select('tbl_events.*');
        $builder->where('date >=', date("Y-m-d"));
        $builder->limit(3);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->thumbnail = base_url() . "/public/uploads/thumbnails/events/" . $res->thumbnail;
            $res->branchname = $this->getBranchName($res->branch);
        }
        return $result;
    }

    function fetchMonthsEvents($month, $year)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->where('month', $month);
        $builder->where('year', $year);
        $builder->orderBy('date', 'desc');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->thumbnail = base_url() . "/public/uploads/thumbnails/events/" . $res->thumbnail;
            $res->branchname = $this->getBranchName($res->branch);
        }
        return $result;
    }


    public function getTotalItems()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->select("COUNT(*) as num");
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    function eventsListing()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->select('tbl_events.*');
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->orderBy('date', 'DESC');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->thumbnail = base_url() . "/public/uploads/thumbnails/events/" . $res->thumbnail;
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

    function addNewEvent($info)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->insert($info);
        $this->status = 'ok';
        $this->message = 'Event added successfully';
        return $this->db->insertID();
    }


    function editEvent($info, $id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->where('id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->update($info);
        $this->status = 'ok';
        $this->message = 'Event edited successfully';
    }


    function getEventInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->select('tbl_events.*');
        $builder->where('id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $row = $query->getRow(0);
        if (count((array)$row) > 0) {
            $row->thumbnail = base_url() . "/uploads/thumbnails/events/" . $row->thumbnail;
        }
        return $row;
    }

    function deleteEvent($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_events');
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Event deleted successfully.';
    }
}

?>
