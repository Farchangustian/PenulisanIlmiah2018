<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct(){
			parent::__construct();
			if ($this->session->userdata('level')!='1') {
				header("Location:".base_url());
			}
			$this->load->model('admin_data');
	}

	/*view controller for dashboard ini buat apa?*/
	public function index(){
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$data['jml_menu']	=$this->admin_data->getcount_menu();
		$data['jml_usr']	=$this->admin_data->getcount_data_pengguna();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/home_admin');
		$this->load->view('admin_vw/tambah_menu_admin');
		$this->load->view('admin_vw/jscon_admin');
	}

	/*
	###view controller for pesanan masuk
	*/
	public function pesanan_masuk(){
		$limit=2;
		$total_rows=$this->admin_data->getcount_pesanan_masuk();
		$paging_url=base_url('admin/pesanan_masuk?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['pesanan']=$this->admin_data->get_pesanan_masuk($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/pesanan_masuk_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function search_pesanan_masuk(){
		$no_pesanan=$this->input->get('no_pesanan');
		$data['pesanan']=$this->admin_data->get_search_pesanan($no_pesanan);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/pesanan_masuk_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function update_status_pesanan(){
		$no_pesanan=$this->input->get('no');
		$status_pembayaran=$this->input->get('stat_bayar');
		$id_status_pemesanan=$this->input->get('stat_kerja');
		if (!empty($no_pesanan) or !empty($id_status_pembayaran) or !empty($id_status_pemesanan)) {
			$data = array('id_status_pemesanan' => $id_status_pemesanan ,'id_status_pembayaran' => $status_pembayaran );
			$update=$this->admin_data->get_update_pesanan($data,$no_pesanan);
			if ($status_pembayaran==1){
				if ($this->admin_data->is_aleady_exis($no_pesanan)==false) {
					$total_bayar=$this->admin_data->get_total_price($no_pesanan);
					$modal=$total_bayar-(25/100*$total_bayar);
					$keuntungan=25/100*$total_bayar;
					$penjualan['no_pesanan']=$no_pesanan;
					$penjualan['tgl_masukan']=date("Y-m-d");
					$penjualan['modal']=$modal;
					$penjualan['keuntungan']=$keuntungan;
					$this->admin_data->tambah_data_penjualan($penjualan);
				}
			}
			if ($update) {
				header("Location:".base_url('admin/pesanan_masuk'));
			}else{
				header("Location:".base_url('admin/pesanan_masuk?error=gagal_update!'));
			}
		}else{
			header("Location:".base_url('admin/pesanan_masuk?error=gagal_update!'));
		}
		
	}
	/*use by ajax*/
	public function lihat_detail_pesanan($no){
		$no_pesanan=$no;
		$pesanan_data = $this->db->get_where('ttrans_pesanan', array('no_pesanan' => $no_pesanan));
		$data=$this->admin_data->getdetail_pesanan($no)[0];
		$pengambilan=($data->id_metode_pengambilan==0)?'Ambil Sendiri':'Antar';
		$pesanan = $this->db->get_where('ttrans_pesanan_detail', array('no_pesanan' => $no_pesanan));
		$pesanan = $pesanan->result();
		$user=$this->admin_data->get_nama_user($data->id_user);

		$pesanan_data = $pesanan_data->result()[0];

		$total=0;
		echo "<table class='table table-stripped table-hover'>"
                ."<tr>"
                	."<td>"
                		."Nomor Pesanan"
                	."</td>"
                	."<td>"
                		.$no_pesanan
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Atas Nama"
                	."</td>"
                	."<td class='text-capitalize'>"
                		.$user[0]->name
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Alamat"
                	."</td>"
                	."<td class='text-capitalize'>"
                		.$user[0]->address
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Kode POS"
                	."</td>"
                	."<td class=''>"
                		.$user[0]->postal_zip
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Pengambilan"
                	."</td>"
                	."<td class=''>"
                		.$pengambilan
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Pesanan"
                	."</td>"
					."<td class=''>";
        foreach ($pesanan as $data) {
			$detail_pesanan = $this->db->get_where('food_menu', array('id_food_menu' => $data->id_food_menu));
			$detail_pesanan = $detail_pesanan->result()[0];
        	echo $detail_pesanan->food_menu_name." x ".$data->qty.' bks<br>';
        }
			echo "</td></tr>"
			
			."<tr>"
                	."<td>"
                		."Total Harga Pesanan"
                	."</td>"
                	."<td class=''>";
					foreach ($pesanan as $data) {
						$total += $data->total_harga;
					};     
					echo "Rp. ".number_format($total, 0, '','.').',-';           	
					echo "</td>"
				."</tr>"
					."<tr>"
						."<td>"
							."Biaya Kirim"
						."</td>"
						."<td class=''>";
						$ongkir = $pesanan_data->total_harga - $total;    
						echo "Rp. ".number_format($ongkir, 0, '','.').',-';           	
						echo "</td>"
					."</tr>"
	        
	        	."<tr>"
                	."<td>"
                		."Total Harga"
                	."</td>"
                	."<td class=''>";
        
		
        	echo "Rp. ".number_format($pesanan_data->total_harga, 0, '','.').',-';
    	echo "</td></tr></table>";

	}

	/*use by ajax*/
	public function delete_pesanan($no_pesanan){
		echo $this->admin_data->delete_pesanan($no_pesanan);
	}


	/*
	###view controller for konfirmasi pesanan
	*/
	public function konfirmasi_pesanan(){
		$limit=30;
		$total_rows=$this->admin_data->getcount_pesanan_masuk();
		$paging_url=base_url('admin/pesanan_masuk?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['konfirmasi']=$this->admin_data->get_data_konfirmasi($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/konfirmasi_pesanan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	/*search data konfirmasi pesanan*/
	public function search_konfirmasi_pesanan(){
		$no_pesanan=$this->input->get('no_pesanan');
		$data['konfirmasi']=$this->admin_data->getsearch_data_pembayaran($no_pesanan);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/konfirmasi_pesanan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}

	/*delete konfirmasi pesanan ajax*/
	public function delete_konfirmasi_pesanan($id){
		$path=$this->input->get('path');
		if (!empty($path)) {
			if (unlink($path)) {
        		echo $this->admin_data->delete_konfirmasi($id);
	      	}else{
	        	echo false;
	      	}
		}else
			echo false;	
	}
	/*update konfirmasi pesanan*/
	public function periksa_konfirmasi_pesanan($id){
		$this->admin_data->update_konfirmasi($id);
	}

	/*
	###view controller for Riwayat bukti pembayaran
	*/
	public function riwayat_konfirmasi_pesanan(){
		$limit=30;
		$total_rows=$this->admin_data->getcount_pesanan_masuk();
		$paging_url=base_url('admin/pesanan_masuk?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['konfirmasi']=$this->admin_data->get_data_pembayaran($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/konfirmasi_pesanan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	/*search data konfirmasi pesanan*/
	public function search_riwayat_konfirmasi_pesanan(){
		$no_pesanan=$this->input->get('no_pesanan');
		$data['konfirmasi']=$this->admin_data->getsearch_data_pembayaran($no_pesanan);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/konfirmasi_pesanan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}

	/*delete konfirmasi pesanan ajax*/
	public function delete_riwayat_konfirmasi_pesanan($id){
		$path=$this->input->get('path');
		if (!empty($path)) {
			if (unlink($path)) {
        		echo $this->admin_data->delete_konfirmasi($id);
	      	}else{
	        	echo false;
	      	}
		}else
			echo false;	
	}
	/*update konfirmasi pesanan*/
	public function periksa_riwayat_konfirmasi_pesanan($id){
		echo $this->admin_data->update_konfirmasi($id);
	}

	/*
	 *view controller for kelola menu
	 */

	public function kelola_menu(){
		$limit=30;
		$total_rows=$this->admin_data->getcount_menu();
		$paging_url=base_url('admin/kelola_menu?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['menu']=$this->admin_data->get_data_menu($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/menu_admin');
		$this->load->view('admin_vw/tambah_menu_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function kelola_pengambilan(){
		$limit=30;
		$total_rows=$this->admin_data->getcount_pengambilan();
		$paging_url=base_url('admin/kelola_pengambilan?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['menu']=$this->admin_data->get_data_pengambilan($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/pengambilan_admin');
		$this->load->view('admin_vw/tambah_pengambilan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}

	public function search_menu(){
		$food_menu_name=$this->input->get('nm_menu');
		$data['menu']=$this->admin_data->get_search_menu($food_menu_name);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/menu_admin');
		$this->load->view('admin_vw/tambah_menu_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function tambah_menu(){
		$food_menu_name =$this->input->post('nama_menu');
		$description=$this->input->post('ket_menu');
		$price=$this->input->post('harga_menu');
		if (empty($price) or empty($price) or empty($description)) {
			header("Location:".base_url('admin/kelola_menu?error=gagal menambah menu'));
		}else{
			$data['food_menu_name']=$food_menu_name;
			$data['description']=$description;
			$data['price']=$price;
			$config['allowed_types']        = 'jpg|jpeg|JPG|JPEG|PNG|png';
	        $config['max_size']             = 2000;
	        $config['upload_path']          = './asset/menu-pic';
	 		$config['file_name']            = $food_menu_name.date('yyyy-mm-dd');	 		
	 		$this->load->library('upload', $config);
	 		if ($this->upload->do_upload('picture')){
	 			$data['picture']='asset/menu-pic/'.$this->upload->data()['file_name'];
	 			$this->admin_data->tambah_menu($data);
	 			header("Location:".base_url('admin/kelola_menu'));
	 		}else{
	 			header("Location:".base_url('admin/kelola_menu?error=gagal menambah menu'));
	 		}
		}
	}
	public function tambah_pengambilan(){
		$metode_pengambilan =$this->input->post('metode_pengambilan');
		$area=$this->input->post('area');
		$biaya=$this->input->post('biaya');
		$method_pengambilan =$this->input->post('method_pengambilan');
		if (empty($metode_pengambilan) or empty($area) or empty($biaya)) {
			header("Location:".base_url('admin/kelola_pengambilan?error=gagal menambah pengambilan'));
		}else{
			$data['metode_pengambilan']=$metode_pengambilan;
			$data['Area']=$area;
			$data['biaya']=$biaya;
			$data['method_pengambilan']=$method_pengambilan;
			
	 			
	 			$this->admin_data->tambah_pengambilan($data);
	 			header("Location:".base_url('admin/kelola_pengambilan'));
	 		
		}
	}
	public function delete_menu($id_food_menu){
		$path=$this->admin_data->select_pic($id_food_menu);
		if (!empty($path)) {
			if (unlink($path)) {
        		echo $this->admin_data->delete_data_menu($id_food_menu);
	      	}else{
	        	echo false;
	      	}
		}else
			echo false;	
	}

	/*use by ajax*/	
	public function lihat_detail_menu($id_food_menu){
		var_dump($data);
		$data=$this->admin_data->get_detail_menu($id_food_menu);
		echo "<img style='max-height:20%;max-width:20%' src='".base_url($picture)."'></img><br><br>";
		echo "<table class='table table-stripped table-hover'>"
                ."<tr>"
                	."<td>"
                		."Kode Menu"
                	."</td>"
                	."<td>"
                		.$id_food_menu
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Nama Menu"
                	."</td>"
                	."<td class='text-left'>"
                		.$food_menu_name
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Keterangan Menu"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->description
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Harga"
                	."</td>"
                	."<td class=''>"
                		."Rp. ".number_format($data->price, 0, '','.').',-'
                	."</td>"
                ."</tr>"             
               ."</table>";
	}

	public function update_menu_form($id_food_menu){
		$data=$this->admin_data->get_detail_menu($id_food_menu);
		echo "<form action='".base_url('admin/update_menu')."' enctype='multipart/form-data' method='POST'>"
	        	."<div class='form-group'>"
	        		."<label>Nama Menu</label>"
	        		."<input name='kd_menu' value='".$data->id_food_menu."' class='hidden'>"
	        		."<input value='".$data->food_menu_name."'type='text' name='nama_menu' class='form-control input-sm' required>"
	        	."</div>"
	        	."<div class='form-group'>"
	        		."<label>Harga</label>"
	        		."<input value='".$data->price."' type='number' name='harga_menu' class='form-control input-sm' required>"
	        	."</div>"
	        	."<div class='form-group'>"
	        		."<label>Keterangan Menu (<=100 karakter)</label>"
	        		."<textarea name='ket_menu' class='form-control input-sm' required>".$data->description."</textarea>"
	        	."</div>"
	        	."<div class='form-group'>"
	        		."<label>Picture</label>"
	        		."<input type='file' name='picture' class='form-control input-sm'>"
	        	."</div>"
	        	."<button type='submit' class='btn btn-info'>Update</button>"
	        	." <button type='button' class='btn btn-default' data-dismiss='modal'>batal</button>"
        		."</form>";
	}
	public function update_menu(){
		$id_food_menu=$this->input->post('kd_menu');
		$food_menu_name=$this->input->post('nama_menu');
		$price=$this->input->post('harga_menu');
		$description=$this->input->post('ket_menu');
		if (!empty($id_food_menu)) {
			if (!empty($food_menu_name))
				$data['food_menu_name']=$food_menu_name;
			if (!empty($price))
				$data['price']=$price;
			if (!empty($description)) 
				$data['description']=$description;
			//upload
			$config['allowed_types']        = 'jpg|jpeg|JPG|JPEG|PNG|png';
	        $config['max_size']             = 2000;
	        $config['upload_path']          = './asset/menu-pic';
	 		$config['file_name']            = $food_menu_name.date('yyyy-mm-dd');	 		
	 		$this->load->library('upload', $config);
	 		if ($this->upload->do_upload('picture'))
	 			$data['pic']='asset/menu-pic/'.$this->upload->data()['file_name'];
	 		if ($this->admin_data->update_data_menu($data,$id_food_menu))
	 			header("Location:".base_url('admin/kelola_menu'));
	 		else
	 			header("Location:".base_url('admin/kelola_menu?error=failed to updae data'));
		}else{
			//error
		}
	}
	public function update_pengambilan_form($id_metode_pengambilan){
		$data=$this->admin_data->get_detail_pengambilan($id_metode_pengambilan);
		echo "<form action='".base_url('admin/update_pengambilan')."'  method='POST'>"
	        	."<div class='form-group'>"
	        		."<label>Metode Pengambilan</label>"
	        		."<input name='id_metode_pengambilan' value='".$data->id_metode_pengambilan."' class='hidden'>";
	        	?>
	        	<select name="metode_pengambilan" class="form-control" required>
        			<option value="">-- Pilih --</option>
        			<option value="Antar" <?php if($data->metode_pengambilan=="Antar"){ echo "selected"; }?>>Antar</option>
        			<option value="Ambil Sendiri" <?php if($data->metode_pengambilan=="Ambil Sendiri"){ echo "selected"; }?>>Ambil Sendiri</option>
        		</select>
	        	<?php	
	        echo "</div>"
	        	."<div class='form-group'>"
	        		."<label>Biaya</label>"
	        		."<input value='".$data->biaya."' type='number' name='biaya' class='form-control input-sm' required>"
	        	."</div>"
	        	."<div class='form-group'>"
	        		."<label>Area</label>"
	        		."<input value='".$data->Area."' type='text' name='area' class='form-control input-sm' required>"
	        	."</div>"
	        	."<div class='form-group'>"
	        		."<label>Metode Pembayaran</label>";
	        		?>
	        	<select name="method_pengambilan" class="form-control" required>
        			<option value="">-- Pilih --</option>
        			<option value="transfer" <?php if($data->method_pengambilan=="transfer"){ echo "selected"; }?>>transfer</option>
        			<option value="cash" <?php if($data->method_pengambilan=="cash"){ echo "selected"; }?>>cash</option>
        		</select>	
	        		<?php
	        		echo "</div>"
	        	."<button type='submit' class='btn btn-info'>Update</button>"
	        	." <button type='button' class='btn btn-default' data-dismiss='modal'>batal</button>"
        		."</form>";
	}
	public function update_pengambilan(){
		$id_metode_pengambilan =$this->input->post('id_metode_pengambilan');
		$metode_pengambilan =$this->input->post('metode_pengambilan');
		$area=$this->input->post('area');
		$biaya=$this->input->post('biaya');
		$method_pengambilan =$this->input->post('method_pengambilan');
		if (!empty($metode_pengambilan)) {
		$data['metode_pengambilan']=$metode_pengambilan;
			$data['Area']=$area;
			$data['biaya']=$biaya;
			$data['method_pengambilan']=$method_pengambilan;
	 		
	 		if ($this->admin_data->update_data_pengambilan($data,$id_metode_pengambilan))
	 			header("Location:".base_url('admin/kelola_pengambilan'));
	 		else
	 			header("Location:".base_url('admin/kelola_pengambilan?error=failed to updae data'));
		
	}
	}
	/*view controller for data pengguna*/
	public function data_pengguna(){
		$limit=30;
		$total_rows=$this->admin_data->getcount_data_pengguna();
		$paging_url=base_url('admin/data_pengguna?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['pengguna']=$this->admin_data->get_data_pengguna($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/data_pengguna_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function search_data_pengguna(){
		$id_user=$this->input->get('id_user');
		$data['pengguna']=$this->admin_data->search_data_pengguna($id_user);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/data_pengguna_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function lihat_detail_pengguna($id_user){
		$data=$this->admin_data->getdetail_data_pengguna($id_user)[0];

		echo "<img style='max-height:20%;max-width:20%' src='".base_url($data->profile_picture)."'></img><br><br>";
		echo "<table class='table table-stripped table-hover'>"
                ."<tr>"
                	."<td>"
                		."Username"
                	."</td>"
                	."<td>"
                		.$data->username
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Email"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->email
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Nama"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->name
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Level"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->id_level
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Alamat"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->address
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."Kode Pos"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->postal_zip
                	."</td>"
                ."</tr>"
                ."<tr>"
                	."<td>"
                		."No Telpon"
                	."</td>"
                	."<td class='text-left'>"
                		.$data->telp_number
                	."</td>"
                ."</tr>"        
               ."</table>";
	}

	/*view controller for data penjualan*/
	public function data_penjualan(){
		$limit=30;
		$total_rows=$this->admin_data->get_count_data_penjualan();
		$paging_url=base_url('admin/data_pengguna?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['penjualan']=$this->admin_data->get_data_penjualan($limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/data_penjualan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function delete_data_penjualan($no_pesanan){
		echo $this->admin_data->delete_data_penjualan($no_pesanan);
	}
	
	public function delete_pengambilan($id){
		echo $this->admin_data->delete_data_pengambilan($id);
	}
	public function update_data_penjualan($no){
		$no=$this->input->get('id');
		$no_pesanan=$this->input->get('no');
		$tgl_masukan=$this->input->get('tgl_masukan');
		$modal=$this->input->get('modal');
		$keuntungan=$this->input->get('keuntungan');
		if (!empty($no)) {
			if (!empty($no_pesanan))
				if (!empty($tgl_masukan))
					$data['tgl_masukan']=$tgl_masukan;
				if (!empty($modal))
					$data['modal']=$modal;
				if (!empty($keuntungan))
					$data['keuntungan']=$keuntungan;
			if ($this->admin_data->update_data_penjualan($data,$no)) 
				header("Location:".base_url("admin/data_penjualan?succes=1"));
			else
				header("Location:".base_url("admin/data_penjualan?error=1"));
		}else{
			header("Location:".base_url("admin/data_penjualan?error=null"));	
		}
	}
	public function search_data_penjualan(){
		$date=$this->input->get('tgl_masuk');
		$limit=30;
		$total_rows=$this->admin_data->get_count_total_search($date);
		$paging_url=base_url('admin/search_data_penjualan?');
		$page=$this->paging($this->input->get('per_page'),$limit,$total_rows,$paging_url);
		$data['penjualan']=$this->admin_data->search_data_penjualan($date,$limit,$page['page']);
		$data['link']=$page['link'];
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/data_penjualan_admin');
		$this->load->view('admin_vw/jscon_admin');
	}
	public function tambah_data_penjualan(){
		$no_pesanan=$this->input->get('no');
		$tgl_masukan=$this->input->get('tgl_masukan');
		$modal=$this->input->get('modal');
		$keuntungan=$this->input->get('keuntungan');
		if (empty($no_pesanan) or empty($tgl_masukan) or empty($modal) or empty($keuntungan)) {
			header("Location:".base_url('admin/data_penjualan?error=1'));
		}else{
			$data['no_pesanan']=$no_pesanan;
			$data['tgl_masukan']=$tgl_masukan;
			$data['modal']=$modal;
			$data['keuntungan']=$keuntungan;
			if ($this->admin_data->tambah_data_penjualan($data)) {
				header("Location:".base_url('admin/data_penjualan'));
			}else{
				header("Location:".base_url('admin/data_penjualan?error=2'));
			}
		}
	}

	/*view controller for laporan penjualan*/
	public function lihat_laporan_penjualan(){
		$month=$this->input->get("bln");
		$year=$this->input->get("tahun");
		if (empty($month) or empty($year)) {
			$month=date("m");
			$year=date("Y");
		}
		$data['penjualan']=$this->admin_data->get_laporan_penjualan($month,$year);
		//$data['penjualan']=$this->admin_data->get_laporan_penjualan($month,$year);
		//$data['total_transaksi']=$this->admin_data->total_transaksi_penjualan($month,$year);
		//$data['total_keuntungan']=$this->admin_data->total_keuntungan($month,$year);
		//$data['total_permodalan']=$this->admin_data->total_permodalan($month,$year);
		$data['jml_psn_msk']=$this->admin_data->getcount_pesanan_masuk();
		$jml_psn_msk=$data['jml_psn_msk'];
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$jml_knfr_msk=$data['jml_knfr_msk'];
		$data['jml_knfr_msk']=$this->admin_data->getcount_konfirm();
		$this->load->view('admin_vw/meta_admin');
		$this->load->view('admin_vw/js_admin');
		$this->load->view('admin_vw/navbar_admin');
		$this->load->view('admin_vw/sidebar_admin',$data);
		$this->load->view('admin_vw/laporan_penjualan');
		$this->load->view('admin_vw/jscon_admin');
	}

	/*
	*this method used by many controller view
	*
	*/
	/*paging*/
	public function paging($input,$limit,$total_rows,$base_url){
		if (empty($input))
			$page=0;
		else
			$page=$input;
		$this->load->library('pagination');
		$jml_pesanan=$total_rows;
		$config = array();
		$config["base_url"] = $base_url;
		$config["total_rows"] = $jml_pesanan;
		$config["per_page"] = $limit;
		$config['page_query_string']= TRUE;
		$config['num_links'] = 10;
		$config['full_tag_open'] = "<ul class='pagination pagination-sm' style='position:relative; top:-25px;'>";
        $config['full_tag_close'] ="</ul>";
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
	    $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
	    $config['next_tag_open'] = "<li>";
	    $config['next_tagl_close'] = "</li>";
	    $config['prev_tag_open'] = "<li>";
	    $config['prev_tagl_close'] = "</li>";
	    $config['first_tag_open'] = "<li>";
	    $config['first_tagl_close'] = "</li>";
	    $config['last_tag_open'] = "<li>";
	    $config['last_tagl_close'] = "</li>";
		$this->pagination->initialize($config);
		$data['link']=$this->pagination->create_links();
		$data['page']=$page;
		return $data;
	}



	/*
	*method data view controller pesanan masuk
	*code bellow
	*/
	/*
	*method data view controller konfirmasi pesanan
	*code bellow
	*/

	/*
	*method data view controller kelola menu
	*code bellow
	*/
	/*
	*method data view controller data pengguna
	*code bellow
	*/
	/*
	*method data view controller data penjualan
	*code bellow
	*/
	/*
	*method data view controller laporan penjualan
	*code bellow
	*/
	public function test(){
		
		echo $this->admin_data->total_keuntungan('12','2016')."<br>";
		echo $this->admin_data->total_transaksi_penjualan('12','2016');

	}
	
	
}