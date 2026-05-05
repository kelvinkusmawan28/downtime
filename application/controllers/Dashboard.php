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

    public function grafik()
    {
        $data['title'] = 'Rekapulasi Mesin Stop';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Dashboard_model->getBulan_off();
        $data['tahun_options'] = $this->Dashboard_model->getTahun_off();
        $data['dept_options'] = $this->Dashboard_model->getFilter();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';
        $data['reason'] = $this->db->order_by('reason')->get('downtime_ketof_line')->result_array();

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
        $this->load->view('dashboard/grafik', $data);
        $this->load->view('templates/footer');
    }
    // satu line
    // public function line()
    // {
    //     $bulan = $this->input->post('bulan');
    //     $tahun = $this->input->post('tahun');
    //     $dept = $this->input->post('dept');
    //     $reason = $this->input->post('reason');

    //     $this->db->select("
    //         DATE(downtime_mesinof.tanggal) as tanggal,
    //         COUNT(downtime_mesinof.ket_id) as jumlah
    //     ");
    //     $this->db->from('downtime_mesinof');
    //     $this->db->join('downtime_ketof', 'downtime_ketof.id = downtime_mesinof.ket_id', 'left');

    //     if ($dept != 'all') {
    //         $this->db->where('downtime_mesinof.dept_id', $dept);
    //     }

    //     if ($bulan != 'all') {
    //         $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
    //     }

    //     if ($tahun != 'all') {
    //         $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
    //     }

    //     if ($reason != 'all') {
    //         $this->db->where('downtime_mesinof.ket_id', $reason);
    //     }

    //     $this->db->group_by('DATE(downtime_mesinof.tanggal)');
    //     $this->db->order_by('tanggal', 'ASC');

    //     $result = $this->db->get()->result_array();

    //     // 🔥 bikin 1 bulan full (1–31)
    //     $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

    //     $dataFull = [];
    //     for ($i = 1; $i <= $jumlahHari; $i++) {
    //         $tgl = str_pad($i, 2, '0', STR_PAD_LEFT);
    //         $dataFull[$tgl] = 0;
    //     }

    //     foreach ($result as $row) {
    //         $tgl = date('d', strtotime($row['tanggal']));
    //         $dataFull[$tgl] = (int)$row['jumlah'] . 'x';
    //     }

    //     echo json_encode([
    //         'tanggal' => array_keys($dataFull),
    //         'jumlah'  => array_values($dataFull)
    //     ]);
    // }

    // pertanggal
    // public function line()
    // {
    //     $bulan  = $this->input->post('bulan');
    //     $tahun  = $this->input->post('tahun');
    //     $dept   = $this->input->post('dept');
    //     $reason = $this->input->post('reason');


    //     $mapDept = [
    //         'SP' => 'sp',
    //         'RR' => 'rr',
    //         'NT' => 'nt',
    //         'AR' => 'nt',
    //         'UT' => 'nt',
    //         'FN' => 'fn'
    //     ];

    //     $this->db->select("
    //         DATE(downtime_mesinof.tanggal) as tanggal,
    //         downtime_mesinof.ket_id,
    //         downtime_ketof.code,
    //         downtime_ketof.clr,
    //         COUNT(*) as jumlah
    //     ");

    //     $this->db->from('downtime_mesinof');
    //     $this->db->where('downtime_mesinof.ket_id IS NOT NULL', null, false);
    //     $this->db->where('downtime_mesinof.ket_id !=', 0);


    //     $this->db->join(
    //         'downtime_ketof',
    //         'downtime_ketof.id = downtime_mesinof.ket_id',
    //         'left'
    //     );

    //     $this->db->join(
    //         'downtime_libur',
    //         'downtime_libur.tanggal = DATE(downtime_mesinof.tanggal)',
    //         'left'
    //     );

    //     if (!empty($dept) && $dept != 'all') {
    //         $this->db->where('downtime_mesinof.dept_id', $dept);

    //         $fieldLibur = $mapDept[$dept] ?? null;

    //         if ($fieldLibur) {
    //             $this->db->where("(downtime_libur.$fieldLibur = 0 OR downtime_libur.$fieldLibur IS NULL)");
    //         }
    //     }

    //     if (!empty($bulan) && $bulan != 'all') {
    //         $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
    //     }

    //     if (!empty($tahun) && $tahun != 'all') {
    //         $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
    //     }

    //     if (!empty($reason) && $reason != 'all') {
    //         $this->db->where('downtime_mesinof.ket_id', $reason);
    //     }

    //     $this->db->group_by([
    //         'DATE(downtime_mesinof.tanggal)',
    //         'downtime_mesinof.ket_id'
    //     ]);

    //     $this->db->order_by('tanggal', 'ASC');

    //     $result = $this->db->get()->result_array();

    //     $tanggalList = [];
    //     $temp = [];
    //     $warnaMap = [];


    //     foreach ($result as $row) {

    //         if (empty($row['code'])) continue;

    //         $tgl = date('d', strtotime($row['tanggal']));
    //         $kode = $row['code'];

    //         if (!in_array($tgl, $tanggalList)) {
    //             $tanggalList[] = $tgl;
    //         }

    //         $temp[$kode][$tgl] = (int)$row['jumlah'];

    //         $warnaMap[$kode] = !empty($row['clr'])
    //             ? 'rgb(' . $row['clr'] . ')'
    //             : '#999999';
    //     }
    //     ksort($temp);
    //     ksort($warnaMap);


    //     $series = [];
    //     $colors = [];

    //     foreach ($temp as $kode => $data) {
    //         $line = [];

    //         foreach ($tanggalList as $tgl) {
    //             $line[] = isset($data[$tgl]) ? $data[$tgl] : 0;
    //         }

    //         $series[] = [
    //             'name' => $kode,
    //             'data' => $line
    //         ];

    //         $colors[] = $warnaMap[$kode];
    //     }

    //     echo json_encode([
    //         'tanggal' => $tanggalList,
    //         'series'  => $series,
    //         'colors'  => $colors
    //     ]);
    // }

    public function line()
    {
        $bulan  = $this->input->post('bulan');
        $tahun  = $this->input->post('tahun');
        $dept   = $this->input->post('dept');
        $reason = $this->input->post('reason');

        $mapDept = [
            'SP' => 'sp',
            'RR' => 'rr',
            'NT' => 'nt',
            'AR' => 'nt',
            'UT' => 'nt',
            'FN' => 'fn'
        ];



        $this->db->select("
        DATE(downtime_mesinof.tanggal) as tanggal,
        IF(downtime_mesinof.shift=3,0,downtime_mesinof.shift) as shift,
        downtime_mesinof.ket_id,
        downtime_ketof.code,
        downtime_ketof.clr,
        COUNT(*) as jumlah
      ");

        $this->db->from('downtime_mesinof');
        $this->db->where('downtime_mesinof.ket_id IS NOT NULL', null, false);
        $this->db->where('downtime_mesinof.ket_id !=', 0);

        $this->db->join(
            'downtime_ketof',
            'downtime_ketof.id = downtime_mesinof.ket_id',
            'left'
        );

        $this->db->join(
            'downtime_libur',
            'downtime_libur.tanggal = DATE(downtime_mesinof.tanggal)',
            'left'
        );

        if (!empty($dept) && $dept != 'all') {
            $this->db->where('downtime_mesinof.dept_id', $dept);

            $fieldLibur = $mapDept[$dept] ?? null;

            if ($fieldLibur) {
                $this->db->where("(downtime_libur.$fieldLibur = 0 OR downtime_libur.$fieldLibur IS NULL)");
            }
        }

        if (!empty($bulan) && $bulan != 'all') {
            $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
        }

        if (!empty($tahun) && $tahun != 'all') {
            $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
        }

        if (!empty($reason) && $reason != 'all') {
            $this->db->where('downtime_mesinof.ket_id', $reason);
        }

        $this->db->group_by([
            'DATE(downtime_mesinof.tanggal)',
            'shift',
            'downtime_mesinof.ket_id'
        ]);

        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('shift', 'ASC');

        $result = $this->db->get()->result_array();

        $tanggalList = [];
        $temp = [];
        $warnaMap = [];

        $tanggalIndexMap = [];
        $currentIndex = 0;
        $labelMap = [];

        $urutanShift = [
            3 => 1,
            1 => 2,
            2 => 3
        ];
        $mapShift = [
            1 => 'P',
            2 => 'S',
            3 => 'M'
        ];

        foreach ($result as $row) {

            if (empty($row['code'])) continue;

            $tglFull = $row['tanggal'];
            $shift   = (int)$row['shift'];

            if (!isset($tanggalIndexMap[$tglFull])) {
                $currentIndex++;
                $tanggalIndexMap[$tglFull] = $currentIndex;
            }

            $tglIndex = $tanggalIndexMap[$tglFull];

            $shiftFix = ($shift == 0 ? 3 : $shift);

            $label = (($tglIndex - 1) * 3) + ($urutanShift[$shiftFix] ?? $shiftFix);

            if (!in_array($label, $tanggalList)) {
                $tanggalList[] = $label;
            }

            $tglView   = date('d', strtotime($tglFull));
            $shiftView = ($shift == 0 ? 3 : $shift);
            $shiftLabel = $mapShift[$shiftView] ?? $shiftView;
            $labelMap[$label] = $tglView . ' (' . $shiftLabel . ')';



            $kode = $row['code'];

            $temp[$kode][$label] = (int)$row['jumlah'];

            $warnaMap[$kode] = !empty($row['clr'])
                ? 'rgb(' . $row['clr'] . ')'
                : '#999999';
        }


        sort($tanggalList);
        ksort($temp);

        $series = [];
        $colors = [];

        foreach ($temp as $kode => $data) {
            $line = [];

            foreach ($tanggalList as $tgl) {
                $line[] = isset($data[$tgl]) ? $data[$tgl] : 0;
            }

            $series[] = [
                'name' => $kode,
                'data' => $line
            ];

            $colors[] = $warnaMap[$kode];
        }

        // Hitung Total Jml dan Total Baris
        $totalJml = 0;
        $totalBaris = count($result);

        foreach ($result as $row) {
            $totalJml += (int)$row['jumlah'];
        }

        // “Rata-rata jumlah downtime untuk
        // setiap kombinasi tanggal + shift + reason
        // dalam dept & filter yang dipilih”
        $rataRata = ($totalBaris > 0) ? ($totalJml / $totalBaris) : 0;

        echo json_encode([
            'tanggal'  => $tanggalList,
            'series'   => $series,
            'colors'   => $colors,
            'labelMap' => $labelMap,
            'rata_rata' => round($rataRata, 2)
        ]);
    }
    public function line_mesin_jalan()
    {
        $bulan  = $this->input->post('bulan');
        $tahun  = $this->input->post('tahun');
        $dept   = $this->input->post('dept');

        $mapDept = [
            'SP' => 'sp',
            'RR' => 'rr',
            'NT' => 'nt',
            'AR' => 'nt',
            'UT' => 'nt',
            'FN' => 'fn'
        ];

        // 🔥 total mesin
        $this->db->from('downtime_spekmesin');
        if (!empty($dept) && $dept != 'all') {
            $this->db->where('dept_kode', $dept);
        }
        $totalMesin = $this->db->count_all_results();

        // 🔥 downtime semua reason
        $this->db->select("
            DATE(downtime_mesinof.tanggal) as tanggal,
            IF(downtime_mesinof.shift=3,0,downtime_mesinof.shift) as shift,
            COUNT(*) as jumlah
        ");

        $this->db->from('downtime_mesinof');

        $this->db->where('downtime_mesinof.ket_id !=', 0);

        // 🔥 JOIN LIBUR (WAJIB)
        $this->db->join(
            'downtime_libur',
            'downtime_libur.tanggal = DATE(downtime_mesinof.tanggal)',
            'left'
        );

        if (!empty($dept) && $dept != 'all') {
            $this->db->where('downtime_mesinof.dept_id', $dept);

            $fieldLibur = $mapDept[$dept] ?? null;

            if ($fieldLibur) {
                $this->db->where("(downtime_libur.$fieldLibur = 0 OR downtime_libur.$fieldLibur IS NULL)");
            }
        }

        if (!empty($bulan) && $bulan != 'all') {
            $this->db->where('MONTH(downtime_mesinof.tanggal)', $bulan);
        }

        if (!empty($tahun) && $tahun != 'all') {
            $this->db->where('YEAR(downtime_mesinof.tanggal)', $tahun);
        }

        $this->db->group_by([
            'DATE(downtime_mesinof.tanggal)',
            'shift'
        ]);

        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('shift', 'ASC');

        $result = $this->db->get()->result_array();

        // =============================
        // 🔥 FORMAT SAMA PERSIS KAYAK line()
        // =============================
        $tanggalList = [];
        $temp = [];
        $warnaMap = [];
        $labelMap = [];

        $tanggalIndexMap = [];
        $currentIndex = 0;

        $urutanShift = [3 => 1, 1 => 2, 2 => 3];
        $mapShift = [1 => 'P', 2 => 'S', 3 => 'M'];
        $totalJalan = 0;
        $totalBaris = 0;

        foreach ($result as $row) {

            $tglFull = $row['tanggal'];
            $shift   = (int)$row['shift'];

            if (!isset($tanggalIndexMap[$tglFull])) {
                $currentIndex++;
                $tanggalIndexMap[$tglFull] = $currentIndex;
            }

            $tglIndex = $tanggalIndexMap[$tglFull];
            $shiftFix = ($shift == 0 ? 3 : $shift);

            $label = (($tglIndex - 1) * 3) + ($urutanShift[$shiftFix] ?? $shiftFix);

            if (!in_array($label, $tanggalList)) {
                $tanggalList[] = $label;
            }

            $tglView = date('d', strtotime($tglFull));
            $shiftLabel = $mapShift[$shiftFix] ?? $shiftFix;
            $labelMap[$label] = $tglView . ' (' . $shiftLabel . ')';

            // 🔥 HITUNG MESIN JALAN
            $jalan = $totalMesin - (int)$row['jumlah'];

            // 🔥 TAMBAHAN UNTUK RATA-RATA
            $totalJalan += $jalan;
            $totalBaris++;

            $kode = 'MESIN JALAN';

            $temp[$kode][$label] = $jalan;
            $warnaMap[$kode] = !empty($row['clr'])
                ? 'rgb(' . $row['clr'] . ')'
                : '#999999';
        }
        $rataRata = ($totalBaris > 0) ? ($totalJalan / $totalBaris) : 0;

        sort($tanggalList);

        $series = [];
        $colors = [];

        foreach ($temp as $kode => $data) {
            $line = [];

            foreach ($tanggalList as $tgl) {
                $line[] = $data[$tgl] ?? 0;
            }

            $series[] = [
                'name' => $kode,
                'data' => $line
            ];

            $colors[] = $warnaMap[$kode];
        }

        echo json_encode([
            'tanggal'  => $tanggalList,
            'series'   => $series,
            'colors'   => $colors,
            'labelMap' => $labelMap,
            'rata_rata' => round($rataRata, 2)
        ]);
    }
}
