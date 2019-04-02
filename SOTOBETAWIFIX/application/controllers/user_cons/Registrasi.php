<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {
	public function __construct(){
			parent::__construct();
			$this->load->model('data');
			$this->load->helper('form');
			$this->load->library('form_validation');
	}
	public function index(){
		// validasi form
		$this->form_validation->set_rules('id_user', 'User Id', 'trim|required|is_unique[tmas_user.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tmas_user.email]');
		$this->form_validation->set_rules('telp_number', 'Nomor Telpon', 'trim|required|max_length[15]');
		$this->form_validation->set_rules('pwd', 'Password', 'trim|required|max_length[16]');
		$this->form_validation->set_rules('repwd', 're-Password', 'trim|required');

		$this->form_validation->set_message('is_unique', '%s sudah dipakai.');

		$username	=$this->input->post('id_user');
		$email  	=$this->input->post('email');
		$no_telp	=$this->input->post('telp_number');
		$password	=$this->input->post('pwd');
		$repassword	=$this->input->post('repwd');

		if ($this->form_validation->run() == FALSE){
				 	$pass['error']="<div class='alert alert-danger col-sm-11'>
				 	<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>".validation_errors()."</div>";
				 	$this->load->view('user_vw/meta');
				 	$this->load->view('user_vw/js');
					$kirim['itemcart']=$this->cart->total_items();
					$this->load->view('user_vw/navbar',$kirim);
					$this->load->view('user_vw/daftar',$pass);
					$this->load->view('user_vw/footer');
					
			}else{

				if ($password==$repassword) {
					//masuk database
					$data = array('username' =>$username , 
					  		  'email'   =>$email,
							  'password'=>md5($password),
							  'id_level'=> 2,
							  'create_date'=> date('Y-m-d H:i:s')
							);
					
						$this->data->insert_into($data,'tmas_user');
						$get_userid = $this->data->_getUserId();
						
						$data2= array('id_user' =>$get_userid['id_user'],
								  'telp_number' =>$no_telp,
								  'profile_picture' =>'asset/img/profile.png'
								  );
						$this->data->insert_into($data2,'tmas_user_detail');
					

				 	//
				 	//load success view
				 	$this->load->view('user_vw/meta');
				 	$this->load->view('user_vw/js');
				 	$kirim['itemcart']=$this->cart->total_items();
				 	$this->load->view('user_vw/navbar',$kirim);
				 	$this->load->view('user_vw/success');
				 	$this->load->view('user_vw/footer');
				 	
				}else{
					$pass['error']="<div class='alert alert-danger col-sm-11'>re-Password salah!</div>";
					$this->load->view('user_vw/meta');
					$this->load->view('user_vw/js');
					$kirim['itemcart']=$this->cart->total_items();
					$this->load->view('user_vw/navbar',$kirim);
					$this->load->view('user_vw/daftar',$pass);
					$this->load->view('user_vw/footer');
					
				}
			}
	}
	public function daftar(){
		if ($this->session->userdata('login')==true) {
			header("location:/".base_url());
		}
		//daftar link
		$pass['error']="";
		$this->load->view('user_vw/meta');
		$this->load->view('user_vw/js');
		$kirim['itemcart']=$this->cart->total_items();
		$this->load->view('user_vw/navbar',$kirim);
		$this->load->view('user_vw/daftar',$pass);
		$this->load->view('user_vw/footer');
		
	}
	public function isi_data_diri(){
		
		$this->form_validation->set_rules('id_user', 'Nama Pengguna', 'trim|is_unique[tmas_user.id_user]');
		$this->form_validation->set_rules('name', 'Nama Asli', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[tmas_user.email]');
		$this->form_validation->set_rules('postal_zip', 'Kode Pos', 'trim|max_length[6]');
		$this->form_validation->set_rules('telp_number', 'Nomer Telepon', 'trim|max_length[12]');

		$photo=$this->input->post("photo");
		$id_user=$this->input->post("id_user");
		$email=$this->input->post("email");
		$nama=$this->input->post("name");
		$alamat=$this->input->post("address");
		$kd_pos=$this->input->post("postal_zip");

		if ($this->form_validation->run() == FALSE){
			$pass['error']="<div class='alert alert-danger col-sm-offset-1 col-sm-11'>"
				 	         ."<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>".validation_errors()
				 		   ."</div>";
			if ($this->data->cek_data_diri($this->session->userdata('id_user'))) {
					$pass['datadiri']=$this->data->get_data_diri($this->session->userdata('id_user'));
			}
			$this->load->view('user_vw/meta');
			$this->load->view('user_vw/js');
			$kirim['itemcart']=$this->cart->total_items();
			$this->load->view('user_vw/navbar',$kirim);
			$this->load->view('user_vw/profile_form',$pass);
			$this->load->view('user_vw/footer');
		}else{
				//update profile table user
				if (!empty($id_user)){
					$user['id_user']=$id_user;
					$user_data['id_user']=$id_user;
					$this->data->update_profile($user,'tmas_user','id_user',$this->session->userdata('id_user'));
					$this->data->update_profile($user_data,'tmas_user_detail','id_user',$this->session->userdata('id_user'));
					$newses = array('id_user'	=> $id_user);
					$this->session->set_userdata($newses);

				}
				if (!empty($email)){
					$user['email']=$email;
					$this->data->update_profile($user,'tmas_user','id_user',$this->session->userdata('id_user'));
					$newses = array('email'	=> $email);
					$this->session->set_userdata($newses);	
				}

				$config['allowed_types']        = 'jpg|png|JPG|PNG';
	            $config['max_size']             = 500;
	            $config['upload_path']          = './asset/profile-pic';
	 			$config['file_name']            = $this->session->userdata('id_user');
	 			$this->load->library('upload', $config);
                if ($this->upload->do_upload('photo')){
                	$userdata['profile_picture']='asset/profile-pic/'.$this->upload->data()['file_name'];
                	$newses =array('profile_picture'=>$userdata['profile_picture']);
                	$this->session->set_userdata($newses);
                }else{
                	$lala['upload']="<p class='text-danger'>upload gagal!</p>";
                }

                $userdata['name']=$nama;
                $userdata['address']=$alamat;
                $userdata['postal_zip']=$kd_pos;
                if (!empty($id_user)) {
                	$this->data->update_profile($userdata,'tmas_user_detail','id_user',$id_user);	
                }else{
                	$this->data->update_profile($userdata,'tmas_user_detail','id_user',$this->session->userdata('id_user'));
                }
                if ($this->data->cek_data_diri($this->session->userdata('id_user'))) {
					$lala['datadiri']=$this->data->get_data_diri($this->session->userdata('id_user'));
				}
				$lala['error']="<div class='alert alert-success col-sm-offset-1 col-sm-11'>"
				 	         ."<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
				 	         ."Data berhasil disimpan"
				 		   ."</div>";
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/profile_form',$lala);
				$this->load->view('user_vw/footer');
                
		}

	}
}