<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kategori_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin_logged_in();
        $this->load->model('produk_kategori_model');
    }

    public function index() {
        $data['list_kategori'] = $this->produk_kategori_model->get_all();
        $this->load->view('administrator/templates/header');
        $this->load->view('administrator/templates/sidebar');
        $this->load->view('administrator/kategori/index',$data);
        $this->load->view('administrator/templates/footer');
    }

    public function tambah_kategori() {
        $data['title'] = 'Tambah Kategori';
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
            if ($this->form_validation->run() !== FALSE) {
                $this->_simpan_kategori();
            } 

            else {
                $this->load->view('administrator/templates/header', $data);
                $this->load->view('administrator/templates/sidebar');
                $this->load->view('administrator/kategori/tambah_kategori');
                $this->load->view('administrator/templates/footer');
            }
    }

    private function _simpan_kategori() {
        $data = [
            'nama' => ($this->input->post('nama_kategori')),
            'deskripsi' => ($this->input->post('deskripsi_kategori'))
        ];

        $simpan = $this->produk_kategori_model->tambah($data);
            if ($simpan) {
                $this->session->set_flashdata('message', '
                <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                    <div>
                        Berhasil Tambah Kategori-!
                    </div>
                </div>
                ');
            } 

            else {
                $this->session->set_flashdata('message', '
                <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                    <div>
                        Gagal Tambah Kategori-!
                    </div>
                </div>
                ');
            }

        redirect('admin/kategori');
    }

    public function hapus_kategori($id) {
        $kategori = $this->produk_kategori_model->get_by_id($id);
        if ($kategori) {
            $hapus = $this->produk_kategori_model->hapus($id);
                if ($hapus) {
                    $this->session->set_flashdata('message', '
                    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
                        <div>
                            Berhasil Hapus Kategori-!
                        </div>
                    </div>
                    ');
                } 

                else {
                    $this->session->set_flashdata('message', '
                    <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                        <div>
                            Gagal Hapus Kategori-!
                        </div>
                    </div>
                    ');
                }
                    redirect('admin/kategori');

        } 

        else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Kategori Tidak Ditemukan-!
                </div>
            </div>
            ');

            redirect('admin/kategori');
        }
    }

    public function ubah_kategori($id) {
        $kategori = $this->produk_kategori_model->get_by_id($id);
        if ($kategori) {
            $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
                if ($this->form_validation->run() !== FALSE) {
                    $this->_ubah_kategori($id);
                } 

                else {
                    $data['title'] = 'Ubah Kategori';
                    $data['kategori'] = $kategori;
                    $this->load->view('administrator/templates/header', $data);
                    $this->load->view('administrator/templates/sidebar');
                    $this->load->view('administrator/kategori/ubah_kategori', $data);
                    $this->load->view('administrator/templates/footer');
                }
        } 

        else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                <div>
                    Kategori Tidak Ditemukan-!
                </div>
            </div>
            ');
            redirect('admin/kategori');
        }
    }

    private function _ubah_kategori($id) {
        $data = [
            'nama' => $this->input->post('nama_kategori'),
            'deskripsi' => $this->input->post('deskripsi_kategori')
        ];

        $ubah = $this->produk_kategori_model->ubah($id, $data); 
        if ($ubah) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Berhasil Ubah Kategori-!
            </div>
            ');
        } else {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Gagal Ubah Kategori!
            </div>
            ');
        }

        redirect('admin/kategori');
    }
}

