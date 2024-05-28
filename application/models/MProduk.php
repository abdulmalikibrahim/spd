<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MProduk extends CI_Model {

	function get_produk($q,$offset,$limit,$sort,$by){
		$q = '%'.$q.'%';
		if($sort==''){
			$q_sort = 'p.id desc';
		}else{
			$q_sort = ' '.$sort.' '.$by.' ';
		}
		$query = "SELECT p.*, e.nama_etalase FROM produk p
		LEFT JOIN etalase e ON e.id = p.id_etalase 
		WHERE barcode LIKE ? OR nama_produk LIKE ? ORDER BY ".$q_sort." limit ? offset ?";
		$get = $this->db->query($query,[$q,$q,$limit,(int)$offset]);
		return $get->result();
	}

	function get_produk_all(){
		$query = "SELECT * FROM produk ORDER BY nama_produk asc";
		$get = $this->db->query($query);
		return $get->result();
	}

	function get_by_barcode($barcode){
		return $this->db->query('SELECT * FROM produk WHERE barcode = ?',[$barcode])->row();
	}

	function add($data){
		$this->db->insert('produk',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('id',$data['id']);
		$this->db->update('produk',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->db->where('id',$id);
		$this->db->delete('produk');
		return $this->db->affected_rows();
	}

	function get_produk_by_id($id){
		return $this->db->query('SELECT * FROM produk WHERE id = ?',[$id])->row();
	}

	function insert_stock_in_out($data){
		$this->db->insert('stock_in_out',$data);
	}

	function data_supplier(){
		return $this->db->query('SELECT * FROM supplier ORDER BY nama_supplier')->result();
	}

	function data_etalase(){
		return $this->db->query('SELECT * FROM etalase ORDER BY nama_etalase')->result();
	}
}
?>