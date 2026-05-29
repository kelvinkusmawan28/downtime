<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Monitoring_model');
        $this->load->model('Mesinof_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Monitoring Mesin ';

        $data['tgl_sekarang'] = $this->session->userdata('tanggal') ?? date('Y-m-d');
        $data['dept_options'] = $this->Monitoring_model->getFilter();
        $data['filter_dept'] = $this->session->userdata('dept') ?? 'all';
        $data['filter_tgl'] = $this->session->userdata('tanggal');


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
        $this->load->view('monitoring/index', $data);
        $this->load->view('templates/footer');
    }

    public function getMesinByDept()
    {

        $dept = $this->input->post('dept');
        $tanggal = $this->input->post('tanggal');
        $this->session->set_userdata('dept', $dept);
        $this->session->set_userdata('tanggal', $tanggal);

        $data = $this->Monitoring_model->getMesinMonitoring($dept);
        $summary = $this->Monitoring_model->getSummaryClr($dept);
        $summary_pi = $this->Monitoring_model->getSummary_pi($dept);
        $summary_gm = $this->Monitoring_model->getSummary_gm($dept);

        echo json_encode([
            'mesin' => $data,
            'summary' => $summary,
            'summary_pi' => $summary_pi,
            'summary_gm' => $summary_gm
        ]);
    }


    public function view($mach_id, $tgl)
    {

        $data['tgl_sekarang'] = $tgl;
        $data['detail'] = $this->Monitoring_model->getDetail($mach_id);
        // echo "<pre>";
        // print_r($data['detail']);
        // die;
        $this->load->view('monitoring/add', $data);
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
        $query = $this->Mesinof_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     Berhasil Ditambahkan!
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>');
        }
        redirect('dashboard');
    }
}
