<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Pb extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        environmental();
        $this->load->model('Approve_ei_model');
        $this->load->model('Pb_model');



        $this->load->library('Pdf');
        // // $this->load->library('Codeqr');
        include_once APPPATH . '/third_party/phpqrcode/qrlib.php';
        // include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function index()
    {
        $data['title'] = 'Permintaan Barang EI';

        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['total_ok'] = $this->Approve_ei_model->getHitungData_ok();
        $data['ok_tuju'] = $this->Approve_ei_model->getHitungOk_tuju();
        $data['dept_options'] = $this->Pb_model->getdept();
        $data['bulan_options'] = $this->Pb_model->getBulan();
        $data['tahun_options'] = $this->Pb_model->getTahun();


        $data['filter_bulan'] = $this->session->userdata('filter_bulan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept') ?? 'all';



        $this->load->view('templates/header', $data);
        $this->load->view('pb/index', $data);
        $this->load->view('templates/footer');
    }


    public function tambahdata()
    {
        $data['tanggal'] = date('Y-m-d');
        $data['dept'] = $this->session->userdata('id_dept');
        $data['limbah'] = $this->Pb_model->Limbah();
        $this->load->view('pb/add', $data);
    }

    public function simpandata()
    {
        $dept_asal = $this->input->post('dept_asal');
        $tanggal = $this->input->post('tanggal');
        $jenis =  $this->input->post('jenislb_id');
        $kategori = $this->input->post('kategori');
        // var_dump($kategori);
        // die();
        $jml_pcs = $this->input->post('jml_pcs');
        $berat = $this->input->post('berat');
        $satuan = $this->input->post('satuan');
        $pihak_pengelola = $this->input->post('pihak_pengelola');
        $lokasi = $this->input->post('lokasi');


        if ($jenis == '6') {
            $beratKeluar = ($kategori * $jml_pcs) / 1000;
        } else {
            $beratKeluar = $berat / 1000;
        }

        if ($kategori == '2.7') {
            $liter = '65 L';
        } elseif ($kategori == '4.5') {
            $liter = '120 L';
        } elseif ($kategori == '8') {
            $liter = '200 L';
        }



        $logData = [
            'dept_asal' => $dept_asal,
            'kategori' => $liter,
            'tanggal' => $tanggal,
            'jenislb_id' => $jenis,
            'berat' => $beratKeluar,
            'jml_pcs' => $jml_pcs,
            'satuan' => $satuan,
            'pihak_pengelola' => $pihak_pengelola,
            'lokasi' => $lokasi,
            'ok_tuju' => 0
        ];
        $inserted = $this->db->insert('env_limbah_keluar', $logData);


        if ($inserted) {
            $this->session->set_flashdata('info', 'Data berhasil disimpan!');
        } else {
            $this->session->set_flashdata('info', 'Gagal menyimpan data.');
        }
        redirect('pb');
    }

    public function filter_data()
    {
        $dept_id = $this->input->post('dept');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');

        $this->session->set_userdata('filter_dept', $dept_id);
        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $search = $this->input->post('search')['value'] ?? '';

        $hakdepartemen = $this->session->userdata('hakdepartemen');
        $akses_dept_diberi = str_split($hakdepartemen, 2);


        $this->db->select('env_limbah_keluar.*, env_limbah.jenis, dept.departemen');
        $this->db->from('env_limbah_keluar');
        $this->db->join('env_limbah', 'env_limbah.id = env_limbah_keluar.jenislb_id', 'left');
        $this->db->join('dept', 'dept.dept_id = env_limbah_keluar.dept_asal', 'left');


        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('env_limbah_keluar.dept_asal', $dept_id);
        } else {
            if (!empty($akses_dept_diberi)) {
                $this->db->where_in('env_limbah_keluar.dept_asal', $akses_dept_diberi);
            } else {
                $this->db->where('1 = 0');
            }
        }


        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(env_limbah_keluar.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(env_limbah_keluar.tanggal)', $tahun);
        }


        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('env_limbah.jenis', $search);
            $this->db->or_like('dept.departemen', $search);
            $this->db->group_end();
        }

        $this->db->order_by('env_limbah_keluar.id', 'DESC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();


        $no = $start + 1;
        foreach ($data as &$row) {
            $row['no'] = $no++;
            $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);
            $row['departemen'] = $row['departemen'];
            $row['permintaan'] = $row['jenis'];
            $row['kategori'] = $row['kategori'];
            $row['pcs'] = $row['jml_pcs'] ? $row['jml_pcs'] : '-';
            $row['berat'] = $row['berat'];
            $row['ket'] = $row['pihak_pengelola'];


            if ($row['ok_tuju'] == 0) {
                $row['status'] = '<span style="color:red;">Menunggu Persetujuan EI</span>';
            } elseif ($row['ok_tuju'] == 1) {
                $row['status'] = '<span style="color:forestgreen; font-weight: bold; font-size:10px;">
                    <i>
                        Di Setujui Oleh: ' . ucwords(strtolower($row['approver'])) . '<br>
                        Pada Tgl: ' . format_tanggal_indonesia_waktu($row['tanggal_approve']) . '
                    </i>
                </span>';
            }

            $row['aksi'] = '';
            if ($row['ok_tuju'] == 0) {
                $row['aksi'] .= '
                    <a class="btn btn-sm btn-danger btn-icon text-white hapus" 
                        data-id="' . $row['id'] . '" 
                        data-url="' . base_url() . 'pb/hapus/' . $row['id'] . '" 
                        href="#">
                        <i class="fa fa-trash-o"></i>
                    </a>
                ';
            }
        }


        $this->db->select('COUNT(*) AS count');
        $this->db->from('env_limbah_keluar');
        $this->db->join('env_limbah', 'env_limbah.id = env_limbah_keluar.jenislb_id', 'left');
        $this->db->join('dept', 'dept.dept_id = env_limbah_keluar.dept_asal', 'left');

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('env_limbah_keluar.dept_asal', $dept_id);
        } elseif (!empty($akses_dept_diberi)) {
            $this->db->where_in('env_limbah_keluar.dept_asal', $akses_dept_diberi);
        } else {
            $this->db->where('1 = 0');
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(env_limbah_keluar.tanggal)', $bulan);
        }
        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(env_limbah_keluar.tanggal)', $tahun);
        }

        $recordsFiltered = $this->db->get()->row()->count;


        $recordsTotal = $this->db->count_all('env_limbah_keluar');


        echo json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }



    // public function filter_data()
    // {
    //     $jenislb_id = $this->input->post('limbah');
    //     $bulan = $this->input->post('bulan');
    //     $tahun = $this->input->post('tahun');
    //     $lokasi = $this->input->post('lokasi');


    //     $this->session->set_userdata('filter_limbah', $jenislb_id);
    //     $this->session->set_userdata('filter_bulan', $bulan);
    //     $this->session->set_userdata('filter_tahun', $tahun);
    //     $this->session->set_userdata('filter_lokasi', $lokasi);


    //     $limit = $this->input->post('length');
    //     $start = $this->input->post('start');
    //     $draw = $this->input->post('draw');
    //     $search = $this->input->post('search')['value'];

    //     $this->db->select('env_limbah_keluar.*, env_limbah.jenis');
    //     $this->db->from('env_limbah_keluar');
    //     $this->db->join('env_limbah', 'env_limbah.id = env_limbah_keluar.jenislb_id');


    //     if ($jenislb_id !== 'all' && !empty($jenislb_id)) {
    //         $this->db->where('env_limbah.id', $jenislb_id);
    //     }

    //     if ($bulan !== 'all' && !empty($bulan)) {
    //         $this->db->where('MONTH(env_limbah_keluar.tanggal)', $bulan);
    //     }
    //     if ($tahun !== 'all' && !empty($tahun)) {
    //         $this->db->where('YEAR(env_limbah_keluar.tanggal)', $tahun);
    //     }

    //     if ($lokasi !== 'all' && $lokasi !== null && $lokasi !== '') {
    //         $this->db->where('env_limbah_keluar.lokasi', $lokasi);
    //     }

    //     if (!empty($search)) {
    //         $this->db->group_start();
    //         $this->db->like('env_limbah_keluar.pihak_pengelola', $search);
    //         $this->db->or_like('env_limbah.jenis', $search);
    //         $this->db->group_end();
    //     }

    //     $this->db->order_by('env_limbah_keluar.id', 'DESC');
    //     $this->db->limit($limit, $start);
    //     $data = $this->db->get()->result_array();

    //     //  view
    //     $no = $start + 1;
    //     foreach ($data as &$row) {
    //         $row['no'] = $no++;
    //         $row['tanggal'] = format_tanggal_indonesia($row['tanggal']);
    //         $row['jenis'] = $row['jenis'];
    //         $row['tps'] = $row['lokasi'];
    //         $row['berat'] = $row['berat'];
    //         $row['satuan'] = $row['satuan'];
    //         $row['pengelola'] = $row['pihak_pengelola'];
    //         $id = $row['id'];
    //         $row['aksi'] = '
    //                         <a class="btn btn-sm btn-danger btn-icon text-white hapus"
    //                              data-id="' . $id . '"
    //                              data-url="' . base_url() . 'limbah_keluar/hapus/' . $id . '" href="#">
    //                             <i class="fa fa-trash-o"></i>
    //                         </a>


    //                         ';
    //     }

    //     // Penghitungan data yang difilter
    //     $this->db->from('env_limbah_keluar');
    //     $this->db->join('env_limbah', 'env_limbah.id = env_limbah_keluar.jenislb_id');
    //     if ($jenislb_id !== 'all' && !empty($jenislb_id)) {
    //         $this->db->where('env_limbah.id', $jenislb_id);
    //     }

    //     if ($bulan !== 'all' && !empty($bulan)) {
    //         $this->db->where('MONTH(env_limbah_keluar.tanggal)', $bulan);
    //     }
    //     if ($tahun !== 'all' && !empty($tahun)) {
    //         $this->db->where('YEAR(env_limbah_keluar.tanggal)', $tahun);
    //     }

    //     if ($lokasi !== 'all' && $lokasi !== null && $lokasi !== '') {
    //         $this->db->where('env_limbah_keluar.lokasi', $lokasi);
    //     }
    //     $recordsFiltered = $this->db->count_all_results();

    //     // Penghitungan data total
    //     $this->db->from('env_limbah_keluar');
    //     $recordsTotal = $this->db->count_all_results();

    //     echo json_encode([
    //         'draw' => intval($draw),
    //         'recordsTotal' => $recordsTotal,
    //         'recordsFiltered' => $recordsFiltered,
    //         'data' => $data
    //     ]);
    // }
    public function hapus($id)
    {
        $hasil = $this->Pb_model->hapus($id);
        if ($hasil) {
            $this->session->set_flashdata('error', 'Data Berhasil DiHapus!');
        }
        $url = base_url('pb');
        redirect($url);
    }
}
