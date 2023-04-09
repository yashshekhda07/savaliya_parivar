<?php

namespace App\Models;

use CodeIgniter\Model;

class Hymns_model extends Model
{
    protected $db;
    public $status = 'error';
    public $message = 'Error processing requested operation.';
    public $user = "";

    public function __construct()
    {
        parent::__construct();
        $builder = \Config\Database::connect();
    }

    function hymnsListing($page, $searchValue)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->select('tbl_hymns.*');
        if ($searchValue != "") {
            $builder->like('title', $searchValue);
            $builder->orlike('content', $searchValue);
        }
        if ($page != 0) {
            $builder->limit(20, $page * 20);
        } else {
            $builder->limit(20);
        }
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $row) {
            if ($row->thumbnail != "") {
                $row->thumbnail = base_url()."/public/uploads/thumbnails/" . $row->thumbnail;
            }
        }
        return $result;
    }

    public function getTotalItems()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->select("COUNT(*) as num");
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    function adminHymnsListing($columnName, $columnSortOrder, $searchValue, $start, $length)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->select('tbl_hymns.*');
        if ($searchValue != "") {
            $builder->like('title', $searchValue);
            $builder->orlike('content', $searchValue);
        }
        if ($columnName != "") {
            $builder->orderby($columnName, $columnSortOrder);
        }
        $builder->limit($length, $start);

        $query = $builder->get();
        $result = $query->getResult();
        return $result;
    }

    public function get_total_hymns($searchValue = "")
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        if ($searchValue == "") {
            $query = $builder->select("COUNT(*) as num")->get();
        } else {
            $builder->select("COUNT(*) as num");
            $builder->like('title', $searchValue);
            $builder->orlike('content', $searchValue);
            $query = $builder->get();
        }
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    function checkHymnExists($title, $id = 0)
    {
        //echo $name . " and ". $group;
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->select("id");
        $builder->where("title", $title);
        if ($id != 0) {
            $builder->where("id !=", $id);
        }
        $query = $builder->get();
        //var_dump($query->result()); die;
        return $query->getResult();
    }


    function addNewHymn($info)
    {
        if (empty($this->checkHymnExists($info['title']))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_hymns');
            $builder->insert($info);
            $this->status = 'ok';
            $this->message = 'Hymn added successfully';
        } else {
            $this->status = 'error';
            $this->message = 'Hymn already added for this title ' . $info['title'];
        }
        return $this->db->insertID();
    }


    function editHymn($info, $id)
    {
        if (empty($this->checkHymnExists($info['title'], $id))) {
            $db = \Config\Database::connect("default");
            $builder = $db->table('tbl_hymns');
            $builder->where('id', $id);
            $builder->update($info);
            $this->status = 'ok';
            $this->message = 'Hymn edited successfully';
        } else {
            $this->status = 'error';
            $this->message = 'title for this Hymn already exists for another';
        }
    }


    function getHymnInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->select('tbl_hymns.*');
        $builder->where('id', $id);
        $query = $builder->get();
        $row = $query->getRow(0);
        if (count((array)$row) > 0 && $row->thumbnail != "") {
            $row->thumbnail = base_url()."/public/uploads/thumbnails/" . $row->thumbnail;
        }
        return $row;
    }

    function deleteHymn($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_hymns');
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Hymn deleted successfully.';
    }
}

?>
