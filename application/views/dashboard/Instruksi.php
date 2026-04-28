<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instruksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Instruksi_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Instruksi Mesin';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');

        $data['bulan_options'] = $this->Instruksi_model->getBulan();
        $data['tahun_options'] = $this->Instruksi_model->getTahun();
        $data['dept_options'] = $this->Instruksi_model->getFilter();

        $data['filter_status'] = $this->session->userdata('filter_status') ?? 'all';
        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';
        $data['downtime_dept_map'] = [
            // 1 => 'FN',
            2 => 'NT',
            // 3 => 'RR',
            // 4 => 'SP',
            // 5 => 'UT',
            // 6 => 'GF',
            7 => 'AR'
        ];


        $this->load->view('templates/header', $data);
        $this->load->view('instruksi/index', $data);
        $this->load->view('templates/footer');
    }


    public function filter_instruksi()
    {

        $dept_id = $this->input->post('dept_id');
        $status  = $this->input->post('status');
        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');


        $this->session->set_userdata('filter_status', $status);
        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);
        $this->session->set_userdata('filter_dept', $dept_id);


        $limit = $this->input->post('length') ?? 100;
        $start = $this->input->post('start') ?? 0;
        $draw  = $this->input->post('draw') ?? 0;


        $searchPost = $this->input->post('search');
        $search = isset($searchPost['value']) ? $searchPost['value'] : '';


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

        $this->db->select('
            downtime.*,
            dept.departemen,
            downtime_spekmesin.mach_no,
            downtime_spekmesin.mach_name,
            downtime_kerusakan.remark,
            downtime_kerusakan.ins_kode,
            user.username,
            user.name,
            downtime_tindakan.id_downtime
        ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('user', 'user.id = downtime.status_by', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');


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

        $this->db->where('downtime.ins', 1);
        $this->db->group_by('downtime.id');
        $this->db->order_by('downtime.id', 'DESC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();


        $no = $start + 1;
        foreach ($data as &$row) {

            $row['no'] = $no++;
            $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);
            $row['departemen'] = ($row['departemen'] == 'FINISHED GOODS') ? 'GUDANG' : $row['departemen'];
            $row['mesin'] = 'Mesin ' . $row['mach_no'] . '<br>' . $row['mach_name'];
            $row['kode'] = $row['dept_id'];
            $row['kerusakan'] = $row['ins_kode'] . '-' . $row['remark'];
            $row['instruksi'] = '<a class = "text-info" style ="text-decoration: underline;" href="' . base_url() . 'instruksi/view/' . $row['id'] . '">' . ($row['keterangan'] ?? '-') . '</a>';
            $row['user'] = $row['user'];

            $id = $row['id'];
            $status_value = $row['status'];


            if ($status_value == 0) {

                $start_time = strtotime($row['kerusakan_mulai']) * 1000;

                $row['status'] = '
                    <span class="badge bg-info">PROGRES</span><br>
                    <small class= "text-dark" style = "font-size :10px;">' . format_tanggal_indonesia_waktu($row['kerusakan_mulai']) . '</small><br>
                    <span class="updateon text-success" data-start="' . $start_time . '  " style = "font-size :8px; " >Loading...</span>
                ';

                $path_files = json_decode($row['path_file'], true);
                $file_names = json_decode($row['file'], true);


                $data_files = htmlspecialchars(json_encode([
                    'paths' => $path_files,
                    'names' => $file_names
                ]), ENT_QUOTES, 'UTF-8');

                $cekdowntime = $this->session->userdata('cekdowntime');


                $aksi = '<a class="btn btn-sm btn-info text-dark lihat-file" 
                        data-files=\'' . $data_files . '\'>
                      Lihat Instruksi
                      </a>';

                if ($cekdowntime == '1') {

                    $aksi = '
                        <a class="btn btn-sm btn-info text-dark lihat-file" 
                            data-files=\'' . $data_files . '\'>
                          Instruksi
                        </a>
                
                        <a class="btn btn-sm btn-danger text-dark hapus"
                            data-id="' . $row['id'] . '"
                            data-url="' . base_url() . 'instruksi/hapus/' . $row['id'] . '" 
                            href="#">
                            Hapus
                        </a>
                    ';


                    if (!empty($row['id_downtime'])) {
                        $aksi .= '
                            <a class="btn btn-sm btn-success font-kecil status text-dark" 
                                data-id="' . $row['id'] . '" 
                                data-url="' . base_url() . 'instruksi/status_ok/' . $row['id'] . '" 
                                href="#">
                                Selesai
                            </a>
                        ';
                    }
                }

                $row['aksi'] = $aksi;
            } else {

                $name = $row['name'] ?? '-';
                $status_on = $row['status_on'] ?? null;
                $downtime = $row['downtime_kerusakan'] ?? 0;

                $row['status'] = '
                    <span class="badge bg-success">CLOSE</span><br>
                    <small class ="text-dark" style = "font-size :12px;">' . $name . '<br>' .
                    ($status_on ? format_tanggal_indonesia_waktu($status_on) : '-') .
                    '</small><br>
                    <small class ="text-danger" style = "font-size :12px ;">Downtime ' . format_downtime($downtime) . '</small>



                ';



                $path_files = json_decode($row['path_file'], true);
                $file_names = json_decode($row['file'], true);

                $data_files = htmlspecialchars(json_encode([
                    'paths' => $path_files,
                    'names' => $file_names
                ]), ENT_QUOTES, 'UTF-8');

                $row['aksi'] = '
                    <a class="btn btn-sm btn-light lihat-file" style = "color :#1e90ff; "
                        data-files=\'' . $data_files . '\'>
                       Lihat Instruksi
                    </a>
                
                ';
            }
        }



        $this->db->from('downtime');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');

        $this->db->where('downtime.ins', 1);


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


        $this->db->group_by('downtime.id');

        $recordsFiltered = $this->db->count_all_results();


        $this->db->from('downtime');
        $this->db->where('downtime.ins', 1);
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
        $this->load->view('instruksi/add', $data);
    }


    public function nomor_mesin()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->input->get('filter');

        $result = $this->Instruksi_model->getMesinLike($inputan, $filter_dept);
        echo json_encode($result);
    }

    public function kerusakan_mesin()
    {
        $kerusakan = $this->input->get('kerusakan');
        $filter_dept = $this->input->get('filter');
        $result = $this->Instruksi_model->getKerusakanLike($kerusakan, $filter_dept);
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
        $cek_mesin = $this->Instruksi_model->CekMesin($mesin);


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
            redirect('instruksi');
        }
        $data = [
            'nomesin_id' => $post['mach_id'],
            'tanggal' => $post['tanggal'],
            'kerusakan_id' => $post['rusak_id'],
            'dept_id' => $post['dept_id'],
            'kerusakan_mulai' => date('Y-m-d H:i'),
            'status' => 0,
            'ins' => 1,
            'keterangan' => $post['keterangan'],
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

        redirect('instruksi');
    }

    public function kesimpulan_detail($id)
    {
        $data['detail'] = $this->Instruksi_model->getdataByid($id);
        $data['id_halaman'] = $id;
        $this->load->view('instruksi/kesimpulan_detail', $data);
    }

    public function update_kesimpulan_detail($id_halaman)
    {
        $query = $this->Instruksi_model->UpdateData_kesimpulan();
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Kesimpulan Berhasil Di Tambahkan!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('instruksi/view/' . $id_halaman);
    }


    public function hapus($id)
    {
        $hasil = $this->Instruksi_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('instruksi');
    }

    public function status_ok($id)
    {
        $query = $this->Instruksi_model->Status_ok($id);
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Status Perbaikan Berhasil Di Close!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('instruksi');
    }

    // public function status_cansel($id)
    // {
    //     $query = $this->Mesin_model->Status_cansel($id);
    //     if ($query) {
    //         $this->session->set_flashdata('message', '
    //                     <div class="alert alert-warning alert-dismissible fade show" role="alert">
    //                         Status Data Kembali Progres!
    //                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //                  </div>
    //                 ');
    //     }
    //     redirect('mesin');
    // }

    public function view($id)
    {
        $data['title'] = 'Tindakan Petugas Ganti Instruksi';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['detail'] = $this->Instruksi_model->getdataByid($id);

        $cek_all = $this->Instruksi_model->GetTindakan($id);


        $data['cek'] = !empty($cek_all) ? $cek_all[0] : [];
        $data['sudah_ada'] = !empty($data['cek']);
        // untuk checkbox
        $checked = array_column($cek_all, 'id_tindakan');
        $data['checked'] = $checked;
        $data['tindakan'] = $this->Instruksi_model->getKategoriDenganTindakan();
        $data['user'] = $this->Instruksi_model->getpetugas($id);
        $data['id_halaman'] = $id;

        $this->load->view('templates/header', $data);
        $this->load->view('instruksi/detail', $data);
        $this->load->view('templates/footer');
    }


    public function simpan_tindakan($id)
    {
        $post = $this->input->post();
        $tindakan = isset($post['tindakan']) ? $post['tindakan'] : [];


        $insert_ids = [];


        if (!empty($tindakan)) {
            foreach ($tindakan as $t) {
                $data = [
                    'id_downtime'      => $id,
                    'tanggal'          => $post['tanggal'],
                    'kakesu_sebelum'   => $post['kakesu_sebelum'],
                    'kakesu_baru'      => $post['kakesu_baru'],
                    'bs_id'            => $post['bs_id'],
                    'bb_id'            => $post['bb_id'],
                    'id_tindakan'      => $t,
                    'user'             => $this->session->userdata('name'),
                ];

                $this->db->insert('downtime_tindakan', $data);


                $insert_ids[] = $this->db->insert_id();
            }
        }


        $users_json = $this->input->post('teknisi');
        $users = json_decode($users_json, true);


        if (!empty($users) && !empty($insert_ids)) {
            $data_users = [];

            foreach ($insert_ids as $dt_id) {
                foreach ($users as $user) {
                    $data_users[] = [
                        'id_tindakan' => $dt_id,
                        'user' => isset($user['value']) ? $user['value'] : $user
                    ];
                }
            }

            $this->db->insert_batch('downtime_tindakan_user', $data_users);
        }


        $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Kerusakan Berhasil Di Tambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        ');

        redirect('instruksi/view/' . $id);
    }

    // public function edit_tindakan($id, $id_halaman)
    // {

    //     $data['detail'] = $this->Mesin_model->getdataByid_tindakan($id);
    //     $data['id_halaman'] = $id_halaman;
    //     $data['user'] = $this->db->get_where('downtime_tindakan_user', ['id_tindakan' => $id])->result_array();
    //     $this->load->view('mesin/edit_tindakan', $data);
    // }



    // public function update_tindakan($id, $id_halaman)
    // {
    //     $config['upload_path'] = 'assets/img/upload/';
    //     $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
    //     $config['max_size'] = 20240; // 20 MB
    //     $this->load->library('upload', $config);

    //     // Ambil data lama dari database
    //     $detail = $this->Mesin_model->getdataByid_tindakan($id);
    //     $old_files = !empty($detail['file']) ? json_decode($detail['file'], true) : [];
    //     $old_file_paths = !empty($detail['path_file']) ? json_decode($detail['path_file'], true) : [];
    //     $old_file_types = !empty($detail['file_type']) ? json_decode($detail['file_type'], true) : [];

    //     //  File yang user ingin hapus
    //     $hapus_file = $this->input->post('hapus_file') ?? [];

    //     // dihapus dari array lama
    //     $filtered_old_files = [];
    //     $filtered_file_paths = [];
    //     $filtered_file_types = [];

    //     foreach ($old_files as $i => $filename) {
    //         if (!in_array($filename, $hapus_file)) {
    //             $filtered_old_files[] = $filename;
    //             if (isset($old_file_paths[$i])) $filtered_file_paths[] = $old_file_paths[$i];
    //             if (isset($old_file_types[$i])) $filtered_file_types[] = $old_file_types[$i];
    //         }
    //     }

    //     // Hapus file dari folder
    //     foreach ($hapus_file as $file) {
    //         $path = 'assets/img/upload/' . $file;
    //         if (file_exists($path)) {
    //             unlink($path);
    //         }
    //     }

    //     // rename
    //     $rename_files = $this->input->post('rename_file');
    //     $nama_file_asli = $this->input->post('nama_file_asli');

    //     if ($rename_files && $nama_file_asli) {
    //         foreach ($rename_files as $i => $rename) {
    //             $old_name = $nama_file_asli[$i];


    //             if (!empty($rename) && in_array($old_name, $filtered_old_files)) {
    //                 $old_path = 'assets/img/upload/' . $old_name;

    //                 $ext = pathinfo($old_name, PATHINFO_EXTENSION);
    //                 $rename_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($rename, PATHINFO_FILENAME));
    //                 $new_name = $rename_clean . '.' . $ext;
    //                 $new_path = 'assets/img/upload/' . $new_name;

    //                 if (file_exists($old_path)) {
    //                     rename($old_path, $new_path);

    //                     // Update di array lama
    //                     $key = array_search($old_name, $filtered_old_files);
    //                     if ($key !== false) {
    //                         $filtered_old_files[$key] = $new_name;
    //                         $filtered_file_paths[$key] = $new_path;
    //                     }
    //                 }
    //             }
    //         }
    //     }


    //     $files = $_FILES['file_upload'];
    //     $new_files = [];
    //     $new_file_paths = [];
    //     $new_file_types = [];

    //     if (!empty($files['name'][0])) {
    //         for ($i = 0; $i < count($files['name']); $i++) {
    //             if (!empty($files['name'][$i])) {
    //                 $_FILES['file_upload_temp']['name'] = $files['name'][$i];
    //                 $_FILES['file_upload_temp']['type'] = $files['type'][$i];
    //                 $_FILES['file_upload_temp']['tmp_name'] = $files['tmp_name'][$i];
    //                 $_FILES['file_upload_temp']['error'] = $files['error'][$i];
    //                 $_FILES['file_upload_temp']['size'] = $files['size'][$i];

    //                 $this->upload->initialize($config);

    //                 if ($this->upload->do_upload('file_upload_temp')) {
    //                     $upload_data = $this->upload->data();
    //                     $new_files[] = $upload_data['file_name'];
    //                     $new_file_paths[] = $config['upload_path'] . $upload_data['file_name'];
    //                     $new_file_types[] = $upload_data['file_type'];
    //                 } else {
    //                     $error = $this->upload->display_errors();
    //                     $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
    //                     redirect('mesin/view/' . $id_halaman);
    //                     return;
    //                 }
    //             }
    //         }
    //     }

    //     // Gabungkan semua file
    //     $all_files = array_merge($filtered_old_files, $new_files);
    //     $all_file_paths = array_merge($filtered_file_paths, $new_file_paths);
    //     $all_file_types = array_merge($filtered_file_types, $new_file_types);

    //     $post = $this->input->post();

    //     $jammulai = $post['jam_start'];
    //     $jamselesai = $post['jam_end'];

    //     $start = strtotime($jammulai);
    //     $end = strtotime($jamselesai);


    //     if ($end < $start) {
    //         $end = strtotime('+1 day', $end);
    //     }

    //     $selisihDetik = $end - $start;
    //     $downtimeMenit = round($selisihDetik / 60);

    //     $data = [
    //         'tanggal' => $this->input->post('tanggal'),
    //         'tindakan' => $this->input->post('tindakan'),
    //         'jam_start' => $this->input->post('jam_start'),
    //         'jam_end' => $this->input->post('jam_end'),
    //         'downtime' => $downtimeMenit,
    //         'file' => !empty($all_files) ? json_encode(array_values($all_files)) : null,
    //         'file_type' => !empty($all_file_types) ? json_encode(array_values($all_file_types)) : null,
    //         'path_file' => !empty($all_file_paths) ? json_encode(array_values($all_file_paths)) : null,
    //     ];

    //     $this->db->where('id', $id);
    //     $this->db->update('downtime_tindakan', $data);
    //     // hapus teknisi
    //     $this->db->where('id_tindakan', $id);
    //     $this->db->delete('downtime_tindakan_user');

    //     $users_json = $this->input->post('teknisi');
    //     $users = json_decode($users_json, true);

    //     if (!empty($users)) {
    //         $data_users = [];
    //         foreach ($users as $user) {
    //             $data_users[] = [
    //                 'id_tindakan' => $id,
    //                 'user' => isset($user['value']) ? $user['value'] : $user
    //             ];
    //         }
    //         $this->db->insert_batch('downtime_tindakan_user', $data_users);
    //     }



    //     $this->session->set_flashdata('message', '
    //     <div class="alert alert-success alert-dismissible fade show" role="alert">
    //         Berhasil Diupdate!
    //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>');


    //     redirect('mesin/view/' . $id_halaman);
    // }

    // public function hapus_tindakan($id, $id_halaman)
    // {
    //     $hasil = $this->Mesin_model->hapus_tindakan($id);
    //     if ($hasil) {
    //         $this->session->set_flashdata('message', '
    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
    //         Data Berhasil Terhapus!
    //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //     </div>');
    //     }
    //     redirect('mesin/view/' . $id_halaman);
    // }
    // public function view_tindakan($id)
    // {
    //     $data['detail'] = $this->Mesin_model->getdataByid_tindakan($id);
    //     $data['user'] = $this->db->get_where('downtime_tindakan_user', ['id_tindakan' => $id])->result_array();
    //     $this->load->view('mesin/view_tindakan', $data);
    // }


    public function teknisi()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->session->userdata('filter_dept');
        $result = $this->Instruksi_model->getTeknisiLike($inputan, $filter_dept);
        echo json_encode($result);
    }
}
