<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Riwayat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Riwayat_model');

        is_logged_in();
    }

    public function index()
    {
        $id = $this->session->userdata('id');
        $data['title'] = 'History Perbaikan';
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['user'] = $this->Riwayat_model->getdata($id);
        $data['bulan_options'] = $this->Riwayat_model->getBulan();
        $data['tahun_options'] = $this->Riwayat_model->getTahun();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';



        $this->load->view('templates/header', $data);
        $this->load->view('riwayat/index', $data);
        $this->load->view('templates/footer');
    }


    public function filter_riwayat()
    {
        $name = $this->session->userdata('name');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);


        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];


        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, 
        downtime_tindakan.*, downtime_tindakan_user.user');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');


        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }
        $this->db->where('downtime_tindakan_user.user', $name);
        $this->db->order_by('downtime.tanggal', 'DESC');
        $this->db->limit($limit, $start);
        $data = $this->db->get()->result_array();

        //  view
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);
            $row['mesin'] = 'Mesin ' . $row['mach_no'];
            $row['kerusakan'] = $row['remark'];
            $row['jam mulai'] =  $row['jam_start'];
            $row['jam selesai'] =  $row['jam_end'];
            $row['waktu perbaikan'] = format_downtime($row['downtime']);
            $row['tindakan'] = $row['tindakan'];
        }


        // Hitung jumlah data yang terfilter
        $this->db->from('downtime');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }
        $this->db->where('downtime_tindakan_user.user', $name);
        $recordsFiltered = $this->db->count_all_results();




        // Penghitungan data total
        $this->db->from('downtime');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');
        $this->db->where('downtime_tindakan_user.user', $name);
        $recordsTotal = $this->db->count_all_results();


        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
}
