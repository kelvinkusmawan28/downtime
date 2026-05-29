<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Monitoring_model extends CI_model
{

    public function getFilter()
    {
        return $this->db->order_by('dept_id', 'ASC')->get('dept')->result_array();
    }

    public function getMesinByDept($dept)
    {

        if ($dept != 'all') {
            $this->db->where('dept_kode', $dept);
        }

        return $this->db->get('downtime_spekmesin')->result();
    }

    public function getMesinDashboard($dept, $tanggal)
    {

        $this->db->select('
            m.mach_id,
            m.mach_no,
            m.mach_name,
            k.reason,
            k.clr
            ');

        $this->db->from('downtime_spekmesin m');

        $this->db->join(
            'downtime_mesinof d',
            "m.mach_id = d.nomesin_id 
            AND DATE(d.tanggal) = '$tanggal'
            ",
            'left'
        );

        $this->db->join(
            'downtime_ketof k',
            'd.ket_id = k.id',
            'left'
        );

        if ($dept != 'all') {
            $this->db->where('m.dept_kode', $dept);
        }

        return $this->db->get()->result();
    }

    public function getMesinMonitoring($dept)
    {
        $this->db->select("
        downtime_spekmesin.*,
        (
            SELECT d.id
            FROM downtime d
            WHERE d.nomesin_id = downtime_spekmesin.mach_id
            AND d.status IN (0,2)
            LIMIT 1
        ) AS downtime_id,
        (
            SELECT dk.clr
            FROM downtime d
            LEFT JOIN downtime_kerusakan dk
            ON d.kerusakan_id = dk.rusak_id
            WHERE d.nomesin_id = downtime_spekmesin.mach_id
            AND d.status IN (0,2)
            LIMIT 1
        ) AS clr
        ");

        $this->db->from('downtime_spekmesin');
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

        if ($dept !== 'all' && !empty($dept)) {

            $this->db->where('downtime_spekmesin.dept_kode', $dept);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_spekmesin.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }


        return $this->db->get()->result();
    }

    public function GetDetail($mach_id)
    {
        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name,
        downtime_kerusakan.remark, downtime_kerusakan.ins_kode
      ');

        $this->db->from('downtime');

        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->where('downtime.id', $mach_id);


        return $this->db->get()->row_array();
    }

    public function getSummaryClr($dept)
    {
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR'
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select("
       kl.ins_kode,  kl.clr,
        COUNT(*) as total
         ");

        $this->db->from('downtime_spekmesin m');

        $this->db->join(
            'downtime d',
            "m.mach_id = d.nomesin_id",
            'left'
        );

        $this->db->join(
            'downtime_kerusakan kl',
            'd.kerusakan_id = kl.rusak_id',
            'left'
        );

        if ($dept !== 'all' && !empty($dept)) {

            $this->db->where('m.dept_kode', $dept);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('m.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }


        $this->db->group_by(['kl.ins_kode', 'kl.clr']);
        $this->db->where_in('d.status', [0, 2]);

        return $this->db->get()->result();
    }
    public function getSummary_pi($dept)
    {
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR'
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select("
       kl.ins_kode,  kl.clr,
        COUNT(*) as total_pi
         ");

        $this->db->from('downtime_spekmesin m');

        $this->db->join(
            'downtime d',
            "m.mach_id = d.nomesin_id",
            'left'
        );

        $this->db->join(
            'downtime_kerusakan kl',
            'd.kerusakan_id = kl.rusak_id',
            'left'
        );

        if ($dept !== 'all' && !empty($dept)) {

            $this->db->where('m.dept_kode', $dept);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('m.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }


        $this->db->where_in('kl.ins_kode', ['PI', 'PO']);
        $this->db->where_in('d.status', [0, 2]);

        return $this->db->get()->result();
    }
    public function getSummary_gm($dept)
    {
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            7 => 'AR'
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select("
       kl.ins_kode,  kl.clr,
        COUNT(*) as total_gm
         ");

        $this->db->from('downtime_spekmesin m');

        $this->db->join(
            'downtime d',
            "m.mach_id = d.nomesin_id",
            'left'
        );

        $this->db->join(
            'downtime_kerusakan kl',
            'd.kerusakan_id = kl.rusak_id',
            'left'
        );

        if ($dept !== 'all' && !empty($dept)) {

            $this->db->where('m.dept_kode', $dept);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('m.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }

        $this->db->where_in('kl.ins_kode', ['GM', 'GI', 'GB']);
        $this->db->where_in('d.status', [0, 2]);

        return $this->db->get()->result();
    }
}
