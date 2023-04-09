<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Books_model as booksmodel;

//use App\Models\Home_model as homemodel;

class Books extends BaseController
{
    protected $session;
    protected $booksmodel;

    /**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->booksmodel = new booksmodel();
    }

    public function index()
    {
        $data['books'] = $this->booksmodel->booksListing();
        return $this->view("books/listing", $data);
    }

    public function newBook()
    {
        return $this->view("books/new", []);
    }

    public function editBook($id = 0)
    {
        $data['book'] = $this->booksmodel->getBookInfo($id);
        if (count((array)$data['book']) == 0) {
            return redirect()->to(base_url() . '/books');
        }
        return $this->view("books/edit", $data);
    }

    function saveNewBook()
    {
        if (empty($_FILES['thumbnail']['name'])) {
            $this->session->setFlashdata('error', "You need to select ebook thumbnail");
        } else if (empty($_FILES['book']['name'])) {
            $this->session->setFlashdata('error', "You need to select a pdf file to upload");
        } else {
            $title = $this->request->getVar('title');
            $description = $this->request->getVar('description');
            $author = $this->request->getVar('author');
            $pages = $this->request->getVar('pages');

            $info = array(
                'title' => $title,
                'description' => $description,
                'author' => $author,
                'pages' => $pages,
            );
            $upload = $this->upload_thumbnail();
            if ($upload[0] == 'ok') {
                $info['thumbnail'] = $upload[1];
            } else {
                $this->session->setFlashdata('error', $upload[1]['thumbnail']);
                return redirect()->to(base_url() . '/editBook/' . $id);
            }
            $upload2 = $this->upload_book();
            if ($upload2[0] == 'ok') {
                $info['book'] = $upload2[1];
            } else {
                //var_dump($upload2); die;
                $this->session->setFlashdata('error', $upload2[1]['book']);
                return redirect()->to(base_url() . '/editBook/' . $id);
            }
            $this->booksmodel->addNewBook($info);
            if ($this->booksmodel->status == "ok") {
                $this->session->setFlashdata('success', $this->booksmodel->message);
            } else {
                $this->session->setFlashdata('error', $this->booksmodel->message);
            }
        }
        return redirect()->to(base_url() . '/books');

    }


    function editBookData()
    {
        $id = $this->request->getVar('id');
        $title = $this->request->getVar('title');
        $description = $this->request->getVar('description');
        $author = $this->request->getVar('author');
        $pages = $this->request->getVar('pages');

        $info = array(
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'pages' => $pages,
        );

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = $this->upload_thumbnail();
            if ($upload[0] == 'ok') {
                $info['thumbnail'] = $upload[1];
            } else {
                $this->session->setFlashdata('error', $upload[1]['thumbnail']);
                return redirect()->to(base_url() . '/editBook/' . $id);
            }
        }
        if (!empty($_FILES['book']['name'])) {
            $upload2 = $this->upload_book();
            if ($upload2[0] == 'ok') {
                $info['book'] = $upload2[1];
            } else {
                $this->session->setFlashdata('error', $upload2[1]['book']);
                return redirect()->to(base_url() . '/editBook/' . $id);
            }
        }

        $this->booksmodel->editBook($info, $id);
        if ($this->booksmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->booksmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->booksmodel->message);
        }
        return redirect()->to(base_url() . '/editBook/' . $id);
        //redirect('editBranch/'.$id);
    }


    function deleteBook($id = 0)
    {
        $book = $this->booksmodel->getBookInfo($id);
        if (count((array)$book) > 0) {
            @unlink('./uploads/thumbnails/' . $book->thumb);
            @unlink('./uploads/books/' . $book->pdf);
        }
        $this->booksmodel->deleteBook($id);
        if ($this->booksmodel->status == "ok") {
            $this->session->setFlashdata('success', $this->booksmodel->message);
        } else {
            $this->session->setFlashdata('error', $this->booksmodel->message);
        }
        return redirect()->to(base_url() . '/books');
        //redirect('branchesListing');
    }

    function upload_thumbnail()
    {
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

    function upload_book()
    {
        helper(['form', 'url']);
        $input = $this->validate([
            'book' => [
                'uploaded[book]',
                'mime_in[book,application/pdf]',
                'max_size[book,100024]',
            ]
        ]);
        if (!$input) {
            //$data = ['errors' => $this->validator->getErrors()];
            return ['error', $this->validator->getErrors()];
        } else {
            $img = $this->request->getFile('book');
            $img->move('./uploads/books');
            $data = [
                'name' => $img->getName(),
                'type' => $img->getClientMimeType()
            ];
            return ['ok', $img->getName()];
        }
    }
}
