<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    // Login
    public function login()
	{
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $password = md5($password);

        $response = array(
            'status' => 'gagal',
            'data' => null
        );

        if ($username && $password) {
            $this->db->where('email', $username);
            $this->db->where('password', $password);
            $this->db->from('user');
            $query = $this->db->get();
            $check = $query->num_rows();

            if ($check == 1) {
                $data = $query->result_array();
                $data = $data[0];

                $biodata = $this->db->get_where('user_data', array(
                    'id_user' => $data['id_user'])
                );
                $biodata = $biodata->result_array();
                $biodata = $biodata[0];

                $response['status'] = 'berhasil';
                $response['data'] = $biodata;

                echo json_encode($response);
            } else {
                echo json_encode($response);
            }
        } else {
            echo json_encode($response);
        }
	}

    // Menampilkan semua menu makanan 
	public function menu()
	{
        $data = $this->db->get('menu');

        echo json_encode($data->result_object());
    }

    // Menampilkan detail menu makanan
    public function menu_detail($kd_menu = 0/** Primary Key nya */) 
    {
        if ($kd_menu) {
            $this->db->where('kd_menu', $kd_menu);
            $this->db->from('menu');
            $query = $this->db->get();
            $check = $query->num_rows();

            if ($check == 1) {
                $data = $query->result_object();
                $data = $data[0];

                echo json_encode($data);    
            } else {
                echo "menu tidak ditemukan";
            }
        }
    }

    public function pembayaran()
    {
        $data = $this->db->get('pembayaran');
        // from nya salah, from nya nama tabel nya
        $this->db->from('pembayaran');
        $query = $this->db->get();
        $check = $query->num_rows();
        echo json_encode($data->result_object());
    }

    public function tpesanan() {
        $data = $this->db->get('tpesanan');
        $this->db->where('id',$id);
        $this->db->from('tpesanan');
        $query = $this->db->get();
        $check = $query->num_rows();
        echo json_encode($data->result_object());
    } 
    public function penjualan()
	{
        $data = $this->db->get('penjualan');

        echo json_encode($data->result_object());
    }
} 
