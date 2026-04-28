<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CheckLevel
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
    }

    public function handle()
    {
        $user_level = $this->CI->session->userdata('level');

        // Ganti 'level' dengan nama kolom yang menyimpan level pengguna di tabel database Anda.
        // bisa menambahkan level sesuai kebutuhan 
        if ($user_level == 1 && $this->CI->router->fetch_class() != 'dashboard') {
            redirect('dashboard');
        } elseif ($user_level == 2 && $this->CI->router->fetch_class() != 'pengguna') {
            redirect('pengguna');
        }
    }
}
