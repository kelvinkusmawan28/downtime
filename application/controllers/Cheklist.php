<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class cheklist extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cheklist_model');

        is_logged_in();
        $this->load->library('Pdf');
    }

    public function index()
    {
        $data['title'] = 'Cheklist Preventif';
        $data['dept_options'] = $this->Cheklist_model->getFilter();
        $data['tahun_options'] = $this->Cheklist_model->getTahun();
        $data['thn_sekarang'] = date('Y');

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
        $this->load->view('cheklist/index', $data);
        $this->load->view('templates/footer');
    }
    public function reset_filter()
    {
        $this->session->unset_userdata('filter_dept');
        redirect('spekmesin');
    }

    private function getJumlahChecklist($mach_id, $kategori, $tahun)
    {
        return $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', $kategori)
            ->where('YEAR(tanggal)', $tahun)
            ->count_all_results('downtime_cheklist');
    }


    public function filter_spek()
    {
        $dept_id      = $this->input->post('dept_id');
        $filter_tahun = $this->input->post('filter_tahun');

        $this->session->set_userdata('filter_dept', $dept_id);
        $this->session->set_userdata('filter_tahun', $filter_tahun);

        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $draw   = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN', 2 => 'NT', 3 => 'RR',
            4 => 'SP', 5 => 'UT', 6 => 'GF',
        ];

        $akses_dept = [];

        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }


        $this->db->from('downtime_spekmesin');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->like('mach_id', $dept_id, 'after');
        } else {
            if (!empty($akses_dept)) {
                $this->db->group_start();
                foreach ($akses_dept as $kode) {
                    $this->db->or_like('mach_id', $kode, 'after');
                }
                $this->db->group_end();
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('mach_no', $search);
            $this->db->or_like('mach_name', $search);
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->count_all_results();


        $this->db->from('downtime_spekmesin');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->like('mach_id', $dept_id, 'after');
        } else {
            if (!empty($akses_dept)) {
                $this->db->group_start();
                foreach ($akses_dept as $kode) {
                    $this->db->or_like('mach_id', $kode, 'after');
                }
                $this->db->group_end();
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('mach_no', $search);
            $this->db->or_like('mach_name', $search);
            $this->db->group_end();
        }

        $this->db->where('aroz', 0);
        $this->db->order_by('mach_id', 'ASC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        $th = ($filter_tahun == 'all') ? date('Y') : $filter_tahun;

        foreach ($data as &$row) {

            $id   = $row['mach_id'];
            $kode = $row['preventif_kode'];

            $row['kodemesin'] = '<b>' . $id . '</b>';
            $row['nomesin'] = '<span class="text-dark">Mesin ' . $row['mach_no'] . '<br>' . $row['mach_name'] . '</span>';






            if ($filter_tahun == 'all' || $filter_tahun == date('Y')) {
                $tanggal = date('Y-m-d');
            } else {
                $tanggal = $filter_tahun . '-12-31';
            }

            $row['harian']   = $this->Cheklist_model->statusHarian($id, $tanggal);
            $row['mingguan'] = $this->Cheklist_model->statusMingguan($id, $tanggal);
            $row['bulanan']  = $this->Cheklist_model->statusBulanan($id, $tanggal);
            $row['3bulan']   = $this->Cheklist_model->statusTigaBulan($id, $tanggal);
            $row['6bulan']   = $this->Cheklist_model->statusEnamBulan($id, $tanggal);
            $row['tahunan']  = $this->Cheklist_model->statusTahunan($id, $tanggal);




            $row['aksi'] = '
            <div class="dropdown mb-1">
              <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Checklist
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item " href="' . base_url("cheklist/harian/$id/$kode") . '">Harian</a></li>
                <li><a class="dropdown-item " href="' . base_url("cheklist/mingguan/$id/$kode") . '">Mingguan</a></li>
                <li><a class="dropdown-item " href="' . base_url("cheklist/bulanan/$id/$kode") . '">Bulanan</a></li>
                <li><a class="dropdown-item " href="' . base_url("cheklist/tiga_bulan/$id/$kode") . '">Per 3 Bulan</a></li>
                <li><a class="dropdown-item " href="' . base_url("cheklist/enam_bulan/$id/$kode") . '">Per 6 Bulan</a></li>
                <li><a class="dropdown-item " href="' . base_url("cheklist/tahunan/$id/$kode") . '">Tahunan</a></li>
              </ul>
            </div>
    
            <a href="' . base_url("cheklist/rekap/$id/$th") . '" 
               class="btn btn-success btn-sm w-100 mb-1">
               Rekap
            </a>
    
          ';
        }

        // ================= TOTAL DATA =================
        $this->db->from('downtime_spekmesin');
        $recordsTotal = $this->db->count_all_results();

        // ================= RESPONSE =================
        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }



    public function rekap($mach_id, $tahun)
    {
        $data['title']  = 'REKAP CHEKLIST';
        $data['header'] = $this->Cheklist_model->IdentitasMesin($mach_id);

        // ================== REKAP PROGRES ==================
        $target = [
            'harian'     => 365,
            'mingguan'   => 52,
            'bulanan'    => 12,
            'tiga_bulan' => 4,
            'enam_bulan' => 2,
            'tahunan'    => 1,
        ];

        $terisi = [
            'harian'     => $this->Cheklist_model->getJumlahHarian($mach_id, $tahun),
            'mingguan'   => $this->Cheklist_model->getJumlahMingguan($mach_id, $tahun),
            'bulanan'    => $this->Cheklist_model->getJumlahBulanan($mach_id, $tahun),
            'tiga_bulan' => $this->Cheklist_model->getJumlahTigaBulanan($mach_id, $tahun),
            'enam_bulan' => $this->Cheklist_model->getJumlahEnamBulanan($mach_id, $tahun),
            'tahunan'    => $this->Cheklist_model->getJumlahTahunan($mach_id, $tahun),
        ];

        $rekap = [];
        foreach ($target as $kategori => $ideal) {
            $isi = $terisi[$kategori];

            if ($isi >= $ideal) {
                $status = '<span class="text-success">✔ Selesai</span>';
            } elseif ($isi > 0) {
                $status = '<span class="text-warning">⏳ Proses</span>';
            } else {
                $status = '<span class="text-muted">➖ Belum</span>';
            }

            $rekap[] = [
                'kategori' => ucfirst(str_replace('_', ' ', $kategori)),
                'target'   => $ideal,
                'terisi'   => $isi,
                'persen'   => round(($isi / $ideal) * 100, 1),
                'status'   => $status
            ];
        }

        $data['rekap'] = $rekap;
        $data['tahun'] = $tahun;

        // ================== REKAP HARIAN PER BULAN ==================


        $bulan = $this->input->get('filter_bulan') ?? date('m');
        // var_dump($bulan);
        // die;
        $data['bulan'] = $bulan;

        $data['bulan_options'] = $this->Cheklist_model->getBulan();
        $rekapharian = $this->Cheklist_model->getRekapHarian($mach_id, $tahun, $bulan);

        $matrix = [];
        foreach ($rekapharian as $row) {
            $item = $row['id_item'];
            $tgl  = date('d', strtotime($row['tanggal'])); // 01,02,03
            $matrix[$item][$tgl] = $row['status'] == 'OK'
                ? '✔'
                : ($row['status'] == 'LB' ? '-' : '❌');
        }

        $data['matrix'] = $matrix;
        $data['items']  = $this->Cheklist_model->getItemPemeriksaan($mach_id);


        // ================== REKAP MINGGUAN ==================
        $rekapmingguan = $this->Cheklist_model->getRekapMingguan($mach_id, $bulan, $tahun);

        $matrix_mingguan = [];
        $headerTanggal = [];

        foreach ($rekapmingguan as $row) {
            $item = $row['id_item'];
            $tgl  = date('d-M', strtotime($row['tanggal'])); // 07-01

            $matrix_mingguan[$item][$tgl] = $row['status'] == 'OK' ? '✔' : '❌';

            if (!in_array($tgl, $headerTanggal)) {
                $headerTanggal[] = $tgl;
            }
        }
        $data['items_mingguan']  = $this->Cheklist_model->getItemPemeriksaan_mingguan($mach_id);
        $data['matrix_mingguan'] = $matrix_mingguan;
        $data['headerTanggal']   = $headerTanggal;

        // ================== REKAP BULANAN ==================
        $rekapbulanan = $this->Cheklist_model->getRekapBulanan($mach_id, $bulan, $tahun);

        $matrix_bulanan = [];
        $headerbulanan = [];

        foreach ($rekapbulanan as $row) {
            $item = $row['id_item'];
            $tgl  =  date('m', strtotime($row['tanggal'])); // 07-01

            $matrix_bulanan[$item][$tgl] = $row['status'] == 'OK' ? '✔' : '❌';

            if (!in_array($tgl, $headerbulanan)) {
                $headerbulanan[] = $tgl;
            }
        }

        $data['items_bulanan']  = $this->Cheklist_model->getItemPemeriksaan_bulanan($mach_id);
        $data['matrix_bulanan'] = $matrix_bulanan;
        $data['headerBulanan']   = $headerbulanan;

        // ================== REKAP 3 BULAN ==================

        $rekap_tigabulan = $this->Cheklist_model->getRekap_TigaBulan($mach_id, $bulan, $tahun);

        $matrix_tigabulan = [];
        $header_tigabulan = [];

        foreach ($rekap_tigabulan as $row) {
            $item = $row['id_item'];
            $tgl  =  date('m', strtotime($row['tanggal'])); // 07-01

            $matrix_tigabulan[$item][$tgl] = $row['status'] == 'OK' ? '✔' : '❌';

            if (!in_array($tgl, $header_tigabulan)) {
                $header_tigabulan[] = $tgl;
            }
        }

        $data['items_tigabulan']  = $this->Cheklist_model->getItemPemeriksaan_TigaBulan($mach_id);
        $data['matrix_tigabulan'] = $matrix_tigabulan;
        $data['header_tigabulan']   = $header_tigabulan;

        // ================== REKAP 6 BULAN ==================

        $rekap_enambulan = $this->Cheklist_model->getRekap_EnamBulan($mach_id, $bulan, $tahun);

        $matrix_enambulan = [];
        $header_enambulan = [];

        foreach ($rekap_enambulan as $row) {
            $item = $row['id_item'];
            $tgl  =  date('m', strtotime($row['tanggal']));

            $matrix_enambulan[$item][$tgl] = $row['status'] == 'OK' ? '✔' : '❌';

            if (!in_array($tgl, $header_enambulan)) {
                $header_enambulan[] = $tgl;
            }
        }

        $data['items_enambulan']  = $this->Cheklist_model->getItemPemeriksaan_EnamBulan($mach_id);
        $data['matrix_enambulan'] = $matrix_enambulan;
        $data['header_enambulan']   = $header_enambulan;
        // ================== REKAP 1 Tahun ==================

        $rekap_tahunan = $this->Cheklist_model->getRekap_Tahunan($mach_id, $bulan, $tahun);

        $matrix_tahunan = [];
        $header_tahunan = [];

        foreach ($rekap_tahunan as $row) {
            $item = $row['id_item'];
            $tgl  =  date('m', strtotime($row['tanggal']));

            $matrix_tahunan[$item][$tgl] = $row['status'] == 'OK' ? '✔' : '❌';

            if (!in_array($tgl, $header_tahunan)) {
                $header_tahunan[] = $tgl;
            }
        }

        $data['items_tahunan']  = $this->Cheklist_model->getItemPemeriksaan_Tahunan($mach_id);
        $data['matrix_tahunan'] = $matrix_tahunan;
        $data['header_tahunan']   = $header_tahunan;



        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/rekap', $data);
        $this->load->view('templates/footer');
    }




    public function harian($mach_id, $kode)
    {
        $data['title'] = 'Cheklist Preventif Harian';

        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

        $sudah_cek = $this->Cheklist_model->Checklis_oke($mach_id, $tanggal);

        $info_cek = null;
        if ($sudah_cek) {
            $info_cek = $this->Cheklist_model->getPetugas($mach_id, $tanggal);
        }

        $data['info_cek'] = $info_cek;
        $data['tgl_sekarang'] = $tanggal;

        $data['header'] = $this->db
            ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
            ->row_array();

        $data['kode'] = $this->db
            ->get_where('downtime_preventif', ['kode' => $kode])
            ->row_array();

        $data['items'] = $this->Cheklist_model
            ->getChecklistHarian($data['kode']['id'], $mach_id, $tanggal);

        $data['sudah_cek'] = $sudah_cek;



        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $hari_sekarang = date('d', strtotime($tanggal));


        $rows = $this->db
            ->select('tanggal')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'harian')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->get('downtime_cheklist')
            ->result();

        $tanggal_terisi = [];
        foreach ($rows as $r) {
            $tanggal_terisi[] = date('Y-m-d', strtotime($r->tanggal));
        }


        $tanggal_terlewat = [];
        for ($i = 1; $i < $hari_sekarang; $i++) {
            $tgl = "$tahun-$bulan-" . str_pad($i, 2, '0', STR_PAD_LEFT);

            if (!in_array($tgl, $tanggal_terisi)) {
                $tanggal_terlewat[] = format_tanggal_indonesia($tgl);
            }
        }

        $data['tanggal_terlewat'] = $tanggal_terlewat;
        $data['ada_terlewat'] = !empty($tanggal_terlewat);



        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/harian', $data);
        $this->load->view('templates/footer');
    }



    public function simpan_harian()
    {
        $tanggal  = $this->input->post('tanggal');
        $mach_id  = $this->input->post('mach_id');
        $petugas  = $this->input->post('petugas');
        $status   = $this->input->post('status');
        $catatan  = $this->input->post('catatan');
        $kode  = $this->input->post('kode');
        $kategori = 'harian';

        foreach ($status as $id_item => $sts) {

            // cek update
            $this->db->where([
                'id_item' => $id_item,
                'mach_id' => $mach_id,
                'tanggal' => $tanggal
            ])->delete('downtime_cheklist');

            $query =  $this->db->insert('downtime_cheklist', [
                'id_item' => $id_item,
                'mach_id' => $mach_id,
                'tanggal' => $tanggal,
                'status'  => $sts,
                'kategori' => $kategori,
                'catatan' => $catatan[$id_item] ?? '',
                'petugas' => $petugas
            ]);
        }
        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                PREVENTIF HARIAN BERHASIL DI SIMPAN !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('cheklist/harian/' . $mach_id . '/' . $kode);
    }

    public function mingguan($mach_id, $kode)
    {
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

        $preventif = $this->db->get_where('downtime_preventif', ['kode' => $kode])->row_array();

        if (!$preventif) {
            show_error('Kode preventif tidak ditemukan');
        }

        $info_minggu = range_minggu_dalam_bulan($tanggal);

        if (!$info_minggu || !isset($info_minggu['minggu_ke'])) {
            show_error('Gagal menentukan minggu checklist');
        }

        $minggu_sekarang = $info_minggu['minggu_ke'];


        // Cekminggu init sudah checklist

        $info_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'mingguan')
            ->where('minggu_ke', $minggu_sekarang)
            ->where('MONTH(tanggal)', date('m', strtotime($tanggal)))
            ->where('YEAR(tanggal)', date('Y', strtotime($tanggal)))
            ->get('downtime_cheklist')
            ->row_array();

        $sudah_cek = !empty($info_cek);

        $rows = $this->db
            ->select('tanggal, minggu_ke')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'mingguan')
            ->where('MONTH(tanggal)', date('m', strtotime($tanggal)))
            ->where('YEAR(tanggal)', date('Y', strtotime($tanggal)))
            ->get('downtime_cheklist')
            ->result();

        // minggu yang SUDAH diisi
        $minggu_terisi = [];
        foreach ($rows as $r) {
            if (!empty($r->minggu_ke)) {
                $minggu_terisi[] = (int)$r->minggu_ke;
            }
        }


        $minggu_terlewat = [];
        for ($i = 1; $i < $minggu_sekarang; $i++) {
            if (!in_array($i, $minggu_terisi)) {
                $info = range_minggu_dalam_bulan(
                    date('Y-m-' . (($i - 1) * 7 + 1))
                );

                $minggu_terlewat[] =
                    "Minggu ke-{$i} {$info['bulan']} {$info['tahun']}";
            }
        }

        $data = [
            'title'           => 'Checklist Preventif Mingguan',
            'tgl_sekarang'    => $tanggal,
            'header'          => $this->db
                ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
                ->row_array(),
            'kode'            => $preventif,
            'items'           => $this->Cheklist_model
                ->getChecklistMingguan($preventif['id'], $mach_id, $tanggal),
            'sudah_cek'       => $sudah_cek,
            'info_cek'        => $info_cek,
            'minggu_terlewat' => $minggu_terlewat,
            'ada_terlewat'    => !empty($minggu_terlewat),
            'tampilkan_info' => $sudah_cek
        ];

        // info periode hanya jika minggu ini sudah dicek
        if ($sudah_cek) {
            $data['periode'] = range_minggu_dalam_bulan($info_cek['tanggal']);
        }

        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/mingguan', $data);
        $this->load->view('templates/footer');
    }



    public function simpan_mingguan()
    {
        $mach_id      = $this->input->post('mach_id');
        $tanggal      = $this->input->post('tanggal');
        $petugas      = $this->input->post('petugas');
        $status_list  = $this->input->post('status');
        $catatan_list = $this->input->post('catatan');
        $kode  = $this->input->post('kode');
        $info = range_minggu_dalam_bulan($tanggal);

        foreach ($status_list as $id_item => $status) {
            $data = [
                'mach_id'  => $mach_id,
                'id_item'  => $id_item,
                'tanggal'  => $tanggal,
                'kategori' => 'mingguan',
                'minggu_ke' => $info['minggu_ke'],
                'status'   => $status,
                'catatan'  => $catatan_list[$id_item] ?? null,
                'petugas'  => $petugas,
            ];

            $query = $this->db->insert('downtime_cheklist', $data);
        }

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                PREVENTIF HARIAN BERHASIL DI SIMPAN !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('cheklist/mingguan/' . $mach_id . '/' . $kode);
    }


    public function bulanan($mach_id, $kode)
    {
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');
        $bulan   = date('m', strtotime($tanggal));
        $tahun   = date('Y', strtotime($tanggal));


        $preventif = $this->db->get_where('downtime_preventif', ['kode' => $kode])->row_array();

        if (!$preventif) {
            show_error('Kode preventif tidak ditemukan');
        }

        $sudah_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'bulanan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->count_all_results('downtime_cheklist') > 0;

        //  Info checklist 
        $info_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'bulanan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->get('downtime_cheklist')
            ->row_array();


        $items = $this->Cheklist_model->getChecklistBulanan($preventif['id'], $mach_id, $tanggal);

        //  Cari bulan yang terlewat
        $rows = $this->db
            ->select('tanggal')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'bulanan')
            ->where('YEAR(tanggal)', $tahun)
            ->get('downtime_cheklist')
            ->result();

        $bulan_terisi = [];
        foreach ($rows as $r) {
            $bulan_terisi[] = date('m', strtotime($r->tanggal));
        }

        $bulan_terlewat = [];
        for ($i = 1; $i < (int)$bulan; $i++) {
            if (!in_array(str_pad($i, 2, '0', STR_PAD_LEFT), $bulan_terisi)) {
                $bulan_terlewat[] = format_bulan_indonesia($i) . ' ' . $tahun;
            }
        }

        // 6️⃣ Kirim ke view
        $data = [
            'title'          => 'Checklist Preventif Bulanan',
            'tgl_sekarang'   => $tanggal,
            'header'         => $this->db
                ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
                ->row_array(),
            'kode'           => $preventif,
            'items'          => $items,
            'sudah_cek'      => $sudah_cek,
            'info_cek'       => $info_cek,
            'bulan_terlewat' => $bulan_terlewat,
            'ada_terlewat'   => !empty($bulan_terlewat)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/bulanan', $data);
        $this->load->view('templates/footer');
    }


    public function simpan_bulanan()
    {
        $mach_id  = $this->input->post('mach_id');
        $kode     = $this->input->post('kode');
        $tanggal  = $this->input->post('tanggal');
        $petugas  = $this->input->post('petugas');
        $status   = $this->input->post('status');
        $catatan  = $this->input->post('catatan');

        foreach ($status as $id_item => $nilai) {
            $data = [
                'mach_id'  => $mach_id,
                'id_item'  => $id_item,
                'tanggal'  => $tanggal,
                'status'   => $nilai,
                'catatan'  => $catatan[$id_item] ?? '',
                'petugas'  => $petugas,
                'kategori' => 'bulanan'
            ];

            $query = $this->db->insert('downtime_cheklist', $data);
        }
        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                PREVENTIF BULANAN DI SIMPAN !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('cheklist/bulanan/' . $mach_id . '/' . $kode);
    }


    public function tiga_bulan($mach_id, $kode)
    {
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

        $bulan   = date('n', strtotime($tanggal));
        $tahun   = date('Y', strtotime($tanggal));
        $triwulan = ceil($bulan / 3);

        $awal = date('Y-m-d', strtotime("$tahun-" . (($triwulan - 1) * 3 + 1) . "-01"));
        $akhir = date('Y-m-t', strtotime("$awal +2 month"));

        // 1️⃣ Ambil preventif
        $preventif = $this->db
            ->get_where('downtime_preventif', ['kode' => $kode])
            ->row_array();

        if (!$preventif) {
            show_error('Kode preventif tidak ditemukan');
        }

        // 2️⃣ Cek sudah checklist triwulan ini?
        $sudah_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', '3 bulan')
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->count_all_results('downtime_cheklist') > 0;

        // 3️⃣ Info checklist triwulan ini
        $info_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', '3 bulan')
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->get('downtime_cheklist')
            ->row_array();

        // 4️⃣ Ambil item checklist
        $items = $this->Cheklist_model
            ->getChecklistTigaBulan($preventif['id'], $mach_id, $tanggal);

        // 5️⃣ Cari triwulan yang terlewat
        $rows = $this->db
            ->select('tanggal')
            ->where('mach_id', $mach_id)
            ->where('kategori', '3 bulan')
            ->where('YEAR(tanggal)', $tahun)
            ->get('downtime_cheklist')
            ->result();

        $triwulan_terisi = [];
        foreach ($rows as $r) {
            $triwulan_terisi[] = ceil(date('n', strtotime($r->tanggal)) / 3);
        }

        $triwulan_terlewat = [];
        for ($i = 1; $i < $triwulan; $i++) {
            if (!in_array($i, $triwulan_terisi)) {
                $triwulan_terlewat[] = "Pengecekan 3 Bulan Periode Ke-$i $tahun";
            }
        }

        // 6️⃣ Kirim ke view
        $data = [
            'title'            => 'Checklist Preventif Per 3 Bulan',
            'tgl_sekarang'     => $tanggal,
            'header'           => $this->db
                ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
                ->row_array(),
            'kode'             => $preventif,
            'items'            => $items,
            'sudah_cek'        => $sudah_cek,
            'info_cek'         => $info_cek,
            'periode'          => [
                'triwulan' => $triwulan,
                'awal' => $awal,
                'akhir' => $akhir
            ],
            'triwulan_terlewat' => $triwulan_terlewat,
            'ada_terlewat'     => !empty($triwulan_terlewat)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/tiga_bulan', $data);
        $this->load->view('templates/footer');
    }

    public function simpan_tigabulan()
    {
        $mach_id = $this->input->post('mach_id');
        $kode    = $this->input->post('kode');
        $tanggal = $this->input->post('tanggal');
        $petugas = $this->input->post('petugas');
        $status  = $this->input->post('status');
        $catatan = $this->input->post('catatan');

        // $bulan = date('n', strtotime($tanggal));
        // $triwulan = ceil($bulan / 3);
        // $tahun = date('Y', strtotime($tanggal));

        // $awal = date('Y-m-d', strtotime("$tahun-" . (($triwulan - 1) * 3 + 1) . "-01"));
        // $akhir = date('Y-m-t', strtotime("$awal +2 month"));

        // $sudah_cek = $this->db
        //     ->where('mach_id', $mach_id)
        //     ->where('kategori', 'tiga_bulan')
        //     ->where('tanggal >=', $awal)
        //     ->where('tanggal <=', $akhir)
        //     ->count_all_results('downtime_cheklist');

        // if ($sudah_cek > 0) {
        //     $this->session->set_flashdata('error', 'Checklist triwulan sudah diisi!');
        //     redirect('cheklist/tiga_bulan/' . $mach_id . '/' . $kode);
        // }

        foreach ($status as $id_item => $nilai) {

            $data = [
                'mach_id'  => $mach_id,
                'id_item'  => $id_item,
                'tanggal'  => $tanggal,
                'status'   => $nilai,
                'catatan'  => $catatan[$id_item] ?? '',
                'petugas'  => $petugas,
                'kategori' => '3 bulan'
            ];

            $query = $this->db->insert('downtime_cheklist', $data);
        }

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                PREVENTIF PER 3 BULAN DI SIMPAN !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('cheklist/tiga_bulan/' . $mach_id . '/' . $kode);
    }

    public function enam_bulan($mach_id, $kode)
    {
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');

        $bulan   = date('n', strtotime($tanggal));
        $tahun   = date('Y', strtotime($tanggal));
        $enambulan = ceil($bulan / 6);

        $awal = date('Y-m-d', strtotime("$tahun-" . (($enambulan - 1) * 6 + 1) . "-01"));
        $akhir = date('Y-m-t', strtotime("$awal +5 month"));

        $preventif = $this->db
            ->get_where('downtime_preventif', ['kode' => $kode])->row_array();

        if (!$preventif) {
            show_error('Kode preventif tidak ditemukan');
        }
        $sudah_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', '6 bulan')
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->count_all_results('downtime_cheklist') > 0;


        $info_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', '6 bulan')
            ->where('tanggal >=', $awal)
            ->where('tanggal <=', $akhir)
            ->get('downtime_cheklist')
            ->row_array();

        $items = $this->Cheklist_model
            ->getChecklistEnamBulan($preventif['id'], $mach_id, $tanggal);

        // Cari 6bulan yang terlewat
        $rows = $this->db
            ->select('tanggal')
            ->where('mach_id', $mach_id)
            ->where('kategori', '6 bulan')
            ->where('YEAR(tanggal)', $tahun)
            ->get('downtime_cheklist')
            ->result();

        $enambulan_terisi = [];
        foreach ($rows as $r) {
            $enambulan_terisi[] = ceil(date('n', strtotime($r->tanggal)) / 6);
        }

        $enambulan_terlewat = [];
        for ($i = 1; $i < $enambulan; $i++) {
            if (!in_array($i, $enambulan_terisi)) {
                $enambulan_terlewat[] = "Pengecekan 6 Bulan Periode Ke-$i $tahun";
            }
        }

        // 6️⃣ Kirim ke view
        $data = [
            'title'            => 'Checklist Preventif Per 6 Bulan',
            'tgl_sekarang'     => $tanggal,
            'header'           => $this->db
                ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
                ->row_array(),
            'kode'             => $preventif,
            'items'            => $items,
            'sudah_cek'        => $sudah_cek,
            'info_cek'         => $info_cek,
            'periode'          => [
                'triwulan' => $enambulan,
                'awal' => $awal,
                'akhir' => $akhir
            ],
            'enambulan_terlewat' => $enambulan_terlewat,
            'ada_terlewat'     => !empty($enambulan_terlewat)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/enam_bulan', $data);
        $this->load->view('templates/footer');
    }

    public function simpan_enambulan()
    {
        $mach_id = $this->input->post('mach_id');
        $kode    = $this->input->post('kode');
        $tanggal = $this->input->post('tanggal');
        $petugas = $this->input->post('petugas');
        $status  = $this->input->post('status');
        $catatan = $this->input->post('catatan');


        foreach ($status as $id_item => $nilai) {

            $data = [
                'mach_id'  => $mach_id,
                'id_item'  => $id_item,
                'tanggal'  => $tanggal,
                'status'   => $nilai,
                'catatan'  => $catatan[$id_item] ?? '',
                'petugas'  => $petugas,
                'kategori' => '6 bulan'
            ];

            $query = $this->db->insert('downtime_cheklist', $data);
        }

        $query = $this->db->insert('downtime_cheklist', $data);

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                PREVENTIF PER 6 BULAN DI SIMPAN !
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }
        redirect('cheklist/enam_bulan/' . $mach_id . '/' . $kode);
    }

    public function tahunan($mach_id, $kode)
    {
        $tanggal = $this->input->get('tanggal') ?? date('Y-m-d');
        $tahun   = date('Y', strtotime($tanggal));


        $preventif = $this->db->get_where('downtime_preventif', ['kode' => $kode])->row_array();

        if (!$preventif) {
            show_error('Kode preventif tidak ditemukan');
        }

        $awal  = "$tahun-01-01";
        $akhir = "$tahun-12-31";


        $sudah_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->where("tanggal BETWEEN '$awal' AND '$akhir'")
            ->count_all_results('downtime_cheklist') > 0;

        $info_cek = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->where("tanggal BETWEEN '$awal' AND '$akhir'")
            ->order_by('tanggal', 'DESC')
            ->get('downtime_cheklist')
            ->row_array();

        $items = $this->Cheklist_model->getChecklistTahunan($preventif['id'], $mach_id, $tanggal);

        $rows = $this->db
            ->select('tanggal')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->get('downtime_cheklist')
            ->result();

        $tahun_terisi = [];
        foreach ($rows as $r) {
            $tahun_terisi[] = date('Y', strtotime($r->tanggal));
        }

        $tahun_terlewat = [];
        for ($i = $tahun - 0; $i < $tahun; $i++) {
            if (!in_array((string)$i, $tahun_terisi)) {
                $tahun_terlewat[] = "Tahun $i";
            }
        }

        $data = [
            'title'          => 'Checklist Preventif Tahunan',
            'tgl_sekarang'   => $tanggal,
            'header'         => $this->db
                ->get_where('downtime_spekmesin', ['mach_id' => $mach_id])
                ->row_array(),
            'kode'           => $preventif,
            'items'          => $items,
            'sudah_cek'      => $sudah_cek,
            'info_cek'       => $info_cek,
            'periode'        => [
                'tahun' => $tahun,
                'awal'  => $awal,
                'akhir' => $akhir
            ],
            'tahun_terlewat' => $tahun_terlewat,
            'ada_terlewat'   => !empty($tahun_terlewat)
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('cheklist/tahunan', $data);
        $this->load->view('templates/footer');
    }

    public function simpan_tahunan()
    {
        $mach_id   = $this->input->post('mach_id');
        $kode      = $this->input->post('kode');
        $petugas   = $this->input->post('petugas');
        $tanggal   = $this->input->post('tanggal');

        $items = $this->input->post('status');

        if (!$items) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger">
                Data checklist kosong!
            </div>');
            redirect('cheklist/tahunan/' . $mach_id . '/' . $kode);
            return;
        }

        foreach ($items as $id_item => $status) {
            $data = [
                'mach_id'  => $mach_id,
                'id_item'  => $id_item,
                'kategori' => 'tahunan',
                'status'   => $status,
                'catatan'  => $this->input->post('catatan')[$id_item] ?? null,
                'petugas'  => $petugas,
                'tanggal'  => $tanggal
            ];

            $this->db->insert('downtime_cheklist', $data);
        }

        $this->session->set_flashdata('message', '
        <div class="alert alert-success">
            PREVENTIF TAHUNAN BERHASIL DISIMPAN!
        </div>');

        redirect('cheklist/tahunan/' . $mach_id . '/' . $kode);
    }

    public function cetak($mach_id, $bulan, $tahun)
    {
        $data['title']  = 'CETAK DATA';
        $data['header'] = $this->Cheklist_model->IdentitasMesin($mach_id);
        $data['bulan']  = $bulan;
        $data['tahun']  = $tahun;

        // harian
        $rekapharian = $this->Cheklist_model->getRekapHarian($mach_id, $tahun, $bulan);
        $matrix = [];

        foreach ($rekapharian as $row) {
            $item = $row['id_item'];
            $tgl  = date('d', strtotime($row['tanggal']));

            $matrix[$item][$tgl] =
                ($row['status'] == 'OK') ? '✔' : (($row['status'] == 'LB') ? '-' : '❌');
        }

        $data['matrix'] = $matrix;
        $data['items']  = $this->Cheklist_model->getItemPemeriksaan($mach_id);

        //    mingguan
        $rekapmingguan = $this->Cheklist_model->getRekapMingguan($mach_id, $bulan, $tahun);
        $matrix_mingguan = [];

        foreach ($rekapmingguan as $row) {
            $item = $row['id_item'];


            $day = date('d', strtotime($row['tanggal']));



            $matrix_mingguan[$item][$day] =
                ($row['status'] == 'OK') ? '✔' : '❌';
        }

        $data['items_mingguan']  = $this->Cheklist_model->getItemPemeriksaan_mingguan($mach_id);
        $data['matrix_mingguan'] = $matrix_mingguan;




        //    bulanan
        $rekapbulanan = $this->Cheklist_model->getRekapBulanan($mach_id, $bulan, $tahun);
        $matrix_bulanan = [];

        foreach ($rekapbulanan as $row) {
            $item = $row['id_item'];

            $day = date('d', strtotime($row['tanggal']));
            $matrix_bulanan[$item][$day] =
                ($row['status'] == 'OK') ? '✔' : '❌';
        }

        $data['items_bulanan']  = $this->Cheklist_model->getItemPemeriksaan_bulanan($mach_id);
        $data['matrix_bulanan'] = $matrix_bulanan;

        // 3bulan
        $rekap3 = $this->Cheklist_model->getRekap_TigaBulan($mach_id, $bulan, $tahun);
        $matrix_3bulan = [];

        foreach ($rekap3 as $row) {
            $item = $row['id_item'];
            $day = date('d', strtotime($row['tanggal']));


            $matrix_3bulan[$item][$day] =
                ($row['status'] == 'OK') ? '✔' : '❌';
        }

        $data['items_tigabulan']  = $this->Cheklist_model->getItemPemeriksaan_TigaBulan($mach_id);
        $data['matrix_tigabulan'] = $matrix_3bulan;

        //6 bulan
        $rekap6 = $this->Cheklist_model->getRekap_EnamBulan($mach_id, $bulan, $tahun);
        $matrix_6bulan = [];

        foreach ($rekap6 as $row) {
            $item = $row['id_item'];
            $day = date('d', strtotime($row['tanggal']));

            $matrix_6bulan[$item][$day] =
                ($row['status'] == 'OK') ? '✔' : '❌';
        }

        $data['items_enambulan']  = $this->Cheklist_model->getItemPemeriksaan_EnamBulan($mach_id);
        $data['matrix_enambulan'] = $matrix_6bulan;



        //tahunan
        $rekaptahun = $this->Cheklist_model->getRekap_Tahunan($mach_id, $bulan, $tahun);
        $matrix_tahun = [];

        foreach ($rekaptahun as $row) {

            $item = $row['id_item'];
            $day = date('d', strtotime($row['tanggal']));

            $matrix_tahun[$item][$day] =
                ($row['status'] == 'OK') ? '✔' : '❌';
        }

        $data['items_tahunan']  = $this->Cheklist_model->getItemPemeriksaan_Tahunan($mach_id);
        $data['matrix_tahunan'] = $matrix_tahun;


        $this->load->view('templates/header_cetak', $data);
        $this->load->view('cheklist/cetak', $data);
        $this->load->view('templates/footer_cetak');
    }

    private function _headerPdf($pdf, $header, $bulan, $tahun)
    {
        $pdf->SetFont('Arial', '', 12);


        $yAwal = $pdf->GetY();


        $pdf->Cell(45, 25, '', 1);
        $pdf->Image(FCPATH . 'assets/img/avatars/new1.jpg', 17, $yAwal + 3, 28);
        $pdf->SetXY(10, $yAwal + 22);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(45, 1, "PT. Indoneptune Net Mfg.", 0, 'C');


        $pdf->SetXY(55, $yAwal);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(166, 25, 'PREVENTIVE MAINTENANCE CHECK LIST', 1, 0, 'C');


        $pdf->SetXY(221, $yAwal);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 25, '', 1);

        $pdf->SetXY(222, $yAwal + 3);
        $pdf->Cell(25, 5, 'No. Dok', 0);
        $pdf->Cell(5, 5, ':', 0);
        $pdf->Cell(30, 5, 'FM-UT-02 U', 0, 1);

        $pdf->SetX(222);
        $pdf->Cell(25, 5, 'Revisi', 0);
        $pdf->Cell(5, 5, ':', 0);
        $pdf->Cell(30, 5, '1', 0, 1);

        $pdf->SetX(222);
        $pdf->Cell(25, 5, 'Tanggal', 0);
        $pdf->Cell(5, 5, ':', 0);
        $pdf->Cell(30, 5, '12-12-2013', 0);


        $pdf->Ln(12);
        $pdf->SetFont('Arial', 'B', 10);

        $pdf->Cell(68, 8, 'No. Mesin : ' . $header['mach_no'], 1);
        $pdf->Cell(68, 8, 'Spek Mesin : ' . $header['mach_name'], 1);
        $pdf->Cell(67, 8, 'Bulan : ' . format_bulan_kapital($bulan), 1);
        $pdf->Cell(68, 8, 'Tahun : ' . $tahun, 1);

        $pdf->Ln(8);
    }

    public function pdf($mach_id, $bulan, $tahun)
    {
        $this->load->library('Pdf');
        $this->load->model('Cheklist_model');

        $header = $this->Cheklist_model->IdentitasMesin($mach_id);


        $items_harian      = $this->Cheklist_model->getItemPemeriksaan($mach_id);
        $items_mingguan    = $this->Cheklist_model->getItemPemeriksaan_mingguan($mach_id);
        $items_bulanan     = $this->Cheklist_model->getItemPemeriksaan_bulanan($mach_id);
        $items_3bulan      = $this->Cheklist_model->getItemPemeriksaan_TigaBulan($mach_id);
        $items_6bulan      = $this->Cheklist_model->getItemPemeriksaan_EnamBulan($mach_id);
        $items_tahunan     = $this->Cheklist_model->getItemPemeriksaan_Tahunan($mach_id);

        $matrix_harian     = $this->_buildMatrix($this->Cheklist_model->getRekapHarian($mach_id, $tahun, $bulan));
        $matrix_mingguan   = $this->_buildMatrix($this->Cheklist_model->getRekapMingguan($mach_id, $bulan, $tahun));
        $matrix_bulanan    = $this->_buildMatrix($this->Cheklist_model->getRekapBulanan($mach_id, $bulan, $tahun));
        $matrix_3bulan     = $this->_buildMatrix($this->Cheklist_model->getRekap_TigaBulan($mach_id, $bulan, $tahun));
        $matrix_6bulan     = $this->_buildMatrix($this->Cheklist_model->getRekap_EnamBulan($mach_id, $bulan, $tahun));
        $matrix_tahunan    = $this->_buildMatrix($this->Cheklist_model->getRekap_Tahunan($mach_id, $bulan, $tahun));


        $pdf = new Pdf('L', 'mm', 'A4');
        $pdf->AddPage();



        $this->_headerPdf($pdf, $header, $bulan, $tahun);

        $this->_tableHeader($pdf);

        $this->_drawData($pdf, $items_harian,   $matrix_harian,   'HARIAN');
        $this->_drawData($pdf, $items_mingguan, $matrix_mingguan, 'MINGGUAN');
        $this->_drawData($pdf, $items_bulanan,  $matrix_bulanan,  'BULANAN');
        $this->_drawData($pdf, $items_3bulan,   $matrix_3bulan,   '3 BULAN');
        $this->_drawData($pdf, $items_6bulan,   $matrix_6bulan,   '6 BULAN');
        $this->_drawData($pdf, $items_tahunan,  $matrix_tahunan,  'TAHUNAN');

        $this->_footerInfo($pdf);


        $pdf->Output('I', 'MESIN ' . $header['mach_no'] . '-' . format_bulan_kapital($bulan) . $tahun . '.pdf');
    }
    private function _buildMatrix($data)
    {
        $matrix = [];
        foreach ($data as $row) {
            $item = $row['id_item'];
            $day  = date('d', strtotime($row['tanggal']));
            $matrix[$item][$day] = $row['status'];
        }
        return $matrix;
    }
    private function _tableHeader($pdf)
    {

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 6, 'ITEM PEMERIKSAAN', 1, 0, 'C');
        $pdf->Cell(25, 6, 'PERIODE', 1, 0, 'C');


        for ($d = 1; $d <= 31; $d++) {
            $pdf->Cell(6, 6, str_pad($d, 2, '0', STR_PAD_LEFT), 1, 0, 'C');
        }
        $pdf->Ln();
    }
    private function _drawData($pdf, $items, $matrix, $periode)
    {
        foreach ($items as $it) {

            if ($pdf->GetY() > 185) {
                $pdf->AddPage();
                $this->_tableHeader($pdf);
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(60, 6, $it['nama_item'], 1);
            $pdf->Cell(25, 6, $periode, 1);

            for ($d = 1; $d <= 31; $d++) {
                $day = str_pad($d, 2, '0', STR_PAD_LEFT);
                $status = $matrix[$it['id']][$day] ?? '-';

                $pdf->SetFont('ZapfDingbats', '', 10);

                if ($status === 'OK') {
                    $symbol = chr(51); // ✔
                } elseif ($status === 'NG') {
                    $symbol = chr(55); // ✗
                } else {
                    $symbol = '-';
                    $pdf->SetFont('Arial', '', 7);
                }

                $pdf->Cell(6, 6, $symbol, 1, 0, 'C');
            }
            $pdf->Ln();
        }
    }

    private function _footerInfo($pdf)
    {
        $pdf->SetFont('Arial', '', 8);

        $x = 10;
        $h = 34;


        if ($pdf->GetY() + $h > $pdf->GetPageHeight() - 10) {
            $pdf->AddPage();
        }

        $y = $pdf->GetY();

        $wLeft  = 134;
        $wMid   = 68;
        $wRight = 69;


        $pdf->Rect($x, $y, $wLeft + $wMid + $wRight, $h);


        $pdf->Rect($x, $y, $wLeft, $h);

        $pdf->SetXY($x, $y);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($wLeft, 6, 'JADWAL PEMERIKSAAN :', 'B', 1);

        $pdf->SetFont('Arial', '', 8);

        $subW = $wLeft / 2;


        $xStart = $x;
        $yStart = $y + 7;


        $pdf->SetXY($xStart, $yStart);
        $pdf->MultiCell(
            $subW,
            5,
            "Harian : 06:00 - 12:00\n" .
                "Mingguan : tgl 1-7 (M1)\n" .
                "tgl 8-15 (M2) dst",
            0
        );

        // Kolom kanan (reset X & Y)
        $pdf->SetXY($xStart + $subW, $yStart);
        $pdf->MultiCell(
            $subW,
            5,
            "3 Bulanan : Jan, Apr, Jul, Okt\n" .
                "6 Bulanan : Jan, Des\n" .
                "1 Tahun : Des",
            0
        );


        $pdf->Rect($x + $wLeft, $y, $wMid, $h);

        $pdf->SetXY($x + $wLeft, $y);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell($wMid, 6, 'CARA PENGERJAAN', 'B', 1);

        $pdf->SetFont('ZapfDingbats', '', 9);
        $pdf->SetX($x + $wLeft);
        $pdf->Cell(6, 5, chr(51), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($wMid - 6, 5, ': OK / tidak ada masalah', 0, 1);

        $pdf->SetX($x + $wLeft);
        $pdf->Cell(6, 5, 'X', 0, 0, 'C');
        $pdf->Cell($wMid - 6, 5, ': Ada masalah', 0, 1);

        $pdf->SetX($x + $wLeft);
        $pdf->Cell(6, 5, '-', 0, 0, 'C');
        $pdf->Cell($wMid - 6, 5, ': Mesin off / Libur', 0, 1);


        $pdf->Rect($x + $wLeft + $wMid, $y, $wRight, $h);

        $pdf->SetXY($x + $wLeft + $wMid + 1, $y + 6);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(
            $wRight - 2,
            5,
            "JIKA ADA MASALAH SEGERA\n" .
                "DITINDAKLANJUTI & DICATAT\n" .
                "PADA KARTU MESIN",
            0,
            'C'
        );
    }
}
