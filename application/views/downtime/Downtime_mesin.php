<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Downtime_mesin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Downtime_mesin_model');

        is_logged_in();

        $this->load->library('Pdf');
        // // $this->load->library('Codeqr');
        include_once APPPATH . '/third_party/phpqrcode/qrlib.php';
        // include_once APPPATH . '/third_party/fpdf/fpdf.php';
    }

    public function index()
    {
        $data['title'] = 'Data Downtime Mesin PT INDONEPTUNE NET';
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Downtime_mesin_model->getBulan();
        $data['tahun_options'] = $this->Downtime_mesin_model->getTahun();
        $data['dept_options'] = $this->Downtime_mesin_model->getFilter();

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
        $this->load->view('downtime/index', $data);
        $this->load->view('templates/footer');
    }

    public function reset_filter()
    {


        $this->session->unset_userdata('filter_dept');
        $this->session->unset_userdata('filter_bulan');
        $this->session->unset_userdata('filter_tahun');


        redirect('downtime_mesin');
    }


    public function filter()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');

        $this->session->set_userdata('filter_bulan', $bulan);
        $this->session->set_userdata('filter_tahun', $tahun);
        $this->session->set_userdata('filter_dept', $dept_id);

        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $draw   = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];

        $akses_dept = [];
        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }


        $this->db->select('
            d.nomesin_id,
            SUM(d.downtime_kerusakan) as total_downtime,
            s.mach_no,
            s.mach_name,
            dept.dept_id,
            dept.departemen
        ');
        $this->db->from('downtime d');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');

        // Filter
        $this->db->where('d.status', 1);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        } elseif (!empty($akses_dept)) {
            $this->db->where_in('d.dept_id', $akses_dept);
        } else {
            $this->db->where('1=0');
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('s.mach_no', $search);
            $this->db->or_like('s.mach_name', $search);
            $this->db->group_end();
        }

        $this->db->group_by('d.nomesin_id, s.mach_no, s.mach_name, dept.dept_id, dept.departemen');
        $this->db->order_by('s.mach_no', 'ASC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();




        $filtered_data = [];
        $no = $start + 1;
        foreach ($data as $row) {
            if (empty($row['mach_no'])) continue;

            $row['no']         = $no++;
            $row['departemen'] = ($row['departemen'] == 'FINISHED GOODS') ? 'GUDANG' : $row['departemen'];
            $row['mesin']      = 'Mesin ' . $row['mach_no'];
            $row['spek']      = $row['mach_name'];
            $row['downtime/menit']   = format_menit($row['total_downtime']);
            $row['downtime/hari']   = format_downtime($row['total_downtime']);

            $filtered_data[] = $row;
        }



        $this->db->from('downtime d');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');
        $this->db->where('d.status', 1);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        } elseif (!empty($akses_dept)) {
            $this->db->where_in('d.dept_id', $akses_dept);
        } else {
            $this->db->where('1=0');
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('s.mach_no', $search);
            $this->db->or_like('s.mach_name', $search);
            $this->db->group_end();
        }

        $this->db->group_by('d.nomesin_id');
        $recordsFiltered = $this->db->get()->num_rows();


        $this->db->from('downtime');
        $this->db->where('status', 1);
        $this->db->group_by('nomesin_id');
        $recordsTotal = $this->db->get()->num_rows();

        echo json_encode([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            =>  $filtered_data
        ]);
    }




    public function export_pdf()
    {
        $dept_id = $this->input->get('dept_id');
        $bulan   = $this->input->get('bulan');
        $tahun   = $this->input->get('tahun');

        $detail = $this->Downtime_mesin_model->getExport_Pdf($dept_id, $bulan, $tahun);

        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddFont('Lato', '', 'Lato-Regular.php');
        $pdf->AddFont('Latob', '', 'Lato-Bold.php');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);


        $pdf->SetFont('Latob', '', 12);
        $pdf->Cell(0, 7, 'LAPORAN DOWNTIME MESIN', 0, 1, 'C');
        $pdf->SetFont('Latob', '', 10);
        $pdf->Cell(0, 6, 'BULAN: ' . strtoupper(get_nama_bulan($bulan)) . ' - ' . $tahun, 0, 1, 'C');
        $pdf->Ln(8);


        $widths = [
            'no' => 10,
            'departemen' => 50,
            'mesin' => 40,
            'downtime_menit' => 45,
            'downtime_hari' => 45
        ];
        $total_table_width = array_sum($widths);


        $page_width = 210 - 20;
        $margin_left = (210 - $total_table_width) / 2;


        $pdf->SetFont('Latob', '', 9);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetX($margin_left);
        $pdf->Cell($widths['no'], 8, 'No', 1, 0, 'C', true);
        $pdf->Cell($widths['departemen'], 8, 'Departemen', 1, 0, 'C', true);
        $pdf->Cell($widths['mesin'], 8, 'Mesin', 1, 0, 'C', true);
        $pdf->Cell($widths['downtime_menit'], 8, 'Downtime / Menit', 1, 0, 'C', true);
        $pdf->Cell($widths['downtime_hari'], 8, 'Downtime / Hari', 1, 1, 'C', true);


        $pdf->SetFont('Lato', '', 8);
        $no = 1;
        $total_downtime = 0;

        foreach ($detail as $row) {
            if (empty($row['mach_no'])) continue;

            $departemen = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $mesin = 'Mesin ' . $row['mach_no'];
            $downtime = (int)$row['total_downtime'];
            $total_downtime += $downtime;

            $pdf->SetX($margin_left);
            $pdf->Cell($widths['no'], 7, $no++, 1, 0, 'C');
            $pdf->Cell($widths['departemen'], 7, $departemen, 1, 0);
            $pdf->Cell($widths['mesin'], 7, $mesin, 1, 0);
            $pdf->Cell($widths['downtime_menit'], 7, format_menit($downtime), 1, 0, 'R');
            $pdf->Cell($widths['downtime_hari'], 7, format_downtime($downtime), 1, 1, 'R');
        }


        $pdf->SetFont('Latob', '', 9);
        $pdf->SetFillColor(245, 230, 200);
        $pdf->SetX($margin_left);
        $pdf->Cell(
            $widths['no'] + $widths['departemen'] + $widths['mesin'],
            8,
            'TOTAL DOWNTIME',
            1,
            0,
            'R',
            true
        );
        $pdf->Cell($widths['downtime_menit'], 8, format_menit($total_downtime), 1, 0, 'R', true);
        $pdf->Cell($widths['downtime_hari'], 8, format_downtime($total_downtime), 1, 1, 'R', true);

        // Output PDF
        $pdf->Output('I', 'Laporan_Downtime_Mesin_' . get_nama_bulan($bulan) . '_' . $tahun . '.pdf');
    }



    public function export_excel()
    {
        $dept_id = $this->input->get('dept_id');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $detail = $this->Downtime_mesin_model->getExport_Pdf($dept_id, $bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Downtime Mesin');


        $sheet->setCellValue('C1', 'LAPORAN DOWNTIME MESIN');
        $sheet->setCellValue('C2', 'Bulan: ' . strtoupper(get_nama_bulan($bulan)) . ' - ' . $tahun);
        $sheet->mergeCells('C1:G1');
        $sheet->mergeCells('C2:G2');

        $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('C2')->getFont()->setItalic(true);
        $sheet->getStyle('C1:C2')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'No');
        $sheet->setCellValue('D4', 'Departemen');
        $sheet->setCellValue('E4', 'Mesin');
        $sheet->setCellValue('F4', 'Downtime / Menit');
        $sheet->setCellValue('G4', 'Downtime / Hari');


        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFEFEFEF']
            ]
        ];
        $sheet->getStyle('C4:G4')->applyFromArray($headerStyle);

        $rowNum = 5;
        $no = 1;
        $total_downtime = 0;

        foreach ($detail as $row) {
            if (empty($row['mach_no'])) continue;

            $departemen = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $mesin = 'Mesin ' . $row['mach_no'];
            $downtime = (int)$row['total_downtime'];
            $total_downtime += $downtime;

            $sheet->setCellValue('C' . $rowNum, $no++);
            $sheet->setCellValue('D' . $rowNum, $departemen);
            $sheet->setCellValue('E' . $rowNum, $mesin);
            $sheet->setCellValue('F' . $rowNum, format_menit($downtime));
            $sheet->setCellValue('G' . $rowNum, format_downtime($downtime));

            $rowNum++;
        }

        // Total row
        $sheet->setCellValue('E' . $rowNum, 'TOTAL DOWNTIME');
        $sheet->setCellValue('F' . $rowNum, format_menit($total_downtime));
        $sheet->setCellValue('G' . $rowNum, format_downtime($total_downtime));


        $totalStyle = [
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFCE4D6']
            ]
        ];

        $sheet->getStyle('C4:G' . $rowNum)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);
        $sheet->getStyle('E' . $rowNum . ':G' . $rowNum)->applyFromArray($totalStyle);


        foreach (range('C', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('C5:C' . $rowNum)->getAlignment()->setHorizontal('center');

        // Export
        $filename = 'Laporan_Downtime_Mesin_' . get_nama_bulan($bulan) . '_' . $tahun . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function pengerjaan()
    {
        $data['title'] = 'Data Pengerjaan Perbaikan Mesin PT INDONEPTUNE NET';
        $data['bln_sekarang'] = date('m');
        $data['thn_sekarang'] = date('Y');
        $data['bulan_options'] = $this->Downtime_mesin_model->getBulan();
        $data['tahun_options'] = $this->Downtime_mesin_model->getTahun();
        $data['dept_options'] = $this->Downtime_mesin_model->getFilter();

        $data['filter_bulan'] = $this->session->userdata('filter_bulan_pengerjaan') ?? 'all';
        $data['filter_tahun'] = $this->session->userdata('filter_tahun_pengerjaan') ?? 'all';
        $data['filter_dept'] = $this->session->userdata('filter_dept_pengerjaan') ?? 'all';

        $data['downtime_dept_map'] = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('downtime/pengerjaan', $data);
        $this->load->view('templates/footer');
    }


    public function reset_filter_pengerjaan()
    {


        $this->session->unset_userdata('filter_dept_pengerjaan');
        $this->session->unset_userdata('filter_bulan_pengerjaan');
        $this->session->unset_userdata('filter_tahun_pengerjaan');


        redirect('downtime_mesin');
    }

    public function filter_pengerjaan()
    {
        $dept_id = $this->input->post('dept_id');
        $bulan   = $this->input->post('bulan');
        $tahun   = $this->input->post('tahun');

        $this->session->set_userdata('filter_bulan_pengerjaan', $bulan);
        $this->session->set_userdata('filter_tahun_pengerjaan', $tahun);
        $this->session->set_userdata('filter_dept_pengerjaan', $dept_id);

        $limit  = $this->input->post('length');
        $start  = $this->input->post('start');
        $draw   = $this->input->post('draw');
        $search = $this->input->post('search')['value'];

        $hakdowntime = $this->session->userdata('hakdowntime');

        $hak_dept_downtime = [
            1 => 'FN',
            2 => 'NT',
            3 => 'RR',
            4 => 'SP',
            5 => 'UT',
            6 => 'GF',
        ];

        $akses_dept = [];
        foreach ($hak_dept_downtime as $index => $dept_code) {
            $start_pos = ($index * 2) - 2;
            if (substr($hakdowntime, $start_pos, 2) === '10') {
                $akses_dept[] = $dept_code;
            }
        }


        $this->db->select('
            d.nomesin_id,
            SUM(t.downtime) as total_downtime,
            s.mach_no,
            s.mach_name,
            dept.dept_id,
            dept.departemen
        ');
        $this->db->from('downtime d');
        $this->db->join('downtime_tindakan t', 't.id_downtime = d.id', 'left');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');

        // Filter
        $this->db->where('d.status', 1);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        } elseif (!empty($akses_dept)) {
            $this->db->where_in('d.dept_id', $akses_dept);
        } else {
            $this->db->where('1=0');
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('s.mach_no', $search);
            $this->db->or_like('s.mach_name', $search);
            $this->db->group_end();
        }

        $this->db->group_by('d.nomesin_id, s.mach_no, s.mach_name, dept.dept_id, dept.departemen');
        $this->db->order_by('s.mach_no', 'ASC');
        $this->db->limit($limit, $start);

        $data = $this->db->get()->result_array();




        $filtered_data = [];
        $no = $start + 1;
        foreach ($data as $row) {
            if (empty($row['mach_no'])) continue;

            $row['no']         = $no++;
            $row['departemen'] = ($row['departemen'] == 'FINISHED GOODS') ? 'GUDANG' : $row['departemen'];
            $row['mesin']      = 'Mesin ' . $row['mach_no'];
            $row['spek']   = $row['mach_name'];
            $row['downtime/menit']   = format_menit($row['total_downtime']);
            $row['downtime/hari']   = format_downtime($row['total_downtime']);

            $filtered_data[] = $row;
        }



        $this->db->from('downtime d');
        $this->db->join('downtime_tindakan t', 't.id_downtime = d.id', 'left');
        $this->db->join('downtime_spekmesin s', 's.mach_id = d.nomesin_id', 'left');
        $this->db->join('dept', 'dept.dept_id = d.dept_id', 'left');
        $this->db->where('d.status', 1);

        if ($dept_id !== 'all' && !empty($dept_id)) {
            $this->db->where('d.dept_id', $dept_id);
        } elseif (!empty($akses_dept)) {
            $this->db->where_in('d.dept_id', $akses_dept);
        } else {
            $this->db->where('1=0');
        }

        if ($bulan !== 'all' && !empty($bulan)) {
            $this->db->where('MONTH(d.tanggal)', $bulan);
        }

        if ($tahun !== 'all' && !empty($tahun)) {
            $this->db->where('YEAR(d.tanggal)', $tahun);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('s.mach_no', $search);
            $this->db->or_like('s.mach_name', $search);
            $this->db->group_end();
        }

        $this->db->group_by('d.nomesin_id');
        $recordsFiltered = $this->db->get()->num_rows();


        $this->db->from('downtime');
        $this->db->where('status', 1);
        $this->db->group_by('nomesin_id');
        $recordsTotal = $this->db->get()->num_rows();

        echo json_encode([
            'draw'            => intval($draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            =>  $filtered_data
        ]);
    }

    public function export_pdf_pengerjaan()
    {
        $dept_id = $this->input->get('dept_id');
        $bulan   = $this->input->get('bulan');
        $tahun   = $this->input->get('tahun');

        $detail = $this->Downtime_mesin_model->getExport_Pengerjaan($dept_id, $bulan, $tahun);

        $pdf = new PDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddFont('Lato', '', 'Lato-Regular.php');
        $pdf->AddFont('Latob', '', 'Lato-Bold.php');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);


        $pdf->SetFont('Latob', '', 12);
        $pdf->Cell(0, 7, 'LAPORAN PERBAIKAN MESIN', 0, 1, 'C');
        $pdf->SetFont('Latob', '', 10);
        $pdf->Cell(0, 6, 'BULAN: ' . strtoupper(get_nama_bulan($bulan)) . ' - ' . $tahun, 0, 1, 'C');
        $pdf->Ln(8);


        $widths = [
            'no' => 10,
            'departemen' => 50,
            'mesin' => 40,
            'downtime_menit' => 45,
            'downtime_hari' => 45
        ];
        $total_table_width = array_sum($widths);


        $page_width = 210 - 20;
        $margin_left = (210 - $total_table_width) / 2;


        $pdf->SetFont('Latob', '', 9);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetX($margin_left);
        $pdf->Cell($widths['no'], 8, 'No', 1, 0, 'C', true);
        $pdf->Cell($widths['departemen'], 8, 'Departemen', 1, 0, 'C', true);
        $pdf->Cell($widths['mesin'], 8, 'Mesin', 1, 0, 'C', true);
        $pdf->Cell($widths['downtime_menit'], 8, 'Hitunagan Menit', 1, 0, 'C', true);
        $pdf->Cell($widths['downtime_hari'], 8, 'Hitungan Jam/Hari', 1, 1, 'C', true);


        $pdf->SetFont('Lato', '', 8);
        $no = 1;
        $total_downtime = 0;

        foreach ($detail as $row) {
            if (empty($row['mach_no'])) continue;

            $departemen = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $mesin = 'Mesin ' . $row['mach_no'];
            $downtime = (int)$row['total_downtime'];
            $total_downtime += $downtime;

            $pdf->SetX($margin_left);
            $pdf->Cell($widths['no'], 7, $no++, 1, 0, 'C');
            $pdf->Cell($widths['departemen'], 7, $departemen, 1, 0);
            $pdf->Cell($widths['mesin'], 7, $mesin, 1, 0);
            $pdf->Cell($widths['downtime_menit'], 7, format_menit($downtime), 1, 0, 'R');
            $pdf->Cell($widths['downtime_hari'], 7, format_downtime($downtime), 1, 1, 'R');
        }


        $pdf->SetFont('Latob', '', 9);
        $pdf->SetFillColor(245, 230, 200);
        $pdf->SetX($margin_left);
        $pdf->Cell(
            $widths['no'] + $widths['departemen'] + $widths['mesin'],
            8,
            'TOTAL DOWNTIME',
            1,
            0,
            'R',
            true
        );
        $pdf->Cell($widths['downtime_menit'], 8, format_menit($total_downtime), 1, 0, 'R', true);
        $pdf->Cell($widths['downtime_hari'], 8, format_downtime($total_downtime), 1, 1, 'R', true);

        // Output PDF
        $pdf->Output('I', 'Laporan_Perbaikan Mesin' . get_nama_bulan($bulan) . '_' . $tahun . '.pdf');
    }



    public function export_excel_pengerjaan()
    {
        $dept_id = $this->input->get('dept_id');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $detail = $this->Downtime_mesin_model->getExport_Pengerjaan($dept_id, $bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Downtime Mesin');


        $sheet->setCellValue('C1', 'LAPORAN DOWNTIME MESIN');
        $sheet->setCellValue('C2', 'Bulan: ' . strtoupper(get_nama_bulan($bulan)) . ' - ' . $tahun);
        $sheet->mergeCells('C1:G1');
        $sheet->mergeCells('C2:G2');

        $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('C2')->getFont()->setItalic(true);
        $sheet->getStyle('C1:C2')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C4', 'No');
        $sheet->setCellValue('D4', 'Departemen');
        $sheet->setCellValue('E4', 'Mesin');
        $sheet->setCellValue('F4', 'Downtime / Menit');
        $sheet->setCellValue('G4', 'Downtime / Hari');


        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFEFEFEF']
            ]
        ];
        $sheet->getStyle('C4:G4')->applyFromArray($headerStyle);

        $rowNum = 5;
        $no = 1;
        $total_downtime = 0;

        foreach ($detail as $row) {
            if (empty($row['mach_no'])) continue;

            $departemen = $row['departemen'] == 'FINISHED GOODS' ? 'GUDANG' : $row['departemen'];
            $mesin = 'Mesin ' . $row['mach_no'];
            $downtime = (int)$row['total_downtime'];
            $total_downtime += $downtime;

            $sheet->setCellValue('C' . $rowNum, $no++);
            $sheet->setCellValue('D' . $rowNum, $departemen);
            $sheet->setCellValue('E' . $rowNum, $mesin);
            $sheet->setCellValue('F' . $rowNum, format_menit($downtime));
            $sheet->setCellValue('G' . $rowNum, format_downtime($downtime));

            $rowNum++;
        }

        // Total row
        $sheet->setCellValue('E' . $rowNum, 'TOTAL DOWNTIME');
        $sheet->setCellValue('F' . $rowNum, format_menit($total_downtime));
        $sheet->setCellValue('G' . $rowNum, format_downtime($total_downtime));


        $totalStyle = [
            'font' => ['bold' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFCE4D6']
            ]
        ];

        $sheet->getStyle('C4:G' . $rowNum)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);
        $sheet->getStyle('E' . $rowNum . ':G' . $rowNum)->applyFromArray($totalStyle);


        foreach (range('C', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->getStyle('C5:C' . $rowNum)->getAlignment()->setHorizontal('center');

        // Export
        $filename = 'Laporan_Perbaikan_Mesin' . get_nama_bulan($bulan) . '_' . $tahun . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
