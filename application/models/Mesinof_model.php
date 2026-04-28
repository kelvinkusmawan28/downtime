<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mesinof_model extends CI_model
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

    public function getBulan()
    {
        $this->db->select('MONTH(tanggal) as bulan, MONTHNAME(tanggal) as nama_bulan');
        $this->db->from('downtime_mesinof');
        $this->db->group_by(['MONTH(tanggal)', 'MONTHNAME(tanggal)']);
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getTahun()
    {
        $this->db->select('YEAR(tanggal) as tahun');
        $this->db->from('downtime_mesinof');
        $this->db->group_by('YEAR(tanggal)');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get()->result_array();
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
    public function getMesinof($inputan)
    {
        $this->db->group_start();
        $this->db->like('code', $inputan);
        $this->db->or_like('reason', $inputan);
        $this->db->group_end();

        $query = $this->db->get('downtime_ketof');
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

    public function cekData($tanggal, $dept, $mesin, $shift)
    {
        $this->db->from('downtime_mesinof');
        $this->db->where('tanggal', $tanggal);
        $this->db->where('dept_id', $dept);
        $this->db->where('nomesin_id', $mesin);
        $this->db->where('shift', $shift);

        return $this->db->count_all_results();
    }

    public function simpan()
    {
        $post = $this->input->post();
        $left  = $this->input->post('left') ? 1 : 0;
        $right = $this->input->post('right') ? 1 : 0;
        $id_ketof = $post['id'];
        $id_benang = $post['id_benang'];


        $data = [
            'tanggal' => $post['tanggal'],
            'dept_id' => $post['dept_id'],
            'nomesin_id' => $post['mach_id'],
            'ket_id' =>   $id_ketof,
            'ket_tb' =>   $id_benang,
            'left' =>   $left,
            'right' =>   $right,
            'keterangan' =>   $post['keterangan'],
            'shift' => $post['shift'],
            'ket_on' => date('Y-m-d H:i'),
            'user_by' => $this->session->userdata('id'),
        ];
        return $this->db->insert('downtime_mesinof', $data);
    }
    public function update()
    {

        $post = $this->input->post();

        $data = [

            'tanggal' => $post['tanggal'],
            'shift' => $post['shift'],
            'ket_id' => $post['ket_id'],
            'ket_tb' => $post['id_benang'],
            'keterangan' => $post['keterangan']

        ];

        $this->db->where('id', $post['id']);
        return $this->db->update('downtime_mesinof', $data);
    }

    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('downtime_mesinof');
    }



    public function getdataByid($id)
    {
        $this->db->select("
            downtime_mesinof.*,
            downtime_ketof.code,
            downtime_ketof.reason,
            downtime_spekmesin.mach_no,
            downtime_spekmesin.mach_name,
            tb_benang.jenis
        ");

        $this->db->from('downtime_mesinof');

        $this->db->join('downtime_ketof', 'downtime_ketof.id = downtime_mesinof.ket_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime_mesinof.nomesin_id', 'left');
        $this->db->join('tb_benang', 'tb_benang.id = downtime_mesinof.ket_tb', 'left');

        $this->db->where('downtime_mesinof.id', $id);

        return $this->db->get()->row_array();
    }
    public function UpdateData_kesimpulan()
    {
        $data = $_POST;
        $this->db->where('id', $data['id']);
        return $this->db->update('downtime', $data);
    }
}
