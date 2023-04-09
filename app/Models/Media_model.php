<?php

namespace App\Models;

use CodeIgniter\Model;

class Media_model extends Model
{
    public $status = 'error';
    public $message = 'Error processing requested operation';

    public function __construct()
    {
        parent::__construct();
    }

    public function searchListing($query, $offset, $email)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*');
        $builder->where("(`title` LIKE '%$query%'");
        $builder->orwhere("`description` LIKE '%$query%')");
        $builder->orderby('dateInserted', 'desc');
        $builder->limit(30, $offset);

        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
            $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->comments_count = 0;//$this->get_total_comments($res->id);
            $res->user_liked = 0;//$this->checkIfUserLikedMedia($res->id,$email);
        }
        return $result;
    }

    public function update_media_total_views($media)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->set('views_count', 'views_count+1', FALSE);
        $builder->where('id', $media);
        $builder->update();
    }


    function getLatestMedia()
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*');
        $builder->orderby('dateInserted', 'desc');
        $builder->limit(10);
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
            $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->comments_count = 0;//$this->get_total_comments($res->id);
            $res->user_liked = 0;//$this->checkIfUserLikedMedia($res->id,$email);
        }
        return $result;
    }

    public function fetch_media($type, $page = 0, $email = "null")
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select('tbl_media.*');
        $builder->where('type', $type);
        $builder->orderby('id', 'desc');
        if ($page != 0) {
            $builder->limit(20, $page * 20);
        } else {
            $builder->limit(20);
        }
        $query = $builder->get();
        $result = $query->getResult();
        foreach ($result as $res) {
            $res->cover_photo = $this->get_thumbnail_source($res->cover_photo);
            $res->stream = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->download = $this->get_media_source($res->type, $res->video_type, $res->source);
            $res->comments_count = 0;//$this->get_total_comments($res->id);
            $res->user_liked = 0;//$this->checkIfUserLikedMedia($res->id,$email);
        }
        return $result;
    }

    public function get_total_media($type)
    {
        $db = \Config\Database::connect("default");
        $builder = $db->table('tbl_media');
        $builder->select("COUNT(*) as num");
        $builder->where('type', $type);
        $query = $builder->get();
        $result = $query->getRow(0);
        if (isset($result)) return $result->num;
        return 0;
    }


    private function get_thumbnail_source($source)
    {
        $array = array("https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/1.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/2.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/3.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/4.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/5.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/6.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/7.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/8.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/9.jpg",
            "https://envisionaps.nyc3.digitaloceanspaces.com/videos/beauty/10.jpg");
        if ($source == "") {
            return $array[array_rand($array)];
        }
        if ($this->isValidURL($source)) {
            return $source;
        }
        return base_url()."/public/uploads/thumbnails/".$source;
    }

    private function get_media_source($type, $video_type, $source)
    {
        /*if($this->isValidURL($source)){
          return $source;
        }
        if($type=="audio"){
          return site_url()."uploads/audios/".$source;
        }else{
          if($video_type == "mp4_video"){
            return site_url()."uploads/videos/".$source;
          }
          return $source;
        }*/
        if ($this->isValidURL($source)) {
            return $source;
        }
        if ($type == "audio") {
            //if($source == "")return "";
            return "https://envisionaps.nyc3.digitaloceanspaces.com/audios/" . $source;
        } else {
            if ($video_type == "mp4_video") {
                return base_url(). "/public/uploads/videos/";//"https://envisionaps.nyc3.digitaloceanspaces.com/videos/".$source;
//                https://4083-2401-4900-53f6-9314-c5bf-dd8e-7605-353f.ngrok-free.app/savaliya_family/public/uploads/videos/
            }
            return $source;
        }

    }

    function isValidURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

}

?>
