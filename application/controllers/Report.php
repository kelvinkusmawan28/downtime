<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model');

        is_logged_in();
        $this->load->library('Pdf');
        // // $this->load->library('Codeqr');
        include_once APPPATH . '/third_party/phpqrcode/qrlib.php';
        // include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function index()
    {
        $data['title'] = 'Laporan Data Perbaikan Teknisi';
        $data['dept_options'] = $this->Report_model->getFilter();
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';

        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Report_model->getBulan();
        $data['tahun_options'] = $this->Report_model->getTahun();
        $data['dept_options'] = $this->Report_model->getFilter();

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
        ];



        $this->load->view('templates/header', $data);
        $this->load->view('report/index', $data);
        $this->load->view('templates/footer');
    }



    public function getPerbaikan_Terbanyak()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);
        $this->session->set_userdata('filter_dept', $dept_id);

        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];

        $akses_dept = [];

        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select('
         downtime_tindakan_user.user, 
         COUNT(*) as total_perbaikan
        ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');


        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }

        if ($bulan != 'all') {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        if ($tahun != 'all') {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }
        $this->db->where('downtime_tindakan_user.user IS NOT NULL');

        $this->db->group_by('downtime_tindakan_user.user');
        $this->db->order_by('total_perbaikan', 'DESC');
        $this->db->limit(5);

        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }
    public function reset_filter()
    {


        $this->session->unset_userdata('filter_dept');
        $this->session->unset_userdata('filter_bulan');
        $this->session->unset_userdata('filter_tahun');
        $this->session->unset_userdata('filter_status');


        redirect('report');
    }

    public function filter_report()
    {
        $dept_id = $this->input->post('dept_id');
        $this->session->set_userdata('filter_dept', $dept_id);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        // Hak akses berdasarkan session
        $hakdowntime = $this->session->userdata('hakdowntime');
        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];
        $akses_dept = [];

        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        // === Ambil Data ===
        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_tindakan_user.user');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0'); // Tidak punya akses
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_tindakan_user.user', $search);
            $this->db->group_end();
        }
        $this->db->where('downtime_tindakan_user.user IS NOT NULL');
        $this->db->group_by('downtime_tindakan_user.user');
        $this->db->order_by('downtime_tindakan_user.user', 'ASC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        // Format data untuk ditampilkan di tabel
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['nama'] = '<a href="' . base_url() . 'report/view/' . $row['user'] . '" style="text-decoration: underline;" title="Views Data" >' . $row['user'] . '</a>';
            $row['departemen'] = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
        }

        // === Hitung recordsFiltered ===
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_tindakan_user.user', $search);
            $this->db->group_end();
        }

        $this->db->group_by('downtime_tindakan_user.user');
        $recordsFiltered = $this->db->get()->num_rows();

        // === Hitung recordsTotal ===
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        $this->db->group_by('downtime_tindakan_user.user');
        $recordsTotal = $this->db->get()->num_rows();

        // === Response ke DataTables ===
        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function filter_mesin()
    {
        $dept_id = $this->input->post('dept_id');
        $this->session->set_userdata('filter_dept', $dept_id);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        // Hak akses berdasarkan session
        $hakdowntime = $this->session->userdata('hakdowntime');
        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];
        $akses_dept = [];

        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        // === Ambil Data ===
        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_name, downtime_spekmesin.mach_no ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id= downtime.nomesin_id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0'); // Tidak punya akses
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_name', $search);
            $this->db->or_like('downtime_spekmesin.mach_no', $search);
            $this->db->group_end();
        }
        $this->db->where('downtime_spekmesin.mach_no IS NOT NULL');
        $this->db->group_by('downtime_spekmesin.mach_no');
        $this->db->order_by('downtime_spekmesin.mach_no', 'ASC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        // Format data untuk ditampilkan di tabel
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['mesin'] = 'Mesin ' . $row['mach_no'];
            $row['spek mesin'] = $row['mach_name'];
            $row['departemen'] = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $row['aksi'] =
                '<a href="' . base_url() . 'report/view_mesin/' . $row['mach_no'] . '/' . $row['dept_id'] . '" class="btn btn-outline-primary font-kecil" title="Views Data"> <i class="fa-solid fa-screwdriver-wrench me-2"></i> Buka Data </a>';
        }

        // === Hitung recordsFiltered ===
        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_name, downtime_spekmesin.mach_no ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id= downtime.nomesin_id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_name', $search);
            $this->db->or_like('downtime_spekmesin.mach_no', $search);;
            $this->db->group_end();
        }

        $this->db->group_by('downtime_spekmesin.mach_no');
        $recordsFiltered = $this->db->get()->num_rows();

        // === Hitung recordsTotal ===
        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_name, downtime_spekmesin.mach_no ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id= downtime.nomesin_id', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        $this->db->group_by('downtime_spekmesin.mach_no');
        $recordsTotal = $this->db->get()->num_rows();

        // === Response ke DataTables ===
        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function view()
    {
        $user = urldecode($this->uri->segment(3));
        $data['title'] = 'Riwayat Perbaikan';
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['user'] = $this->Report_model->getdata($user);
        $data['bulan_options'] = $this->Report_model->getBulan();
        $data['tahun_options'] = $this->Report_model->getTahun();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';

        $this->load->view('templates/header', $data);
        $this->load->view('report/view', $data);
        $this->load->view('templates/footer');
    }
    public function view_mesin()
    {
        $mesin = urldecode($this->uri->segment(3));
        $dept = urldecode($this->uri->segment(4));
        // var_dump($dept);
        // die;
        $data['dept'] = $dept;
        $data['title'] = 'Riwayat Kerusakan';
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['header_dept'] = $this->Report_model->deptsekarang($dept);
        $data['user'] = $this->Report_model->getdata_mesin($mesin, $dept);
        $data['bulan_options'] = $this->Report_model->getBulan();
        $data['tahun_options'] = $this->Report_model->getTahun();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';

        $this->load->view('templates/header', $data);
        $this->load->view('report/view_kerusakan', $data);
        $this->load->view('templates/footer');
    }

    public function filter_riwayat()
    {
        $name = $this->input->post('name');
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
    public function kerusakan_riwayat()
    {
        $mesin = $this->input->post('mesin');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $dept = $this->input->post('dept');


        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);


        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];


        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_spekmesin.dept_kode, downtime_kerusakan.remark, 
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
            $this->db->group_end();
        }
        $this->db->where('downtime_spekmesin.mach_no', $mesin);
        $this->db->where('downtime_spekmesin.dept_kode', $dept);
        $this->db->where('downtime.status', 1);
        $this->db->order_by('downtime.tanggal', 'DESC');
        $this->db->limit($limit, $start);
        $data = $this->db->get()->result_array();

        //  view
        $no = $start + 1;
        $kerusakan_sebelumnya = '';
        $jam_mulai_sebelumnya = '';
        $jam_selesai_sebelumnya = '';
        $tindakan_sebelumnya = '';

        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);
            $row['mesin'] = 'Mesin ' . $row['mach_no'];
            $row['kerusakan'] = $row['remark'];
            $row['jam mulai'] = $row['jam_start'];
            $row['jam selesai'] = $row['jam_end'];
            $row['tindakan'] = $row['tindakan'];
            $row['teknisi'] = $row['user'];

            if (
                $row['kerusakan'] == $kerusakan_sebelumnya &&
                $row['jam_start'] == $jam_mulai_sebelumnya &&
                $row['jam_end'] == $jam_selesai_sebelumnya &&
                $row['tindakan'] == $tindakan_sebelumnya
            ) {
                $row['waktu perbaikan'] = '';
            } else {
                $row['waktu perbaikan'] = format_downtime($row['downtime']);
            }


            $kerusakan_sebelumnya = $row['kerusakan'];
            $jam_mulai_sebelumnya = $row['jam mulai'];
            $jam_selesai_sebelumnya = $row['jam selesai'];
            $tindakan_sebelumnya = $row['tindakan'];
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
            $this->db->group_end();
        }
        $this->db->where('downtime_spekmesin.mach_no', $mesin);
        $recordsFiltered = $this->db->count_all_results();

        // Penghitungan data total
        $this->db->from('downtime');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->where('downtime_spekmesin.mach_no', $mesin);
        $this->db->where('downtime_spekmesin.dept_kode', $dept);
        $recordsTotal = $this->db->count_all_results();


        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function export_excel()
    {
        $mesin = $this->input->get('mesin');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $detail = $this->Report_model->getExport_excel($mesin, $bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Downtime Mesin');


        $sheet->setCellValue('C1', 'LAPORAN DOWNTIME MESIN ' . $mesin);
        $sheet->setCellValue('C2', 'Bulan: ' . strtoupper(get_nama_bulan($bulan)) . ' - ' . $tahun);
        $sheet->mergeCells('C1:K1');
        $sheet->mergeCells('C2:K2');

        $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('C2')->getFont()->setItalic(true);
        $sheet->getStyle('C1:C2')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'No');
        $sheet->setCellValue('D4', 'Tanggal');
        $sheet->setCellValue('E4', 'Mesin');
        $sheet->setCellValue('F4', 'Kerusakan');
        $sheet->setCellValue('G4', 'Jam Mulai');
        $sheet->setCellValue('H4', 'Jam Selesai');
        $sheet->setCellValue('I4', 'Waktu Perbaikan');
        $sheet->setCellValue('J4', 'Tindakan');
        $sheet->setCellValue('K4', 'Teknisi');


        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFEFEFEF']
            ]
        ];
        $sheet->getStyle('C4:K4')->applyFromArray($headerStyle);

        $rowNum = 5;
        $no = 1;
        $total_downtime = 0;
        $kerusakan_sebelumnya = '';
        $jam_mulai_sebelumnya = '';
        $jam_selesai_sebelumnya = '';
        $tindakan_sebelumnya = '';

        foreach ($detail as $row) {

            $departemen = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $mesin = 'Mesin ' . $row['mach_no'];
            $downtime = (int)$row['downtime'];


            $sheet->setCellValue('C' . $rowNum, $no++);
            $sheet->setCellValue('D' . $rowNum, format_tanggal_indonesia($row['tanggal']));
            $sheet->setCellValue('E' . $rowNum, $mesin);
            $sheet->setCellValue('F' . $rowNum, $row['remark']);
            $sheet->setCellValue('G' . $rowNum, $row['jam_start']);
            $sheet->setCellValue('H' . $rowNum, $row['jam_end']);
            $sheet->setCellValue('I' . $rowNum, format_menit($row['downtime']));
            $sheet->setCellValue('J' . $rowNum, $row['tindakan']);
            $sheet->setCellValue('K' . $rowNum, $row['user']);


            if (
                $row['remark'] == $kerusakan_sebelumnya &&
                $row['jam_start'] == $jam_mulai_sebelumnya &&
                $row['jam_end'] == $jam_selesai_sebelumnya &&
                $row['tindakan'] == $tindakan_sebelumnya
            ) {
                $sheet->setCellValue('I' . $rowNum, '');
            } else {
                $sheet->setCellValue('I' . $rowNum, format_menit($row['downtime']));
                $total_downtime += $downtime;
            }


            $kerusakan_sebelumnya = $row['remark'];
            $jam_mulai_sebelumnya = $row['jam_start'];
            $jam_selesai_sebelumnya = $row['jam_end'];
            $tindakan_sebelumnya = $row['tindakan'];

            $rowNum++;
        }

        // Total row
        $sheet->setCellValue('E' . $rowNum, 'TOTAL DOWNTIME');
        $sheet->setCellValue('I' . $rowNum, format_menit($total_downtime));



        $totalStyle = [
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFCE4D6']
            ]
        ];

        $sheet->getStyle('C4:K' . $rowNum)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);
        $sheet->getStyle('E' . $rowNum . ':K' . $rowNum)->applyFromArray($totalStyle);


        foreach (range('C', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('C5:C' . $rowNum)->getAlignment()->setHorizontal('center');

        // Export
        $filename = 'Downtime_Mesin_' . get_nama_bulan($bulan) . '_' . $tahun . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
