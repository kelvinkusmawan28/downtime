<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Riwayat_model extends CI_model
{

    public function getdata($id)
    {
        $this->db->select('user. *, dept.departemen');
        $this->db->from('user');
        $this->db->join('dept', 'dept.dept_id = user.id_dept', 'left');
        $this->db->where('user.id', $id);
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
    public function getTahun()
    {
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('downtime');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
    }
}
