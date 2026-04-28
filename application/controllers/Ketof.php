<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ketof extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ketof_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Master Data Keterangan Mesin OFF';
        $data['data'] = $this->db->get('downtime_ketof')->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('ketof/index', $data);
        $this->load->view('templates/footer');
    }

    public function tambahdata()
    {
        $data['dept_option'] = $this->Ketof_model->dept();
        $data['jmldept'] = $this->Ketof_model->jmldept();
        $this->load->view('ketof/add', $data);
    }

    public function simpan()
    {
        $query = $this->Ketof_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                   Data Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('ketof');
    }
    public function edit($id)
    {
        $data['detail'] = $this->Ketof_model->getdataByid($id);
        $data['dept_option'] = $this->Ketof_model->dept();
        $data['jmldept'] = $this->Ketof_model->jmldept();
        $this->load->view('ketof/edit', $data);
    }

    public function update()
    {
        $query = $this->Ketof_model->UpdateData();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data Berhasil DiUpdate!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('ketof');
    }

    public function hapus($id)
    {
        $hasil = $this->Ketof_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('ketof');
    }
}
