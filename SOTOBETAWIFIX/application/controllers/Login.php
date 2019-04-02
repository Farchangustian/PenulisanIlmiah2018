<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if ($this->session->userdata('login')==true) {
				header("Location:".base_url('admin'));
		}
		$this->load->model('admin_data');
		$this->load->library('form_validation');
	}
	public function index(){
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/login_admin');
	}
	public function auth(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$data['error']="<div class='alert alert-danger'>"
								."<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>".validation_errors()
						  ."</div>";
			$this->load->view('admin_vw/meta_admin');
			$this->load->view('admin_vw/js_admin');
			$this->load->view('admin_vw/navbar_admin');
			$this->load->view('admin_vw/login_admin',$data);
		}else{
			$username =$this->input->post('username');
			$password=$this->input->post('password');
			$login=$this->admin_data->admin_login($username,($password));
			if ($login==false) {
				$data['error']="<div class='alert alert-danger'>"
								."Username atau Password salah!"
						  ."</div>";
				$this->load->view('admin_vw/meta_admin');
				$this->load->view('admin_vw/js_admin');
				$this->load->view('admin_vw/navbar_admin');
				$this->load->view('admin_vw/login_admin',$data);
			}else{
				$admin = array('login'	=> true,
							  'id_admin' => 1,
							  'username' => 'admin',
							  'level'	=> 1,
							);
							$this->session->set_userdata($admin);
							header("Location:".base_url('admin'));
						}		
					}
				}
				public function logout(){
					$this->session->sess_destroy();
					header("Location:".base_url('login'));
				}
			}