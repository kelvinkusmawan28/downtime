<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Kerusakan_model extends CI_model
{
    public function getdata_dept($id_dept)
    {
        // return $this->db->get('downtime')->result_array();
        $this->db->select('downtime_kerusakan.*, dept.departemen');
        $this->db->from('downtime_kerusakan');
        $this->db->join('dept', 'dept.dept_id = downtime_kerusakan.dept_id');
        if ($id_dept !== 'IT') {
            $this->db->where('downtime_kerusakan.dept_id', $id_dept);
        }
        $this->db->order_by('downtime_kerusakan.rusak_id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }



    public function simpan()
    {
        $data = $_POST;
        $data['data_ok'] = 1;
        $data['clr'] = '255,255,0';
        $data['ins_kode'] = 'PI';
        return $this->db->insert('downtime_kerusakan', $data);
    }

    public function getdataByid($id)
    {
        return $this->db->get_where('downtime_kerusakan', ['rusak_id' => $id])->row_array();
    }

    public function UpdateData()
    {
        $data = $_POST;
        $this->db->where('rusak_id', $data['rusak_id']);
        return $this->db->update('downtime_kerusakan', $data);
    }

    public function hapus($id)
    {
        $this->db->where('rusak_id', $id);
        return $this->db->delete('downtime_kerusakan');
    }
}
