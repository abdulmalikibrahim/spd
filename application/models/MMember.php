<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MMember extends CI_Model {

	function get_member_by_phone($phone){
		$query = "SELECT * FROM member WHERE no_hp = ? LIMIT 1 OFFSET 0";
		$get = $this->db->query($query,[$phone]);
		return $get->row();
	}

	function add($data){
		$this->db->insert('member',$data);
		return $this->db->affected_rows();
	}

	function update($data){
		$this->db->where('id',$data['id']);
		$this->db->update('member',$data);
		return $this->db->affected_rows();
	}

	function delete_by_id($id){
		$this->db->where('id',$id);
		$this->db->delete('member');
		return $this->db->affected_rows();
	}
    
	function updateQuantity($qty,$id,$id_kasir){
        $data_temp['kuantitas'] = $qty;
		$this->db->where('id_produk',$id);
		$this->db->where('id_kasir',$id_kasir);
		$this->db->update('keranjang',$data_temp);
		return $this->db->affected_rows();
    }

	function get_member($q,$offset, $limit){
		$query = "SELECT * FROM member WHERE no_hp like ? OR nama like ? ORDER BY nama LIMIT ? OFFSET ?";
		$get = $this->db->query($query,['%'.$q.'%','%'.$q.'%',$limit,$offset]);
		return $get->result();
	}

	function get_member_all($q){
		$query = "SELECT * FROM member WHERE no_hp like ? OR nama like ? ORDER BY nama";
		$get = $this->db->query($query,['%'.$q.'%','%'.$q.'%']);
		return $get->result();
	}
}
?>