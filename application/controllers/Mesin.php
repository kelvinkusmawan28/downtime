<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mesin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mesin_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Downtime Mesin';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $id_dept = $this->session->userdata('id_dept');
        $data['mesin'] = $this->Mesin_model->getdata_dept($id_dept);
        $data['bulan_options'] = $this->Mesin_model->getBulan();
        $data['tahun_options'] = $this->Mesin_model->getTahun();
        $data['dept_options'] = $this->Mesin_model->getFilter();

        $data['filter_status'] = $this->session->userdata('filter_status') ?? 'all';
        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';
        $data['downtime_dept_map'] = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];


        $this->load->view('templates/header', $data);
        $this->load->view('mesin/index', $data);
        $this->load->view('templates/footer');
    }
    public function reset_filter()
    {

        $this->session->unset_userdata('filter_dept');
        $this->session->unset_userdata('filter_bulan');
        $this->session->unset_userdata('filter_tahun');
        $this->session->unset_userdata('filter_status');


        redirect('mesin');
    }

    // public function filter_downtime()
    // {
    //     $dept_id = $this->input->post('dept_id');
    //     $status = $this->input->post('status');
    //     $bulan = $this->input->post('bulan');
    //     $tahun = $this->input->post('tahun');

    //     $this->session->set_userdata('filter_status', $status);
    //     $this->session->set_userdata('filter_bulan', $bulan);
    //     $this->session->set_userdata('filter_tahun', $tahun);
    //     $this->session->set_userdata('filter_dept', $dept_id);

    //     $limit = $this->input->post('length');
    //     $start = $this->input->post('start');
    //     $draw = $this->input->post('draw');
    //     $search = $this->input->post('search')['value'];


    //     $hakdowntime = $this->session->userdata('hakdowntime');

    //     $hak_dept_downtime = [
    //         1 => 'FN',
    //         2 => 'NT',
    //         3 => 'RR',
    //         4 => 'SP',
    //         5 => 'UT',
    //         6 => 'GF',
    //         7 => 'AR',
    //     ];

    //     $akses_dept = [];


    //     foreach ($hak_dept_downtime as $index => $dept_code) {
    //         $start_pos = ($index * 2) - 2;
    //         if (substr($hakdowntime, $start_pos, 2) === '10') {
    //             $akses_dept[] = $dept_code;
    //         }
    //     }

    //     $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, user.username, user.name, downtime_tindakan.id_downtime');
    //     $this->db->from('downtime');
    //     $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
    //     $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
    //     $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
    //     $this->db->join('user', 'user.id = downtime.status_by', 'left');
    //     $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');

    //     if ($dept_id !== 'all' && !empty($dept_id)) {

    //         $this->db->where('downtime.dept_id', $dept_id);
    //     } else {

    //         if (!empty($akses_dept)) {
    //             $this->db->where_in('downtime.dept_id', $akses_dept);
    //         } else {

    //             $this->db->where('1=0');
    //         }
    //     }

    //     if ($status !== 'all' && $status !== null && $status !== '') {
    //         $this->db->where('downtime.status', $status);
    //     }


    //     if ($bulan !== 'all' && !empty($bulan)) {
    //         $this->db->where('MONTH(downtime.tanggal)', $bulan);
    //     }
    //     if ($tahun !== 'all' && !empty($tahun)) {
    //         $this->db->where('YEAR(downtime.tanggal)', $tahun);
    //     }

    //     if (!empty($search)) {
    //         $this->db->group_start();
    //         $this->db->like('downtime_spekmesin.mach_no', $search);
    //         $this->db->or_like('downtime_kerusakan.remark', $search);
    //         $this->db->group_end();
    //     }
    //     $this->db->where('(downtime.ins != 1 OR downtime.ins IS NULL)');
    //     $this->db->group_by('downtime.id');
    //     $this->db->order_by('downtime.id', 'DESC');
    //     $this->db->limit($limit, $start);
    //     $data = $this->db->get()->result_array();

    //     //  view
    //     $no = $start + 1;
    //     foreach ($data as &$row) {
    //         $row['no'] = '<span style="color:black;">' . $no++ . '</span>';
    //         $row['tanggal'] = '<span style="color:black;">' . format_tanggal_indonesia($row['tanggal']) . '</span>';
    //         $row['departemen'] = '<span style="color:black;">' .
    //             ($row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen']) .
    //             '</span>';
    //         $row['mesin'] = '<span style="color:black;">' . 'Mesin '  . $row['mach_no'] . '</span>' . '<br><span style="color:black;">' . $row['mach_name'] . '</span>';

    //         $row['kerusakan'] = '<a href="' . base_url() . 'mesin/view/' . $row['id'] . '" style="text-decoration: underline; color:red ;" title="Views Data">' . $row['remark'] . '</a>';
    //         $id = $row['id'];
    //         $status_value = $row['status'];
    //         if ($status_value == 0) {

    //             $start_time = strtotime($row['kerusakan_mulai']) * 1000;

    //             $row['status'] = '
    //                     <span class="badge bg-success">PROGRES</span><br>
    //                     <small class="font-bold text-dark" style="font-size: 12px;">' . format_tanggal_indonesia_waktu($row['kerusakan_mulai']) . '</small><br>
    //                     <span class="font-bold text-success updateon" data-start="' . $start_time . '">Loading...</span>
    //                 ';

    //             $dropdown = '';


    //             if (!empty($row['id_downtime'])) {
    //                 if (!empty($row['kesimpulan'])) {
    //                     $dropdown .= '
    //                     <li>
    //                         <a class="btn btn-outline-primary font-kecil status" 
    //                            data-id="' . $id . '" 
    //                            data-url="' . base_url() . 'mesin/status_ok/' . $id . '" 
    //                            href="#">
    //                            <i class="fa fa-lock me-2"></i> Kunci Data
    //                         </a>
    //                     </li>';
    //                 } else {
    //                     $dropdown .= '<span  style="font-size : 14px ; color:red; margin-left:10px ;" >Isi Kesimpulan !</span> <br>';
    //                 }
    //             } else {
    //                 $dropdown .= '<span  style="font-size : 14px ; color:red; margin-left:10px ;" >Belum Ada Tindakan !</span> <br>';
    //             }


    //             $dropdown .= '
    //             <li><a class="btn btn-outline-danger font-kecil hapus" style="margin-top : 5px ;"  data-id="' . $id . '" data-url="' . base_url() . 'mesin/hapus/' . $id . '" href="#"><i class="fa fa-trash me-2" ></i> Hapus Data</a></li>
    //              ';


    //             $row['aksi'] = '';
    //             $cekdowntime = $this->session->userdata('cekdowntime');
    //             if ($cekdowntime == '1') {
    //                 $row['aksi'] = '
    //                     <div class="dropdown font-kecil">
    //                         <a class="btn btn-secondary dropdown-toggle font-kecil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    //                             Aksi
    //                         </a>
    //                         <ul class="dropdown-menu">' . $dropdown . '</ul>
    //                     </div>';
    //             }
    //         } else {

    //             $row['status'] = '
    //                 <span class="badge bg-danger">CLOSE</span><br>
    //                 <small class="font-bold" style="font-size:9px; color:black;">' .
    //                 $row['name'] . '<br>' .
    //                 format_tanggal_indonesia_waktu($row['status_on']) .
    //                 '</small><br>
    //                 <small class="font-bold" style="font-size:14px; color:red;">' .
    //                 'Downtime ' . format_downtime($row['downtime_kerusakan'])  .
    //                 '</small>';

    //             $dropdown = '
    //                 <li><a class="btn btn-outline-warning font-kecil status_cansel" data-id="' . $id . '" data-url="' . base_url() . 'mesin/status_cansel/' . $id . '" href="#"><i class="fa fa-key me-2"></i> Buka Data</a></li>

    //             ';

    //             $row['aksi'] = '';
    //             $cekdowntime = $this->session->userdata('cekdowntime');
    //             if ($cekdowntime == '1') {

    //                 $row['aksi'] = '
    //                     <div class="dropdown font-kecil">
    //                         <a class="btn btn-info dropdown-toggle font-kecil" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    //                             Aksi
    //                         </a>
    //                         <ul class="dropdown-menu">' . $dropdown . '</ul>
    //                     </div>';
    //             }
    //         }
    //     }

    //     // Penghitungan data yang difilter
    //     $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, user.username, user.name, downtime_tindakan.id_downtime');
    //     $this->db->from('downtime');
    //     $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
    //     $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
    //     $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
    //     $this->db->join('user', 'user.id = downtime.status_by', 'left');
    //     $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');

    //     if ($dept_id !== 'all' && !empty($dept_id)) {

    //         $this->db->where('downtime.dept_id', $dept_id);
    //     } else {

    //         if (!empty($akses_dept)) {
    //             $this->db->where_in('downtime.dept_id', $akses_dept);
    //         } else {

    //             $this->db->where('1=0');
    //         }
    //     }

    //     if ($status !== 'all' && $status !== null && $status !== '') {
    //         $this->db->where('downtime.status', $status);
    //     }

    //     if ($bulan !== 'all' && !empty($bulan)) {
    //         $this->db->where('MONTH(downtime.tanggal)', $bulan);
    //     }
    //     if ($tahun !== 'all' && !empty($tahun)) {
    //         $this->db->where('YEAR(downtime.tanggal)', $tahun);
    //     }

    //     if (!empty($search)) {
    //         $this->db->group_start();
    //         $this->db->like('downtime_spekmesin.mach_no', $search);
    //         $this->db->or_like('downtime_kerusakan.remark', $search);
    //         $this->db->group_end();
    //     }

    //     $recordsFiltered = $this->db->count_all_results();

    //     // Penghitungan data total
    //     $this->db->from('downtime');
    //     $recordsTotal = $this->db->count_all_results();

    //     echo json_encode([
    //         'draw' => intval($draw),
    //         'recordsTotal' => $recordsTotal,
    //         'recordsFiltered' => $recordsFiltered,
    //         'data' => $data
    //     ]);
    // }


    public function filter_downtime()
    {
        $dept_id = $this->input->post('dept_id');
        $status = $this->input->post('status');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $this->session->set_userdata('filter_status', $status);
        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);
        $this->session->set_userdata('filter_dept', $dept_id);

        $limit = $this->input->post('length') ?? 100;
        $start = $this->input->post('start') ?? 0;
        $draw  = $this->input->post('draw') ?? 0;

        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? $searchPost['value'] : '';

        // ================== HAK AKSES ==================
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN', 2 => 'NT', 3 => 'RR', 4 => 'SP',
            5 => 'UT', 6 => 'GF', 7 => 'AR',
        ];

        $akses_dept = [];
        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        // ================== QUERY DATA ==================
        $this->db->select('downtime.*, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, user.name, downtime_tindakan.id_downtime');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('user', 'user.id = downtime.status_by', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');

        // ================== FILTER ==================

        // DEPT
        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        // STATUS
        if ($status !== 'all' && $status !== null && $status !== '') {
            $this->db->where('downtime.status', $status);
        }

        // BULAN
        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        // TAHUN
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        // SEARCH (DIRAPIKAN)
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime.keterangan', $search);
            $this->db->or_like('downtime_spekmesin.mach_no', $search);
            $this->db->group_end();
        }

        $this->db->where('(downtime.ins != 1 OR downtime.ins IS NULL)');

        // 🔥 PENTING: hindari duplicate
        $this->db->group_by('downtime.id');

        $this->db->order_by('downtime.id', 'DESC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        // ================== FORMAT DATA ==================
        foreach ($data as &$row) {

            $status_asli = $row['status'];

            $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);

            $row['departemen'] = ($row['departemen'] == 'FINISHED GOODS')
                ? 'GUDANG'
                : $row['departemen'];

            $row['mesin'] = 'Mesin ' . $row['mach_no'] . '<br>' . $row['mach_name'];

            $row['kerusakan'] = '<a href="' . base_url() . 'mesin/view/' . $row['id'] . '" style="color:red;">' . ($row['remark'] ?? '') . '</a>';

            if ($status_asli == 2) {

                $row['status'] = '
                <span class="badge bg-light text-danger">Dalam Antrian</span>
                ';
            } elseif ($status_asli == 0) {

                $start_time = strtotime($row['kerusakan_mulai']) * 1000;

                $row['status'] = '
                    <span class="badge bg-success">PROGRES</span><br>
                    <small style="font-size:10px;">' . format_tanggal_indonesia_waktu($row['kerusakan_mulai']) . '</small><br>
                    <span class="updateon text-success" data-start="' . $start_time . '">Loading...</span>
                ';
            } else {

                $row['status'] = '
                    <span class="badge bg-danger">CLOSE</span><br>
                    <small>' . $row['name'] . '</small><br>
                    <small style="font-size:10px;color:red;">DOWNTIME STOP</small><br>
                    <small style="font-size:14px;color:red;">' . format_downtime($row['downtime_kerusakan']) . '</small>
                ';
            }

            $aksi = '';

            if ($status_asli == 2) {


                if ($this->session->userdata('cekdowntime') == '1') {

                    $aksi .= '
                    <a href="#" class="btn btn-danger btn-sm hapus"
                       data-id="' . $row['id'] . '"
                       data-url="' . base_url() . 'mesin/hapus/' . $row['id'] . '">
                       Hapus</a>

                       <a href="#" 
                       class="btn btn-sm btn-warning text-dark edit" 
                       title="Kerjakan" 
                       data-id="' . $row['id']  . '">
                           Kerjakan
                     </a>
                     
                       ';
                } else {

                    $aksi .= '    
                       <a href="#" 
                       class="btn btn-sm btn-warning text-dark edit" 
                       title="Kerjakan Instruksi" 
                       data-id="' . $row['id']  . '">
                           Kerjakan
                     </a>
                     
                       ';
                }
            }
            if ($status_asli == 0) {

                if (!empty($row['id_downtime'])) {
                    if ($this->session->userdata('cekdowntime') == '1') {

                        if (!empty($row['kesimpulan'])) {
                            $aksi .= '
                                <a class="btn btn-success btn-sm status"
                                   data-id="' . $row['id'] . '"
                                   data-url="' . base_url() . 'mesin/status_ok/' . $row['id'] . '"
                                   href="#">Selesai</a>
                            ';
                        } else {
                            $aksi .= '
                                <a href="#" data-id="' . $row['id'] . '" 
                                   class="kesimpulan btn btn-secondary btn-sm">
                                   Kesimpulan
                                </a>
                                <a href="#" class="btn btn-danger btn-sm hapus"
                                   data-id="' . $row['id'] . '"
                                   data-url="' . base_url() . 'mesin/hapus/' . $row['id'] . '">
                                   Hapus
                                </a>
                            ';
                        }
                    }
                }

                $aksi .= '
                <a href="' . base_url() . 'mesin/view/' . $row['id'] . '" 
                class="btn bg-light btn-sm">Detail</a>
                ';
            }

            if ($status_asli == 1) {

                $aksi .= '
                <a href="' . base_url() . 'mesin/view/' . $row['id'] . '" 
                class="btn bg-light btn-sm">Detail</a>
                ';
            }




            $row['aksi'] = $aksi;
        }

        // ================== COUNT FILTERED ==================
        $this->db->from('downtime');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');

        // FILTER ULANG (SAMA PERSIS)
        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if ($status !== 'all' && $status !== null && $status !== '') {
            $this->db->where('downtime.status', $status);
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime.keterangan', $search);
            $this->db->or_like('downtime_spekmesin.mach_no', $search);
            $this->db->group_end();
        }

        $this->db->where('(downtime.ins != 1 OR downtime.ins IS NULL)');
        $this->db->group_by('downtime.id');

        $recordsFiltered = $this->db->count_all_results();

        // ================== COUNT TOTAL ==================
        $this->db->from('downtime');
        $this->db->where('(downtime.ins != 1 OR downtime.ins IS NULL)');
        $recordsTotal = $this->db->count_all_results();

        header('Content-Type: application/json');

        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function tambahdata($filter_dept = null)
    {
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['dept_id'] = $filter_dept;
        $this->session->set_userdata('filter_dept', $filter_dept);
        $data['spekmesin'] = $this->db->get('downtime_spekmesin')->result_array();
        $this->load->view('mesin/add', $data);
    }


    public function nomor_mesin()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->input->get('filter');

        $result = $this->Mesin_model->getMesinLike($inputan, $filter_dept);
        echo json_encode($result);
    }

    public function kerusakan_mesin()
    {
        $kerusakan = $this->input->get('kerusakan');
        $filter_dept = $this->input->get('filter');
        $result = $this->Mesin_model->getKerusakanLike($kerusakan, $filter_dept);
        echo json_encode($result);
    }



    public function simpan()
    {
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 20240; // 20 MB

        $this->load->library('upload', $config);
        $files = $_FILES['file_upload'];

        $file_names = [];
        $file_types = [];
        $file_paths = [];

        $is_file_uploaded = false;
        for ($i = 0; $i < count($files['name']); $i++) {
            if (!empty($files['name'][$i])) {
                $is_file_uploaded = true;
                $_FILES['file_upload']['name'] = $files['name'][$i];
                $_FILES['file_upload']['type'] = $files['type'][$i];
                $_FILES['file_upload']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file_upload']['error'] = $files['error'][$i];
                $_FILES['file_upload']['size'] = $files['size'][$i];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_upload')) {
                    $upload_data = $this->upload->data();
                    $file_names[] = $upload_data['file_name'];
                    $file_types[] = $upload_data['file_type'];
                    $file_paths[] = 'assets/img/upload/' . $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
                    redirect('mesin');
                    return;
                }
            }
        }

        $post = $this->input->post();

        $mesin = $post['mach_id'];
        $cek_mesin = $this->Mesin_model->CekMesin($mesin);


        if ($cek_mesin) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h4 class="alert-heading d-flex align-items-center flex-wrap gap-1">
                <span class="alert-icon rounded-circle"><i class="icon-base bx bx-error"></i></span>Error!!
                </h4>
                <p><b>DATA TIDAK DAPAT DISIMPAN !</b> 
                Masih terdapat kerusakan sebelumnya di mesin ini yang belum diperiksa. 
                Mohon lakukan pengecekan terlebih dahulu atau sudah ada dalam data antiran. Terimkakasih</p>
                <hr />
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
            ');
            redirect('mesin');
        }
        $data = [
            'nomesin_id' => $post['mach_id'],
            'tanggal' => $post['tanggal'],
            'keterangan' => $post['keterangan'],
            'kerusakan_id' => $post['rusak_id'],
            'dept_id' => $post['dept_id'],
            'kerusakan_mulai' => date('Y-m-d H:i'),
            'status' => 0,
            'user' => $this->session->userdata('name'),
            'file' => $is_file_uploaded ? json_encode($file_names) : null,
            'file_type' => $is_file_uploaded ? json_encode($file_types) : null,
            'path_file' => $is_file_uploaded ? json_encode($file_paths) : null,
        ];

        $query = $this->db->insert('downtime', $data);

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Berhasil Ditambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }

        redirect('mesin');
    }
    public function update($id)
    {
        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 20240; // 20 MB

        $this->load->library('upload', $config);
        $files = $_FILES['file_upload'];

        $file_names = [];
        $file_types = [];
        $file_paths = [];

        $is_file_uploaded = false;
        for ($i = 0; $i < count($files['name']); $i++) {
            if (!empty($files['name'][$i])) {
                $is_file_uploaded = true;
                $_FILES['file_upload']['name'] = $files['name'][$i];
                $_FILES['file_upload']['type'] = $files['type'][$i];
                $_FILES['file_upload']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file_upload']['error'] = $files['error'][$i];
                $_FILES['file_upload']['size'] = $files['size'][$i];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_upload')) {
                    $upload_data = $this->upload->data();
                    $file_names[] = $upload_data['file_name'];
                    $file_types[] = $upload_data['file_type'];
                    $file_paths[] = 'assets/img/upload/' . $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
                    redirect('mesin');
                    return;
                }
            }
        }

        $post = $this->input->post();

        $mesin = $post['mach_id'];
        $cek_mesin = $this->Mesin_model->CekMesin_update($mesin);


        if ($cek_mesin) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h4 class="alert-heading d-flex align-items-center flex-wrap gap-1">
                <span class="alert-icon rounded-circle"><i class="icon-base bx bx-error"></i></span>Error!!
                </h4>
                <p><b>DATA TIDAK DAPAT DISIMPAN !</b> 
                Masih terdapat kerusakan sebelumnya di mesin ini yang belum diperiksa. 
                Mohon lakukan pengecekan terlebih dahulu. Terimkakasih</p>
                <hr />
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
            ');
            redirect('mesin');
        }
        $data = [


            'keterangan' => $post['keterangan'],
            'kerusakan_id' => $post['rusak_id'],
            'dept_id' => $post['dept_id'],
            'kerusakan_mulai' => date('Y-m-d H:i'),
            'status' => 0,
            'user' => $this->session->userdata('name'),
            'file' => $is_file_uploaded ? json_encode($file_names) : null,
            'file_type' => $is_file_uploaded ? json_encode($file_types) : null,
            'path_file' => $is_file_uploaded ? json_encode($file_paths) : null,
        ];
        $this->db->where('id', $id);
        $query = $this->db->update('downtime', $data);

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Berhasil Ditambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>');
        }

        redirect('mesin');
    }

    public function kesimpulan($id)
    {
        $data['detail'] = $this->Mesin_model->getdataByid($id);
        $data['id_halaman'] = $id;
        $this->load->view('mesin/kesimpulan', $data);
    }

    public function update_kesimpulan()
    {
        $query = $this->Mesin_model->UpdateData_kesimpulan();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Kesimpulan Berhasil Di Tambahkan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('mesin');
    }
    public function kesimpulan_detail($id)
    {
        $data['detail'] = $this->Mesin_model->getdataByid($id);
        $data['id_halaman'] = $id;
        $this->load->view('mesin/kesimpulan_detail', $data);
    }

    public function update_kesimpulan_detail($id_halaman)
    {
        $query = $this->Mesin_model->UpdateData_kesimpulan();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Kesimpulan Berhasil Di Tambahkan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('mesin/view/' . $id_halaman);
    }


    public function hapus($id)
    {
        $hasil = $this->Mesin_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('mesin');
    }

    public function status_ok($id)
    {
        $query = $this->Mesin_model->Status_ok($id);
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Status Perbaikan Berhasil Di Close!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('mesin');
    }

    public function status_cansel($id)
    {
        $query = $this->Mesin_model->Status_cansel($id);
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Status Data Kembali Progres!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('mesin');
    }

    public function view($id)
    {
        $data['title'] = ' Detail Tindakan Perbaikan';
        $data['detail'] = $this->Mesin_model->getdataByid($id);
        $data['tindakan'] = $this->db->get_where('downtime_tindakan', ['id_downtime' => $id])->result_array();
        $data['id_halaman'] = $id;

        $this->load->view('templates/header', $data);
        $this->load->view('mesin/detail', $data);
        $this->load->view('templates/footer');
    }


    public function tambah_tindakan($id_halaman)
    {
        $filter_dept = $this->session->userdata('filter_dept');
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['id'] = $id_halaman;
        $data['users'] = $this->db->get_where('user', ['id_dept' => $filter_dept])->result_array();


        // var_dump($data['users']);
        // exit;
        $this->load->view('mesin/add_tindakan', $data);
    }

    public function simpan_tindakan($id)
    {


        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 20240; // 20 MB

        $this->load->library('upload', $config);
        $files = $_FILES['file_upload'];

        $file_names = [];
        $file_types = [];
        $file_paths = [];

        $is_file_uploaded = false;
        for ($i = 0; $i < count($files['name']); $i++) {
            if (!empty($files['name'][$i])) {
                $is_file_uploaded = true;
                $_FILES['file_upload']['name'] = $files['name'][$i];
                $_FILES['file_upload']['type'] = $files['type'][$i];
                $_FILES['file_upload']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file_upload']['error'] = $files['error'][$i];
                $_FILES['file_upload']['size'] = $files['size'][$i];

                $this->upload->initialize($config);

                if ($this->upload->do_upload('file_upload')) {
                    $upload_data = $this->upload->data();
                    $file_names[] = $upload_data['file_name'];
                    $file_types[] = $upload_data['file_type'];
                    $file_paths[] = 'assets/img/upload/' . $upload_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
                    redirect('mesin');
                    return;
                }
            }
        }

        $post = $this->input->post();

        $jammulai = $post['jam_start'];
        $jamselesai = $post['jam_end'];

        $start = strtotime($jammulai);
        $end = strtotime($jamselesai);


        if ($end < $start) {
            $end = strtotime('+1 day', $end);
        }

        $selisihDetik = $end - $start;
        $downtimeMenit = round($selisihDetik / 60);
        $data = [
            'id_downtime' => $post['id_downtime'],
            'tanggal' => $post['tanggal'],
            'tindakan' => $post['tindakan'],
            'jam_start' => $post['jam_start'],
            'jam_end' => $post['jam_end'],
            'user' => $this->session->userdata('name'),
            'downtime' => $downtimeMenit,
            'file' => $is_file_uploaded ? json_encode($file_names) : null,
            'file_type' => $is_file_uploaded ? json_encode($file_types) : null,
            'path_file' => $is_file_uploaded ? json_encode($file_paths) : null,
        ];

        $this->db->insert('downtime_tindakan', $data);

        $id_tindakan = $this->db->insert_id();

        $users_json = $this->input->post('teknisi');
        $users = json_decode($users_json, true);

        if (!empty($users)) {
            $data_users = [];
            foreach ($users as $user) {
                $data_users[] = [
                    'id_tindakan' => $id_tindakan,
                    'user' => isset($user['value']) ? $user['value'] : $user
                ];
            }
            $this->db->insert_batch('downtime_tindakan_user', $data_users);
        }


        $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Kerusakan Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');

        redirect('mesin/view/' . $id);
    }
    public function edit($id)
    {
        $data['detail'] = $this->db->get_where('downtime', ['id' => $id])->row_array();
        $this->load->view('mesin/edit', $data);
    }
    public function edit_tindakan($id, $id_halaman)
    {

        $data['detail'] = $this->Mesin_model->getdataByid_tindakan($id);
        $data['id_halaman'] = $id_halaman;
        $data['user'] = $this->db->get_where('downtime_tindakan_user', ['id_tindakan' => $id])->result_array();
        $this->load->view('mesin/edit_tindakan', $data);
    }



    public function update_tindakan($id, $id_halaman)
    {
        $config['upload_path'] = 'assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 20240; // 20 MB
        $this->load->library('upload', $config);

        // Ambil data lama dari database
        $detail = $this->Mesin_model->getdataByid_tindakan($id);
        $old_files = !empty($detail['file']) ? json_decode($detail['file'], true) : [];
        $old_file_paths = !empty($detail['path_file']) ? json_decode($detail['path_file'], true) : [];
        $old_file_types = !empty($detail['file_type']) ? json_decode($detail['file_type'], true) : [];

        //  File yang user ingin hapus
        $hapus_file = $this->input->post('hapus_file') ?? [];

        // dihapus dari array lama
        $filtered_old_files = [];
        $filtered_file_paths = [];
        $filtered_file_types = [];

        foreach ($old_files as $i => $filename) {
            if (!in_array($filename, $hapus_file)) {
                $filtered_old_files[] = $filename;
                if (isset($old_file_paths[$i])) $filtered_file_paths[] = $old_file_paths[$i];
                if (isset($old_file_types[$i])) $filtered_file_types[] = $old_file_types[$i];
            }
        }

        // Hapus file dari folder
        foreach ($hapus_file as $file) {
            $path = 'assets/img/upload/' . $file;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // rename
        $rename_files = $this->input->post('rename_file');
        $nama_file_asli = $this->input->post('nama_file_asli');

        if ($rename_files && $nama_file_asli) {
            foreach ($rename_files as $i => $rename) {
                $old_name = $nama_file_asli[$i];


                if (!empty($rename) && in_array($old_name, $filtered_old_files)) {
                    $old_path = 'assets/img/upload/' . $old_name;

                    $ext = pathinfo($old_name, PATHINFO_EXTENSION);
                    $rename_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($rename, PATHINFO_FILENAME));
                    $new_name = $rename_clean . '.' . $ext;
                    $new_path = 'assets/img/upload/' . $new_name;

                    if (file_exists($old_path)) {
                        rename($old_path, $new_path);

                        // Update di array lama
                        $key = array_search($old_name, $filtered_old_files);
                        if ($key !== false) {
                            $filtered_old_files[$key] = $new_name;
                            $filtered_file_paths[$key] = $new_path;
                        }
                    }
                }
            }
        }


        $files = $_FILES['file_upload'];
        $new_files = [];
        $new_file_paths = [];
        $new_file_types = [];

        if (!empty($files['name'][0])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                if (!empty($files['name'][$i])) {
                    $_FILES['file_upload_temp']['name'] = $files['name'][$i];
                    $_FILES['file_upload_temp']['type'] = $files['type'][$i];
                    $_FILES['file_upload_temp']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['file_upload_temp']['error'] = $files['error'][$i];
                    $_FILES['file_upload_temp']['size'] = $files['size'][$i];

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('file_upload_temp')) {
                        $upload_data = $this->upload->data();
                        $new_files[] = $upload_data['file_name'];
                        $new_file_paths[] = $config['upload_path'] . $upload_data['file_name'];
                        $new_file_types[] = $upload_data['file_type'];
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
                        redirect('mesin/view/' . $id_halaman);
                        return;
                    }
                }
            }
        }

        // Gabungkan semua file
        $all_files = array_merge($filtered_old_files, $new_files);
        $all_file_paths = array_merge($filtered_file_paths, $new_file_paths);
        $all_file_types = array_merge($filtered_file_types, $new_file_types);

        $post = $this->input->post();

        $jammulai = $post['jam_start'];
        $jamselesai = $post['jam_end'];

        $start = strtotime($jammulai);
        $end = strtotime($jamselesai);


        if ($end < $start) {
            $end = strtotime('+1 day', $end);
        }

        $selisihDetik = $end - $start;
        $downtimeMenit = round($selisihDetik / 60);

        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'tindakan' => $this->input->post('tindakan'),
            'jam_start' => $this->input->post('jam_start'),
            'jam_end' => $this->input->post('jam_end'),
            'downtime' => $downtimeMenit,
            'file' => !empty($all_files) ? json_encode(array_values($all_files)) : null,
            'file_type' => !empty($all_file_types) ? json_encode(array_values($all_file_types)) : null,
            'path_file' => !empty($all_file_paths) ? json_encode(array_values($all_file_paths)) : null,
        ];

        $this->db->where('id', $id);
        $this->db->update('downtime_tindakan', $data);
        // hapus teknisi
        $this->db->where('id_tindakan', $id);
        $this->db->delete('downtime_tindakan_user');

        $users_json = $this->input->post('teknisi');
        $users = json_decode($users_json, true);

        if (!empty($users)) {
            $data_users = [];
            foreach ($users as $user) {
                $data_users[] = [
                    'id_tindakan' => $id,
                    'user' => isset($user['value']) ? $user['value'] : $user
                ];
            }
            $this->db->insert_batch('downtime_tindakan_user', $data_users);
        }



        $this->session->set_flashdata('message', '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Berhasil Diupdate!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');


        redirect('mesin/view/' . $id_halaman);
    }

    public function hapus_tindakan($id, $id_halaman)
    {
        $hasil = $this->Mesin_model->hapus_tindakan($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('mesin/view/' . $id_halaman);
    }
    public function view_tindakan($id)
    {
        $data['detail'] = $this->Mesin_model->getdataByid_tindakan($id);
        $data['user'] = $this->db->get_where('downtime_tindakan_user', ['id_tindakan' => $id])->result_array();
        $this->load->view('mesin/view_tindakan', $data);
    }


    public function teknisi()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->session->userdata('filter_dept');
        $result = $this->Mesin_model->getTeknisiLike($inputan, $filter_dept);
        echo json_encode($result);
    }
}
