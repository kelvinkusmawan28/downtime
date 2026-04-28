<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mesin_of extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mesinof_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Downtime Mesin';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $id_dept = $this->session->userdata('id_dept');
        $data['mesin'] = $this->Mesinof_model->getdata_dept($id_dept);
        $data['bulan_options'] = $this->Mesinof_model->getBulan();
        $data['tahun_options'] = $this->Mesinof_model->getTahun();
        $data['dept_options'] = $this->Mesinof_model->getFilter();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
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
        $this->load->view('mesinof/index', $data);
        $this->load->view('templates/footer');
    }


    public function filter()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);
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
            7 => 'AR'
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select('downtime_mesinof.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, user.name, downtime_ketof.reason');
        $this->db->from('downtime_mesinof');
        $this->db->join('dept', 'dept.dept_id = downtime_mesinof.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');
        $this->db->join('user', 'user.id = downtime_mesinof.user_by', 'left');
        $this->db->join('downtime_ketof', 'downtime_ketof.id = downtime_mesinof.ket_id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime_mesinof.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_mesinof.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }
        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->group_end();
        }
        $this->db->order_by('downtime_mesinof.id', 'DESC');
        $this->db->limit($limit, $start);
        $data = $this->db->get()->result_array();

        //  view
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['tanggal'] = '<span style="color:black;">' . format_tanggal_indonesia($row['tanggal']) . '</span>';
            $row['departemen'] = '<span style="color:black;">' .  $row['departemen'] . '</span>' . '<br><span style="color:red;">' . $row['name']  . '</span>';
            $row['mesin'] = '<span style="color:black;">' . 'Mesin '  . $row['mach_no'] . '</span>' . '<br><span style="color:black;">' . $row['mach_name'] . '</span>';
            $row['ket'] = '<span style="color:black;">' .  $row['reason'] . '</span>' . '<br><span style="color:red;">' . format_tanggal_indonesia_waktu($row['ket_on'])  . '</span>';
            $shift_value = $row['shift'];
            if ($shift_value == 1) {
                $row['shift'] = '<span style="color:black;">' . 'PAGI' . '</span>';
            } else if ($shift_value == 2) {
                $row['shift'] = '<span style="color:black;">' . 'SIANG' . '</span>';
            } else {
                $row['shift'] = '<span style="color:black;">' . 'MALAM' . '</span>';
            }
            $row['aksi'] = '
            <a href="#" 
                    class="btn btn-sm btn-info edit" 
                    title="Edit Data" 
                    data-id="' . $row['id'] . '">
                    <i class="fa fa-edit"></i>
             </a>
                <a class="btn btn-sm btn-danger btn-icon text-white hapus"
                    data-id="' . $row['id'] . '"
                    data-url="' . base_url() . 'mesin_of/hapus/' . $row['id'] . '" href="#">
                    <i class="fa fa-trash"></i>
                </a>';
        }

        // Penghitungan data yang difilter

        $this->db->select('downtime_mesinof.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, user.name,');
        $this->db->from('downtime_mesinof');
        $this->db->join('dept', 'dept.dept_id = downtime_mesinof.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');
        $this->db->join('user', 'user.id = downtime_mesinof.user_by', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime_mesinof.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_mesinof.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }
        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->count_all_results();

        // Penghitungan data total
        $this->db->from('downtime_mesinof');
        $recordsTotal = $this->db->count_all_results();

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function tambahdata($filter_dept = null)
    {
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['dept_id'] = $filter_dept;
        $this->session->set_userdata('filter_dept', $filter_dept);
        $data['ketof'] = $this->db->get('downtime_ketof')->result_array();
        $this->load->view('mesinof/add', $data);
    }


    public function nomor_mesin()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->input->get('filter');

        $result = $this->Mesinof_model->getMesinLike($inputan, $filter_dept);
        echo json_encode($result);
    }

    public function ket_mesinof()
    {
        $inputan = $this->input->get('term');
        $result = $this->Mesinof_model->getMesinof($inputan);
        echo json_encode($result);
    }
    public function benang()
    {
        $inputan = $this->input->get('term');
        $result = $this->Mesinof_model->getBenang($inputan);
        echo json_encode($result);
    }



    public function simpan()
    {
        $post = $this->input->post();

        $dept = $post['dept_id'];
        $tanggal = $post['tanggal'];
        $nomesin = $post['mach_id'];
        $shift = $post['shift'];
        $cek = $this->Mesinof_model->cekData($tanggal, $dept, $nomesin, $shift);

        if ($cek > 0) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <b>DATA SUDAH ADA!</b><br>
                Data mesin pada tanggal tersebut sudah tersimpan di sistem.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>');
            redirect('mesin_of');
        }

        $query = $this->Mesinof_model->simpan();

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Berhasil Ditambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>');
        }

        redirect('mesin_of');
    }

    public function edit($id)
    {
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['mesinof'] = $this->Mesinof_model->getdataByid($id);
        $this->load->view('mesinof/edit', $data);
    }

    public function update()
    {
        $query = $this->Mesinof_model->update();
        if ($query) {
            redirect('mesin_of');
        }
    }

    public function hapus($id)
    {
        $hasil = $this->Mesinof_model->hapus($id);
        if ($hasil) {
            redirect('mesin_of');
        }
    }
}
