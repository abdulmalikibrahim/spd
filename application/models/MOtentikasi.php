<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MOtentikasi extends CI_Model {

	function getdatauser($username){
		$query = "SELECT * FROM user WHERE nama_pengguna = ?";
		$get = $this->db->query($query,$username);
		return $get->row();
	}
}
?>