<?php

namespace App\Models;

use CodeIgniter\Model;

class Video_model extends Model
{
    protected $db;
    public $status = 'error';
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
        $builder = $db->table('tbl_media');
        $builder->select("COUNT(*) as num");
        $builder->where('type', 'video');
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    function videoListing($columnName, $columnSortOrder, $searchValue, $start, $length)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*,tbl_branches.id as branch_id,tbl_branches.name as branchname');
        $builder->join('tbl_branches', 'tbl_branches.id=tbl_media.branch');
        //$builder->from('tbl_media');
        //$builder->join('tbl_categories','tbl_categories.id=tbl_media.category');
        if ($this->role != 0) {
            $builder->where('tbl_media.branch', $this->branch);
        }
        $builder->where('type', 'video');
        if ($searchValue != "") {
            $builder->like('title', $searchValue);
            $builder->orlike('description', $searchValue);
        }
        if ($columnName != "") {
            $builder->orderby($columnName, $columnSortOrder);
        }
        $builder->limit($length, $start);

        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->source = $this->get_media_source($res->source);
        }
        return $result;
    }

    public function get_total_videos($searchValue = "")
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select("COUNT(*) as num");
        if ($this->role != 0) {
            $builder->where('tbl_media.branch', $this->branch);
        }
        $builder->where('tbl_media.type', 'video');
        if ($searchValue != "") {
            $builder->like('title', $searchValue);
            $builder->orlike('description', $searchValue);
        }

        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    function addNewVideo($info)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $info['dateInserted'] = date('Y-m-d H:i:s');
        $builder->insert($info);
        $this->status = 'ok';
        $this->message = $info['title'] . ' Uploaded successfully';
        return $this->db->insertID();
    }

    function getVideoInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*');
        $builder->where('tbl_media.id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $query = $builder->get();
        $row = $query->getRow(0);
        if (count((array)$row) > 0) {
            $row->thumbnail = $this->get_thumbnail_source($row->cover_photo);
            $row->video = $this->get_media_source($row->source);
        }
        return $row;
    }

    function editVideoData($info, $id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->where('id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->update($info);

        $this->status = 'ok';
        $this->message = 'Video Data edited successfully';
    }

    function deleteVideo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->where('id', $id);
        if ($this->role != 0) {
            $builder->where('branch', $this->branch);
        }
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Video Data deleted successfully.';
    }

    private function get_thumbnail_source($source)
    {
        if ($this->isValidURL($source)) {
            return $source;
        }
        return site_url() . "uploads/thumbnails/" . $source;
    }

    private function get_media_source($source)
    {
        if ($this->isValidURL($source)) {
            return $source;
        }
        return base_url() . "/public/uploads/videos/" . $source;
    }

    function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

}

?>
