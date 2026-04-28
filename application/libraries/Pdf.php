<?php

include_once APPPATH . '/third_party/fpdf/fpdf.php';

class Pdf extends FPDF
{
    // function __construct() {
    // }

    // function Header()
    // {

    //     $this->Image(base_url() . 'assets/image/logodepanK.png', 0, 5, 55);


    //     $this->SetFont('Arial', 'I', 8);
    //     $this->Cell(0, 10, 'DATA BC MASUK', 0, 1, 'C');
    //     $this->Ln(8);

    //     $this->SetFont('Arial', 'I', 8); // Font untuk nomor halaman
    //     $this->SetY(14); // Atur kembali posisi Y
    //     $this->Cell(0, 10, 'Halaman: ' . $this->PageNo(), 0, 0, 'C'); // Nomor halaman di kanan atas
    //     $this->Ln(15);

    //     // sett lebar kolom
    //     $col_no = 10;
    //     $col_jenis = 12;
    //     $col_dokumen = 39;
    //     $col_penerimaan = 58;
    //     $col_pemasok = 80;
    //     $col_sat = 10;
    //     $col_jumlah = 21;
    //     $nilai_barang = 50;


    //     $this->Cell($col_no, 13, 'NO', 1, 0, 'C');
    //     $this->Cell($col_jenis, 13, 'JENIS', 1, 0, 'C');
    //     $this->Cell($col_dokumen, 6, 'DOKUMEN PABEAN', 1, 0, 'C');
    //     $this->Cell($col_penerimaan, 6, 'BUKTI PENERIMAAN BRG', 1, 0, 'C');
    //     $this->Cell($col_pemasok, 13, 'PEMASOK/PENGIRIM', 1, 0, 'C');
    //     $this->Cell($col_sat, 13, 'SAT', 1, 0, 'C');
    //     $this->Cell($col_jumlah, 13, 'JUMLAH', 1, 0, 'C');
    //     $this->Cell($nilai_barang, 6, 'NILAI BARANG', 1, 0, 'C');

    //     // Sub-header
    //     $this->SetXY(32, 35);
    //     $this->Cell(16, 7, 'NOMOR', 1, 0, 'C');
    //     $this->Cell(23, 7, 'TANGGAL', 1, 0, 'C');
    //     $this->Cell(35, 7, 'NOMOR', 1, 0, 'C');
    //     $this->Cell(23, 7, 'TANGGAL', 1, 0, 'C');
    //     $this->SetXY(240, 35);
    //     $this->Cell(27, 7, 'IDR', 1, 0, 'C');
    //     $this->Cell(23, 7, 'USD', 1, 0, 'C');
    //     $this->Ln(7);
    // }


    function Footer()
    {
        // //atur posisi 1.5 cm dari bawah
        // $this->SetY(-15);
        // //buat garis horizontal
        // $this->Line(10,$this->GetY(),200,$this->GetY());
        // //Arial italic 9
        // $this->SetFont('Arial','I',9);
        // //nomor halaman
        // // $this->Cell(0,10, $this->Image(base_url().'assets/images/sab/logoitalic1.png',10,$this->GetY()+2,15),0,0);
        // $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'R');
    }
    function WordWrap(&$text, $maxwidth)
    {
        $text = trim($text);
        if ($text === '')
            return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line) {
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word) {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth) {
                    // Word is too long, we cut it
                    for ($i = 0; $i < strlen($word); $i++) {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if ($width + $wordwidth <= $maxwidth) {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        } else {
                            $width = $wordwidth;
                            $text = rtrim($text) . "\n" . substr($word, $i, 1);
                            $count++;
                        }
                    }
                } elseif ($width + $wordwidth <= $maxwidth) {
                    $width += $wordwidth + $space;
                    $text .= $word . ' ';
                } else {
                    $width = $wordwidth + $space;
                    $text = rtrim($text) . "\n" . $word . ' ';
                    $count++;
                }
            }
            $text = rtrim($text) . "\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }
}
