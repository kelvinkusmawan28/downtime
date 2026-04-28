<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cheklist_model extends CI_model
{
    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }

    public function getTahun()
    {
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('downtime_cheklist');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getChecklistHarian($id_preventif, $mach_id, $tanggal)
    {
        return $this->db->select('
            dpi.id as id_item,
            dpi.nama_item,
            dpi.standar,
            dc.status,
            dc.catatan,
            dc.petugas
            
        ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
             AND dc.mach_id = '$mach_id'
             AND dc.tanggal = '$tanggal'",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', 'harian')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }

    public function Checklis_oke($mach_id, $tanggal)
    {
        return $this->db->where('mach_id', $mach_id)
            ->where('kategori', 'harian')
            ->where('tanggal', $tanggal)
            ->count_all_results('downtime_cheklist') > 0;
    }

    public function getPetugas($mach_id, $tanggal)
    {
        $this->db->select('petugas,tanggal');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('tanggal', $tanggal);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }


    public function isChecklistMingguanExist($mach_id, $tanggal)
    {
        return $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'mingguan')
            ->where('MONTH(tanggal)', date('m', strtotime($tanggal)))
            ->where('YEAR(tanggal)', date('Y', strtotime($tanggal)))
            ->count_all_results('downtime_cheklist') > 0;
    }
    public function getChecklistMingguan($id_preventif, $mach_id, $tanggal)
    {
        $info = range_minggu_dalam_bulan($tanggal);
        $minggu_ke = $info['minggu_ke'];

        return $this->db->select('
            dpi.id AS id_item,
            dpi.nama_item,
            dpi.standar,
            dc.status,
            dc.catatan,
            dc.petugas,
            dc.tanggal
        ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
                 AND dc.mach_id = '$mach_id'
                 AND dc.kategori = 'mingguan'
                 AND dc.minggu_ke = '$minggu_ke'
                 AND MONTH(dc.tanggal) = MONTH('$tanggal')
                 AND YEAR(dc.tanggal) = YEAR('$tanggal')",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', 'mingguan')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }

    public function getChecklistBulanan($id_preventif, $mach_id, $tanggal)
    {
        return $this->db->select('
            dpi.id AS id_item,
            dpi.nama_item,
            dpi.standar,
            dc.status,
            dc.catatan,
            dc.petugas,
            dc.tanggal
        ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
             AND dc.mach_id = '$mach_id'
             AND MONTH(dc.tanggal) = MONTH('$tanggal')
             AND YEAR(dc.tanggal) = YEAR('$tanggal')",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', 'bulanan')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }

    public function getChecklistTigaBulan($id_preventif, $mach_id, $tanggal)
    {
        $bulan   = date('n', strtotime($tanggal));
        $tahun   = date('Y', strtotime($tanggal));
        $triwulan = ceil($bulan / 3);

        $awal = date('Y-m-d', strtotime("$tahun-" . (($triwulan - 1) * 3 + 1) . "-01"));
        $akhir = date('Y-m-t', strtotime("$awal +2 month"));

        return $this->db->select('
            dpi.id AS id_item,
            dpi.nama_item,
            dpi.standar,
            dc.status,
            dc.catatan,
            dc.petugas,
            dc.tanggal
        ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
             AND dc.mach_id = '$mach_id'
             AND dc.kategori = '3 bulan'
             AND dc.tanggal >= '$awal'
             AND dc.tanggal <= '$akhir'",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', '3 bulan')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }
    public function getChecklistEnamBulan($id_preventif, $mach_id, $tanggal)
    {
        $bulan   = date('n', strtotime($tanggal));
        $tahun   = date('Y', strtotime($tanggal));
        $enambulan = ceil($bulan / 6);

        $awal = date('Y-m-d', strtotime("$tahun-" . (($enambulan - 1) * 6 + 1) . "-01"));
        $akhir = date('Y-m-t', strtotime("$awal +5 month"));

        return $this->db->select('
            dpi.id AS id_item,
            dpi.nama_item,
            dpi.standar,
            dc.status,
            dc.catatan,
            dc.petugas,
            dc.tanggal
        ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
             AND dc.mach_id = '$mach_id'
             AND dc.kategori = '6 bulan'
             AND dc.tanggal >= '$awal'
             AND dc.tanggal <= '$akhir'",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', '6 bulan')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }

    public function getChecklistTahunan($id_preventif, $mach_id, $tanggal)
    {
        return $this->db->select('
        dpi.id AS id_item,
        dpi.nama_item,
        dpi.standar,
        dc.status,
        dc.catatan,
        dc.petugas,
        dc.tanggal
      ')
            ->from('downtime_preventif_item dpi')
            ->join(
                'downtime_cheklist dc',
                "dc.id_item = dpi.id
         AND dc.mach_id = '$mach_id'
         AND dc.kategori = 'tahunan'
         AND YEAR(dc.tanggal) = YEAR('$tanggal')",
                'left'
            )
            ->where('dpi.id_preventif', $id_preventif)
            ->where('dpi.kategori', 'tahunan')
            ->order_by('dpi.id')
            ->get()
            ->result();
    }
    private function badgeSelesai()
    {
        return '<span class="text-danger" style="font-size: 13px;">✔ Checklist Selesai</span>';
    }

    private function badgeTunggu()
    {
        return '<span class="text-dark" style="font-size: 13px;">⏳ Waiting ..</span>';
    }

    private function badgeBelum()
    {
        return '<span style="font-size: 14px;" text-align : center ;> - </span>';
    }


    // HARIAN (hari ini)
    public function statusHarian($mach_id, $tanggal)
    {
        $ada = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'harian')
            ->where('tanggal', $tanggal)
            ->count_all_results('downtime_cheklist');

        return $ada
            ? '<span class="text-danger">✔ Checklist Selesai</span>'
            : '<span class="text-dark">⏳ Waiting ..</span>';
    }


    public function statusMingguan($mach_id, $tanggal)
    {
        $day   = date('d', strtotime($tanggal));
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        // Hitung minggu versi 1-7, 8-14, dst
        if ($day <= 7)       $minggu = 1;
        elseif ($day <= 14) $minggu = 2;
        elseif ($day <= 21) $minggu = 3;
        elseif ($day <= 28) $minggu = 4;
        else                $minggu = 5;

        $cek = $this->db->where('mach_id', $mach_id)
            ->where('kategori', 'mingguan')
            ->where('YEAR(tanggal)', $tahun)
            ->where('MONTH(tanggal)', $bulan)
            ->where("DAY(tanggal) BETWEEN " . (($minggu - 1) * 7 + 1) . " AND " . ($minggu * 7))
            ->get('downtime_cheklist')
            ->row_array();

        if ($cek) {
            return $this->badgeSelesai();
        }

        return $this->badgeTunggu();
    }




    public function statusBulanan($mach_id, $tanggal)
    {
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        $ada = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'bulanan')
            ->where('YEAR(tanggal)', $tahun)
            ->where('MONTH(tanggal)', $bulan)
            ->count_all_results('downtime_cheklist');

        if ($ada) return $this->badgeSelesai();

        if (date('d') < 01) return $this->badgeBelum();

        return $this->badgeTunggu();
    }

    public function statusTigaBulan($mach_id, $tanggal)
    {
        $bulan = date('n', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $periode = ceil($bulan / 3);

        $ada = $this->db
            ->select('CEIL(MONTH(tanggal)/3) as p')
            ->where('mach_id', $mach_id)
            ->where('kategori', '3 bulan')
            ->where('YEAR(tanggal)', $tahun)
            ->having('p', $periode)
            ->get('downtime_cheklist')
            ->num_rows();

        if ($ada) return $this->badgeSelesai();

        if ($bulan % 3 != 0) return $this->badgeBelum();

        return $this->badgeTunggu();
    }


    public function statusEnamBulan($mach_id, $tanggal)
    {
        $bulan = date('n', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $periode = ceil($bulan / 6);

        $ada = $this->db
            ->select('CEIL(MONTH(tanggal)/6) as p')
            ->where('mach_id', $mach_id)
            ->where('kategori', '6 bulan')
            ->where('YEAR(tanggal)', $tahun)
            ->having('p', $periode)
            ->get('downtime_cheklist')
            ->num_rows();

        if ($ada) return $this->badgeSelesai();

        if ($bulan != 6 && $bulan != 12) return $this->badgeBelum();

        return $this->badgeTunggu();
    }


    public function statusTahunan($mach_id, $tanggal)
    {
        $tahun = date('Y', strtotime($tanggal));

        $ada = $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->where('YEAR(tanggal)', $tahun)
            ->count_all_results('downtime_cheklist');

        if ($ada) return $this->badgeSelesai();

        if (date('n') < 12) return $this->badgeBelum();

        return $this->badgeTunggu();
    }



    public function getJumlahHarian($mach_id, $tahun)
    {
        $this->db->select('DATE(tanggal)');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('kategori', 'harian');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('DATE(tanggal)');

        return $this->db->get()->num_rows();
    }

    public function getJumlahMingguan($mach_id, $tahun)
    {
        $this->db->select('YEARWEEK(tanggal, 1)');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('kategori', 'mingguan');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('YEARWEEK(tanggal, 1)');

        return $this->db->get()->num_rows();
    }

    public function getJumlahBulanan($mach_id, $tahun)
    {
        $this->db->select('MONTH(tanggal)');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('kategori', 'bulanan');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('MONTH(tanggal)');

        return $this->db->get()->num_rows();
    }

    public function getJumlahTigaBulanan($mach_id, $tahun)
    {
        $this->db->select('CEIL(MONTH(tanggal)/3) as periode');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('kategori', '3 bulan');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('periode');

        return $this->db->get()->num_rows();
    }


    public function getJumlahEnamBulanan($mach_id, $tahun)
    {
        $this->db->select('CEIL(MONTH(tanggal)/6) as periode');
        $this->db->from('downtime_cheklist');
        $this->db->where('mach_id', $mach_id);
        $this->db->where('kategori', '6 bulan');
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->group_by('periode');

        return $this->db->get()->num_rows();
    }

    public function getJumlahTahunan($mach_id, $tahun)
    {
        return $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->where('YEAR(tanggal)', $tahun)
            ->limit(1)
            ->count_all_results('downtime_cheklist');
    }


    public function IdentitasMesin($mach_id)
    {
        $this->db->select("downtime_spekmesin.*, dept.departemen ");
        $this->db->from('downtime_spekmesin');
        $this->db->join('dept', 'dept.dept_id = downtime_spekmesin.dept_kode', 'left');
        $this->db->where('downtime_spekmesin.mach_id', $mach_id);
        return $this->db->get()->row_array();
    }


    public function getBulan()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
        $this->db->from('downtime_cheklist');
        $this->db->group_by(['MONTH(tanggal)', 'MONTHNAME(tanggal)']);
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getRekapHarian($mach_id, $tahun, $bulan)
    {
        return $this->db
            ->where('mach_id', $mach_id)
            ->where('kategori', 'harian')
            ->where('YEAR(tanggal)', $tahun)
            ->where('MONTH(tanggal)', $bulan)
            ->get('downtime_cheklist')
            ->result_array();
    }


    public function getItemPemeriksaan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', 'harian');


        return $this->db->get()->result_array();
    }

    public function getRekapMingguan($mach_id, $bulan, $tahun)
    {
        return $this->db
            ->select('id_item, tanggal, status')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'mingguan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'ASC')
            ->get('downtime_cheklist')
            ->result_array();
    }



    public function getItemPemeriksaan_mingguan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', 'mingguan');


        return $this->db->get()->result_array();
    }

    public function getRekapBulanan($mach_id, $bulan, $tahun)
    {
        return $this->db
            ->select('id_item, tanggal, status')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'bulanan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'ASC')
            ->get('downtime_cheklist')
            ->result_array();
    }

    public function getItemPemeriksaan_bulanan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', 'bulanan');


        return $this->db->get()->result_array();
    }


    public function getRekap_TigaBulan($mach_id, $bulan, $tahun)
    {
        return $this->db
            ->select('id_item, tanggal, status')
            ->where('mach_id', $mach_id)
            ->where('kategori', '3 bulan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'ASC')
            ->get('downtime_cheklist')
            ->result_array();
    }
    public function getItemPemeriksaan_TigaBulan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', '3 bulan');


        return $this->db->get()->result_array();
    }
    public function getRekap_EnamBulan($mach_id, $bulan, $tahun)
    {
        return $this->db
            ->select('id_item, tanggal, status')
            ->where('mach_id', $mach_id)
            ->where('kategori', '6 bulan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'ASC')
            ->get('downtime_cheklist')
            ->result_array();
    }
    public function getItemPemeriksaan_EnamBulan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', '6 bulan');


        return $this->db->get()->result_array();
    }

    public function getRekap_Tahunan($mach_id, $bulan, $tahun)
    {
        return $this->db
            ->select('id_item, tanggal, status')
            ->where('mach_id', $mach_id)
            ->where('kategori', 'tahunan')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->order_by('tanggal', 'ASC')
            ->get('downtime_cheklist')
            ->result_array();
    }
    public function getItemPemeriksaan_Tahunan($mach_id)
    {
        $this->db->select('
            dpi.id,
            dpi.nama_item,
            dpi.standar,
            dpi.kategori,
            ds.mach_id
        ');
        $this->db->from('downtime_preventif_item dpi');
        $this->db->join('downtime_preventif dp', 'dp.id = dpi.id_preventif', 'left');
        $this->db->join('downtime_spekmesin ds', 'ds.preventif_kode = dp.kode', 'left');
        $this->db->where('ds.mach_id', $mach_id);


        $this->db->where('dpi.kategori', 'tahunan');


        return $this->db->get()->result_array();
    }
}
