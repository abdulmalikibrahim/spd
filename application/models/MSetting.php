<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MSetting extends CI_Model {

	function settings($search){
		$query = "SELECT * FROM setting WHERE `key` like ? ORDER BY `key`";
		$get = $this->db->query($query,['%'.$search.'%']);
		return $get->result();
	}
    
	function updateSetting($key, $val){
		$this->db->where('key',$key);
		$this->db->update('setting',['val'=>$val]);
		return $this->db->affected_rows();
    }

	function get($key){
		return $this->db->query("SELECT val FROM setting WHERE `key` = ?",[$key])->row()->val;
	}
}
?>