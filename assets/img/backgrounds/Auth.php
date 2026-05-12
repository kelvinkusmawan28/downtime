<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->load->model('Dashboard_model');
    }


    public function index()
    {
        cheklog();
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            // $this->load->view('auth/error');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }


    private function _login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['username' => $username])->row_array();

        if ($user) {
            if (encrypto($password) == $user['password'] && $user['aktif'] == 1) {
                if (substr($user['hakprogram'], 8, 2) == '10') {
                    $user_data = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'username' => $user['username'],
                        'bagian' => $user['bagian'],
                        'id_dept' => $user['id_dept'],
                        'jabatan' => $user['jabatan'],
                        'cekdowntime' => $user['cekdowntime'],
                        'cekdowntime_pi' => $user['cekdowntime_pi'],
                        'cekdowntime_gi' => $user['cekdowntime_gi'],
                        'cekdowntime_master' => $user['cekdowntime_master'],
                        'hakdowntime' => $user['hakdowntime'],
                        'getin_downtime' => true
                    ];
                    $this->session->set_userdata($user_data);
                    $url = base_url('dashboard');
                    redirect($url);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Anda Tidak Memiliki Akses Login Ke Sistem Ini , Silahkan Hubungi IT !</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '   
                <div class="alert alert-danger alert-dismissible" role="alert">
                    Password Yang Anda Masukan Salah, Silahkan Coba Kembali!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              ');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert"> Pengguna Tidak di temukan.</div>');
            redirect('auth');
        }
    }

    public function regis()
    {
        $data['title'] = 'Creat User';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/registrasi');
        $this->load->view('templates/auth_footer');
    }

    public function simpandata()
    {
        $query = $this->Dashboard_model->simpan();
        if ($query) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User berhasil di tambahkan !</div>');
            redirect('auth');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata(['username', 'getin_downtime']);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">anda berhasil Logout dari aplikasi !</div>');
        redirect('auth');
    }
}
