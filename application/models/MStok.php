<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MStok extends CI_Model {

	function get_stok($tgl,$q,$supplier_id,$io,$offset,$limit){
		$q = '%'.$q.'%';
		$supplier_id = '%'.$supplier_id.'%';
		$io = '%'.$io.'%';
		$tgl = '%'.$tgl.'%';
		$q_supp = '';

		if($limit > 0){
			$q_page = "limit ? offset ?";
		}
		
		$query = "SELECT sio.*, p.barcode, p.nama_produk, sp.nama_supplier, u.nama_lengkap as dibuat_oleh FROM stock_in_out sio
		LEFT JOIN produk p ON p.id = sio.produk_id
		LEFT JOIN supplier sp ON sp.id = sio.supplier_id 
		LEFT JOIN user u ON u.id = sio.dibuat_oleh WHERE  tanggal LIKE ? AND (sio.supplier_id LIKE ? AND (in_out LIKE ?
		AND (u.nama_lengkap LIKE ? OR p.barcode LIKE ? OR p.nama_produk LIKE ? OR sio.keterangan LIKE ?)))  ORDER BY sio.id desc ".$q_page;
		if($limit > 0){
			$get = $this->db->query($query,[$tgl,$supplier_id,$io,$q,$q,$q,$q,$limit,(int)$offset]);
		}else{
			$get = $this->db->query($query,[$tgl,$supplier_id,$io,$q,$q,$q,$q]);
		}
		
		return $get->result();
	}

	function get_stok_all(){
		$query = "SELECT * FROM stok ORDER BY nama_stok asc";
		$get = $this->db->query($query);
		return $get->result();
	}

	function add($data){
		$this->db->insert('stok',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('id',$data['id']);
		$this->db->update('stok',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->db->where('id',$id);
		$this->db->delete('stok');
		return $this->db->affected_rows();
	}

	function get_stok_by_id($id){
		return $this->db->query('SELECT * FROM stok WHERE id = ?',[$id])->row();
	}

	function insert_stock_in_out($data){
		$this->db->insert('stock_in_out',$data);
	}

	function data_supplier(){
		return $this->db->query('SELECT * FROM supplier ORDER BY nama_supplier')->result();
	}
}
?>