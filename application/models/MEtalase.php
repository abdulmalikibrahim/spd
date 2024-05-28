<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MEtalase extends CI_Model {

	function get_etalase($q,$offset,$limit){
		$q = '%'.$q.'%';

		$q_page = '';
		if($limit > 0){
			$q_page = "limit ? offset ?";
		}
		
		$query = "SELECT * FROM etalase WHERE nama_etalase LIKE ? ORDER BY nama_etalase ".$q_page;
		if($limit > 0){
			$get = $this->db->query($query,[$q,(int)$limit,(int)$offset]);
		}else{
			$get = $this->db->query($query,[$q]);
		}
		
		return $get->result();
	}

	function add($data){
		$this->db->insert('etalase',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('id',$data['id']);
		$this->db->update('etalase',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->db->where('id',$id);
		$this->db->delete('etalase');
		return $this->db->affected_rows();
	}

	function get_etalase_by_id($id){
		return $this->db->query('SELECT * FROM etalase WHERE id = ?',[$id])->row();
	}

	function data_supplier(){
		return $this->db->query('SELECT * FROM supplier ORDER BY nama_supplier')->result();
	}
}
?>