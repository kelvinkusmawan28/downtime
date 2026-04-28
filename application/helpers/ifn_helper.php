<?php

// function is_logged_in()
// {

//     $ci = get_instance();
//     if (!$ci->session->userdata('username')) {
//         redirect('auth');
//     }
// }
function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('getin_downtime')) {
        redirect('auth');
    }
}


function cheklog()
{
    $ci = get_instance();
    $getinlab = $ci->session->userdata('getin_downtime');
    if (!empty($getinlab)) {
        redirect('dashboard');
    }
}

function cekmenuheader($kata)
{
    $hasil = '';
    $pos = strpos($kata, '1');
    if ($pos === false) {
        $hasil = 'hilang';
    }
    return $hasil;
}
function cekmenudetail($kata, $nomor)
{
    $hasil = '';
    if (substr($kata, ($nomor * 2) - 2, 1) != 1) {
        $hasil = 'hilang';
    }
    return $hasil;
}

function encrypto($str)
{
    $kunci = '22alsjkk8393h25ask1x51s6solkld2122';
    $hasil = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kunci, ($i % strlen($kunci)) - 1, 1);
        $karakter = chr(ord($karakter) + ord($kuncikarakter));
        $hasil .= $karakter;
    }
    return urlencode(base64_encode($hasil));
}

if (!function_exists('terbilang')) {
    function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        if ($angka < 12) {
            return " " . $huruf[$angka];
        } elseif ($angka < 20) {
            return terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            return terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
        } elseif ($angka < 200) {
            return " Seratus" . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return " Seribu" . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
        } else {
            return "Angka terlalu besar!";
        }
    }
}

function decrypto($str)
{
    $str = base64_decode(urldecode($str));
    $hasil = '';
    $kunci = '22alsjkk8393h25ask1x51s6solkld2122';
    for ($i = 0; $i < strlen($str); $i++) {
        $karakter = substr($str, $i, 1);
        $kuncikarakter = substr($kunci, ($i % strlen($kunci)) - 1, 1);
        $karakter = chr(ord($karakter) - ord($kuncikarakter));
        $hasil .= $karakter;
    }
    return $hasil;
}

function format_tanggal_indonesia($tanggal)
{
    $hari_indonesia = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $bulan_indonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $date = new DateTime($tanggal);
    $hari = $hari_indonesia[$date->format('l')];
    $tanggal = $date->format('d');
    $bulan = $bulan_indonesia[$date->format('F')];
    $tahun = $date->format('Y');

    return "$hari, $tanggal $bulan $tahun";
}
function format_tanggal_indonesia_waktu($tanggal)
{
    $hari_indonesia = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $bulan_indonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $date = new DateTime($tanggal);
    $hari = $hari_indonesia[$date->format('l')];
    $tgl = $date->format('d');
    $bulan = $bulan_indonesia[$date->format('F')];
    $tahun = $date->format('Y');
    $jam = $date->format('H:i');

    return "$hari, $tgl $bulan $tahun - $jam";
}

function format_riwayat($tanggal, $shift = null)
{
    $hari_indonesia = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $bulan_indonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $date = new DateTime($tanggal);

    $hari = $hari_indonesia[$date->format('l')];
    $tgl = $date->format('d');
    $bulan = $bulan_indonesia[$date->format('F')];
    $tahun = $date->format('Y');
    $jam = $date->format('H:i'); // 🔥 selalu ambil dari DB

    return "$hari, $tgl $bulan $tahun - $jam";
}



function max_upload()
{
    $max_filesize = (int) (ini_get('upload_max_filesize'));
    $max_post     = (int) (ini_get('post_max_size'));
    $memory_limit = (int) (ini_get('memory_limit'));
    return min($max_filesize, $max_post, $memory_limit);
}




function nomor_dokumen($dept_id, $rd_event, $rd, $tgl_input)
{
    $bulan = date('m', strtotime($tgl_input));
    $tahun = date('y', strtotime($tgl_input));
    $kode_bulan_tahun = $bulan . $tahun;

    $prefix_nomor = 'RD-' . $dept_id . '-' . $kode_bulan_tahun;


    $rd->from($rd_event);
    $rd->like('nomor', '-' . $kode_bulan_tahun . '-', 'both');
    $jumlah = $rd->count_all_results();

    $next_number = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
    return $prefix_nomor . '-' . $next_number;
}


if (!function_exists('format_downtime')) {
    function format_downtime($total_menit)
    {
        $hari = floor($total_menit / 1440);
        $sisa_menit = $total_menit % 1440;

        $jam = floor($sisa_menit / 60);
        $menit = $sisa_menit % 60;

        $hasil = '';
        if ($hari > 0) {
            $hasil .= "$hari hari ";
        }
        if ($jam > 0) {
            $hasil .= "$jam jam ";
        }
        if ($menit > 0) {
            $hasil .= "$menit menit";
        }

        return trim($hasil);
    }
}



function nomor_dokumen_mesin($dept, $tabel)
{
    $ci = &get_instance();
    $ci->db->select('mach_id');
    $ci->db->from($tabel);
    $ci->db->like('mach_id', $dept, 'after');
    $ci->db->order_by('mach_id', 'DESC');
    $ci->db->limit(1);

    $result = $ci->db->get()->row();

    if ($result) {

        $last_number = (int) substr($result->mach_id, strlen($dept));
        $next_number = $last_number + 1;
    } else {
        $next_number = 1;
    }


    $next_id = $dept . str_pad($next_number, 3, '0', STR_PAD_LEFT);

    return $next_id;
}

if (!function_exists('get_nama_bulan')) {
    function get_nama_bulan($bulan)
    {
        $nama_bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];


        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        return isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan;
    }

    if (!function_exists('format_menit')) {
        function format_menit($total_menit, $format_jam = false)
        {
            if ($total_menit === null) return '0 menit';


            $total_menit = (int)$total_menit;


            if ($format_jam) {
                $jam = floor($total_menit / 60);
                $menit = $total_menit % 60;

                if ($jam > 0) {
                    return "{$jam} jam {$menit} menit";
                } else {
                    return "{$menit} menit";
                }
            }


            return number_format($total_menit) . ' menit';
        }
    }
}


function minggu_ke_dalam_bulan($tanggal)
{
    $tanggal = strtotime($tanggal);

    $hari = date('j', $tanggal);
    return ceil($hari / 7);
}

// function range_minggu_dalam_bulan($tanggal)
// {
//     if (!$tanggal) {
//         return null;
//     }

//     $time = strtotime($tanggal);
//     if (!$time) {
//         return null;
//     }

//     $tahun = date('Y', $time);
//     $bulan = date('m', $time);

//     $minggu_ke = minggu_ke_dalam_bulan($tanggal);

//     if (!$minggu_ke) {
//         return null;
//     }

//     $start = ($minggu_ke - 1) * 7 + 1;
//     $end   = min($start + 6, date('t', $time));

//     return [
//         'minggu_ke' => $minggu_ke,
//         'awal'     => "$tahun-$bulan-" . str_pad($start, 2, '0', STR_PAD_LEFT),
//         'akhir'    => "$tahun-$bulan-" . str_pad($end, 2, '0', STR_PAD_LEFT),
//     ];
// }

function format_bulan_indonesia($bulan)
{
    $bulan_array = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    return $bulan_array[(int)$bulan];
}
function format_bulan_kapital($bulan)
{
    $bulan_array = [
        1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET',
        4 => 'APRIL', 5 => 'MEI', 6 => 'JUNI',
        7 => 'JULI', 8 => 'AGUSTUS', 9 => 'SEPTEMBER',
        10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
    ];

    return $bulan_array[(int)$bulan];
}

function range_minggu_dalam_bulan($tanggal)
{
    $time  = strtotime($tanggal);
    $tahun = date('Y', $time);
    $bulan = date('m', $time);
    $nama_bulan = format_bulan_indonesia($bulan); // pakai helper bulan

    $minggu_ke = minggu_ke_dalam_bulan($tanggal);

    $start = ($minggu_ke - 1) * 7 + 1;
    $end   = min($start + 6, date('t', $time));

    $tgl_awal  = "$tahun-$bulan-" . str_pad($start, 2, '0', STR_PAD_LEFT);
    $tgl_akhir = "$tahun-$bulan-" . str_pad($end, 2, '0', STR_PAD_LEFT);

    return [
        'minggu_ke' => $minggu_ke,
        'bulan'     => $nama_bulan,
        'tahun'     => $tahun,
        'awal'      => $tgl_awal,
        'akhir'     => $tgl_akhir
    ];
}
