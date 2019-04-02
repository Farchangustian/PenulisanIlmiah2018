<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends CI_Model{
	function insert_into($data=array(),$tablename){
		$this->db->insert($tablename,$data);
	}
	//login user
	function user_login($key,$password){
	   $this->db-> select('u.id_user, u.email, u.id_level, d.telp_number, d.profile_picture');
	   $this->db-> from('tmas_user as u');
	   $this->db-> join('tmas_user_detail as d','u.id_user=d.id_user');
	   $this->db-> where('email', $key);
	   $this->db-> where('password', MD5($password));
	   $this->db-> limit(1);
	   $query=$this->db->get();
	   if($query -> num_rows() == 1)
	   {
	     return $query->result();
	   }
	   else
	   {
	     return false;
	   }
	}
	//user detail
	function _getUserId() {
		$this->db->select('id_user');
		$this->db->from('tmas_user');
		$this->db->limit(1);
		$this->db->order_by('id_user','DESC');

		$query=$this->db->get();

		return $query->row_array();
	}

	// get data menu random in
	function get_food_menu_rand(){
		return $this->db->query("SELECT m.* FROM food_menu m JOIN (SELECT id_food_menu FROM food_menu WHERE RAND() < (SELECT ((4 / COUNT(*)) * 10) FROM food_menu) ORDER BY RAND()
						  LIMIT 4) AS z ON z.id_food_menu = m.id_food_menu")->result();
	}
	// data lihat menu
	function get_menu($limit,$last_id){
		$this->db->select('*');
		$this->db->from('food_menu');
		$this->db->limit($limit);
		$this->db->offset($last_id);
		$query=$this->db->get();
		return $query->result();
	}
	
	//metode pengambilan
	function metode_pengambilan(){
		$this->db->select('*');
		$this->db->from('tmas_metode_pengambilan');
		$query = $this->db->get();
	   	return $query->result_array();
	}
	
	//metode pembayaran
	function metode_pembayaran(){
		$this->db->select('method_pengambilan');
		$this->db->from('tmas_metode_pengambilan');
		$query = $this->db->get();
	   	return $query->result_array();
	}
	//take menu picture
	function get_menu_pic($id_food_menu){
		$this->db->select('picture');
		$this->db->from('food_menu');
		$this->db->where('id_food_menu',$id_food_menu);
		$query=$this->db->get();
		$url=$query->result();
		return $url[0]->picture;
	}
	//cekprofile
	function cek_data_diri($id_user){
		$this->db->select('name, address, postal_zip, telp_number');
		$this->db->from('tmas_user_detail');
		$this->db->where('id_user',$id_user);
		$result=$this->db->get()->result();
		if (is_null($result[0]->name) or is_null($result[0]->address) or is_null($result[0]->postal_zip) or is_null($result[0]->telp_number)) {
			return false;
		}else{
			return true;
		}
	}
    function update_profile($data,$table,$selector,$selectorvalue){
    	if ($this->db->update($table, $data, array($selector => $selectorvalue))) {
    		return true;
    	}else{
    		return false;
    	}
    }
    function get_data_diri($id_user){
    	$this->db->select('name, address, postal_zip, telp_number');
    	$this->db->from('tmas_user_detail');
    	$this->db->where('id_user',$id_user);
    	return $this->db->get()->result();
	}
	//jika no_pesanan di pecah*/
	// function get_riwayat_pesanan($id_user,$limit,$offset){
    // 	$this->db->select("t.id_pesanan, t.no_pesanan, t.tgl_pesan, t.tgl_ambil, t.status_pesanan, t.status_pembayaran, t.metode_pengambilan, d.qty, d.harga_total, m.nama");
    // 	$this->db->from('ttrans_pesanan as t');
    // 	$this->db->join('ttrans_pesanan_detail as d', 't.no_pesanan=d.no_pesanan');
    // 	$this->db->join('menu as m', 'd.kd_menu=m.kd_menu');
    // 	$this->db->where('id_user',$id_user);
    // 	$this->db->offset($offset);
    // 	$this->db->limit($limit);
    // 	$this->db->order_by('tgl_pesan','DESC');
    // 	return $this->db->get()->result();
    // }

    //jika no_pesanan tidak di pecah*/
    function get_riwayat_pesanan($id_user,$limit,$offset){
    	$this->db->select(" t.no_pesanan, t.tgl_pesan, t.tgl_ambil, t.id_status_pemesanan, t.id_status_pembayaran, t.id_metode_pengambilan, t.total_harga");
    	$this->db->from('ttrans_pesanan as t');
    	$this->db->where('id_user',$id_user);
    	$this->db->offset($offset);
    	$this->db->limit($limit);
    	$this->db->order_by('tgl_pesan','DESC');
    	return $this->db->get()->result();
    }
    function count_data_pesanan($id_user){
    	$this->db->select('count(no_pesanan) as row');
    	$this->db->from('ttrans_pesanan');
    	$this->db->where('id_user',$id_user);
    	$data=$this->db->get()->result();
    	return $data{0}->row;
    }
    function cek_no_pesanan($no_pesanan){
    	$this->db->select('no_pesanan');
    	$this->db->from('ttrans_pesanan');
    	$this->db->where('no_pesanan',$no_pesanan);
    	if ($this->db->get()->num_rows()>0) {
    		return true;
    	}else{
    		return false;
    	}
    }
	function m_hapus_laporan($pnjl,$no_pesanan){
	$this->db->where($pnjl);
	$this->db->delete($no_pesanan);
		}
}