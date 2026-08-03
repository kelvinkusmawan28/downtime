<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instruksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Instruksi_model');

        is_logged_in();

        cek_akses_gi();
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
        $this->db->where_in('status', ['0', '2']);
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
            // $row['kerusakan'] = !empty($row['ins_kode']) ? $row['ins_kode'] : 'PETUGAS BELUM ADA ' . $row['remark'];
            $row['kerusakan'] = !empty($row['ins_kode'])
                ? $row['ins_kode'] . ' - ' . $row['remark']
                : 'PETUGAS BELUM ADA - ' . $row['remark'];
            $row['instruksi'] = '<a class = "text-info" style ="text-decoration: underline;" href="' . base_url() . 'instruksi/view/' . $row['id'] . '">' . ($row['keterangan'] ?? '') . '</a>';
            $row['user'] = ($row['user']) ?? '';

            $id = $row['id'];
            $status_value = $row['status'];


            if ($status_value == 2) {

                $row['status'] = '
                    <span class="badge bg-light text-danger">Dalam Antrian</span><br>
                ';
                $cekdowntime = $this->session->userdata('cekdowntime');
                $aksi = '<a href="#" 
                            class="btn btn-sm btn-warning text-dark edit" 
                            title="Kerjakan Instruksi" 
                            data-id="' . $id . '">
                                Kerjakan
                        </a>
                    
                    ';
                if ($cekdowntime == '1') {

                    $aksi = '
                             <a href="#" 
                                class="btn btn-sm btn-warning text-dark edit" 
                                title="Kerjakan Instruksi" 
                                data-id="' . $id . '">
                                    Kerjakan
                            </a>
                        
                                <a class="btn btn-sm btn-danger text-dark hapus"
                                    data-id="' . $row['id'] . '"
                                    data-url="' . base_url() . 'instruksi/hapus/' . $row['id'] . '" 
                                    href="#">
                                    Hapus
                                </a>
                            ';
                }

                $row['aksi'] = $aksi;
            } else if ($status_value == 0) {

                $start_time = strtotime($row['kerusakan_mulai']) * 1000;

                $row['status'] = '
                    <span class="text-dark">Progres..</span><br>
                    <small class= "text-dark" style = "font-size :9px;">' . format_tanggal_indonesia_waktu($row['kerusakan_mulai']) . '</small><br>
                    <span class="updateon text-danger" data-start="' . $start_time . '  " style = "font-size :12px; " >Loading...</span>
                ';

                $path_files = json_decode($row['path_file'], true);
                $file_names = json_decode($row['file'], true);


                $data_files = htmlspecialchars(json_encode([
                    'paths' => $path_files,
                    'names' => $file_names
                ]), ENT_QUOTES, 'UTF-8');

                $cekdowntime = $this->session->userdata('cekdowntime');

                // default (kalau tidak punya hak)
                $aksi = '<a class="btn btn-sm btn-info text-dark lihat-file" 
                        data-files=\'' . $data_files . '\'>
                        <i class="fa-regular fa-eye"></i>    Lihat Instruksi
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

                    // tombol selesai hanya kalau ada downtime
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
                    <span class="badge bg-danger" style = "font-size :10px;">CLOSE</span><br>
                    <small class ="text-dark" style = "font-size :10px;">' . $name .
                    '</small><br>
                    <small class ="text-danger" style = "font-size :10px ;">Downtime ' . format_downtime($downtime) . '</small>

                ';


                // ================= AKSI SAAT STATUS = 1 =================
                $path_files = json_decode($row['path_file'], true);
                $file_names = json_decode($row['file'], true);

                $data_files = htmlspecialchars(json_encode([
                    'paths' => $path_files,
                    'names' => $file_names
                ]), ENT_QUOTES, 'UTF-8');

                $row['aksi'] = '
                    <a class="btn btn-sm btn-light text-dark lihat-file" 
                        data-files=\'' . $data_files . '\'>
                       Lihat Instruksi
                    </a>
                
                ';
            }
        }



        $this->db->from('downtime');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');

        $this->db->where('downtime.ins', 1);
        $this->db->where_in('status', ['0', '2']);


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
        $this->db->where_in('status', ['0', '2']);
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
    public function edit($id)
    {
        $data['detail'] = $this->db->get_where('downtime', ['id' => $id])->row_array();
        $this->load->view('instruksi/edit', $data);
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
                instruksi sebelumnya di mesin ini sedang progress atau antri. 
                Mohon lakukan pengecekan terlebih dahulu. Terimkakasih</p>
                <hr />
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            ');
            redirect('instruksi');
        }
        $data = [
            'nomesin_id' => $post['mach_id'],
            'kerusakan_id' => $post['rusak_id'],
            'tanggal' => $post['tanggal'],
            'dept_id' => $post['dept_id'],
            'status' => 2,
            'ins' => 1,

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
                    redirect('instruksi');
                    return;
                }
            }
        }

        $post = $this->input->post();

        $mesin = $post['mach_id'];
        $cek_mesin = $this->Instruksi_model->CekMesin_update($mesin);


        if ($cek_mesin) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-danger alert-dismissible" role="alert">
                <h4 class="alert-heading d-flex align-items-center flex-wrap gap-1">
                <span class="alert-icon rounded-circle"><i class="icon-base bx bx-error"></i></span>Error!!
                </h4>
                <p><b>DATA TIDAK DAPAT DISIMPAN !</b> 
                instruksi sebelumnya di mesin ini sedang progress . 
                Mohon lakukan pengecekan terlebih dahulu. Terimkakasih</p>
                <hr />
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            ');
            redirect('instruksi');
        }
        $data = [
            'user' => $this->session->userdata('name'),
            'kerusakan_mulai' => date('Y-m-d H:i'),
            'status' => 0,
            'ins' => 1,
            'keterangan' => $post['keterangan'],
            'file' => $is_file_uploaded ? json_encode($file_names) : null,
            'file_type' => $is_file_uploaded ? json_encode($file_types) : null,
            'path_file' => $is_file_uploaded ? json_encode($file_paths) : null,
        ];

        $this->db->where('id', $id);
        $query = $this->db->update('downtime', $data);

        if ($query) {
            $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Insturksi Berhasil Ditambahkan!
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
    public function hapus_tindakan($id)
    {
        $hasil = $this->Instruksi_model->hapus_tindakan_gi($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Teriset!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('instruksi/view/' . $id);
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

    public function status_cansel($id)
    {
        $query = $this->Instruksi_model->Status_cansel($id);
        if ($query) {
            $this->session->set_flashdata('message', '
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            Status Data Kembali Di Buka!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');
        }
        redirect('instruksi/report');
    }

    public function view($id)
    {
        $data['title'] = 'Tindakan Petugas Ganti Instruksi';
        $data['tgl_sekarang'] = date('Y-m-d');
        $data['detail'] = $this->Instruksi_model->getdataByid($id);

        $cek_all = $this->Instruksi_model->GetTindakan($id);

        // ambil 1 baris pertama (untuk form)
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

        // 🔥 tampung semua ID hasil insert
        $insert_ids = [];

        // ✅ 1. Simpan checklist ke downtime_tindakan
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

                // 🔥 simpan semua ID (INI KUNCI NYA)
                $insert_ids[] = $this->db->insert_id();
            }
        }

        // ✅ 2. Ambil teknisi
        $users_json = $this->input->post('teknisi');
        $users = json_decode($users_json, true);

        // ✅ 3. Simpan teknisi ke setiap tindakan
        if (!empty($users) && !empty($insert_ids)) {
            $data_users = [];

            foreach ($insert_ids as $dt_id) {
                foreach ($users as $user) {
                    $data_users[] = [
                        'id_tindakan' => $dt_id, // 🔥 relasi ke hasil insert
                        'user' => isset($user['value']) ? $user['value'] : $user
                    ];
                }
            }

            $this->db->insert_batch('downtime_tindakan_user', $data_users);
        }

        // ✅ notifikasi
        $this->session->set_flashdata('message', '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Kerusakan Berhasil Di Tambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        ');

        redirect('instruksi/view/' . $id);
    }

    public function teknisi()
    {
        $inputan = $this->input->get('term');
        $filter_dept = $this->session->userdata('filter_dept');
        $result = $this->Instruksi_model->getTeknisiLike($inputan, $filter_dept);
        echo json_encode($result);
    }


    public function report()
    {
        $data['title'] = 'Laporan Ganti Instruksi';
        $data['tgl_sekarang'] = date('Y-m-d');

        $data['dept_options'] = $this->Instruksi_model->getFilter();
        $data['filter_status'] = $this->session->userdata('filter_status') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';
        $data['filter_tanggal'] = $this->session->userdata('filter_tanggal');

        $data['downtime_dept_map'] = [
            // 1 => 'FN',
            2 => 'NT',
            // 3 => 'RR',
            // 4 => 'SP',
            // 5 => 'UT',
            // 6 => 'GF',
            7 => 'AR',
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('instruksi/report', $data);
        $this->load->view('templates/footer');
    }

    public function filter_report()
    {

        $dept_id = $this->input->post('dept_id');
        $tanggal = $this->input->post('tanggal');
        $status = $this->input->post('status');

        $this->session->set_userdata('filter_tanggal', $tanggal);
        $this->session->set_userdata('filter_status', $status);
        $this->session->set_userdata('filter_dept', $dept_id);
        $tanggal_aktif = !empty($tanggal) ? $tanggal : date('Y-m-d');
        $total = $this->Instruksi_model->getTotal($dept_id);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];


        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            // 1 => 'FN',
            2 => 'NT',
            // 3 => 'RR',
            // 4 => 'SP',
            // 5 => 'UT',
            // 6 => 'GF',
            7 => 'AR',
        ];

        $akses_dept = [];


        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, user.username, user.name, downtime_tindakan.id_downtime,
        GROUP_CONCAT(DISTINCT downtime_tindakan_user.user) as nama_petugas');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');
        $this->db->join('downtime_spekmesin', 'downtime_spekmesin.mach_id = downtime.nomesin_id', 'left');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('user', 'user.id = downtime.status_by', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');


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



        if (!empty($tanggal)) {
            $this->db->where('date(downtime.tanggal)', $tanggal);
        }



        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }
        $this->db->where('downtime.ins', 1);
        $this->db->group_by('downtime.id');
        $this->db->order_by('downtime.id', 'DESC');
        $this->db->limit($limit, $start);
        $data = $this->db->get()->result_array();

        //  view
        $no = $start + 1;
        foreach ($data as &$row) {

            $path_files = json_decode($row['path_file'], true);
            $file_names = json_decode($row['file'], true);

            $data_files = htmlspecialchars(json_encode([
                'paths' => $path_files,
                'names' => $file_names
            ]), ENT_QUOTES, 'UTF-8');



            $row['no'] = ' <a class=" status_cansel text-dark"
                                data-id="' . $row['id'] . '"
                                data-url="' . base_url() . 'instruksi/status_cansel/' . $row['id'] . '"
                                href="#">' . $no++ . '</a>';
            $row['nomesin'] = '<span style="color:black;">' . 'Mesin '  . $row['mach_no'] . '</span>' . '<br><span style="color:black;">' . $row['mach_name'] . '</span>';
            $row['instruksi'] = 'Instruksi . 
                                <a href="#" 
                                class="lihat-file" 
                                data-files=\'' . $data_files . '\' 
                                style="color:blue; text-decoration: underline;">
                                ' . strtoupper($row['keterangan']) . '
                                </a>
                                <br>
                                <span style="color:black;">' . $row['remark'] . '</span>';
            $row['mulai']   = '<span style="color:black;">' .
                (!empty($row['kerusakan_mulai'])
                    ? date('H:i', strtotime($row['kerusakan_mulai']))
                    : ''
                )
                . '</span>';

            $row['selesai'] =  '<span style="color:black;">' .
                (!empty($row['kerusakan_selesai'])
                    ? date('H:i', strtotime($row['kerusakan_selesai']))
                    : ''
                )
                . '</span>';
            $id = $row['id'];
            $status_value = $row['status'];
            if ($status_value == 2) {
                $row['status'] = '
                <span class=" text-dark">Waiting List .. </span><br>
              ';
            } else if ($status_value == 0) {

                $start_time = strtotime($row['kerusakan_mulai']) * 1000;

                $row['status'] = '
                        <span class= "text-dark" >Progres ..</span><br>
                        <small class="font-bold text-dark" style="font-size: 10px;">' . format_tanggal_indonesia_waktu($row['kerusakan_mulai']) . '</small><br>
                        <span class="font-bold text-success updateon" style="font-size: 10px;" data-start="' . $start_time . '">Loading...</span>
                    ';
            } else {
                $name = $row['name'] ?? '-';
                $row['status'] = '
                    <span"><i class="fa-solid fa-check text-danger"></i>  <span class=" text-dark">Selesai </span></span><br> 
                    <small class="font-bold" style="font-size:12px; color:red;">' .
                    'Downtime ' . format_downtime($row['downtime_kerusakan'])  .
                    '</small> <br> <span class="text-dark">Pemeriksa : ' . $name . '</span>';
            }
            $row['petugas'] = '<span style="color:black;">' . $row['nama_petugas'] . '</span>';
        }


        $this->db->select('downtime.*, dept.dept_id, dept.departemen, downtime_spekmesin.mach_no, downtime_spekmesin.mach_name, downtime_kerusakan.remark, user.username, user.name, downtime_tindakan.id_downtime');
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

        if (!empty($tanggal)) {
            $this->db->where('date(downtime.tanggal)', $tanggal);
        }


        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }
        $this->db->group_by('downtime.id');
        $this->db->where('downtime.ins', 1);
        $recordsFiltered = $this->db->get()->num_rows();




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

        if (!empty($tanggal)) {
            $this->db->where('date(downtime.tanggal)', $tanggal);
        }


        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_kerusakan.remark', $search);
            $this->db->group_end();
        }

        $this->db->from('downtime');
        $this->db->where('downtime.ins', 1);

        $recordsTotal = $this->db->count_all_results();

        $this->db->select('
        SUM(CASE WHEN downtime.status = 0 THEN 1 ELSE 0 END) as total_progres,
        SUM(CASE WHEN downtime.status = 1 THEN 1 ELSE 0 END) as total_selesai,
        SUM(CASE WHEN downtime.status = 2 THEN 1 ELSE 0 END) as total_waiting
     ');
        $this->db->from('downtime');
        $this->db->join('dept', 'dept.dept_id = downtime.dept_id', 'left');


        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($tanggal)) {
            $this->db->where('date(downtime.tanggal)', $tanggal);
        }

        $this->db->where('downtime.ins', 1);

        $count_status = $this->db->get()->row_array();


        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,

            'total_progres' => $count_status['total_progres'],
            'total_selesai' => $count_status['total_selesai'],
            'total_waiting' => $count_status['total_waiting'],


            'total_all' => $total['total'],
            'total_progres_all' => $total['progress'],
            'total_selesai_all' => $total['selesai'],
            'total_waiting_all' => $total['antri'],

            'tanggal_aktif' => format_tanggal_indonesia($tanggal_aktif)
        ]);
    }

    public function detail_waiting()
    {
        $dept = $this->input->post('dept_id');
        $status = $this->input->post('status');
        $data['header'] = $this->db->get_where('dept', ['dept_id' => $dept])->row_array();
        $data['detail'] = $this->Instruksi_model->getWaiting($dept, $status);
        $this->load->view('instruksi/waiting', $data);
    }
    public function detail_progres()
    {
        $dept = $this->input->post('dept_id');
        $status = $this->input->post('status');
        $data['header'] = $this->db->get_where('dept', ['dept_id' => $dept])->row_array();
        $data['detail'] = $this->Instruksi_model->getProgres($dept, $status);
        $this->load->view('instruksi/progres', $data);
    }



    public function petugas()
    {
        $data['title'] = 'Laporan  Ganti Instruksi';
        $data['dept_options'] = $this->Instruksi_model->getFilter();
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Instruksi_model->getBulan();
        $data['tahun_options'] = $this->Instruksi_model->getTahun();


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
            7 => 'AR',
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('instruksi/petugas', $data);
        $this->load->view('templates/footer');
    }


    public function filter_petugas()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw  = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        $this->session->set_userdata('filter_dept', $dept_id);
        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);

        // akses dept
        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            2 => 'NT',
            7 => 'AR',
        ];

        $akses_dept = [];
        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        // =========================
        // QUERY UTAMA (ANTI DUPLICATE)
        // =========================
        $this->db->select("
            downtime_tindakan_user.user AS nama,
    
            COUNT(DISTINCT CASE WHEN downtime_kerusakan.ins_kode = 'GM' THEN downtime.id END) AS gm,
            COUNT(DISTINCT CASE WHEN downtime_kerusakan.ins_kode = 'GB' THEN downtime.id END) AS gb,
            COUNT(DISTINCT CASE WHEN downtime_kerusakan.ins_kode = 'GI' THEN downtime.id END) AS gi
        ");

        $this->db->from('downtime');

        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        // filter dept
        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        // filter bulan & tahun
        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        // search
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_tindakan_user.user', $search);
            $this->db->group_end();
        }

        $this->db->where('downtime.ins', 1);
        $this->db->where('downtime_tindakan_user.user IS NOT NULL', null, false);

        // IMPORTANT FIX: group per user
        $this->db->group_by('downtime_tindakan_user.user');

        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();

        // =========================
        // FORMAT OUTPUT
        // =========================
        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['nama'] = '<span style="color:black;">' . $row['nama'] . '</span>';
            $row['gm'] = (int)$row['gm'];
            $row['gm'] = (int)$row['gm'];
            $row['gb'] = (int)$row['gb'];
            $row['gi'] = (int)$row['gi'];

            $row['total'] = $row['gm'] + $row['gb'] + $row['gi'];
        }
        // usort($data, function ($a, $b) {
        //     return $b['total'] <=> $a['total']; // DESC (terbesar ke terkecil)
        // });

        // =========================
        // FILTERED COUNT (FIX)
        // =========================
        $this->db->select("COUNT(DISTINCT downtime_tindakan_user.user) AS total");
        $this->db->from('downtime');
        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        $this->db->where('downtime.ins', 1);
        $this->db->where('downtime_tindakan_user.user IS NOT NULL', null, false);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        $recordsFiltered = $this->db->get()->row()->total;

        // =========================
        // TOTAL COUNT (FIX)
        // =========================
        $this->db->select("COUNT(DISTINCT downtime_tindakan_user.user) AS total");
        $this->db->from('downtime');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        $this->db->where('downtime.ins', 1);
        $this->db->where('downtime_tindakan_user.user IS NOT NULL', null, false);

        $recordsTotal = $this->db->get()->row()->total;

        // =========================
        // RESPONSE
        // =========================
        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
    public function gi_terbanyak()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');

        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            2 => 'NT',
            7 => 'AR',
        ];

        $akses_dept = [];
        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }

        $this->db->select("
        downtime_tindakan_user.user,
        COUNT(DISTINCT downtime.id) as total
     ");

        $this->db->from('downtime');

        $this->db->join('downtime_kerusakan', 'downtime_kerusakan.rusak_id = downtime.kerusakan_id', 'left');
        $this->db->join('downtime_tindakan', 'downtime_tindakan.id_downtime = downtime.id', 'left');
        $this->db->join('downtime_tindakan_user', 'downtime_tindakan_user.id_tindakan = downtime_tindakan.id', 'left');

        $this->db->where('downtime.ins', 1);
        $this->db->where('downtime.status', 1);
        $this->db->where('downtime_tindakan_user.user IS NOT NULL', null, false);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime.dept_id', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime.dept_id', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(downtime.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(downtime.tanggal)', $tahun);
        }

        $this->db->group_by('downtime_tindakan_user.user'); // ✅ INI YANG BENAR
        $this->db->order_by('total', 'DESC');
        // $this->db->limit(15);

        $result = $this->db->get()->result_array();
        echo json_encode($result);
    }
}
