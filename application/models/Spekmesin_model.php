<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Spekmesin_model extends CI_model
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

        $kode_dept = $data['mach_id'];
        $data['mach_id'] = nomor_dokumen_mesin($kode_dept, 'downtime_spekmesin');

        $data['aroz'] = 0;

        return $this->db->insert('downtime_spekmesin', $data);
    }


    public function getdataByid($id)
    {
        return $this->db->get_where('downtime_spekmesin', ['mach_id' => $id])->row_array();
    }

    public function UpdateData()
    {
        $data = $_POST;
        if (isset($data['idle'])) {
            $data['idle'] = 0;
        } else {
            $data['idle'] = 1;
        }
        $this->db->where('mach_id', $data['mach_id']);
        return $this->db->update('downtime_spekmesin', $data);
    }

    public function hapus($id)
    {
        $this->db->where('mach_id', $id);
        return $this->db->delete('downtime_spekmesin');
    }
}
