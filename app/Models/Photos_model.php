<?php

namespace App\Models;

use CodeIgniter\Model;

class Photos_model extends Model
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

    function fetch_photos($page = 0)
    {
        $this->db = \Config\Database::connect();
        $builder = $this->db->table('tbl_photos');
        //$builder->select('DISTINCT(identifier)');
        $builder->select('tbl_photos.*');
        $builder->orderBy('id', 'DESC');
        if ($page != 0) {
            $builder->limit(20, $page * 20);
        } else {
            $builder->limit(20);
        }
        //$builder->from('tbl_clients');
        $query = $builder->get();
        $result = $query->getResult();
        //var_dump($result); die;
        foreach ($result as $res) {
            $res->date = date('F j, Y', strtotime($res->date));
            if ($res->thumbnail != "") {
                $media = json_decode($res->thumbnail);
                $res->thumbnail = [];
                foreach ($media as $mdia) {
                    $mdia = base_url() . "/public/uploads/photos/" . $mdia;
                    array_push($res->thumbnail, $mdia);
                }
            }
        }
        return $result;
    }

    public function get_total_photos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_photos');
        $builder->select("COUNT(id) AS num");
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    function photosListing()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_photos');
        $builder->select('tbl_photos.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
        $builder->join('tbl_branches', 'tbl_branches.id=tbl_photos.branch');
        if ($this->role != 0) {
            $builder->where('tbl_photos.branch', $this->branch);
        }
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            if ($res->thumbnail != "") {
                $media = json_decode($res->thumbnail);
                $res->thumbnail = [];
                foreach ($media as $mdia) {
                    $mdia = base_url()."/public/uploads/photos/" . $mdia;
                    array_push($res->thumbnail, $mdia);
                }
            }
        }
        return $result;
    }


    function addNewPhoto($info)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_photos');
        $builder->insert($info);
        $this->status = 'ok';
    }


    function getPhotoInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_photos');
        $builder->select('tbl_photos.*');
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->where('id', $id);
        $query = $builder->get();
        $row = $query->getRow(0);
        return $row;
    }

    function editPhoto($info, $id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_photos');
        $builder->where('id', $id);
        $builder->update($info);
        $this->status = 'ok';
        $this->message = 'Photo details edited successfully.';
    }

    function deletePhoto($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_photos');
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Photo deleted successfully.';
    }

    private function get_thumbnail_source($source)
    {
        if ($this->isValidURL($source)) {
            return $source;
        }
        return base_url() . "/public/uploads/photos/" . $source;
    }

    function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

?>
