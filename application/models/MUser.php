<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MUser extends CI_Model {

	function get_user($offset,$limit=null){
		if($offset == null){
			$query = "SELECT * FROM user ORDER BY nama_lengkap asc";
		}else{
			$query = "SELECT * FROM user ORDER BY id desc limit ? offset ?";
		}
		$get = $this->db->query($query,[$limit,$offset]);
		return $get->result();
	}

	function get_user_by_username($username){
		$query = "SELECT * FROM user WHERE nama_pengguna = ?";
		return $this->db->query($query,[$username])->result();
	}

	function list_user_type(){
		return $this->db->get('user_tipe')->result();
	}

	function get_user_search($key,$offset,$limit){
		$this->db->like('nama_pengguna', $key);
		$this->db->or_like('nama_lengkap', $key);
		$this->db->offset($offset);
		$this->db->limit($limit);
		$get = $this->db->get('user');
		return $get->result();
	}

	function get_user_all(){
		$query = "SELECT * FROM user ORDER BY nama_user asc";
		$get = $this->db->query($query);
		return $get->result();
	}

	function add($data){
		$this->db->insert('user',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('id',$data['id']);
		$this->db->update('user',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->db->where('id',$id);
		$this->db->delete('user');
		return $this->db->affected_rows();
	}
}
?>