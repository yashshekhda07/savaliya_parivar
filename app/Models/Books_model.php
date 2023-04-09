<?php

namespace App\Models;

use CodeIgniter\Model;

class Books_model extends Model
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

    function biblesListing()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_bible_versions');
        $builder->select('tbl_bible_versions.*');
        $builder->orderby('name', 'ASC');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->source = base_url() . "/public/uploads/" . $res->source;
        }
        return $result;
    }

    function getLatestBooks()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select('tbl_books.*');
        $builder->orderby('id', 'desc');
        $builder->limit(2);
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $row) {
            $row->thumbnail = base_url() . "/public/uploads/thumbnails/" . $row->thumbnail;
            $row->book = base_url() . "/public/uploads/books/" . $row->book;
        }
        return $result;
    }

    public function fetch_books($page = 0)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select('tbl_books.*');
        $builder->orderby('id', 'desc');
        if ($page != 0) {
            $builder->limit(20, $page * 20);
        } else {
            $builder->limit(20);
        }
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $row) {
            $row->thumbnail = base_url() . "/public/uploads/thumbnails/" . $row->thumbnail;
            $row->book = base_url() . "/public/uploads/books/" . $row->book;
        }
        return $result;
    }

    public function get_total_books()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select("COUNT(*) as num");
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }

    public function getTotalItems()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select("COUNT(*) as num");
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    function booksListing()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select('tbl_books.*');
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $row) {
            $row->thumbnail = base_url() . "/public/uploads/thumbnails/" . $row->thumbnail;
            $row->book = base_url() . "/public/uploads/books/" . $row->book;
        }
        return $result;
    }


    function addNewBook($info)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->insert($info);
        $this->status = 'ok';
        $this->message = 'Book added successfully';
    }


    function editBook($info, $id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->where('id', $id);
        $builder->update($info);
        $this->status = 'ok';
        $this->message = 'Book edited successfully';
    }


    function getBookInfo($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->select('tbl_books.*');
        $builder->where('id', $id);
        $query = $builder->get();
        $row = $query->getRow(0);
        if (count((array)$row) > 0 && $row->thumbnail != "") {
            $row->thumb = $row->thumbnail;
            $row->pdf = $row->book;
            $row->thumbnail = base_url() . "/public/uploads/thumbnails/" . $row->thumbnail;
            $row->book = base_url() . "/public/uploads/books/" . $row->book;
        }
        return $row;
    }

    function deleteBook($id)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_books');
        $builder->where('id', $id);
        $builder->delete();
        $this->status = 'ok';
        $this->message = 'Book deleted successfully.';
    }
}

?>
