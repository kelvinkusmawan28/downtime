<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard_model extends CI_model
{
    public function GetDetail($mesin, $kerusakan, $dept_id, $bulan, $tahun)
    {
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
        downtime.*, 
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
        $this->db->where('downtime.nomesin_id', $mesin);
        $this->db->where('downtime.kerusakan_id', $kerusakan);
        $this->db->order_by('downtime.tanggal', 'DESC');

        return $this->db->get()->result_array();
    }
    public function GetDetail_mesinof($code, $tanggal, $dept_id)
    {

        $year  = date('Y', strtotime($tanggal));
        $month = date('m', strtotime($tanggal));
        $day   = date('d', strtotime($tanggal));


        $startDate = "$year-$month-01";
        $endDate   = $tanggal;

        $this->db->select('
            downtime_mesinof.nomesin_id, 
            downtime_mesinof.ket_id, 
            COUNT(*) as total,
            downtime_ketof.code,
            downtime_ketof.reason,
            downtime_ketof.clr,
            dept.departemen,
            downtime_spekmesin.mach_name,
            downtime_spekmesin.mach_no
        ');
        $this->db->from('downtime_mesinof');
        $this->db->join('dept', 'dept.dept_id = downtime_mesinof.dept_id', 'left');
        $this->db->join('downtime_ketof', 'downtime_ketof.id = downtime_mesinof.ket_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');


        $this->db->where('downtime_mesinof.dept_id', $dept_id);


        $this->db->where('downtime_ketof.code', $code);

        $this->db->where('downtime_mesinof.tanggal >=', $startDate);
        $this->db->where('downtime_mesinof.tanggal <=', $endDate);

        $this->db->group_by('downtime_mesinof.nomesin_id, downtime_mesinof.ket_id');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);

        // $query = $this->db->get();
        // echo $this->db->last_query();
        // die();

        return [
            'data' => $this->db->get()->result_array(),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }
    public function GetDetail_header($code, $dept_id)
    {
        $this->db->select('
            downtime_mesinof.nomesin_id, 
            downtime_mesinof.ket_id, 
            COUNT(*) as total,
            downtime_ketof.code,
            downtime_ketof.reason,
            downtime_ketof.clr,
            dept.departemen,
            downtime_spekmesin.mach_name,
            downtime_spekmesin.mach_no
        ');
        $this->db->from('downtime_mesinof');
        $this->db->join('dept', 'dept.dept_id = downtime_mesinof.dept_id', 'left');
        $this->db->join('downtime_ketof', 'downtime_ketof.id = downtime_mesinof.ket_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');


        $this->db->where('downtime_mesinof.dept_id', $dept_id);


        $this->db->where('downtime_ketof.code', $code);

        return $this->db->get()->row_array();
    }
    public function getBulan()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
        $this->db->from('downtime');
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get()->result_array();
    }
    public function getBulan_off()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
        $this->db->from('downtime_mesinof');
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get()->result_array();
    }


    public function getTahun_off()
    {
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('downtime_mesinof');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }
    public function getTahun()
    {
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('downtime');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }
    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }
    public function getKetof($inputan, $dept)
    {
        $this->db->group_start();
        $this->db->like('code', $inputan);
        $this->db->or_like('reason', $inputan);
        $this->db->group_end();

        $this->db->like('sp', $dept);

        $query = $this->db->get('downtime_ketof');
        return $query->result_array();
    }
    public function getKetbb($inputan)
    {
        $this->db->group_start();
        $this->db->like('spesifikasi', $inputan);
        $this->db->group_end();

        $query = $this->db->get('downtime_bobin');
        return $query->result_array();
    }
    public function getBenang($inputan)
    {
        $this->db->group_start();
        $this->db->like('jenis', $inputan);
        $this->db->group_end();

        $query = $this->db->get('tb_benang');
        return $query->result_array();
    }
    public function GetRR()
    {
        $this->db->select('*');
        $this->db->from('downtime_spekmesin');
        $this->db->where('dept_kode', 'RR');
        $this->db->group_by('preventif_kode');
        $this->db->order_by('preventif_kode', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function GetSP()
    {
        $this->db->select('*');
        $this->db->from('downtime_spekmesin');
        $this->db->where('dept_kode', 'SP');
        $this->db->group_by('preventif_kode');
        $this->db->order_by('preventif_kode', 'asc');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function GetFN()
    {
        $this->db->select('*');
        $this->db->from('downtime_spekmesin');
        $this->db->where('dept_kode', 'FN');
        $this->db->group_by('preventif_kode');
        $this->db->order_by('preventif_kode', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function GetNT()
    {
        $this->db->select('*');
        $this->db->from('downtime_spekmesin');
        $this->db->where('dept_kode', 'NT');
        $this->db->group_by('preventif_kode');
        $this->db->order_by('preventif_kode', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getMesinByDept($dept)
    {

        if ($dept != 'all') {
            $this->db->where('dept_kode', $dept);
        }

        return $this->db->get('downtime_spekmesin')->result();
    }
    public function cekData($tanggal, $dept, $mesin, $shift)
    {
        $this->db->from('downtime_mesinof');
        $this->db->where('tanggal', $tanggal);
        $this->db->where('dept_id', $dept);
        $this->db->where('nomesin_id', $mesin);
        $this->db->where('shift', $shift);

        return $this->db->count_all_results();
    }
    public function getdataByid($id)
    {
        $this->db->select("
            d.*, u.name ,
            dp.departemen,
            kl.code  as code_left,
            kl.reason as reason_left,
    
            kr.code  as code_right,
            kr.reason as reason_right,
    
            m.mach_no,
            m.mach_name,
            m.preventif_kode,
    
            bl.jenis as jenis_left,
            br.jenis as jenis_right,

            b1.spesifikasi as spek_kiri,
            b2.spesifikasi as spek_kanan

          ");

        $this->db->from('downtime_mesinof d');
        $this->db->join('user u', 'u.id = d.user_by', 'left');
        $this->db->join('dept dp', 'dp.dept_id = d.dept_id', 'left');
        // reason kiri
        $this->db->join('downtime_ketof kl', 'kl.id = d.ket_id', 'left');

        // reason kanan
        $this->db->join('downtime_ketof kr', 'kr.id = d.ket_idr', 'left');

        // mesin
        $this->db->join('downtime_spekmesin m', 'm.mach_id = d.nomesin_id', 'left');

        // benang kiri
        $this->db->join('tb_benang bl', 'bl.id = d.ket_tb', 'left');

        // benang kanan
        $this->db->join('tb_benang br', 'br.id = d.ket_tbr', 'left');

        $this->db->join('downtime_bobin b1', 'b1.id = d.ket_bb', 'left');
        $this->db->join('downtime_bobin b2', 'b2.id = d.ket_bbr', 'left');

        $this->db->where('d.id', $id);

        return $this->db->get()->row_array();
    }
    function getPrevShift($shift)
    {
        if ($shift == 1) return 3;
        if ($shift == 2) return 1;
        if ($shift == 3) return 2;
    }
    //MINGGU DI CEK
    // public function Cari($id, $nomesin_id, $dept, $ket_id, $ket_idr, $shift)
    // {
    //     // ambil data berdasarkan mesin & dept
    //     $this->db->where('dept_id', $dept);
    //     $this->db->where('nomesin_id', $nomesin_id);
    //     $this->db->order_by('ket_on', 'DESC');

    //     $data = $this->db->get('downtime_mesinof')->result_array();
    //     $expected_shift = $shift;
    //     $count_left  = 0;
    //     $count_right = 0;

    //     // anggap "0" / kosong = tidak aktif
    //     $aktif_kiri  = ($ket_id !== null && $ket_id !== '' && $ket_id !== '0');
    //     $aktif_kanan = ($ket_idr !== null && $ket_idr !== '' && $ket_idr !== '0');

    //     $start = false;
    //     $prev_date = null;
    //     foreach ($data as $row) {

    //         // ======================
    //         // TITIK AWAL (DATA SEKARANG)
    //         // ======================


    //         if (!$start) {
    //             if ($row['id'] == $id) {
    //                 $start = true;

    //                 if ($aktif_kiri)  $count_left  = 1;
    //                 if ($aktif_kanan) $count_right = 1;

    //                 // shift berikutnya yang diharapkan
    //                 $expected_shift = $this->getPrevShift($row['shift']);
    //             }
    //             continue;
    //         }

    //         // 🔥 CEK URUTAN SHIFT
    //         if ($row['shift'] != $expected_shift) {
    //             break; // STOP kalau tidak sesuai urutan
    //         }
    //         $current_date = date('Y-m-d', strtotime($row['ket_on']));

    //         if ($prev_date !== null) {
    //             $expected_prev_date = date('Y-m-d', strtotime($prev_date . ' -1 day'));

    //             // ❌ kalau bukan hari yang sama & bukan mundur 1 hari → STOP
    //             if ($current_date != $prev_date && $current_date != $expected_prev_date) {
    //                 break;
    //             }


    //         }

    //         // update prev_date
    //         $prev_date = $current_date;
    //         // update expected shift berikutnya
    //         $expected_shift = $this->getPrevShift($row['shift']);

    //         // ======================
    //         // CEK KIRI
    //         // ======================
    //         if ($aktif_kiri) {
    //             if ($row['ket_id'] == $ket_id) {
    //                 $count_left++;
    //             } else {
    //                 $aktif_kiri = false;
    //             }
    //         }

    //         // ======================
    //         // CEK KANAN
    //         // ======================
    //         if ($aktif_kanan) {
    //             if ($row['ket_idr'] == $ket_idr) {
    //                 $count_right++;
    //             } else {
    //                 $aktif_kanan = false;
    //             }
    //         }

    //         // ======================
    //         // STOP kalau dua-duanya selesai
    //         // ======================
    //         if (!$aktif_kiri && !$aktif_kanan) {
    //             break;
    //         }
    //     }

    //     return [
    //         'left'  => $count_left,
    //         'right' => $count_right
    //     ];
    // }

    private function getDeptColumn($dept_id)
    {
        $dept_id = strtoupper(trim($dept_id));

        $map = [
            'NT' => 'nt',
            'AR' => 'nt',
            'FN' => 'fn',
            'SP' => 'sp',
            'RR' => 'rr',
        ];

        return $map[$dept_id] ?? null;
    }
    private function isLibur($tanggal, $dept_id)
    {
        $kolom = $this->getDeptColumn($dept_id);

        if (!$kolom) return false;

        $this->db->where('tanggal', $tanggal);
        $row = $this->db->get('downtime_libur')->row_array();

        if (!$row) return false;

        return ($row[$kolom] == 1);
    }
    public function Cari($id, $nomesin_id, $dept, $ket_id, $ket_idr, $shift)
    {
        $this->db->where('dept_id', $dept);
        $this->db->where('nomesin_id', $nomesin_id);
        $this->db->order_by('ket_on', 'DESC');

        $data = $this->db->get('downtime_mesinof')->result_array();

        $expected_shift = $shift;
        $count_left  = 0;
        $count_right = 0;

        $aktif_kiri  = ($ket_id !== null && $ket_id !== '' && $ket_id !== '0');
        $aktif_kanan = ($ket_idr !== null && $ket_idr !== '' && $ket_idr !== '0');

        $start = false;
        $prev_date = null;

        foreach ($data as $row) {

            // ======================
            // TITIK AWAL
            // ======================
            if (!$start) {
                if ($row['id'] == $id) {
                    $start = true;

                    if ($aktif_kiri)  $count_left  = 1;
                    if ($aktif_kanan) $count_right = 1;

                    $expected_shift = $this->getPrevShift($row['shift']);
                    $prev_date = $row['tanggal'];
                }
                continue;
            }

            // $current_date = date('Y-m-d', strtotime($row['ket_on']));
            $current_date = $row['tanggal'];

            // ======================
            // 🔥 SKIP LIBUR (DARI TABEL)
            // ======================
            // if ($this->isLibur($current_date, $dept)) {
            //     continue;
            // }

            // ======================
            // 🔥 CEK URUTAN SHIFT
            // ======================
            if ($row['shift'] != $expected_shift) {
                break;
            }

            // ======================
            // 🔥 CEK URUTAN TANGGAL
            // ======================
            if ($prev_date !== null) {

                $expected_prev_date = date('Y-m-d', strtotime($prev_date . ' -1 day'));

                if ($current_date != $prev_date && $current_date != $expected_prev_date) {

                    $diff = (strtotime($prev_date) - strtotime($current_date)) / (60 * 60 * 24);

                    if ($diff > 1) {

                        $valid = true;

                        // cek semua tanggal di antara prev_date dan current_date
                        for ($i = 1; $i < $diff; $i++) {

                            $check_date = date('Y-m-d', strtotime($prev_date . " -$i day"));

                            // kalau ada 1 saja yang bukan libur → STOP
                            if (!$this->isLibur($check_date, $dept)) {
                                $valid = false;
                                break;
                            }
                        }

                        if (!$valid) {
                            break;
                        }
                    }
                }
            }

            // ======================
            // UPDATE UNTUK ITERASI BERIKUTNYA
            // ======================
            $prev_date = $current_date;
            $expected_shift = $this->getPrevShift($row['shift']);

            // ======================
            // CEK KIRI
            // ======================
            if ($aktif_kiri) {
                if ($row['ket_id'] == $ket_id) {
                    $count_left++;
                } else {
                    $aktif_kiri = false;
                }
            }

            // ======================
            // CEK KANAN
            // ======================
            if ($aktif_kanan) {
                if ($row['ket_idr'] == $ket_idr) {
                    $count_right++;
                } else {
                    $aktif_kanan = false;
                }
            }

            // ======================
            // STOP kalau dua-duanya selesai
            // ======================
            if (!$aktif_kiri && !$aktif_kanan) {
                break;
            }
        }

        return [
            'left'  => $count_left,
            'right' => $count_right
        ];
    }
    public function CariRiwayat($id, $nomesin_id, $dept)
    {
        $this->db->select('d.*,
        kl.reason as reason_left,
        kl.clr as clr_left,
        kr.reason as reason_right,
        kr.clr as clr_right
        ');
        $this->db->from('downtime_mesinof d');

        $this->db->join(
            'downtime_ketof kl',
            'd.ket_id = kl.id',
            'left'
        );

        // reason kanan
        $this->db->join(
            'downtime_ketof kr',
            'd.ket_idr = kr.id',
            'left'
        );


        $this->db->where('d.dept_id', $dept);
        $this->db->where('d.nomesin_id', $nomesin_id);
        $this->db->order_by('d.ket_on', 'DESC');

        $data = $this->db->get()->result_array();

        $group_kiri  = [];
        $group_kanan = [];
        $timeline    = [];

        $start = false;
        $expected_shift = null;
        $prev_date = null;

        // 🔥 tambahan untuk range tanggal
        $first_date = null;
        $last_date  = null;

        foreach ($data as $row) {

            // ======================
            // TITIK AWAL
            // ======================
            if (!$start) {
                if ($row['id'] == $id) {
                    $start = true;

                    // set range
                    $first_date = $row['ket_on'];
                    $last_date  = $row['ket_on'];


                    if (!empty($row['reason_left'])) {
                        if (!isset($group_kiri[$row['reason_left']])) {
                            $group_kiri[$row['reason_left']] = [
                                'jumlah' => 0,
                                'warna'  => $row['clr_left']
                            ];
                        }
                        $group_kiri[$row['reason_left']]['jumlah']++;
                    }

                    if (!empty($row['reason_right'])) {
                        if (!isset($group_kanan[$row['reason_right']])) {
                            $group_kanan[$row['reason_right']] = [
                                'jumlah' => 0,
                                'warna'  => $row['clr_right']
                            ];
                        }
                        $group_kanan[$row['reason_right']]['jumlah']++;
                    }
                    $timeline[] = [
                        'tanggal' => $row['ket_on'],
                        'shift'   => $row['shift'],
                        'ket_id'  => $row['ket_id'],
                        'ket_idr' => $row['ket_idr'],
                        'reason_left'  => $row['reason_left'],
                        'reason_right' => $row['reason_right'],
                        'clr_left' => $row['clr_left'],
                        'clr_right' => $row['clr_right']
                    ];
                    // set tracking
                    $expected_shift = $this->getPrevShift($row['shift']);
                    // $prev_date = date('Y-m-d', strtotime($row['ket_on']));
                    $prev_date = $row['tanggal'];
                }
                continue;
            }


            // cek tangal akahlibur atau tidak 
            $current_date = $row['tanggal'];

            // cek apakah datanya 0 atau nul 
            if (empty($row['ket_id']) || $row['ket_id'] < 1) {
                break;
            }

            // ======================
            // 🔥 CEK URUTAN SHIFT
            // ======================
            if ($row['shift'] != $expected_shift) {
                break;
            }



            // ======================
            // 🔥 CEK URUTAN TANGGAL
            // ======================
            if ($prev_date !== null) {

                $expected_prev_date = date('Y-m-d', strtotime($prev_date . ' -1 day'));

                if ($current_date != $prev_date && $current_date != $expected_prev_date) {

                    $diff = (strtotime($prev_date) - strtotime($current_date)) / (60 * 60 * 24);

                    $diff = (strtotime($prev_date) - strtotime($current_date)) / (60 * 60 * 24);

                    if ($diff > 1) {

                        $valid = true;

                        // cek semua tanggal di antara prev_date dan current_date
                        for ($i = 1; $i < $diff; $i++) {

                            $check_date = date('Y-m-d', strtotime($prev_date . " -$i day"));

                            // kalau ada 1 saja yang bukan libur → STOP
                            if (!$this->isLibur($check_date, $dept)) {
                                $valid = false;
                                break;
                            }
                        }

                        if (!$valid) {
                            break;
                        }
                    }
                }
            }

            // ======================
            // HITUNG
            // ======================
            if (!empty($row['reason_left'])) {
                if (!isset($group_kiri[$row['reason_left']])) {
                    $group_kiri[$row['reason_left']] = [
                        'jumlah' => 0,
                        'warna'  => $row['clr_left']
                    ];
                }
                $group_kiri[$row['reason_left']]['jumlah']++;
            }

            if (!empty($row['reason_right'])) {
                if (!isset($group_kanan[$row['reason_right']])) {
                    $group_kanan[$row['reason_right']] = [
                        'jumlah' => 0,
                        'warna'  => $row['clr_right']
                    ];
                }
                $group_kanan[$row['reason_right']]['jumlah']++;
            }

            // update range
            $last_date = $row['ket_on'];
            // echo $row['ket_on'];
            // die;

            // masuk timeline
            $timeline[] = [
                'tanggal' => $row['ket_on'],
                'shift'   => $row['shift'],
                'ket_id'  => $row['ket_id'],
                'ket_idr' => $row['ket_idr'],
                'reason_left'  => $row['reason_left'],
                'reason_right' => $row['reason_right'],
                'clr_left' => $row['clr_left'],
                'clr_right' => $row['clr_right']
            ];

            // update tracking
            $prev_date = $current_date;
            $expected_shift = $this->getPrevShift($row['shift']);
        }

        return [
            'kiri'  => $group_kiri,
            'kanan' => $group_kanan,
            'timeline' => $timeline,
            'range' => [
                'start' => $last_date,   // paling lama
                'end'   => $first_date   // paling baru
            ]
        ];
    }
    // public function CariRiwayat($id, $nomesin_id, $dept)
    // {
    //     $this->db->select('d.*,
    // kl.reason as alasan_kiri,
    // kr.reason as alasan_kanan');
    //     $this->db->from('downtime_mesinof d');

    //     $this->db->join(
    //         'downtime_ketof kl',
    //         'd.ket_id = kl.id',
    //         'left'
    //     );

    //     $this->db->join(
    //         'downtime_ketof kr',
    //         'd.ket_idr = kr.id',
    //         'left'
    //     );

    //     $this->db->where('d.dept_id', $dept);
    //     $this->db->where('d.nomesin_id', $nomesin_id);
    //     $this->db->order_by('d.ket_on', 'DESC');

    //     $data = $this->db->get()->result_array();

    //     $group_kiri  = [];
    //     $group_kanan = [];
    //     $timeline    = [];

    //     $mulai = false;
    //     $shift_diharapkan = null;
    //     $tanggal_sebelumnya = null;

    //     // range tanggal
    //     $tanggal_awal = null;
    //     $tanggal_akhir = null;

    //     foreach ($data as $row) {

    //         // ======================
    //         // TITIK AWAL
    //         // ======================
    //         if (!$mulai) {
    //             if ($row['id'] == $id) {
    //                 $mulai = true;

    //                 // set range
    //                 $tanggal_awal  = $row['ket_on'];
    //                 $tanggal_akhir = $row['ket_on'];

    //                 // hitung awal
    //                 if (!empty($row['alasan_kiri'])) {
    //                     $group_kiri[$row['alasan_kiri']] = ($group_kiri[$row['alasan_kiri']] ?? 0) + 1;
    //                 }

    //                 if (!empty($row['alasan_kanan'])) {
    //                     $group_kanan[$row['alasan_kanan']] = ($group_kanan[$row['alasan_kanan']] ?? 0) + 1;
    //                 }

    //                 // simpan timeline
    //                 $timeline[] = [
    //                     'tanggal' => $row['ket_on'],
    //                     'shift'   => $row['shift'],
    //                     'ket_id'  => $row['ket_id'],
    //                     'ket_idr' => $row['ket_idr'],
    //                     'alasan_kiri'  => $row['alasan_kiri'],
    //                     'alasan_kanan' => $row['alasan_kanan']
    //                 ];

    //                 // set tracking
    //                 $shift_diharapkan = $this->getPrevShift($row['shift']);
    //                 $tanggal_sebelumnya = date('Y-m-d', strtotime($row['ket_on']));
    //             }
    //             continue;
    //         }

    //         $tanggal_sekarang = date('Y-m-d', strtotime($row['ket_on']));

    //         // ======================
    //         // 🔥 SKIP LIBUR
    //         // ======================
    //         if ($this->isLibur($tanggal_sekarang, $dept)) {
    //             continue;
    //         }

    //         // ======================
    //         // 🔥 CEK URUTAN SHIFT
    //         // ======================
    //         if ($row['shift'] != $shift_diharapkan) {
    //             break;
    //         }

    //         // ======================
    //         // 🔥 CEK URUTAN TANGGAL
    //         // ======================
    //         if ($tanggal_sebelumnya !== null) {

    //             $tanggal_harusnya = date('Y-m-d', strtotime($tanggal_sebelumnya . ' -1 day'));

    //             if ($tanggal_sekarang != $tanggal_sebelumnya && $tanggal_sekarang != $tanggal_harusnya) {

    //                 $selisih = (int) ((strtotime($tanggal_sebelumnya) - strtotime($tanggal_sekarang)) / 86400);

    //                 if ($selisih > 1) {

    //                     $valid = true;

    //                     // cek semua tanggal yang dilompati
    //                     for ($i = 1; $i < $selisih; $i++) {

    //                         $tanggal_cek = date('Y-m-d', strtotime($tanggal_sebelumnya . " -$i day"));

    //                         if (!$this->isLibur($tanggal_cek, $dept)) {
    //                             $valid = false;
    //                             break;
    //                         }
    //                     }

    //                     if (!$valid) {
    //                         break;
    //                     }
    //                 }
    //             }
    //         }

    //         // ======================
    //         // HITUNG
    //         // ======================
    //         if (!empty($row['alasan_kiri'])) {
    //             $group_kiri[$row['alasan_kiri']] = ($group_kiri[$row['alasan_kiri']] ?? 0) + 1;
    //         }

    //         if (!empty($row['alasan_kanan'])) {
    //             $group_kanan[$row['alasan_kanan']] = ($group_kanan[$row['alasan_kanan']] ?? 0) + 1;
    //         }

    //         // update range
    //         $tanggal_akhir = $row['ket_on'];

    //         // simpan timeline
    //         $timeline[] = [
    //             'tanggal' => $row['ket_on'],
    //             'shift'   => $row['shift'],
    //             'ket_id'  => $row['ket_id'],
    //             'ket_idr' => $row['ket_idr'],
    //             'alasan_kiri'  => $row['alasan_kiri'],
    //             'alasan_kanan' => $row['alasan_kanan']
    //         ];

    //         // update tracking
    //         $tanggal_sebelumnya = $tanggal_sekarang;
    //         $shift_diharapkan = $this->getPrevShift($row['shift']);
    //     }

    //     return [
    //         'kiri'  => $group_kiri,
    //         'kanan' => $group_kanan,
    //         'timeline' => $timeline,
    //         'range' => [
    //             'start' => $tanggal_akhir,
    //             'end'   => $tanggal_awal
    //         ]
    //     ];
    // }
    public function update_ppic()
    {
        $data = $_POST;
        $this->db->where('id', $data['id']);
        return $this->db->update('downtime_mesinof', $data);
    }
    // public function update()
    // {

    //     $post = $this->input->post();
    //     $left  = $this->input->post('left') ? 1 : 0;
    //     $right = $this->input->post('right') ? 1 : 0;
    //     $ket_id = isset($post['ket_id']) ? $post['ket_id'] : '';
    //     $ket_id_old = isset($post['ket_id_old']) ? $post['ket_id_old'] : '';
    //     $id_benang = isset($post['id_benang']) ? $post['id_benang'] : '';
    //     $id_id_r = isset($post['ket_id_r']) ? $post['ket_id_r'] : '';
    //     $id_benang_r = isset($post['id_benang_r']) ? $post['id_benang_r'] : '';
    //     $ket_bb = isset($post['id_bobin']) ? $post['id_bobin'] : '';
    //     $ket_bbr = isset($post['id_bobin_r']) ?  $post['id_bobin_r'] : '';


    //     $data = [
    //         'user_by' => $this->session->userdata('id'),
    //         'tanggal' => $post['tanggal'],
    //         'shift' => $post['shift'],
    //         'ket_id' =>   $ket_id,
    //         'ket_tb' =>   $id_benang,
    //         'ket_idr' =>   $id_id_r,
    //         'ket_tbr' =>   $id_benang_r,
    //         'ket_bb' =>   $ket_bb,
    //         'ket_bbr' =>   $ket_bbr,
    //         'left' =>   $left,
    //         'right' =>   $right,
    //         'ket_on' => date('Y-m-d H:i'),
    //         'keterangan' => $post['keterangan']

    //     ];
    //     $this->db->trans_start();
    //     $this->db->where('id', $post['id']);
    //     $this->db->update('downtime_mesinof', $data);

    //     if ($ket_id_old == 5) {
    //         $cek = $this->db->get_where('downtime', [
    //             'dept_id' => $post['dept_id'],
    //             'nomesin_id' => $post['mach_id'],
    //             'status' => 2
    //         ]);
    //         if ($cek->num_rows() > 1) {

    //             $this->db->where('ins', 1);
    //             $this->db->where('status', 2);
    //             $this->db->where('mach_id', $post['mach_id']);
    //             $this->db->where('dept_id', $post['dept_id']);
    //             $this->db->delete('downtime');
    //         }
    //     }
    //     $this->db->trans_complete();

    //     return $this->db->trans_status();
    // }

    public function update()
    {
        $post = $this->input->post();

        $left  = $this->input->post('left') ? 1 : 0;
        $right = $this->input->post('right') ? 1 : 0;

        $data = [
            'user_by' => $this->session->userdata('id'),
            'tanggal' => $post['tanggal'],
            'shift' => $post['shift'],
            'ket_id' => $post['ket_id'] ?? '',
            'ket_tb' => $post['id_benang'] ?? '',
            'ket_idr' => $post['ket_id_r'] ?? '',
            'ket_tbr' => $post['id_benang_r'] ?? '',
            'ket_bb' => $post['id_bobin'] ?? '',
            'ket_bbr' => $post['id_bobin_r'] ?? '',
            'left' => $left,
            'right' => $right,
            'ket_on' => date('Y-m-d H:i'),
            'keterangan' => $post['keterangan']
        ];

        $this->db->trans_start();

        // update utama
        $this->db->where('id', $post['id']);
        $this->db->update('downtime_mesinof', $data);

        // cek & delete downtime
        if ($post['ket_id_old'] == 5) {

            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where('nomesin_id', $post['nomesin_id']);
            $this->db->where('status', 2);

            $cek = $this->db->get('downtime');

            if ($cek->num_rows() > 0) {

                $this->db->where('ins', 1);
                $this->db->where('status', 2);
                $this->db->where('nomesin_id', $post['nomesin_id']);
                $this->db->where('dept_id', $post['dept_id']);
                $this->db->delete('downtime');
            }
        }
        if ($post['ket_id_old'] == 19 || $post['ket_id_old'] == 20) {

            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where('nomesin_id', $post['nomesin_id']);
            $this->db->where('status', 2);

            $cek = $this->db->get('downtime');

            if ($cek->num_rows() > 0) {
                $this->db->where('status', 2);
                $this->db->where('nomesin_id', $post['nomesin_id']);
                $this->db->where('dept_id', $post['dept_id']);
                $this->db->delete('downtime');
            }
        }


        if ($post['ket_id'] == 5 && ($post['dept_id'] == 'NT' || $post['dept_id'] == 'AR')) {

            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where('nomesin_id', $post['nomesin_id']);
            $this->db->where_in('status', [0, 2]);

            $cek = $this->db->get('downtime');

            if ($cek->num_rows() == 0) {

                $ket = strtoupper(trim($post['keterangan']));

                if (strpos($ket, 'GB') !== false) {
                    $kerusakan_id = '1326';
                } elseif (strpos($ket, 'GM') !== false) {
                    $kerusakan_id = '1327';
                } elseif (strpos($ket, 'GI') !== false) {
                    $kerusakan_id = '1328';
                } else {
                    $kerusakan_id = '1327';
                }

                $data_downtime = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['nomesin_id'],
                    'kerusakan_id' => $kerusakan_id ?? null,
                    'ins' => 1,
                    'status' => 2,
                ];

                $this->db->insert('downtime', $data_downtime);
            }
        }

        if ($post['ket_id'] == 19) {
            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where_in('status', [0, 2]);
            $this->db->where('nomesin_id', $post['nomesin_id']);

            $cekpi = $this->db->get('downtime');

            if ($cekpi->num_rows() == 0) {
                $datapi = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['nomesin_id'],
                    'kerusakan_id' => 1235,
                    'status' => 2,
                ];
                $this->db->insert('downtime', $datapi);
            }
        }
        if ($post['ket_id'] == 20) {
            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where_in('status', [0, 2]);
            $this->db->where('nomesin_id', $post['nomesin_id']);

            $cekpi = $this->db->get('downtime');

            if ($cekpi->num_rows() == 0) {
                $datapo = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['nomesin_id'],
                    'kerusakan_id' => 2406,
                    'status' => 2,
                ];
                $this->db->insert('downtime', $datapo);
            }
        }


        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function simpan()
    {
        $post = $this->input->post();
        $left  = $this->input->post('left') ? 1 : 0;
        $right = $this->input->post('right') ? 1 : 0;
        $id_benang = isset($post['id_benang']) ? $post['id_benang'] : '';
        $id_benang_r = isset($post['id_benang_r']) ? $post['id_benang_r'] : '';
        $id_ketof = isset($post['id']) ? $post['id'] : '';
        $id_ketof_r = isset($post['id_r']) ? $post['id_r'] : '';
        $ket_bb = isset($post['id_bobin']) ? $post['id_bobin'] : '';
        $ket_bbr = isset($post['id_bobin_r']) ? $post['id_bobin_r'] : '';

        $data = [
            'tanggal' => $post['tanggal'],
            'dept_id' => $post['dept_id'],
            'nomesin_id' => $post['mach_id'],
            'ket_id' =>   $id_ketof,
            'ket_tb' =>   $id_benang,
            'ket_idr' =>   $id_ketof_r,
            'ket_tbr' =>   $id_benang_r,
            'ket_bb' =>   $ket_bb,
            'ket_bbr' =>   $ket_bbr,
            'left' =>   $left,
            'right' =>   $right,
            'keterangan' =>   $post['keterangan'],
            'shift' => $post['shift'],
            'ket_on' => date('Y-m-d H:i'),
            'user_by' => $this->session->userdata('id'),
        ];

        $this->db->trans_start();
        $this->db->insert('downtime_mesinof', $data);

        if ($id_ketof == 5 && ($post['dept_id'] == 'NT' || $post['dept_id'] == 'AR')) {

            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where('nomesin_id', $post['mach_id']);
            $this->db->where_in('status', [0, 2]);

            $cek = $this->db->get('downtime');

            if ($cek->num_rows() == 0) {

                $ket = strtoupper(trim($post['keterangan']));

                if (strpos($ket, 'GB') !== false) {
                    $kerusakan_id = '1326';
                } elseif (strpos($ket, 'GM') !== false) {
                    $kerusakan_id = '1327';
                } elseif (strpos($ket, 'GI') !== false) {
                    $kerusakan_id = '1328';
                } else {
                    $kerusakan_id = '1327';
                }

                $data_downtime = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['mach_id'],
                    'kerusakan_id' => $kerusakan_id ?? null,
                    'ins' => 1,
                    'status' => 2,
                ];

                $this->db->insert('downtime', $data_downtime);
            }
        }

        if ($id_ketof == 19) {
            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where_in('status', [0, 2]);
            $this->db->where('nomesin_id', $post['mach_id']);

            $cekpi = $this->db->get('downtime');

            if ($cekpi->num_rows() == 0) {
                $datapi = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['mach_id'],
                    'kerusakan_id' => 1235,
                    'status' => 2,
                ];
                $this->db->insert('downtime', $datapi);
            }
        }
        if ($id_ketof == 20) {
            $this->db->where('dept_id', $post['dept_id']);
            $this->db->where_in('status', [0, 2]);
            $this->db->where('nomesin_id', $post['mach_id']);

            $cekpi = $this->db->get('downtime');

            if ($cekpi->num_rows() == 0) {
                $datapo = [
                    'tanggal' => $post['tanggal'],
                    'dept_id' => $post['dept_id'],
                    'nomesin_id' => $post['mach_id'],
                    'kerusakan_id' => 2406,
                    'status' => 2,
                ];
                $this->db->insert('downtime', $datapo);
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }




    public function getMesinDashboard($dept, $tanggal, $shift, $reason, $preventif, $filter_rc, $spinning, $finishing, $netting)
    {

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

        $this->db->select("
            m.mach_id,
            m.mach_no,
            m.mach_name,
            m.name,
            m.preventif_kode,
            d.id,
            d.left,
            d.right,
            kl.clr as clr_left,
            kr.clr as clr_right,
            kl.clr as clr,
            kl.reason as reason_left,
            kr.reason as reason_right,
            b1.jenis as jenis_left,
            b2.jenis as jenis_right
        ");

        $this->db->from('downtime_spekmesin m');

        $this->db->join(
            'downtime_mesinof d',
            "m.mach_id = d.nomesin_id
            AND DATE(d.tanggal)='$tanggal'
            AND d.shift='$shift'",
            'left'
        );

        $this->db->join(
            'tb_benang b1',
            "b1.id = d.ket_tb",
            'left'
        );
        $this->db->join(
            'tb_benang b2',
            "b2.id = d.ket_tbr",
            'left'
        );

        // reason kiri
        $this->db->join(
            'downtime_ketof kl',
            'd.ket_id = kl.id',
            'left'
        );

        // reason kanan
        $this->db->join(
            'downtime_ketof kr',
            'd.ket_idr = kr.id',
            'left'
        );

        if ($dept !== 'all' && !empty($dept)) {

            $this->db->where('m.dept_kode', $dept);
            if ($dept == 'AR') {
                $this->db->where('m.idle', 0);
            }
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('m.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }
        if ($preventif != 'all') {
            $this->db->where('m.preventif_kode', $preventif);
        }
        if ($spinning != 'all') {
            $this->db->where('m.preventif_kode', $spinning);
        }
        if ($netting != 'all') {
            $this->db->where('m.preventif_kode', $netting);
        }
        if ($finishing != 'all') {
            $this->db->where('m.preventif_kode', $finishing);
        }

        if ($filter_rc != 'all') {
            $this->db->where('m.raschel', $filter_rc);
        }

        if ($reason != 'all') {
            $this->db->where('d.ket_id', $reason);
        }
        $this->db->where('m.idle', 0);
        $this->db->order_by('m.mach_no', 'ASC');
        $data = $this->db->get()->result();

        $result = [];

        foreach ($data as $row) {

            // handle NULL dari LEFT JOIN
            $row->left  = $row->left ?? 0;
            $row->right = $row->right ?? 0;

            $left_color  = "#eeeeee";
            $right_color = "#eeeeee";

            // khusus RR-RING
            if ($row->preventif_kode == 'RR-RING') {

                if ($row->left == 1 && !empty($row->clr_left)) {
                    $left_color = "rgb(" . $row->clr_left . ")";
                }

                if ($row->right == 1 && !empty($row->clr_right)) {
                    $right_color = "rgb(" . $row->clr_right . ")";
                }

                $row->left_color  = $left_color;
                $row->right_color = $right_color;
            } else {

                // mesin biasa
                if (!empty($row->clr)) {
                    $row->color = "rgb(" . $row->clr . ")";
                } else {
                    $row->color = "#ffffff";
                }
            }

            $result[] = $row;
        }

        return $result;
    }

    public function getPerbaikan($mach_id, $tanggal, $dept)
    {

        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $tanggal_30_hari_lalu = date('Y-m-d', strtotime($tanggal . ' -30 days'));

        $this->db->select('
            downtime.*, 
            dept.dept_id, 
            dept.departemen, 
            downtime_spekmesin.mach_no, 
            downtime_spekmesin.mach_name, 
            downtime_spekmesin.dept_kode, 
            downtime_kerusakan.remark, 
            downtime_tindakan.*, 
            downtime_tindakan_user.user
        ');

        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');


        $this->db->where('downtime.status', 1);
        $this->db->where('downtime.tanggal >=', $tanggal_30_hari_lalu);
        $this->db->where('downtime.tanggal <=', $tanggal);
        $this->db->where('(downtime.ins != 1 OR downtime.ins IS NULL)');


        if (!empty($mach_id)) {
            $this->db->where('downtime.nomesin_id', $mach_id);
        }

        if (!empty($dept)) {
            $this->db->where('downtime.dept_id', $dept);
        }

        $this->db->order_by('downtime.tanggal', 'DESC');

        return $this->db->get()->result_array();
    }





    public function getSummaryClr($dept, $tanggal, $shift, $reason, $preventif, $filter_rc, $spinning, $finishing, $netting)
    {
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
        $where = "";

        if ($dept !== 'all' && !empty($dept)) {
            $where .= " AND m.dept_kode = '$dept' ";
            if ($dept == 'AR') {
                $where .= " AND m.idle != 1 ";
            }
        } else {
            if (!empty($akses_dept)) {
                $dept_list = "'" . implode("','", $akses_dept) . "'";
                $where .= " AND m.dept_kode IN ($dept_list)";
            } else {
                $where .= " AND 1=0 ";
            }
        }

        if ($preventif != 'all') {
            $where .= " AND m.preventif_kode = '$preventif'";
        }
        if ($spinning != 'all') {
            $where .= " AND m.preventif_kode = '$spinning'";
        }
        if ($netting != 'all') {
            $where .= " AND m.preventif_kode = '$netting'";
        }
        if ($finishing != 'all') {
            $where .= " AND m.preventif_kode = '$finishing'";
        }

        if ($filter_rc != 'all') {
            $where .= " AND m.raschel = '$filter_rc'";
        }

        if ($reason != 'all') {
            $where .= " AND (d.ket_id = '$reason' OR d.ket_idr = '$reason')";
        }

        $sql = "
        SELECT code, clr, SUM(bobot) as total
        FROM (
        
            -- KIRI
            SELECT 
                COALESCE(kl.code, '') as code,
                COALESCE(kl.clr, '240,240,240') as clr,
                COALESCE(kl.lama, 0) as lama,
                CASE 
                    WHEN m.preventif_kode = 'RR-RING' THEN 0.5
                    ELSE 1
                END as bobot
            FROM downtime_spekmesin m
            LEFT JOIN downtime_mesinof d 
                ON m.mach_id = d.nomesin_id
                AND DATE(d.tanggal) = '$tanggal'
                AND d.shift = '$shift'
            LEFT JOIN downtime_ketof kl 
                ON d.ket_id = kl.id
            WHERE 1=1 $where
        
            UNION ALL
        
            -- KANAN
            SELECT 
                COALESCE(kr.code, '') as code,
                COALESCE(kr.clr, '240,240,240') as clr,
                COALESCE(kr.lama, 0) as lama,
                0.5 as bobot
            FROM downtime_spekmesin m
            LEFT JOIN downtime_mesinof d 
                ON m.mach_id = d.nomesin_id
                AND DATE(d.tanggal) = '$tanggal'
                AND d.shift = '$shift'
            LEFT JOIN downtime_ketof kr 
                ON d.ket_idr = kr.id
            WHERE m.preventif_kode = 'RR-RING'
            $where
        
        ) as x
        GROUP BY code, clr
        ORDER BY MAX(code) ASC
        ";

        return $this->db->query($sql)->result();
    }


    public function getLastUpdate($dept, $tanggal, $shift)
    {
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

        $this->db->select('downtime_mesinof.*');
        $this->db->from('downtime_mesinof');

        if ($dept !== 'all' && !empty($dept)) {
            $this->db->where('downtime_mesinof.dept_id', $dept);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_mesinof.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        // if ($tanggal !== 'all' && !empty($tanggal)) {
        //     $this->db->where('downtime_mesinof.ket_on >=', $tanggal . ' 00:00:00');
        //     $this->db->where('downtime_mesinof.ket_on <=', $tanggal . ' 23:59:59');
        // }

        if ($tanggal !== 'all' && !empty($tanggal)) {

            if ($shift == 3) {

                $prevDay = date('Y-m-d', strtotime($tanggal . ' -1 day'));

                $this->db->group_start();
                $this->db->where('downtime_mesinof.ket_on >=', $prevDay . ' 23:00:00');
                $this->db->where('downtime_mesinof.ket_on <=', $tanggal . ' 07:00:00');
                $this->db->group_end();
            } else {

                $this->db->where('downtime_mesinof.ket_on >=', $tanggal . ' 00:00:00');
                $this->db->where('downtime_mesinof.ket_on <=', $tanggal . ' 23:59:59');
            }
        }


        if ($shift !== 'all' && !empty($shift)) {
            $this->db->where('downtime_mesinof.shift', $shift);
        }

        $this->db->order_by('downtime_mesinof.ket_on', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function getExport($dept_id, $tanggal, $shift, $reason)
    {
        $this->db->select("downtime_mesinof. *, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, tb_benang.jenis");
        $this->db->from('downtime_mesinof');
        $this->db->join('dept', 'dept.dept_id = downtime_mesinof.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');
        $this->db->join('tb_benang', 'tb_benang.id = downtime_mesinof.ket_tb', 'left');
        $this->db->where('downtime_mesinof.dept_id', $dept_id);
        $this->db->where('downtime_mesinof.tanggal', $tanggal);
        $this->db->where('downtime_mesinof.ket_id', $reason);
        $this->db->where('downtime_mesinof.shift', $shift);

        return $this->db->get()->result_array();
    }
}
