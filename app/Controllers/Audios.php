<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use Khill\Duration\Duration;
use App\Models\Audio_model as audiomodel;
use App\Models\Branches_model as branchesmodel;

class Audios extends BaseController
{
    protected $session;

    /**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        //$this->session = session();
        //$this->mediamodel = new mediamodel();
        $this->session = session();
    }

    public function index()
    {
        return $this->view("media/audiolisting", []);
    }

    function fetch()
    {
        // Datatables Variables
        $this->audiomodel = new audiomodel();
        $draw = intval($_POST['draw']);
        $start = intval($_POST['start']);
        $length = intval($_POST['length']);
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = "";
        if (isset($_POST['search']['value'])) {
            $searchValue = $_POST['search']['value']; // Search value
        }

        $columnName = "";
        if (isset($_POST['columns'][$columnIndex]['data'])) {
            $columnSortOrder = $_POST['columns'][$columnIndex]['data']; // Search value
        }

        $columnSortOrder = "ASC";
        if (isset($_POST['order'][0]['dir'])) {
            $columnSortOrder = $_POST['order'][0]['dir']; // Search value
        }


        $audios = $this->audiomodel->audioListing($columnName, $columnSortOrder, $searchValue, $start, $length);
        $total_audios = $this->audiomodel->get_total_audios($searchValue);
        //var_dump($users); die;
        $dat = array();

        $count = $start + 1;
        foreach ($audios as $r) {
            $dat[] = array(
                $count,//'.site_url()."stream?m=".$r->id.'
                '<audio controls preload="none">
                  <source src="' . $r->source . '" type="audio/mpeg">
                Your browser does not support the audio element.
                </audio>',
                $r->title,
                $r->description,
                '
                <div class="dropdown">
                  <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <i class="dw dw-more"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="' . base_url() . '/editAudio/' . $r->id . '"><i class="dw dw-edit2"></i> Edit</a>
                    <a data-type="audio" data-id="' . $r->id . '" class="dropdown-item" onclick="delete_item(event)">
                    <i data-type="audio" data-id="' . $r->id . '" class="dw dw-delete-3"></i> Delete</a>
                  </div>
                </div>
                '
            );
            $count++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_audios,
            "recordsFiltered" => $total_audios,
            "data" => $dat
        );
        echo json_encode($output);
    }

    public function newAudio()
    {
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("media/newaudio", $data);
    }

    public function editAudio($id = 0)
    {
        $this->audiomodel = new audiomodel();
        $data['audio'] = $this->audiomodel->getAudioInfo($id);
        if (count((array)$data['audio']) == 0) {
            return redirect()->to(base_url() . '/audios');
        }
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        $_duration = new Duration;
        $data['audio']->duration = $_duration->formatted(($data['audio']->duration) / 1000);
        return $this->view("media/editaudio", $data);
    }

    public function saveNewAudio()
    {
        $this->audiomodel = new audiomodel();
        $data = $this->get_data();
        if (isset($data) && isset($data->title)) {
            //var_dump($data); die;
            $branch = 0;
            if (isset($data->branch)) {
                $branch = $data->branch;
            }
            $media_type = 0;
            if (isset($data->media_type)) {
                $media_type = $data->media_type;
            }
            $title = $data->title;
            $category = 0;
            $subcategory = 0;

            $description = "";
            if (isset($data->description)) {
                $description = $data->description;
            }
            $duration = 0;
            if (isset($data->duration)) {
                $duration = $data->duration;
            }
            $is_free = 1;
            if (isset($data->is_free)) {
                $is_free = $data->is_free;
            }
            $can_download = 1;
            if (isset($data->can_download)) {
                $can_download = $data->can_download;
            }
            $can_preview = 1;
            $preview_duration = 0;

            $_duration = new Duration;
            $info = array(
                'branch' => $branch,
                'category' => $category,
                'title' => $title,
                'description' => $description,
                'is_free' => $is_free,
                'can_download' => $can_download,
                'can_preview' => $can_preview,
                'preview_duration' => $preview_duration,
                'sub_category' => $subcategory,
                'duration' => $_duration->toSeconds($duration) * 1000,
                'type' => 'audio'
            );

            if ($media_type == 0) {
                //upload image file
                $thumb_upload = $this->upload_thumbnail();
                $audio_upload = $this->upload_audio();

                //var_dump($audio_upload); die;
                //echo json_encode(array("status" => "error","msg" => $audio_upload)); die;
                //upload video file
                //if there are any error, display to user
                if ($audio_upload[0] == 'error' || $thumb_upload[0] == 'error') {
                    $msg = $audio_upload[0] == 'error' ? "Audio upload error: " . $audio_upload[1]['audio'] : "";
                    $msg .= $thumb_upload[0] == 'error' ? "\nThumbnail upload error: " . $thumb_upload[1]['thumbnail'] : "";
                    echo json_encode(array("status" => "error", "msg" => $msg));
                    exit;
                }

                $info['cover_photo'] = $thumb_upload[1];
                $info['source'] = $audio_upload[1];
            } else {
                $info['cover_photo'] = $data->thumbnail_link;
                $info['source'] = $data->media_link;
            }

            $this->audiomodel->addNewAudio($info);

        }
        echo json_encode(array("status" => $this->audiomodel->status, "msg" => $this->audiomodel->message));
        exit;
    }

    public function editAudioData()
    {
        $this->audiomodel = new audiomodel();
        $data = $this->get_data();
        if (!isset($data) || !isset($data->title)) {
            echo json_encode(array("status" => $this->audiomodel->status, "msg" => $this->audiomodel->message));
            exit;
        }

        $id = isset($data->id) ? $data->id : 0;
        $title = $data->title;
        $description = "";
        if (isset($data->description)) {
            $description = $data->description;
        }
        $branch = 0;
        if (isset($data->branch)) {
            $branch = $data->branch;
        }

        $duration = 0;
        if (isset($data->duration)) {
            $duration = $data->duration;
        }

        $_duration = new Duration;
        $info = array(
            'branch' => $branch,
            'title' => $title,
            'description' => $description,
            'duration' => $_duration->toSeconds($duration) * 1000,
        );

        $this->audiomodel->editAudioData($info, $id);

        echo json_encode(array("status" => $this->audiomodel->status, "msg" => $this->audiomodel->message));
        exit;
    }

    function deleteAudio($id = 0)
    {
        $this->audiomodel = new audiomodel();
        $audio = $this->audiomodel->getAudioInfo($id);
        if (count((array)$audio) > 0) {
            @unlink('./uploads/audios/' . $audio->source);
            @unlink('./uploads/thumbnails/' . $audio->cover_photo);
        }
        $this->audiomodel->deleteAudio($id);
        if ($this->audiomodel->status == "ok") {
            $this->session->setFlashdata('success', $this->audiomodel->message);
        } else {
            $this->session->setFlashdata('error', $this->audiomodel->message);
        }
        return redirect()->to(base_url() . '/audios');
    }

    public function upload_audio()
    {
        //var_dump($this->request->getFile('audio')); die;
        //echo $mimeType = $this->request->getFile('audio')->getClientmimeType(); die;
        helper(['form', 'url']);
        $input = $this->validate([
            'audio' => [
                'uploaded[audio]',
                'mime_in[audio,mp3,audio/mpeg,audio/mpg,audio/mpeg3,audio/mp3,application/octet-stream,]',
                'max_size[audio,100000]',
            ]
        ]);
        if (!$input) {
            //$data = ['errors' => $this->validator->getErrors()];
            //var_dump($data);
            return ['error', $this->validator->getErrors()];
        } else {

            $img = $this->request->getFile('audio');
            $img->move('./uploads/audios');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            //var_dump($data);
            return ['ok', $img->getName()];
        }
        /*$path = $_FILES['audio']['name'];
        $file_name = "audio_".time().".".pathinfo($path, PATHINFO_EXTENSION);
        $config['upload_path']          = './uploads/audios';
        $config['file_name'] = $file_name;
        //$config['max_size']             = 10000;
        $config['allowed_types']        = 'mp3';
        $config['overwrite'] = FALSE; //overwrite file

        //var_dump($config);
        $this->load->library('upload');
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('audio')){
            //$error = array('error' => $this->upload->display_errors());
            return ['error',strip_tags($this->upload->display_errors())];
        }else{
            $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
            return ['ok',$file_name];
        }*/
    }

    function upload_thumbnail()
    {
        /*$config['upload_path']          = './uploads/thumbnails';
        //$config['max_size']             = 10000;
        $config['allowed_types']        = 'jpeg|jpg|png|JPEG|PNG';
        $config['overwrite'] = TRUE; //overwrite file

        $this->load->library('upload');
        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload('thumbnail'))
        {
            //$error = array('error' => $this->upload->display_errors());
            return ['error',strip_tags($this->upload->display_errors())];
        }
        else{
           $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
            $file_name = $upload_data['file_name'];
           return ['ok',$file_name];
        }*/
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
            $img->move('./uploads/thumbnails');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }
}
