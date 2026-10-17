<?php
defined('BASEPATH') or exit('No direct script access allowed');

class produk_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin_logged_in();
        // Load model
        $this->load->model('produk_model');
        $this->load->model('produk_kategori_model');
        $this->load->model('produk_gambar_model');
    }

    public function index() {
        $data['title'] = 'Daftar Produk';
        $data['list_produk'] = $this->produk_model->get_all();
        $this->load->view('administrator/templates/header', $data);
        $this->load->view('administrator/templates/sidebar');
        $this->load->view('administrator/produk/index');
        $this->load->view('administrator/templates/footer');
    }

    public function tambah_produk() {
        $data['title'] = 'Tambah Produk';
        $this->form_validation->set_rules('nama_produk', 'Nama produk', 'required');
        $this->form_validation->set_rules('kategori_produk', 'Kategori', 'required');
        if ($this->form_validation->run() !== FALSE) {
            $this->__simpan_produk();
        } 

        else {
            $data['list_kategori'] = $this->produk_kategori_model->get_all();
            $this->load->view('administrator/templates/header', $data);
            $this->load->view('administrator/templates/sidebar');
            $this->load->view('administrator/produk/tambah_produk', $data);
            $this->load->view('administrator/templates/footer');
        }
    }

    private function __simpan_produk() {
        $data = [
            'nama' => ($this->input->post('nama_produk')),
            'kategori_id' => $this->input->post('kategori_produk'),
            'harga' => $this->input->post('harga_produk'),
            'stok' => $this->input->post('stok_produk'),
            'deskripsi' => ($this->input->post('deskripsi_produk'))
        ];

        # simpan dan return id_produk yang disimpan
        $id_produk = $this->produk_model->tambah($data);
        # simpan data, terus lakukan upload gambar kebalikan id produk

        $count = count($_FILES['gambar_produk']['name']);
            if ($count > 0) {
                // jika ada gambar upload
                $this->__produk_gambar_upload($count, $id_produk);
            }

            $this->session->set_flashdata('message', '
                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                    <div>
                        Berhasil Tambah Produk-!
                    </div>
                </div>
            ');
        redirect('admin/produk');
    }

    private function __produk_gambar_upload($count, $id_produk) {
        for ($i = 0; $i < $count; $i++) {
            if (!empty($_FILES['gambar_produk']['name'][$i])) {
                $_FILES['file']['name'] = $_FILES['gambar_produk']['name'][$i];
                $_FILES['file']['type'] = $_FILES['gambar_produk']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['gambar_produk']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['gambar_produk']['error'][$i];
                $_FILES['file']['size'] = $_FILES['gambar_produk']['size'][$i];

                $config['upload_path'] = 'uploads/produk/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '5000';
                // $config['file_name'] = $_FILES['gambar_produk']['name'][$i];
                $config['file_name'] = "produk-" . $id_produk . "-" . $i;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $filename = $uploadData['file_name'];
                    $data = [
                        'nama_gambar' => $filename,
                        'produk_id' => $id_produk
                    ];
                    $this->produk_gambar_model->tambah($data);
                }
            }
        }
    }

    public function ubah_produk($id) {
        // check dulu apakah id dengan produk ada?
        $produk = $this->produk_model->get_by_id($id);
        if ($produk) {
            $this->form_validation->set_rules('nama_produk', 'Nama produk', 'required');
            $this->form_validation->set_rules('kategori_produk', 'Kategori', 'required');
                if ($this->form_validation->run() !== FALSE) {
                    $this->__ubah_produk($id);
                } 

                else {
                    $data['title'] = 'Ubah produk';
                    $data['produk'] = $produk;
                    $data['list_kategori'] = $this->produk_kategori_model->get_all();
                    $data['gambar_model'] = $this->produk_gambar_model;
                    $this->load->view('administrator/templates/header', $data);
                    $this->load->view('administrator/templates/sidebar');
                    $this->load->view('administrator/produk/ubah_produk', $data);
                    $this->load->view('administrator/templates/footer');
                }
        } 

        else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Produk Tidak Ditemukan-!
                </div>
            </div>
            ');
            redirect('admin/produk');
        }
    }

    private function __ubah_produk($id) {

        $data = [
            'nama' => $this->input->post('nama_produk'),
            'kategori_id' => $this->input->post('kategori_produk'),
            'harga' => $this->input->post('harga_produk'),
            'stok' => $this->input->post('stok_produk'),
            'deskripsi' => $this->input->post('deskripsi_produk')
        ];

        $this->produk_model->ubah($data, $id);

        $db_error = $this->db->error();
        $update_berhasil = empty($db_error['code']);

        $upload_berhasil = false;
        if (!empty($_FILES['gambar_produk']['name'][0])) {
            $count = count($_FILES['gambar_produk']['name']);
            if ($count > 0) {
                $this->__produk_gambar_upload($count, $id);
                $upload_berhasil = true;
            }
        }

        if ($update_berhasil || $upload_berhasil) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Data Produk Berhasil Diperbarui-!
                </div>
            </div>
            ');
        } 

        else {

            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Gagal Memperbarui Produk-!
                </div>
            </div>
            ');
        }
        
        redirect('admin/produk');
    }

    public function hapus_produk($id) {
        // check apakah ada produk
        $produk = $this->produk_model->get_by_id($id);
        if ($produk) {
            // ambil data gambar
            $list_gambar = $this->produk_gambar_model->get_by_produk_id($id);
            foreach ($list_gambar as $gambar) {
                $id_gambar = $gambar['id_gambar'];
                $nama_gambar = $gambar['nama_gambar'];
                // hapus gambar di database
                $this->produk_gambar_model->hapus($id_gambar);
                // hapus gambar di file
                $path = './uploads/produk/' . $nama_gambar;
                unlink($path);
            }

            // hapus produk di database
            $this->produk_model->hapus($id);
            $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Berhasil Hapus Produk-!
                </div>
            </div>
            ');
            redirect('admin/produk');
        } 

        else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Produk Tidak Ditemukan-!
                </div>
            </div>
            ');
            redirect('admin/produk');
        }
    }

    public function hapus_gambar($id_gambar) {
        //ambil data gambar berdasarkan id_gambar
        $gambar = $this->produk_gambar_model->get_by_id($id_gambar);
        
        if ($gambar) {
            $produk_id = $gambar['produk_id'];
            $nama_gambar = $gambar['nama_gambar'];

            //hapus file fisik dari folder uploads/produk/
            $path = './uploads/produk/' . $nama_gambar;
            if (file_exists($path)) {
                unlink($path);
            }

            //hapus data dari database
            $this->produk_gambar_model->hapus($id_gambar);

            $this->session->set_flashdata('message', '
            <div class="alert alert-success d-flex mt-3 align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Berhasil Hapus Gambar Produk-!
                </div>
            </div>
            ');
            
            redirect('admin/produk/ubah/' . $produk_id);
        } 

        else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex mt-3 align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Gambar Tidak Ditemukan-!
                </div>
            </div>
            ');
            redirect('admin/produk');
        }
    }

    // --- FUNGSI BARU: Untuk mengubah satu gambar spesifik ---
    public function ubah_gambar($id_gambar) {
        $produk_id = $this->input->post('produk_id');
        $gambar_lama = $this->produk_gambar_model->get_by_id($id_gambar);

        if ($gambar_lama && !empty($_FILES['gambar_baru']['name']) && $_FILES['gambar_baru']['error'] !== 4) {
            $config['upload_path'] = './uploads/produk/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = '5000';
            $config['file_name'] = "produk-" . $produk_id . "-" . time();

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('gambar_baru')) {
                // Hapus gambar lama
                $path_lama = './uploads/produk/' . $gambar_lama['nama_gambar'];

                if (file_exists($path_lama)) {
                    unlink($path_lama);
                }

                $uploadData = $this->upload->data();
                $this->db->where('id_gambar', $id_gambar);
                $this->db->update('produk_gambar', ['nama_gambar' => $uploadData['file_name']]);

                $this->session->set_flashdata('message', '
                <div class="alert alert-success d-flex mt-3 align-items-center alert-dismissible fade show" role="alert">
                    <div>
                        Gambar Produk Berhasil Diubah-!
                    </div>
                </div>
                ');
            } 

            else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal Mengubah Gambar Produk-!' . $this->upload->display_errors() . '</div>');
            }
        }

        redirect('admin/produk/ubah/' . $produk_id);
    }
}