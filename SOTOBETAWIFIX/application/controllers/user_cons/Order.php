<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
	public function __construct(){
			parent::__construct();
			$this->load->model('data');
			$this->load->library('pagination');
	}
	public function lihat_pesanan(){
		$limit=30;
		if ($this->session->userdata('login')!=true) {
			header("location:halaman-login.html");
		}else{
			if (empty($this->input->get("per_page"))) {
				$page=0;
			}else{
				$page=$this->input->get("per_page");
			}
			$total_row=$this->data->count_data_pesanan($this->session->userdata('id_user'));
			//change limit here
			$pesanan['data']=$this->data->get_riwayat_pesanan($this->session->userdata('id_user'),$limit,$page);
		}

		$config = array();
		$config["base_url"] = 'lihat-pesanan?';
		$config["total_rows"] = $total_row;
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
		$pesanan['paging']=$this->pagination->create_links();
		//load data pesanan
		$this->load->view('user_vw/meta');
		$this->load->view('user_vw/js');
		$kirim['itemcart']=$this->cart->total_items();
		$this->load->view('user_vw/navbar',$kirim);
		$this->load->view('user_vw/pesanan',$pesanan);
		$this->load->view('user_vw/footer');
		
	}
	public function proses_pesanan(){
		if ($this->session->userdata('login')!=true) {
				header("location:halaman-login.html");
		}else{
			$metodeBayar = $this->input->post('metodeBayar');  
			$metodeAmbil = $this->input->post('metode_pengambilan');  

			$detailAmbil = $this->db->get_where('tmas_metode_pengambilan', 
				array('id_metode_pengambilan' => $metodeAmbil)
			);
			$detailAmbil = $detailAmbil->result()[0];

			if ($metodeBayar == '0') {
				$metodeBayar = 'Transfer';
			} else {
				$metodeBayar = 'Cash';
			}

			$datadiri = array(
				'metodeBayarId' => $this->input->post('metodeBayar'),
				'metodeBayar' => $metodeBayar,
				'metodeKirimId' => $this->input->post('metode_pengambilan'),
				'metodeKirim' => $detailAmbil->biaya
			);

			if ($this->data->cek_data_diri($this->session->userdata('id_user'))) {
				$datadiri['invoice']=$this->data->get_data_diri($this->session->userdata('id_user'));
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$kirim['total_harga']=$this->cart->total();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/invoice',$datadiri);
				$this->load->view('user_vw/footer');
			}else{
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$kirim['total_harga']=$this->cart->total();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/invoice', $datadiri);
				$this->load->view('user_vw/footer');
			}
		}
	}
	public function record_pesanan(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Nama Asli', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('postal_zip', 'Kode Pos', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('tgl_ambil', 'Tanggal Pengambilan', 'trim|required');
		$this->form_validation->set_rules('tgl_pesan', 'Tanggal Pemesanan', 'trim|required');

		$metodeBayar = $this->input->post('metodeBayar');  
		$metodeAmbil = $this->input->post('metode_pengambilan');  

		$detailAmbil = $this->db->get_where('tmas_metode_pengambilan', 
			array('id_metode_pengambilan' => $metodeAmbil)
		);
		$detailAmbil = $detailAmbil->result()[0];

		if ($metodeBayar == '0') {
			$metodeBayar = 'Transfer';
		} else {
			$metodeBayar = 'Cash';
		}

		$datadiri = array(
			'metodeBayarId' => $this->input->post('metodeBayar'),
			'metodeBayar' => $metodeBayar,
			'metodeKirimId' => $this->input->post('metode_pengambilan'),
			'metodeKirim' => $detailAmbil->biaya
		);

		$invoice['nama']=$this->input->post("name");
		$invoice['alamat']=$this->input->post("address");
		$invoice['kd_pos']=$this->input->post("postal_zip");
		$invoice['tgl_ambil']=$this->input->post("tgl_ambil");
		$invoice['metode']=$this->input->post("metode_pengambilan");
		$invoice['hargaMetode']=$this->input->post("hargaMetode");
		$tgl_pesan=date("Y-m-d H:i:s");
		
		if ($this->form_validation->run() == FALSE){
			$kirim['error']="<div class='alert alert-danger col-md-12'>"
				 	         ."<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>".validation_errors()
				 		   ."</div>";
			if ($this->data->cek_data_diri($this->session->userdata('id_user'))) {
				$datadiri['invoice'] = $this->data->get_data_diri($this->session->userdata('id_user'));
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$kirim['total_harga']= $this->cart->total();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/invoice',$datadiri);
				$this->load->view('user_vw/footer');
			}else{
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$kirim['total_harga']=$this->cart->total();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/invoice');
				$this->load->view('user_vw/footer');
			}
		//random_string = digunakan untuk membuat random huruf/angka
		}else{
			$this->load->helper('string');
			$invoice['no_pesanan'] = random_string('numeric', 10);
			$invoice['tgl_pesan']= $tgl_pesan;
			$userdata = array('name' =>$invoice['nama'],
							  'address'=>$invoice['alamat'],
							  'postal_zip'=>$invoice['kd_pos']
						     );
			$this->data->update_profile($userdata, 'tmas_user_detail','id_user',$this->session->userdata('id_user'));
			// foreach ($this->cart->contents() as $items) {
			// 	//insert ke db
			// 	$data['no_pesanan']=$invoice['no_pesanan'];
			// 	$data['id_user']=$this->session->userdata('id_user');
			// 	$data['id_food_menu']=$items['id'];
			// 	$data['tgl_pesan']=$invoice['tgl_pesan'];
			// 	$data['tgl_ambil']=$invoice['tgl_ambil'];
			// 	$data['metode_pengambilan']=$invoice['metode'];
			// 	$data['qty']=$items['qty'];
			// 	$data['harga_total']=$items['qty']*$items['price']; //disini buat jumlahin harga total
			// 	$this->data->insert_into($data,'tpesanan');
			// }


			$data_pesanan = array(
				'no_pesanan' => $invoice['no_pesanan'],
				'id_user' => $this->session->userdata('id_user'),
				'tgl_pesan' => $invoice['tgl_pesan'],
				'tgl_ambil' => $invoice['tgl_ambil'],
				'id_metode_pengambilan' => $invoice['metode'],
				'total_harga' => $this->cart->total() + $invoice['hargaMetode']
			);
			$this->db->insert('ttrans_pesanan', $data_pesanan);

			foreach ($this->cart->contents() as $items) {
				//insert ke db
				$data['no_pesanan'] = $invoice['no_pesanan'];
				$data['id_food_menu'] = $items['id'];
				$data['qty']=$items['qty'];
				$data['total_harga']=$items['qty']*$items['price'];
				$this->data->insert_into($data,'ttrans_pesanan_detail');
			};

			$invoice['itemcart']=$this->cart->total_items();
			$invoice['total_harga']=$this->cart->total() + $invoice['hargaMetode'];
			$this->load->view('user_vw/meta');
			$this->load->view('user_vw/js');
			$this->load->view('user_vw/tagihan',$invoice);
			$this->cart->destroy();
		}
	}
	public function bukti_pembayaran_form(){
			if ($this->session->userdata('login')!=true) {
				header("location:halaman-login.html");
			}else{
				$this->load->view('user_vw/meta');
				$this->load->view('user_vw/js');
				$kirim['itemcart']=$this->cart->total_items();
				$this->load->view('user_vw/navbar',$kirim);
				$this->load->view('user_vw/pembayaran');
				$this->load->view('user_vw/footer');
			}
	}
	public function bukti_pembayaran_proses(){
		$no_pesanan=$this->input->post('no_pesanan');
		if (empty($no_pesanan)) {
			header("Location:upload-bukti-pembayaran.html?error=Nomor pesanan kosong!");
		}else{
			if ($this->data->cek_no_pesanan($no_pesanan)) {
				$data = array('no_pesanan' => $no_pesanan);
				$config['allowed_types']        = 'jpg|jpeg|JPG|JPEG';
		        $config['max_size']             = 3000;
		        $config['upload_path']          = './asset/bukti-pembayaran/';
		 		$config['file_name']            = $no_pesanan;
		 		$this->load->library('upload', $config);
	            if ($this->upload->do_upload('photo')){
	            	$data['bukti_pembayaran']='./asset/bukti-pembayaran/'.$this->upload->data()['file_name'];
	            	$this->data->insert_into($data,'ttrans_pesanan_pembayaran');
	            	header("Location:upload-bukti-pembayaran.html?success=File berhasil diupload, atas nomor pesanan ".$no_pesanan);
	            }else{
	            	//var_dump($this->upload->display_errors());
	            	header("Location:upload-bukti-pembayaran.html?error=Upload error (maksimal ukuran foto : 2mb, format foto harus .JPG)");

	            }
			}else{
				header("Location:upload-bukti-pembayaran.html?error=Nomor pesanan tidak cocok!");
			}
		}
	}

	public function lihat_detail_pesanan($id) {
		$query = $this->db->get_where('ttrans_pesanan_detail', array('no_pesanan' => $id));
		$data = $query->result();

		echo "<table class='table'>
		<tr>
			<td><h5><strong>Menu</td>
			<td><h5><strong>Harga</td>
			<td><h5><strong>Jumlah</td>
			<td><h5><strong>Total</td>
		</tr>
		<tbody>";

		foreach($data as $pesanan) {
			$id_makanan = $pesanan->id_food_menu;
			$query_makanan = $this->db->get_where('food_menu', array('id_food_menu' => $id_makanan));
			$data_makanan = $query_makanan->result()[0];

			echo
				"
				<tr>
					<td><h5>$data_makanan->food_menu_name</td>
					<td><h5>$data_makanan->price</td>
					<td><h5>$pesanan->qty</td>
					<td><h5>$pesanan->total_harga</td>
				</tr>";
		}
		echo "
				</tbody>
			</table>";
	}
}