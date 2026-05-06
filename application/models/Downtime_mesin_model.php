<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Downtime_mesin_model extends CI_model
{
    public function getBulan()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
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
    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }

    public function getExport_Pdf($dept_id, $bulan, $tahun)
    {
        $this->db->select('
        d.nomesin_id,d.ins,
        SUM(d.downtime_kerusakan) as total_downtime,
        s.mach_no,
        s.mach_name,
        dept.dept_id,
        dept.departemen
     ');
        $this->db->from('downtime d');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');
        $this->db->where('d.status', 1);
        $this->db->where('(d.ins != 1 OR d.ins IS NULL)');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        $this->db->group_by('d.nomesin_id, s.mach_no, s.mach_name, dept.dept_id, dept.departemen');
        $this->db->order_by('s.mach_no', 'ASC');

        return $this->db->get()->result_array();
    }
    public function getExport_Pengerjaan($dept_id, $bulan, $tahun)
    {
        $this->db->select('
        d.nomesin_id,d.ins,
        SUM(t.downtime) as total_downtime,
        s.mach_no,
        s.mach_name,
        dept.dept_id,
        dept.departemen
     ');
        $this->db->from('downtime d');
        $this->db->join('downtime_tindakan t', 't.id_downtime = d.id', 'left');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');
        $this->db->where('d.status', 1);
        $this->db->where('(d.ins != 1 OR d.ins IS NULL)');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        $this->db->group_by('d.nomesin_id, s.mach_no, s.mach_name, dept.dept_id, dept.departemen');
        $this->db->order_by('s.mach_no', 'ASC');

        return $this->db->get()->result_array();
    }
}
