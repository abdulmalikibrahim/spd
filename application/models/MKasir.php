<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MKasir extends CI_Model {

	function findingProductByBarcode($barcode){
		$query = "SELECT *, id as id_produk FROM produk WHERE barcode = ? ";
		$get = $this->db->query($query, [$barcode]);
		return $get->row();
	}

	function list_metode_pembayaran(){
		return $this->db->query('SELECT * FROM metode_pembayaran ORDER BY id')->result();
	}

	function findingProductById($id){
		$query = "SELECT * FROM produk WHERE id = ? ";
		$get = $this->db->query($query, [$id]);
		return $get->row();
	}

	function keranjangByKasir($id_kasir, $id_pelanggan){
		$query = "SELECT *, keranjang.id id FROM keranjang LEFT JOIN produk ON produk.id = keranjang.id_produk WHERE id_kasir = ? AND id_pelanggan = ? order by keranjang.id DESC ";
		$get = $this->db->query($query,[$id_kasir, $id_pelanggan]);
		return $get->result();
	}

	function simpanKerangjang($id_pelanggan){
		$this->db->where('id_pelanggan','');
		$this->db->update('keranjang',['id_pelanggan'=>$id_pelanggan]);
		return $this->db->affected_rows();
	}

	function findingKeranjang($id,$id_kasir){
		$query = "SELECT * FROM keranjang LEFT JOIN produk ON produk.id = keranjang.id_produk WHERE id_produk = ? AND id_kasir = ? AND id_pelanggan = ''";
		$get = $this->db->query($query, [$id,$id_kasir]);
		return $get->row();
    }
    
	function updateQuantity($qty,$id,$id_kasir){
        $data_temp['kuantitas'] = $qty;
		$this->db->where('id_produk',$id);
		$this->db->where('id_kasir',$id_kasir);
		$this->db->update('keranjang',$data_temp);
		return $this->db->affected_rows();
    }
    
    function insertToCart($data){
        $this->db->insert('keranjang',$data);
		return $this->db->affected_rows();
	}
	
	function insertDetailPenjualan($data){
        $this->db->insert('detail_transaksi',$data);
		return $this->db->affected_rows();
	}

	function deleteKeranjang($id_produk, $id_kasir){
		$query = "DELETE FROM keranjang WHERE id_produk = ? AND id_kasir = ?";
		$get = $this->db->query($query, [$id_produk, $id_kasir]);
		return 0;
	}

	function insertPenjualan($data){
        $this->db->insert('transaksi',$data);
		return $this->db->affected_rows();
	}

	function deleteCart($id){
		$this->db->where('id', $id);
		$this->db->delete('keranjang');
		return $this->db->affected_rows();
	}

	function penjualan($nota){
		$q = $this->db->query('SELECT t.*, mp.metode_pembayaran, mp2.metode_pembayaran as metode_pembayaran_2, m.nama as nama_member, u.nama_lengkap as nama_kasir FROM `transaksi` t 
		LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran
		LEFT JOIN metode_pembayaran mp2 ON mp2.id = t.metode_pembayaran_2
		LEFT JOIN member m ON m.id = t.id_member
		LEFT JOIN `user` u ON u.id = t.id_kasir WHERE t.no_nota = ?',[$nota]);
		return $q->row();
	}

	function detailPenjualan($nota){
		return $this->db->query('SELECT dt.*, p.nama_produk FROM detail_transaksi dt LEFT JOIN produk p ON p.id = dt.id_produk where no_nota = ?',[$nota])->result();
	}

	function getSetting($key){
		$data = $this->db->get_where('setting',['key'=>$key])->row();
		return $data->val;
	}

	function data_member($no_hp){
		return $this->db->query('SELECT * FROM member WHERE no_hp = ?',[$no_hp])->row();
	}

	function update_point_member($no_hp,$point){
		return $this->db->query('UPDATE member SET `point` = ? WHERE no_hp = ?',[$point,$no_hp]);
	}

	function insertToMemberDiskonPoint($no_nota,$id_member,$diskon,$sisa_point){
		$this->db->insert('member_diskon_point',[
			'no_nota'=>$no_nota,
			'id_member'=>$id_member,
			'diskon'=>$diskon,
			'sisa_point'=>$sisa_point
		]);
	}

	function updateStock($id_produk,$stock){
		return $this->db->query('UPDATE produk SET `stok` = ? WHERE id = ?',[$stock,$id_produk]);
	}

	function listKeranjang(){
		return $this->db->query("SELECT distinct id_pelanggan FROM keranjang ORDER BY id_pelanggan")->result();
	}

	function deleteCartBuyer($id_pel){
		return $this->db->query("DELETE FROM keranjang WHERE id_pelanggan = ?",[$id_pel]);
	}
}
?>