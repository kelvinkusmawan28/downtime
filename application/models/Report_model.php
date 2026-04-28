<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report_model extends CI_model
{

    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }

    public function getdata($user)
    {
        $this->db->select('user. *, dept.departemen');
        $this->db->from('user');
        $this->db->join('dept', 'dept.dept_id = user.id_dept', 'left');
        $this->db->where('user.name', $user);
        return $this->db->get()->row_array();
    }
    public function getdata_mesin($mesin, $dept)
    {
        $this->db->select('downtime_spekmesin.mach_no, downtime_spekmesin.mach_name');
        $this->db->from('downtime_spekmesin');
        $this->db->where('downtime_spekmesin.mach_no', $mesin);
        $this->db->where('downtime_spekmesin.dept_kode', $dept);
        return $this->db->get()->row_array();
    }

    public function deptsekarang($dept)
    {
        return $this->db->get_where('dept', ['dept_id' => $dept])->row_array();
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
        $this->db->select('MONTH(tanggal) as bulan, MIN(MONTHNAME(tanggal)) as nama_bulan');
        $this->db->from('downtime');
        $this->db->group_by('MONTH(tanggal)');
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

    public function getExport_excel($mesin, $bulan, $tahun)
    {
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

        $this->db->where('downtime_spekmesin.mach_no', $mesin);
        $this->db->where('downtime.status', 1);
        $this->db->order_by('downtime.tanggal', 'DESC');
        return $this->db->get()->result_array();
    }
}
