<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spekmesin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Spekmesin_model');

        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Master Data Spekmesin';
        $data['dept_options'] = $this->Spekmesin_model->getFilter();
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';


        $data['downtime_dept_map'] = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
            6 => 'AR',
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('spekmesin/index', $data);
        $this->load->view('templates/footer');
    }
    public function reset_filter()
    {
        $this->session->unset_userdata('filter_dept');
        redirect('spekmesin');
    }

    public function filter_spek()
    {
        $dept_id = $this->input->post('dept_id');

        $this->session->set_userdata('filter_dept', $dept_id);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

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

        $this->db->from('downtime_spekmesin');

        if ($dept_id !== 'all' && !empty($dept_id)) {

            $this->db->where('downtime_spekmesin.dept_kode', $dept_id);
        } else {

            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_spekmesin.dept_kode', $akses_dept);
            } else {

                $this->db->where('1=0');
            }
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_spekmesin.mach_name', $search);
            $this->db->group_end();
        }

        $this->db->order_by('downtime_spekmesin.mach_id', 'DESC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();


        $no = $start + 1;
        foreach ($data as &$row) {
            $row['kodemesin'] =  $row['mach_id'];
            $row['nomesin'] = '<span>' . 'Mesin '  . $row['mach_no'] . '</span>' . '<br><span  style="color:red;">' . $row['name'] . '</span>';
            $row['spekmesin'] = $row['mach_name'];
            $row['groupmesin'] = $row['preventif_kode'];
            $row['alsmesin'] = $row['als'];
            $row['kapasitas'] = $row['kapasitas'];
            $row['statusmesin'] = $row['idle'] == 0 ? ' <i class="fa-solid fa-check text-info"></i> Aktif ' : '<i class="fa-solid fa-xmark text-danger"> </i> Tidak Aktif';
            $id = $row['mach_id'];

            $path_files = json_decode($row['path_file_spek'], true);
            $file_names = json_decode($row['file_spek'], true);


            $data_files = htmlspecialchars(json_encode([
                'paths' => $path_files,
                'names' => $file_names
            ]), ENT_QUOTES, 'UTF-8');


            $row['aksi'] = '
            <a class="btn btn-sm btn-success text-dark lihat-file" 
                        data-files=\'' . $data_files . '\'>
                        <i class="fa fa-camera"></i>
                      </a>
            <a href="#" 
                    class="btn btn-sm btn-info text-dark edit" 
                    title="Edit Data" 
                    data-id="' . $id . '">
                    <i class="fa fa-edit"></i>
             </a>
                <a class="btn btn-sm btn-danger btn-icon text-dark hapus"
                    data-id="' . $id . '"
                    data-url="' . base_url() . 'spekmesin/hapus/' . $id . '" href="#">
                    <i class="fa fa-trash"></i>
                </a>';
        }

        $this->db->from('downtime_spekmesin');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('downtime_spekmesin.dept_kode', $dept_id);
        } else {
            if (!empty($akses_dept)) {
                $this->db->where_in('downtime_spekmesin.dept_kode', $akses_dept);
            } else {
                $this->db->where('1=0');
            }
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('downtime_spekmesin.mach_no', $search);
            $this->db->or_like('downtime_spekmesin.mach_name', $search);
            $this->db->group_end();
        }

        $recordsFiltered = $this->db->count_all_results();




        $this->db->from('downtime_spekmesin');
        $recordsTotal = $this->db->count_all_results();


        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
    public function tambahdata($filter_dept)
    {
        $data['dept'] = $filter_dept;
        $this->load->view('spekmesin/add', $data);
    }

    public function simpan()
    {
        $query = $this->Spekmesin_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data Berhasil Di Tambahkan!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ');
        }
        redirect('spekmesin');
    }
    public function edit($id)
    {
        $data['detail'] = $this->Spekmesin_model->getdataByid($id);
        $this->load->view('spekmesin/edit', $data);
    }

    public function update($id)
    {
        $config['upload_path'] = 'assets/img/upload_spek/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp4|pdf|doc|docx|xls|xlsx|zip';
        $config['max_size'] = 20240; // 20 MB
        $this->load->library('upload', $config);

        // Ambil data lama dari database
        $detail = $this->Spekmesin_model->getdataByid($id);
        $old_files = !empty($detail['file_spek']) ? json_decode($detail['file_spek'], true) : [];
        $old_file_paths = !empty($detail['path_file_spek']) ? json_decode($detail['path_file_spek'], true) : [];
        $old_file_types = !empty($detail['file_type_spek']) ? json_decode($detail['file_type_spek'], true) : [];

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
            $path = 'assets/img/upload_spek/' . $file;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // === Proses Rename File Lama ===
        $rename_files = $this->input->post('rename_file');
        $nama_file_asli = $this->input->post('nama_file_asli');

        if ($rename_files && $nama_file_asli) {
            foreach ($rename_files as $i => $rename) {
                $old_name = $nama_file_asli[$i];

                // Hanya diproses kalau user isi nama baru dan file belum dihapus
                if (!empty($rename) && in_array($old_name, $filtered_old_files)) {
                    $old_path = 'assets/img/upload_spek/' . $old_name;

                    $ext = pathinfo($old_name, PATHINFO_EXTENSION);
                    $rename_clean = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($rename, PATHINFO_FILENAME));
                    $new_name = $rename_clean . '.' . $ext;
                    $new_path = 'assets/img/upload_spek/' . $new_name;

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

        // Upload file baru
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
                        $new_file_types[] = $upload_data['file_type_spek'];
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
                        redirect('downtime_spekmesin');
                        return;
                    }
                }
            }
        }

        // Gabungkan semua file
        $all_files = array_merge($filtered_old_files, $new_files);
        $all_file_paths = array_merge($filtered_file_paths, $new_file_paths);
        $all_file_types = array_merge($filtered_file_types, $new_file_types);

        $data = $_POST;

        $data['idle'] = isset($data['idle']) ? 1 : 0;


        $data = [
            'mach_no' => $this->input->post('mach_no'),
            'mach_name' => $this->input->post('mach_name'),
            'kapasitas' => $this->input->post('kapasitas'),
            'name' => $this->input->post('name'),
            'preventif_kode' => $this->input->post('preventif_kode'),
            'als' => $this->input->post('als'),
            'file_spek' => !empty($all_files) ? json_encode(array_values($all_files)) : null,
            'file_type_spek' => !empty($all_file_types) ? json_encode(array_values($all_file_types)) : null,
            'path_file_spek' => !empty($all_file_paths) ? json_encode(array_values($all_file_paths)) : null,
        ];
        $this->db->where('mach_id', $id);
        $this->db->update('downtime_spekmesin', $data);



        $this->session->set_flashdata('message', '
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data Berhasil DiUpdate!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                    ');

        redirect('spekmesin');
    }

    public function hapus($id)
    {
        $hasil = $this->Spekmesin_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('message', '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Terhapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
        }
        redirect('spekmesin');
    }
}
