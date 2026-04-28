<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kerusakan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kerusakan_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Master Data Kerusakan';
        $data['dept_options'] = $this->Kerusakan_model->getFilter();
        // $id_dept = $this->session->userdata('id_dept');
        // $data['kerusakan'] = $this->Kerusakan_model->getdata_dept($id_dept);
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';

        $data['downtime_dept_map'] = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR',
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('kerusakan/index', $data);
        $this->load->view('templates/footer');
    }

    public function reset_filter()
    {
        $this->session->unset_userdata('filter_dept');
        redirect('kerusakan');
    }

    public function filter_kerusakan()
    {
        $dept_id = $this->input->post('dept_id');
        $this->session->set_userdata('filter_dept', $dept_id);
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];


        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR',
        ];

        $akses_dept = [];

        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select('downtime_kerusakan.*, dept.dept_id, dept.departemen');
        $this->db->from('downtime_kerusakan');
        $this->db->join('dept', 'dept.dept_id = downtime_kerusakan.dept_id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime_kerusakan.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_kerusakan.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            // $this->db->like('dept.departemen', $search);
            $this->db->like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }

        $this->db->order_by('downtime_kerusakan.rusak_id', 'DESC');
        $this->db->where('downtime_kerusakan.ins_kode', 'PI');
        $this->db->limit($limit, $start);
        $data = $this->db->get()->result_array();


        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['departemen'] = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $row['remark'] = $row['remark'];
            $id = $row['rusak_id'];
            $dropdown = ' <li><a class=" btn btn-outline-success font-kecil edit" data-id="' . $id . '" href="#"> <i class="fa-solid fa-pencil me-2"></i> Edit Data</a></li>';
            $dropdown .= '
                    <li><a class="btn btn-outline-danger font-kecil hapus" data-id="' . $id . '" data-url="' . base_url() . 'kerusakan/hapus/' . $id . '" href="#"><i class="fa fa-trash me-2" ></i> Hapus Data</a></li>
                ';
            $row['aksi'] = '
                        <div class="dropdown font-kecil">
                        
                        <a class="btn btn-secondary dropdown-toggle font-kecil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </a>
                        <ul class="dropdown-menu">' . $dropdown . '</ul>
                    </div>';
        }

        // Penghitungan data yang difilter
        $this->db->from('downtime_kerusakan');

        $this->db->join('dept', 'dept.dept_id = downtime_kerusakan.dept_id');

        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime_kerusakan.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_kerusakan.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            // $this->db->like('dept.departemen', $search);
            $this->db->like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->count_all_results();

        // Penghitungan data total
        $this->db->from('downtime_kerusakan');
        $recordsTotal = $this->db->count_all_results();

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function tambahdata($filter_dept)
    {
        $data['dept'] = $filter_dept;
        $this->load->view('kerusakan/add', $data);
    }

    public function simpan()
    {
        $query = $this->Kerusakan_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Kerusakan Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('kerusakan');
    }
    public function edit($id)
    {
        $data['detail'] = $this->Kerusakan_model->getdataByid($id);
        $this->load->view('kerusakan/edit', $data);
    }

    public function update()
    {
        $query = $this->Kerusakan_model->UpdateData();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data Berhasil DiUpdate!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('kerusakan');
    }

    public function hapus($id)
    {
        $hasil = $this->Kerusakan_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('kerusakan');
    }
}
