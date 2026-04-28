<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Preventif extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Preventif_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Master Data Item Preventif';
        $data['header'] = $this->db->get('downtime_preventif')->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('preventif/index', $data);
        $this->load->view('templates/footer');
    }

    public function reset_filter()
    {
        $this->session->unset_userdata('filter_dept');
        redirect('spekmesin');
    }

    public function item()
    {
        $id_preventif = $this->input->post('id');
        $kategori = $this->input->post('kategori');
        $this->session->set_userdata('filter_kategori', $kategori);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];


        $this->db->from('downtime_preventif_item');

        if ($kategori !== 'all' && $kategori !== null && $kategori !== '') {
            $this->db->where('downtime_preventif_item.kategori', $kategori);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_preventif_item.standar', $search);
            $this->db->or_like('downtime_preventif_item.nama_item', $search);
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->count_all_results();

        //  2. Ambil data untuk ditampilkan
        $this->db->from('downtime_preventif_item');

        if ($kategori !== 'all' && $kategori !== null && $kategori !== '') {
            $this->db->where('downtime_preventif_item.kategori', $kategori);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_preventif_item.standar', $search);
            $this->db->or_like('downtime_preventif_item.nama_item', $search);
            $this->db->group_end();
        }

        $this->db->order_by('downtime_preventif_item.id', 'DESC');
        $this->db->where('id_preventif', $id_preventif);
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        // Format kolom-kolom hasil
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['item'] =  $row['nama_item'];
            $row['standar'] = $row['standar'];
            $row['kategori'] = $row['kategori'];

            $row['aksi'] = '    <a class="btn btn-sm btn-danger btn-icon text-black hapus" 
                                 data-id="' . $row['id'] . '"
                                 data-url="' . base_url() . 'preventif/detail_hapus/' . $row['id'] . '/' . $id_preventif . '" href="#">
                                <i class="fa fa-trash" ></i>
                            </a>';
        }

        // ========== 3. Hitung total semua data tanpa filter ========== //
        $this->db->from('downtime_preventif_item');
        $recordsTotal = $this->db->count_all_results();

        // ========== 4. Kirim respon ke DataTables ========== //
        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function detail($id)
    {
        $data['title'] = 'Master Data Item Preventif';
        $data['id'] = $id;
        $data['item'] = $this->db->get_where('downtime_preventif_item', ['id_preventif' => $id])->result_array();
        $data['filter_kategori'] = $this->session->userdata('filter_kategori') ?? 'all';
        $this->load->view('templates/header', $data);
        $this->load->view('preventif/detail', $data);
        $this->load->view('templates/footer');
    }
    public function tambahdata()
    {
        $this->load->view('preventif/add');
    }
    public function tambahdata_item($id)
    {
        $data['id_halaman'] = $id;
        $this->load->view('preventif/add_item', $data);
    }

    public function simpan()
    {
        $query = $this->Preventif_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('preventif');
    }
    public function simpan_item($id)
    {
        $query = $this->Preventif_model->simpan_item();
        if ($query) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('preventif/detail/' . $id);
    }
    public function edit($id)
    {
        $data['detail'] = $this->Spekmesin_model->getdataByid($id);
        $this->load->view('spekmesin/edit', $data);
    }

    public function update()
    {
        $query = $this->Spekmesin_model->UpdateData();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data Berhasil DiUpdate!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('spekmesin');
    }

    public function detail_hapus($id, $id_preventif)
    {
        $hasil = $this->Preventif_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('preventif/detail/' . $id_preventif);
    }
}
