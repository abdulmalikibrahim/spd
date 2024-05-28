<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MUserTipe extends CI_Model {

	function get_user_tipe(){
		$query = "SELECT * FROM user_tipe ORDER BY nama_tipe asc";
		$get = $this->db->query($query);
		return $get->result();
	}

	function get_user_akses($q){
		$query = "SELECT * FROM user_akses WHERE nama_akses like ? ORDER BY nama_akses asc";
		$get = $this->db->query($query,['%'.$q.'%']);
		return $get->result();
	}

	function izin($kode_akses){
		$query = "SELECT * FROM user_akses_diizinkan WHERE kode_akses = ? ORDER BY kode_akses asc";
		$get = $this->db->query($query,[$kode_akses]);
		return $get->result();
	}

	function izin_check($kode_akses, $user_tipe){
		$query = "SELECT * FROM user_akses_diizinkan WHERE kode_akses = ? AND user_tipe = ? ORDER BY kode_akses asc";
		$get = $this->db->query($query,[$kode_akses, $user_tipe]);
		return $get->result();
	}

	function izin_create($kode_akses,$user_tipe){
		$this->db->insert('user_akses_diizinkan',['kode_akses'=>$kode_akses, 'user_tipe'=>$user_tipe]);
	}

	function izin_delete($kode_akses,$user_tipe){
		$query = "DELETE FROM user_akses_diizinkan WHERE kode_akses = ? AND user_tipe = ?";
		$this->db->query($query,[$kode_akses, $user_tipe]);
	}

	function get_user_tipe_by_id($id){
		$query = "SELECT * FROM user_tipe WHERE user_tipe = ?";
		$get = $this->db->query($query,[$id]);
		return $get->row();
	}

	function list_user_type(){
		return $this->db->get('user_tipe')->result();
	}

	function add($data){
		$this->db->insert('user_tipe',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('user_tipe',$data['user_tipe']);
		$this->db->update('user_tipe',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->delete_izin($id);
		$this->db->where('user_tipe',$id);
		$this->db->delete('user_tipe');
		return $this->db->affected_rows();
	}

	function delete_izin($id){
		$this->db->where('user_tipe',$id);
		$this->db->delete('user_akses_diizinkan');
		return $this->db->affected_rows();
	}

	function find($user_tipe, $kode_akses){
		$query = "SELECT * FROM user_akses_diizinkan WHERE kode_akses like ? AND user_tipe = ? ORDER BY kode_akses asc";
		$get = $this->db->query($query,[$kode_akses.'%', $user_tipe]);
		return $get->row();
	}
}
?>