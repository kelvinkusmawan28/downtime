<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ketof_model extends CI_model
{
    public function dept()
    {
        $this->db->select("dept.*");
        $this->db->from('dept');
        $this->db->where_in('dept_id', ['AR', 'FN', 'NT', 'RR', 'SP', 'UT', 'GF']);
        $this->db->order_by('departemen', 'ASC');
        return $this->db->get()->result_array();
    }
    public function jmldept()
    {
        $this->db->select('dept.*, kategori_departemen.nama');
        $this->db->from('dept');
        $this->db->join('kategori_departemen', 'kategori_departemen.id = dept.katedept_id', 'left');

        return $this->db->get()->num_rows();
    }

    public function simpan()
    {
        $data = $_POST;

        $haksp = '';
        if (isset($data['sp'])) {
            foreach ($data['sp'] as $deptid) {
                $haksp .= $deptid;
            }
            unset($data['sp']);
        }

        $data['sp'] = $haksp;
        $data['clr'] = $data['clr_rgb'];

        unset($data['clr_rgb']);

        return $this->db->insert('downtime_ketof', $data);
    }

    public function getdataByid($id)
    {
        return $this->db->get_where('downtime_ketof', ['id' => $id])->row_array();
    }

    public function UpdateData()
    {
        $data = $_POST;

        $haksp = '';
        if (isset($data['sp'])) {
            foreach ($data['sp'] as $deptid) {
                $haksp .= $deptid;
            }
            unset($data['sp']);
        }

        $data['sp'] = $haksp;
        $data['clr'] = $data['clr_rgb'];

        unset($data['clr_rgb']);

        $this->db->where('id', $data['id']);
        return $this->db->update('downtime_ketof', $data);
    }

    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('downtime_ketof');
    }
}
