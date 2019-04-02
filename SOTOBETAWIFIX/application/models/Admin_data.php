<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_data extends CI_Model{
  	/*
  		Login Admin, kumpulan method untuk kasus login admin 
  	*/

  	/*method login admin*/
  	function admin_login($key,$password){
      $this->db-> select('username, password');
      $this->db-> from('tmas_admin');
      $this->db-> where('username', $key);
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
 
   function _getUserId() {
     $this->db->select('id_admin');
     $this->db->from('tmas_admin');
     $this->db->limit(1);
     $this->db->order_by('id_admin','DESC');
 
     $query=$this->db->get();
 
     return $query->row_array();
   }
  	/*
  		*data pesanan, kumpulan method untuk kasus data pesanan
  	*/

  	/*method hitung pesanan masuk*/
  	function getcount_pesanan_masuk(){
  		$this->db->select('no_pesanan');
  		$this->db->from('ttrans_pesanan');
      $this->db->where('id_status_pembayaran',0);
  		$this->db->group_by('no_pesanan');
  		return $this->db->get()->num_rows();
  	}



  	/*method table pesanan*/
  	function get_pesanan_masuk($limit,$offset){
      $this->db->select("tmas_user_detail.name, no_pesanan, ttrans_pesanan.id_user, tgl_pesan, tgl_ambil, id_status_pembayaran, id_status_pemesanan");
      $this->db->join('tmas_user_detail', 'tmas_user_detail.id_user = ttrans_pesanan.id_user');
    	$this->db->from('ttrans_pesanan');
    	$this->db->offset($offset);
    	//$this->db->limit($limit);
      $this->db->group_by('no_pesanan');
    	$this->db->order_by('tgl_pesan','ASC');
    	return $this->db->get()->result();
    }

    /*method data pesanan search*/
    function get_search_pesanan($no_pesanan){
    	$this->db->select("no_pesanan, id_user, tgl_pesan, tgl_ambil, id_status_pembayaran, id_status_pemesanan");
      $this->db->from('ttrans_pesanan');
    	$this->db->like('no_pesanan',$no_pesanan);
      $this->db->group_by('no_pesanan');
    	return $this->db->get()->result();
    }
    /*atas nama*/
    function get_nama_user($id_user){
      $this->db->select('name, address, postal_zip');
      $this->db->from('tmas_user_detail');
      $this->db->where('id_user',$id_user);
      return $this->db->get()->result();
    }
    /*method untuk melihat detail pesanan*/
    // function getdetail_pesanan($no_pesanan){
    //   $this->db->select('d.nama, p.qty, p.harga_total, p.metode_pengambilan');
    //   $this->db->from('ttrans_pesanan as p');
    //   $this->db->join('menu as d','p.id_food_menu=d.id_food_menu');
    //   $this->db->where('no_pesanan',$no_pesanan);
    //   return $this->db->get()->result();
    // }
    function getdetail_pesanan($no_pesanan){
      $this->db->select('m.food_menu_name, p.id_user, d.qty, d.total_harga, p.id_metode_pengambilan');
      $this->db->from('ttrans_pesanan as p');
      $this->db->join('ttrans_pesanan_detail as d','p.no_pesanan=d.no_pesanan');
      $this->db->join('food_menu as m','d.id_food_menu=m.id_food_menu');
      $this->db->where('p.no_pesanan',$no_pesanan);
      return $this->db->get()->result();
    }
    function get_total_price($no_pesanan){
      $this->db->select('total_harga');
      $this->db->from('ttrans_pesanan');
      $this->db->where('no_pesanan',$no_pesanan);
      $data=$this->db->get()->result();
      $total=0;
      foreach ($data as $a) {
        $total+=$a->harga_total;
      }
      return $total;
    }
    /*method update data pesanan */
  	function get_update_pesanan($data = array(),$no_pesanan){
  		$this->db->where('no_pesanan', $no_pesanan);
  		$this->db->update('ttrans_pesanan', $data);
  		$retVal = ($this->db->affected_rows()!=0) ? true : false ;
     	return $retVal;
  		
    }
     	
   	/*method delete data pesanan*/
   	function delete_pesanan($no_pesanan){
   		$this->db->where('no_pesanan',$no_pesanan);
   		$this->db->delete('ttrans_pesanan');
   		$retVal = ($this->db->affected_rows()!=0) ? true : false ;
   		return $retVal;
   		
   	}

     	/*
     		*data pengguna
     	*/
     	/*method hitung data pengguna*/
    function getcount_data_pengguna(){
  		$this->db->select('id_user');
  		$this->db->from('tmas_user');
  		return $this->db->get()->num_rows();
  	}

   	/*method lihat data pengguna*/
   	function get_data_pengguna($limit,$offset){
      $this->db->select('id_user, username, email, id_level');
      $this->db->from('tmas_user');
      $this->db->offset($offset);
      $this->db->limit($limit);
      $query=$this->db->get();
      return $query->result();
   	}

    function search_data_pengguna($username){
    $this->db->select('id_user, username, email, id_level');
    $this->db->from('tmas_user');
    $this->db->like('username',$username);
    $query=$this->db->get();
    return $query->result();
    }

   	/*method lihat data lengkap pengguna*/
   	function getdetail_data_pengguna($id_user){
   		$this->db->select('c.*, d.*');
   		$this->db->from('tmas_user as c');
   		$this->db->join('tmas_user_detail as d','c.id_user=d.id_user');
       $this->db->where('c.id_user', $id_user);
      
       return $this->db->get()->result_object();
   	}

   	/*method lihat data admin*/
   	function get_data_admin(){
   		$this->db->select('username, password, id_level');
   		$this->db->from('tmas_admin');
   		$this->db->where('id_level','1');
   		return $this->db->get()->result();
   	}

   	/*
		#method kasus data menu
   	*/

   	/*method tambah menu*/
   	function tambah_menu($data){
   		$this->db->insert('food_menu',$data);
   		$retVal = ($this->db->affected_rows()!=0) ? true : false ;
   		return $retVal;
   	}

   	/*method hitung jumlah menu*/
   	function getcount_menu(){
  		$this->db->select('id_food_menu');
  		$this->db->from('food_menu');
  		return $this->db->get()->num_rows();
	  }

    function getcount_pengambilan(){
  		$this->db->select('id_metode_pengambilan');
  		$this->db->from('tmas_metode_pengambilan');
  		return $this->db->get()->num_rows();
    }
    
     /*method lihat detail menu*/
   	function get_detail_menu($id_food_menu){
      $this->db->select('id_food_menu, food_menu_name, description, price, picture');
      $this->db->from('food_menu');
      $this->db->where('id_food_menu',$id_food_menu);
      $query=$this->db->get();
      return $query->row();
    }
    

   	/*method get data menu*/
   	function get_data_menu($limit, $offset){
   		$this->db->select('id_food_menu, description, food_menu_name, price');
   		$this->db->from('food_menu');
      $this->db->offset($offset);
      $this->db->limit($limit);
   		$query=$this->db->get();
   		return $query->result();
   	}

    /*method get data menu pengambilan*/
   	function get_data_pengambilan($limit, $offset){
      $this->db->select('id_metode_pengambilan, metode_pengambilan, biaya	, Area, method_pengambilan');
      $this->db->from('tmas_metode_pengambilan');
     $this->db->offset($offset);
     $this->db->limit($limit);
      $query=$this->db->get();
      return $query->result();
    }
    /*method get update data menu pengambilan*/
    function update_data_pengambilan($data=array(), $id_metode_pengambilan){
      $this->db->where('id_metode_pengambilan',$id_metode_pengambilan);
      $this->db->update('tmas_metode_pengambilan', $data);
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    } 
    /*method get hapus data menu pengambilan*/
    function delete_data_pengambilan($id){
      $this->db->where('id_metode_pengambilan',$id);
      $this->db->delete('tmas_metode_pengambilan');
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    }
    /*method hapus menu*/
    function get_search_menu($food_menu_name){
      $this->db->select('id_food_menu, food_menu_name, price');
      $this->db->from('food_menu');
      $this->db->like('food_menu_name',$food_menu_name);
      $query=$this->db->get();
      return $query->result();
    }

   	/*method update data menu*/
   	function update_data_menu($data=array(), $id_food_menu){
   		$this->db->where('id_food_menu',$id_food_menu);
   		$this->db->update('food_menu', $data);
   		$retVal = ($this->db->affected_rows()!=0) ? true : false ;
   		return $retVal;
   	}

   	/*method hapus menu*/
   	function delete_data_menu($id_food_menu){
   		$this->db->where('id_food_menu',$id_food_menu);
   		$this->db->delete('food_menu');
   		$retVal = ($this->db->affected_rows()!=0) ? true : false ;
   		return $retVal;	
   	}	
    /*select pic*/
    function select_pic($id_food_menu){
      $this->db->select('picture');
      $this->db->from('food_menu');
      $this->db->where('id_food_menu',$id_food_menu);
      return $this->db->get()->result()[0]->picture;
    }	


    /*
    *method kasus konfirmasi pesanan
    */		
    function getcount_konfirm(){
      $this->db->select('*');
      $this->db->from('ttrans_pesanan_pembayaran');
      $this->db->where('checked', '0');
      return $this->db->get()->num_rows();
    }
    
    /*data konfirmasi*/
    function get_data_konfirmasi($limit, $offset){
      $this->db->select('*');
      $this->db->from('ttrans_pesanan_pembayaran');
      $this->db->limit($limit);
      $this->db->offset($offset);
      $this->db->where('checked', '0');
      return $this->db->get()->result();
    }

    /*master*/
    function get_data_pembayaran($limit, $offset){
      $this->db->select('*');
      $this->db->from('ttrans_pesanan_pembayaran');
      $this->db->limit($limit);
      $this->db->offset($offset);
      $this->db->where('checked',1);
      return $this->db->get()->result();
    }

    /*search*/
    function getsearch_data_pembayaran($no_pesanan){
      $this->db->select('*');
      $this->db->from('ttrans_pesanan_pembayaran');
      $this->db->like('no_pesanan',$no_pesanan);
      return $this->db->get()->result();
    }

    /*update*/
    function update_konfirmasi($id){
      $data = array('checked' => 1);
      $this->db->where('no_pesanan', $id);
      $this->db->update('ttrans_pesanan_pembayaran', $data);
      $retVal = ($this->db->affected_rows()!=0) ? 'true' : 'false' ;
      return $retVal;
    }
    /*delete ajx*/ 
    function delete_konfirmasi($id){
      $this->db->where('no_pesanan',$id);
      $this->db->delete('ttrans_pesanan_pembayaran');
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    }

    /*data Penjualan*/
    function get_data_penjualan($offset, $limit){
      $this->db->select('*');
      $this->db->from('lap_penjualan');
      $this->db->offset($offset);
      $this->db->limit($limit);
      $this->db->order_by('tgl_masukan','DESC');
      return $this->db->get()->result();
    }
    function get_count_data_penjualan(){
      $this->db->select('no');
      $this->db->from('lap_penjualan');
      $this->db->group_by('no_pesanan');
      return $this->db->get()->num_rows();
    }
    function get_count_total_search($date){
      $this->db->select('no');
      $this->db->from('lap_penjualan');
      $this->db->where('tgl_masukan',$date);
      return $this->db->get()->num_rows();
    }
    function tambah_data_penjualan($data = array()){
      $this->db->insert('lap_penjualan',$data);
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    }
    function update_data_penjualan($data=array(),$no){
      $this->db->where('no',$no);
      $this->db->update('lap_penjualan',$data);
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    }
    function delete_data_penjualan($no){
      $this->db->where('no',$no);
      $this->db->delete('lap_penjualan');
      $retVal = ($this->db->affected_rows()!=0) ? true : false ;
      return $retVal;
    }
    function search_data_penjualan($date, $offset, $limit){
      $this->db->select('*');
      $this->db->from('lap_penjualan');
      $this->db->offset($offset);
      $this->db->limit($limit);
      $this->db->where('tgl_masukan',$date);
      return $this->db->get()->result();
    }
    function is_aleady_exis($no_pesanan){
      $this->db->select("no_pesanan");
      $this->db->from("ttrans_pesanan");
      if ($this->db->get()->num_rows()>0) {
        return true;
      }else{
        return false;
      }

    }

    /*laporan pejualan perbulan*/
    /*function get_laporan_penjualan($month,$year){
      $this->db->select('*');
      $this->db->from('lap_penjualan');
      $this->db->where('MONTH(tgl_masukan)',$month);
      $this->db->where('YEAR(tgl_masukan)',$year);
      return $this->db->get()->result();
    }

    function total_transaksi_penjualan($month,$year){
      $this->db->from('lap_penjualan');
      $this->db->where('MONTH(tgl_masukan)',$month);
      $this->db->where('YEAR(tgl_masukan)',$year);
      return $this->db->get()->num_rows();
    }

    function total_keuntungan($month,$year){
      $this->db->select('keuntungan');
      $this->db->from('lap_penjualan');
      $this->db->where('MONTH(tgl_masukan)',$month);
      $this->db->where('YEAR(tgl_masukan)',$year);
      $total=0;
      $keuntungan=$this->db->get()->result();
      foreach ($keuntungan as $a) {
        $total+=$a->keuntungan;
      }
      return $total;
    }

    function total_permodalan($month,$year){
      $this->db->select('modal');
      $this->db->from('lap_penjualan');
      $this->db->where('MONTH(tgl_masukan)',$month);
      $this->db->where('YEAR(tgl_masukan)',$year);
      $total=0;
      $modal=$this->db->get()->result();
      foreach ($modal as $a) {
        $total+=$a->modal;
      }
      return $total;
    }
}

function get_laporan_penjualan($nomor,$bulan) {
  $this->db->select('*');
  $this->db->from('tpesanan');
  $this->db->join('tpesanan as id_user', 'tpesanan as no_pesanan', 'tpesanan as tgl pesan', 'tpesanan as harga_total');    
  $this->db->select('no');
  $this->db->where('MONTH(tgl_pesan)',$bulan);
  $this->db->group_by('no_pesanan');
  $query = $this->db->get();
  if ($query->num_rows() > 0) {
      return $query->result();
      } else {
      return false;
  }
 } 
}*/
 
function get_laporan_penjualan($bulan){
  $this->db->select("*");
    	$this->db->from('ttrans_pesanan');
      $this->db->group_by('no_pesanan');
    	$this->db->order_by('tgl_pesan','ASC');
      return $this->db->get()->result();
      if ($query->num_rows() > 0) {
        return $query->result();
        } else {
        return false;
    }
}
}