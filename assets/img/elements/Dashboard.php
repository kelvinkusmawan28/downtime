<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Dashboard_model');
        // is_logged_in();
        environmental();
        $this->load->model('Approve_ei_model');
        $this->load->model('Dashboard_model');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['total_ok'] = $this->Approve_ei_model->getHitungData_ok();
        $data['ok_tuju'] = $this->Approve_ei_model->getHitungOk_tuju();
        // grafik inlet
        $data['bln_sekarang_grafik'] = date('m');
        $data['thn_sekarang_grafik'] = date('Y');
        $data['bulan_options_grafik'] = $this->Dashboard_model->getBulan_grafik();
        $data['tahun_options_grafik'] = $this->Dashboard_model->getTahun_grafik();

        $data['filter_bulan_grafik'] = $this->session->userdata('filter_bulan_grafik') ?? 'all';
        $data['filter_tahun_grafik'] = $this->session->userdata('filter_tahun_grafik') ?? 'all';


        // // debit
        // $data['bln_sekarang_debit'] = date('m');
        // $data['thn_sekarang_debit'] = date('Y');
        // $data['bulan_options_debit'] = $this->Dashboard_model->getBulan_debit();
        // $data['tahun_options_debit'] = $this->Dashboard_model->getTahun_debit();
        // $data['filter_bulan_debit'] = $this->session->userdata('filter_bulan_debit') ?? 'all';
        // $data['filter_tahun_debit'] = $this->session->userdata('filter_tahun_debit') ?? 'all';

        // // Chemical
        // $data['bln_sekarang_chemical'] = date('m');
        // $data['thn_sekarang_chemical'] = date('Y');
        // $data['bulan_options_chemical'] = $this->Dashboard_model->getBulan_chemical();
        // $data['tahun_options_chemical'] = $this->Dashboard_model->getTahun_chemical();
        // $data['filter_bulan_chemical'] = $this->session->userdata('filter_bulan_chemical') ?? 'all';
        // $data['filter_tahun_chemical'] = $this->session->userdata('filter_tahun_chemical') ?? 'all';


        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }



    public function cod()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        // $this->session->set_userdata('filter_bulan_grafik', $bulan);
        // $this->session->set_userdata('filter_tahun_grafik', $tahun);

        $this->db->select("
            DATE(env_form_cod.tanggal) as tanggal,
            MAX(CASE WHEN env_cod.jenis_cod = 'inlet' THEN env_form_cod.hasil_rumus END) AS inlet,
            MAX(CASE WHEN env_cod.jenis_cod = 'outlet' THEN env_form_cod.hasil_rumus END) AS outlet
        ");
        $this->db->from('env_form_cod');
        $this->db->join('env_cod', 'env_cod.id = env_form_cod.cod_id', 'left');

        if ($bulan != 'all') {
            $this->db->where('MONTH(env_form_cod.tanggal)', $bulan);
        }

        if ($tahun != 'all') {
            $this->db->where('YEAR(env_form_cod.tanggal)', $tahun);
        }

        $this->db->group_by('DATE(env_form_cod.tanggal)');
        $this->db->order_by('tanggal', 'ASC');

        $result = $this->db->get()->result_array();


        $tanggal = array_map(function ($tgl) {
            return format_tanggal_indonesia($tgl);
        }, array_column($result, 'tanggal'));


        $inlet = array_map(function ($v) {
            return $v !== null ? number_format((float)$v, 2, '.', '') : null;
        }, array_column($result, 'inlet'));

        $outlet = array_map(function ($v) {
            return $v !== null ? number_format((float)$v, 2, '.', '') : null;
        }, array_column($result, 'outlet'));

        echo json_encode([
            'tanggal' => $tanggal,
            'inlet'   => $inlet,
            'outlet'  => $outlet
        ]);
    }

    public function debit()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        // $this->session->set_userdata('filter_bulan_grafik', $bulan);
        // $this->session->set_userdata('filter_tahun_grafik', $tahun);

        $this->db->select("
            env_debit_inlet.tanggal,
            env_debit_inlet.awal as awal_inlet, env_debit_inlet.akhir as akhir_inlet,
            env_debitair_limbah.awal as awal_limbah, env_debitair_limbah.akhir as akhir_limbah,
            env_debit_produksi.awal as awal_produksi, env_debit_produksi.akhir as akhir_produksi,
            env_debit_domestik.awal as awal_domestik, env_debit_domestik.akhir as akhir_domestik
        ");
        $this->db->from('env_debit_inlet');
        $this->db->join('env_debitair_limbah', 'env_debitair_limbah.tanggal = env_debit_inlet.tanggal', 'left');
        $this->db->join('env_debit_produksi', 'env_debit_produksi.tanggal = env_debit_inlet.tanggal', 'left');
        $this->db->join('env_debit_domestik', 'env_debit_domestik.tanggal = env_debit_inlet.tanggal', 'left');

        if ($bulan != 'all') {
            $this->db->where('MONTH(env_debit_inlet.tanggal)', $bulan);
        }

        if ($tahun != 'all') {
            $this->db->where('YEAR(env_debit_inlet.tanggal)', $tahun);
        }

        $result = $this->db->get()->result_array();

        // Hitung total
        $total_inlet = 0;
        $total_limbah = 0;
        $total_produksi = 0;
        $total_domestik = 0;

        foreach ($result as $row) {
            $total_inlet     += ($row['akhir_inlet']     - $row['awal_inlet']);
            $total_limbah    += ($row['akhir_limbah']    - $row['awal_limbah']);
            $total_produksi  += ($row['akhir_produksi']  - $row['awal_produksi']);
            $total_domestik  += ($row['akhir_domestik']  - $row['awal_domestik']);
        }

        echo json_encode([
            'debit_inlet'    => $total_inlet,
            'debit_air'      => $total_limbah,
            'debit_produksi' => $total_produksi,
            'domestik'       => $total_domestik
        ]);
    }
    public function chemical()
    {
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $this->session->set_userdata('filter_bulan_grafik', $bulan);
        $this->session->set_userdata('filter_tahun_grafik', $tahun);

        $this->db->select_sum('koasa')
            ->select_sum('caustic')
            ->select_sum('anion')
            ->select_sum('flock_sc')
            ->select_sum('hdc')
            ->select_sum('tn_14')
            ->select_sum('dc_15')
            ->select_sum('defoam')
            ->select_sum('nutrisi');
        $this->db->from('env_chemical');

        if ($bulan != 'all') {
            $this->db->where('MONTH(env_chemical.tanggal)', $bulan);
        }

        if ($tahun != 'all') {
            $this->db->where('YEAR(env_chemical.tanggal)', $tahun);
        }

        $result = $this->db->get()->row_array();

        echo json_encode($result);
    }
}
