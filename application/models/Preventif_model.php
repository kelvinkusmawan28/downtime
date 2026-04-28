<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Preventif_model extends CI_model
{
    public function getdata_dept($id_dept)
    {
        if ($id_dept !== 'IT') {
            $this->db->like('mach_id', $id_dept, 'after');
        }

        $this->db->order_by('downtime_spekmesin.mach_id', 'DESC');
        return $this->db->get('downtime_spekmesin')->result_array();
    }

    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }

    public function simpan()
    {
        $data = $_POST;
        return $this->db->insert('downtime_preventif', $data);
    }
    public function simpan_item()
    {
        $data = $_POST;
        return $this->db->insert('downtime_preventif_item', $data);
    }


    public function getdataByid($id)
    {
        return $this->db->get_where('downtime_spekmesin', ['mach_id' => $id])->row_array();
    }

    public function UpdateData()
    {
        $data = $_POST;
        $this->db->where('mach_id', $data['mach_id']);
        return $this->db->update('downtime_spekmesin', $data);
    }

    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('downtime_preventif_item');
    }
}
