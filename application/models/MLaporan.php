<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MLaporan extends CI_Model {

	function get_penjualan($use_limit = true,$offset=0,$limit=10){
		if($use_limit){
			$limitq = 'limit ? offset ?';
		}else{
			$limitq = '';
		}
		$query = "SELECT t.*, mp.metode_pembayaran, mp2.metode_pembayaran  metode_pembayaran_2, m.nama as nama_member, u.nama_lengkap as nama_kasir FROM `transaksi` t 
		LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran
        LEFT JOIN metode_pembayaran mp2 ON mp2.id = t.metode_pembayaran_2
		LEFT JOIN member m ON m.id = t.id_member
		LEFT JOIN `user` u ON u.id = t.id_kasir WHERE date(t.tanggal) >= date(now()) ORDER BY t.tanggal desc ".$limitq;
		
		if($use_limit){
			$get = $this->db->query($query,[$limit,$offset]);
		}else{
			$get = $this->db->query($query);
		}
		return $get->result();
    }

	function get_penjualan_all($from,$to){
		$from = $from == ''?date('Y-m-d'):$from;
		$to = $to == ''?date('Y-m-d'):$to;
		$query = "SELECT t.*, mp.metode_pembayaran, mp2.metode_pembayaran  metode_pembayaran_2, m.nama as nama_member, u.nama_lengkap as nama_kasir FROM `transaksi` t 
		LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran
        LEFT JOIN metode_pembayaran mp2 ON mp2.id = t.metode_pembayaran_2
		LEFT JOIN member m ON m.id = t.id_member
		LEFT JOIN `user` u ON u.id = t.id_kasir WHERE date(t.tanggal) >= ? AND date(t.tanggal) <= ? ORDER BY t.tanggal desc";
		$get = $this->db->query($query,[$from,$to]);
		return $get->result();
    }

	function get_penjualan_total(){
		$query = "SELECT sum(total_pembayaran) sum_total_pembayaran, count(*) total_transaksi FROM transaksi WHERE date(tanggal) >= date(now())";
		return $this->db->query($query)->row();
    }
    
    function get_penjualan_filter($use_limit = true,$from,$to,$offset=0,$limit=10){
		if($use_limit){
			$limitq = 'limit ? offset ?';
		}else{
			$limitq = '';
		}
        $query = "SELECT t.*, mp.metode_pembayaran, mp2.metode_pembayaran  metode_pembayaran_2, m.nama as nama_member, u.nama_lengkap as nama_kasir FROM `transaksi` t 
		LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran
        LEFT JOIN metode_pembayaran mp2 ON mp2.id = t.metode_pembayaran_2
		LEFT JOIN member m ON m.id = t.id_member
		LEFT JOIN `user` u ON u.id = t.id_kasir WHERE date(t.tanggal) >= ? AND date(t.tanggal) <= ? ORDER BY t.tanggal desc ".$limitq;
		if($use_limit){
			$get = $this->db->query($query,[$from,$to, $limit, $offset]);
		}else{
			$get = $this->db->query($query,[$from,$to]);
		}
		return $get->result();
    }

	function get_penjualan_filter_total($from,$to){
		$query = "SELECT sum(total_pembayaran) sum_total_pembayaran, count(*) total_transaksi FROM transaksi WHERE date(tanggal) >= ? AND date(tanggal) <= ?";
		return $this->db->query($query, [$from,$to])->row();
    }

    function get_daftar_produk($no_nota){
        $query = "SELECT *,produk.nama_produk FROM detail_transaksi LEFT JOIN produk ON produk.id = detail_transaksi.id_produk WHERE no_nota = ?";
		$get = $this->db->query($query,$no_nota);
		return $get->result();
    }

    function get_header($no_nota){
		$query = "SELECT t.*, m.nama as nama_member, u.nama_lengkap as nama_kasir, mp.metode_pembayaran nama_metode_pembayaran, mp2.metode_pembayaran as nama_metode_pembayaran_2 FROM `transaksi` t 
		LEFT JOIN member m ON m.id = t.id_member
		LEFT JOIN metode_pembayaran mp ON mp.id = t.metode_pembayaran
		LEFT JOIN metode_pembayaran mp2 ON mp2.id = t.metode_pembayaran_2
		LEFT JOIN `user` u ON u.id = t.id_kasir WHERE t.no_nota = ?";
		$get = $this->db->query($query,$no_nota);
		return $get->row();
    }
}
?>