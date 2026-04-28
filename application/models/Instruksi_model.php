<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Instruksi_model extends CI_model
{
    public function getdata_dept($id_dept)
    {
        // return $this->db->get('downtime')->result_array();
        return $this->db->get_where('downtime', ['dept_id' => $id_dept])->result_array();
    }

    public function getFilter()
    {

        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }
    // public function getBulan()
    // {
    //     $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
    //     $this->db->from('downtime');
    //     $this->db->group_by('MONTH(tanggal)');
    //     $this->db->order_by('bulan', 'ASC');
    //     return $this->db->get()->result_array();
    // }
    public function getBulan()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
        $this->db->from('downtime');
        $this->db->group_by(['MONTH(tanggal)', 'MONTHNAME(tanggal)']);
        $this->db->order_by('bulan', 'ASC');
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



    public function getTeknisiLike($inputan, $filter_dept)
    {
        $this->db->group_start();
        $this->db->like('name', $inputan);
        $this->db->group_end();

        $this->db->where('id_dept', $filter_dept);
        $this->db->where_in('jabatan', ['KARYAWAN', 'SUBLEADER', 'LEADER']);

        $query = $this->db->get('user');
        return $query->result_array();
    }

    public function getMesinLike($inputan, $filter_dept)
    {
        $this->db->group_start();
        $this->db->like('mach_no', $inputan);
        $this->db->or_like('mach_name', $inputan);
        $this->db->group_end();
        $this->db->where('dept_kode', $filter_dept);

        $query = $this->db->get('downtime_spekmesin');
        return $query->result_array();
    }

    // public function getTeknisiLike($inputan, $filter_dept)
    // {
    //     $this->db->group_start();
    //     $this->db->like('name', $inputan);
    //     $this->db->group_end();
    //     $this->db->where('id_dept', $filter_dept);

    //     $query = $this->db->get('user');
    //     return $query->result_array();
    // }
    public function getKerusakanLike($kerusakan, $filter_dept)
    {
        $this->db->group_start();
        $this->db->like('ins_kode', $kerusakan);
        $this->db->group_end();
        $this->db->where('dept_id', $filter_dept);
        // $this->db->where_in('ins_kode', ['GM', 'GI', 'GB']);

        $query = $this->db->get('downtime_kerusakan');
        return $query->result_array();
    }

    public function simpan()
    {
        $data = $_POST;
        unset($data['nomesin']);
        unset($data['kerusakan']);

        $data['nomesin_id'] = $data['mach_id'];
        $data['kerusakan_id'] = $data['rusak_id'];

        unset($data['mach_id']);
        unset($data['rusak_id']);

        $data['dept_id'] = $data['dept_id'];
        $data['user'] = $this->session->userdata('name');
        $data['status'] = 0;
        return $this->db->insert('downtime', $data);
    }

    public function hapus($id)
    {

        $this->db->where('id', $id);
        $hapusdowntime = $this->db->delete('downtime');
        $this->db->where('id_downtime', $id);
        $hapustindakan = $this->db->delete('downtime_tindakan');
        return $hapusdowntime && $hapustindakan;
    }



    public function Status_ok($id)
    {

        $data = $this->db->get_where('downtime', ['id' => $id])->row_array();

        $waktu_awal = $data['kerusakan_mulai'];
        $waktu_selesai = date('Y-m-d H:i:s');


        $start = strtotime($waktu_awal);
        $end = strtotime($waktu_selesai);
        $selisih_menit = round(($end - $start) / 60);


        $this->db->set('status', 1);
        $this->db->set('status_by', $this->session->userdata('id'));
        $this->db->set('status_on', $waktu_selesai);
        $this->db->set('kerusakan_selesai', $waktu_selesai);
        $this->db->set('downtime_kerusakan', $selisih_menit);
        $this->db->where('id', $id);

        return $this->db->update('downtime');
    }


    public function Status_cansel($id)
    {
        $this->db->set('status', 0);
        $this->db->set('status_by', '');
        $this->db->set('status_on',  '');
        $this->db->set('kerusakan_selesai', '');
        $this->db->set('downtime_kerusakan', '');
        $this->db->where('id', $id);
        return $this->db->update('downtime');
    }
    // public function getdataByid($id)
    // {
    //     return $this->db->get_where('downtime', ['id' => $id])->row_array();
    // }
    public function UpdateData_kesimpulan()
    {
        $data = $_POST;
        $this->db->where('id', $data['id']);
        return $this->db->update('downtime', $data);
    }

    public function getdataByid($id)
    {
        $this->db->select('downtime .* ,dept.departemen , downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, downtime_kerusakan.ins_kode,  user.name');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('user', 'user.id = downtime.status_by', 'left');
        $this->db->where('downtime.id', $id);
        return $this->db->get()->row_array();
    }
    public function getKategoriDenganTindakan()
    {
        $this->db->select('k.*, t.id_tindakan, t.nama_tindakan');
        $this->db->from('downtime_maskateg k');
        $this->db->join('downtime_mastin t', 't.id_kategori = k.id_kategori', 'left');
        $this->db->order_by('k.id_kategori', 'ASC');

        return $this->db->get()->result();
    }



    public function GetTindakan($id)
    {
        $this->db->select('downtime_tindakan.*, bs.jenis as jenis_sebelum, bb.jenis as jenis_baru, ');
        $this->db->from('downtime_tindakan');
        $this->db->join('tb_benang as bs', 'bs.id = downtime_tindakan.bs_id', 'left');
        $this->db->join('tb_benang as bb', 'bb.id = downtime_tindakan.bb_id', 'left');
        $this->db->where('downtime_tindakan.id_downtime', $id);

        return $this->db->get()->result_array();
    }
    public function getpetugas($id)
    {
        $this->db->select('downtime_tindakan_user.user');
        $this->db->from('downtime_tindakan_user');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id = downtime_tindakan_user.id_tindakan');
        $this->db->where('downtime_tindakan.id_downtime', $id);

        return $this->db->get()->result_array();
    }

    public function simpan_tindakan()
    {
        $data = $_POST;
        $jammulai = $data['jam_start'];
        $jamselesai = $data['jam_end'];

        $start = strtotime($jammulai);
        $end = strtotime($jamselesai);


        if ($end < $start) {
            $end = strtotime('+1 day', $end);
        }

        $selisihDetik = $end - $start;
        $downtimeMenit = round($selisihDetik / 60);

        $data['downtime'] = $downtimeMenit;

        $data['user'] = $this->session->userdata('name');

        return $this->db->insert('downtime_tindakan', $data);
    }

    public function getdataByid_tindakan($id)
    {
        return $this->db->get_where('downtime_tindakan', ['id' => $id])->row_array();
    }


    public function hapus_tindakan($id)
    {
        $this->db->where('id', $id);
        $tindakan = $this->db->delete('downtime_tindakan');
        $this->db->where('id_tindakan', $id);
        $maintenance = $this->db->delete('downtime_tindakan_user');

        return $tindakan && $maintenance;
    }



    public function CekMesin($mesin)
    {
        $this->db->from('downtime');
        $this->db->where('nomesin_id', $mesin);
        $this->db->where_in('status', [0, 2]);
        return $this->db->count_all_results();
    }

    public function getTotal($dept_id)
    {
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR',
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select("
            COUNT(*) as total,
            COUNT(CASE WHEN status = 1 THEN 1 END) as selesai,
            COUNT(CASE WHEN status = 0 THEN 1 END) as progress,
            COUNT(CASE WHEN status = 2 THEN 1 END) as antri
        ");
        $this->db->from('downtime');
        $this->db->where('ins', 1);
        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime.dept_id', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }

        return $this->db->get()->row_array();
    }

    public function getWaiting($dept, $status)
    {
        $this->db->select("downtime.*, dept.departemen,
        downtime_kerusakan.remark as instruksi,
        downtime_kerusakan.ins_kode,
        downtime_spekmesin.mach_name,
        downtime_spekmesin.mach_no");

        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');

        $this->db->where('downtime.status', $status);
        $this->db->where('downtime.dept_id', $dept);
        $this->db->where('downtime.ins', 1);

        return $this->db->get()->result_array();
    }
    public function getProgres($dept, $status)
    {
        $this->db->select("downtime.*, dept.departemen,
        downtime_kerusakan.remark as instruksi,
        downtime_kerusakan.ins_kode,
        downtime_spekmesin.mach_name,
        downtime_spekmesin.mach_no");

        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');

        $this->db->where('downtime.status', $status);
        $this->db->where('downtime.dept_id', $dept);
        $this->db->where('downtime.ins', 1);

        return $this->db->get()->result_array();
    }
}
