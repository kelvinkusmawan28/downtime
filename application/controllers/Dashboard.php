<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        $this->load->model('Mesinof_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = ' Daftar Mesin Dengan Kerusakan Berkala Berdasarkan Data Downtime';

        $data['tgl_sekarang'] = date('Y-m-d');
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Dashboard_model->getBulan();
        $data['tahun_options'] = $this->Dashboard_model->getTahun();
        $data['dept_options'] = $this->Dashboard_model->getFilter();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';
        $shift_session = $this->session->userdata('shift');

        if ($shift_session) {
            $data['shift'] = $shift_session;
        } else {
            date_default_timezone_set('Asia/Jakarta');
            $jam = date('H:i');

            if ($jam >= '06:00' && $jam < '14:00') {
                $data['shift'] = 1;
            } elseif ($jam >= '14:00' && $jam < '22:00') {
                $data['shift'] = 2;
            } else {
                $data['shift'] = 3;
            }
        }

        $data['filter_rc'] = $this->session->userdata('filter_rc');


        $data['reason'] = $this->db->get('downtime_ketof')->result_array();
        $data['rr_type'] = $this->Dashboard_model->getRR();
        $data['sp_type'] = $this->Dashboard_model->getSP();
        $data['fn_type'] = $this->Dashboard_model->getFN();
        $data['nt_type'] = $this->Dashboard_model->getNT();

        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

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
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }

    public function getKerusakan_Terbanyak()
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

        $akses_departemen = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_departemen[] = $dept_code;
            }
        }

        $this->db->select('
        downtime.nomesin_id, 
        downtime.kerusakan_id, 
        COUNT(*) as total_downtime,
        dept.departemen,
        downtime_kerusakan.remark as kerusakan,
        downtime_spekmesin.mach_name,
        downtime_spekmesin.mach_no
        ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');


        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime.dept_id', $dept_id);
        } else {

            if (!empty($akses_departemen)) {
                $this->db->where_in('downtime.dept_id', $akses_departemen);
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

        $this->db->group_by('downtime.nomesin_id, downtime.kerusakan_id');
        $this->db->order_by('total_downtime', 'DESC');
        $this->db->limit(5);

        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }


    public function detail()
    {
        $mesin = $this->input->post('mesin');
        $kerusakan = $this->input->post('kerusakan');
        $dept_id = $this->input->post('dept_id');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $data['detail'] = $this->Dashboard_model->GetDetail($mesin, $kerusakan, $dept_id, $bulan, $tahun);
        $this->load->view('dashboard/detail', $data);
    }
    public function detail_mesinof()
    {
        $code = $this->input->post('code');
        $tanggal = $this->input->post('tanggal');
        $dept_id = $this->input->post('dept_id');

        $result = $this->Dashboard_model->GetDetail_mesinof($code, $tanggal, $dept_id);
        $data['header'] = $this->Dashboard_model->GetDetail_header($code, $dept_id);
        $data['detail'] = $result['data'];
        $data['startDate'] = $result['startDate'];
        $data['endDate'] = $result['endDate'];

        $this->load->view('dashboard/mesinof', $data);
    }


    public function getMesinByDept()
    {

        $dept = $this->input->post('dept');
        $tanggal = $this->input->post('tanggal');
        $shift = $this->input->post('shift');
        $reason = $this->input->post('reason');
        $preventif = $this->input->post('filter_rr');
        $netting = $this->input->post('filter_nt');
        $spinning = $this->input->post('filter_sp');
        $finishing = $this->input->post('filter_fn');
        $filter_rc = $this->input->post('filter_rc');


        $this->session->set_userdata('dept', $dept);
        $this->session->set_userdata('tanggal', $tanggal);
        $this->session->set_userdata('shift', $shift);
        $this->session->set_userdata('filter_rc', $filter_rc);

        $data = $this->Dashboard_model->getMesinDashboard($dept, $tanggal, $shift, $reason, $preventif, $filter_rc, $spinning, $finishing, $netting);
        $summary = $this->Dashboard_model->getSummaryClr($dept, $tanggal, $shift, $reason, $preventif, $filter_rc, $spinning, $finishing, $netting);
        $lastupdate = $this->Dashboard_model->getLastUpdate($dept, $tanggal, $shift);

        echo json_encode([
            'mesin' => $data,
            'summary' => $summary,
            'lastupdate' => $lastupdate
        ]);
    }


    public function tambahdata($mach_id)
    {
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['dept_id'] = $this->db->get_where('downtime_spekmesin', ['mach_id' => $mach_id])->row_array();
        $tanggal = $this->session->userdata('tanggal');
        // var_dump($tanggal);
        // die();
        $dept = $this->session->userdata('dept');
        $data['perbaikan'] = $this->Dashboard_model->getPerbaikan($mach_id, $tanggal, $dept);

        date_default_timezone_set('Asia/Jakarta'); // pastikan timezone benar

        $jam = date('H:i');

        if ($jam >= '06:00' && $jam < '14:00') {
            $data['shift'] = 1;
        } elseif ($jam >= '14:00' && $jam < '22:00') {
            $data['shift'] = 2;
        } else {
            $data['shift'] = 3;
        }
        // var_dump($data['perbaikan']);
        $this->load->view('dashboard/add', $data);
    }


    public function edit($id)
    {
        $data['tgl_sekarang'] = date('Y-m-d');
        $mesin = $this->Dashboard_model->getdataByid($id);
        $data['title'] = $mesin;
        $data['mesinof'] = $mesin;
        $id = $mesin['id'];

        $nomesin_id  = $mesin['nomesin_id'];
        $dept        = $mesin['dept_id'];
        $ket_id      = $mesin['ket_id'];
        $ket_idr     = $mesin['ket_idr'];
        $shift       = $mesin['shift'];

        $data['total'] = $this->Dashboard_model->Cari(
            $id,
            $nomesin_id,
            $dept,
            $ket_id,
            $ket_idr,
            $shift
        );

        $data['riwayat'] = $this->Dashboard_model->CariRiwayat(
            $id,
            $nomesin_id,
            $dept
        );

        $tanggal = $this->session->userdata('tanggal');
        $dept = $this->session->userdata('dept');
        $data['perbaikan'] = $this->Dashboard_model->getPerbaikan($nomesin_id, $tanggal, $dept);

        $this->load->view('dashboard/edit', $data);
    }

    public function update()
    {
        $query = $this->Dashboard_model->update();
        if ($query) {
            redirect('dashboard');
        }
    }
    public function update_ppic()
    {
        $query = $this->Dashboard_model->update_ppic();
        if ($query) {
            redirect('dashboard');
        }
    }

    public function ket_bobin()
    {
        $inputan = $this->input->get('term');
        $result = $this->Dashboard_model->getKetbb($inputan);
        echo json_encode($result);
    }
    public function ket_mesinof()
    {
        $inputan = $this->input->get('term');
        $dept = $this->input->get('filter');
        $result = $this->Dashboard_model->getKetof($inputan, $dept);
        echo json_encode($result);
    }

    public function benang()
    {
        $inputan = $this->input->get('term');
        $result = $this->Dashboard_model->getBenang($inputan);
        echo json_encode($result);
    }


    public function simpan()
    {
        $post = $this->input->post();

        $dept = $post['dept_id'];
        $tanggal = $post['tanggal'];
        $nomesin = $post['mach_id'];
        $shift = $post['shift'];
        $cek = $this->Dashboard_model->cekData($tanggal, $dept, $nomesin, $shift);

        if ($cek > 0) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <b>DATA SUDAH ADA!</b><br>
                Data mesin pada tanggal tersebut sudah tersimpan di sistem.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>');
            redirect('Dashboard');
        }

        $query = $this->Dashboard_model->simpan();


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
