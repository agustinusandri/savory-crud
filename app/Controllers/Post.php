<?php

namespace App\Controllers;


use App\Models\PostModel;

class Post extends BaseController
{

public function index()
{
    // Model initialization
    $postModel = new PostModel();

    // Sort data by ID in descending order (newest first)
    $posts = $postModel->orderBy('id', 'DESC')
                       ->paginate(4, 'post');

    // Initialize pager
    $pager = \Config\Services::pager();

    // Prepare data for view
    $data = [
        'posts' => $posts,
        'pager' => $pager
    ];

    return view('post-index', $data);
}

    /**
     * create function
     */
    public function create()
    {
        return view('post-create');
    }

    /**
     * store function
     */
    public function store()
    {
        //load helper form dan URL
        helper(['form', 'url']);
         
        //Jika form data tidak diinput maka muncul error
        $validation = $this->validate([
            'title' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan Judul Post.'
                ]
            ],
            'content'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan konten Post.'
                ]
            ],
        ]);

        // Jika ada data yang belum diinput, maka kembali ke form create
        if(!$validation) {

            //render view with error validation message
            return view('post-create', [
                'validation' => $this->validator
            ]);

            // Jika semua data terisi, maka lanjut untuk insert data
        } else {

            //model initialize
            $postModel = new PostModel();
                    // Check jika title sudah ada di tabel
            $existingPost = $postModel->where('title', $this->request->getPost('title'))->first();

            if ($existingPost) {
                
                $session = session();
                $session->setFlashdata('alert', [
                    'type' => 'danger', // Use 'danger' for error messages
                    'message' => 'Nama sudah ada di table!'
                ]);

                return redirect()->to(base_url('post'));
            } else {

                $postModel->insert([
                    'title'   => $this->request->getPost('title'),
                    'content' => $this->request->getPost('content'),
                ]);
    
                //flash message
                session()->setFlashdata('message', 'Post Berhasil Disimpan');
                session()->setFlashdata("success", "Berhasil menambahkan data");
    
    
                return redirect()->to(base_url('post'));
            }
                
        }



    }
     /**
     * edit function
     */
    public function edit($id)
    {
        //model initialize
        $postModel = new PostModel();

        $data = array(
            'post' => $postModel->find($id)
        );

        return view('post-edit', $data);
    }

    /**
     * update function
     */
    public function update($id)
    {
        //load helper form and URL
        helper(['form', 'url']);
         
        //define validation
        $validation = $this->validate([
            'title' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan Judul Post.'
                ]
            ],
            'content'    => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Masukkan konten Post.'
                ]
            ],
        ]);

        if(!$validation) {

            //model initialize
            $postModel = new PostModel();

            //render view with error validation message
            return view('post-edit', [
                'post' => $postModel->find($id),
                'validation' => $this->validator
            ]);

        } else {

             //model initialize
            $postModel = new PostModel();

                    // Check jika title sudah ada di tabel
            $existingPost = $postModel->where('title', $this->request->getPost('title'))->first();
            $existingPost = $postModel->where('content', $this->request->getPost('content'))->first();
     

            if ($existingPost) {
                
                $session = session();
                $session->setFlashdata('alert', [
                    'type' => 'danger', // Use 'danger' for error messages
                    'message' => 'Nama sudah ada di table!'
                    
                    
                ]);

                return redirect()->to(base_url('post'));
            } else {

                //model initialize
            $postModel = new PostModel();
            
            //insert data into database
            $postModel->update($id, [
                'title'   => $this->request->getPost('title'),
                'content' => $this->request->getPost('content'),
            ]);

            //flash message
            session()->setFlashdata('message', 'Post Berhasil Diupdate');
            session()->setFlashdata("success", "Data berhasil di ubah");

            return redirect()->to(base_url('post'));
            }

        }

    }

     /**
     * delete function
     */
    public function delete($id)
    {
        //model initialize
        $postModel = new PostModel();

        $post = $postModel->find($id);

        if($post) {
            $postModel->delete($id);

            //flash message
            session()->setFlashdata('message', 'Post Berhasil Dihapus');
            session()->setFlashdata("success", "This is success delete");


            return redirect()->to(base_url('post'));
        }
        
    }


}