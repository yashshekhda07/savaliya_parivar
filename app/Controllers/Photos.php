<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Photos_model as photosmodel;
use App\Models\Branches_model as branchesmodel;

//use App\Models\Home_model as homemodel;

class Photos extends BaseController
{
    protected $session;
    protected $photosmodel;

    /**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->photosmodel = new photosmodel();
    }

    public function index()
    {
        $data['photos'] = $this->photosmodel->photosListing();
        return $this->view("photos/listing", $data);
    }

    public function newPhotos()
    {
        $this->branchesmodel = new branchesmodel();
        $data['branches'] = $this->branchesmodel->branchesListing();
        return $this->view("photos/new", $data);
    }

    public function editPhoto($id = 0)
    {
        $data['photo'] = $this->photosmodel->getPhotoInfo($id);
        if (count((array)$data['photo']) == 0) {
            return redirect()->to(base_url() . '/photos');
        }
        return $this->view("photos/edit", $data);
    }

    function savenewphoto()
    {
        $upload_files = [];
        if ($this->request->getFileMultiple('file')) {
            foreach ($this->request->getFileMultiple('file') as $file) {
                $file->move('./uploads/photos');
                $data = [
                    'name' => $file->getClientName(),
                    'type' => $file->getClientMimeType()
                ];
                array_push($upload_files, $file->getName());
            }
        }

        $branch = 1;
        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $info = array(
            'branch' => $branch,
            'description' => $description,
            'title' => $title,
            'thumbnail' => json_encode($upload_files)
        );
        //var_dump($info); die;
        $this->photosmodel->addNewPhoto($info);
        echo $this->photosmodel->message;
    }

    function editPhotoData()
    {
        $id = $this->request->getVar('id');
        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $info = array(
            'description' => $description,
            'title' => $title,
        );
        $this->photosmodel->editPhoto($info, $id);
        if ($this->photosmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->photosmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->photosmodel->message);
        }
        return redirect()->to(base_url() . '/photos');

    }

    function deletePhoto($id = 0)
    {
        $this->photosmodel->deletePhoto($id);
        if ($this->photosmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->photosmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->photosmodel->message);
        }
        return redirect()->to(base_url() . '/photos');
        //redirect('branchesListing');
    }

    function upload_thumbnail()
    {
        helper(['form', 'url']);
        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png]',
                'max_size[file,10024]',
            ]
        ]);
        if (!$input) {
            //$data = ['errors' => $this->validator->getErrors()];
            return ['error', $this->validator->getErrors()];
        } else {
            $img = $this->request->getFile('file');
            $img->move('./uploads/photos');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }
}
