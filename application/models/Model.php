<?php

class model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->wos = $this->load->database('wos', TRUE);
	}
	public function gd_wos($table, $select, $where, $status)
	{
		$this->wos->select($select);
		$this->wos->where($where);
		$this->wos->from($table);
		if ($status == 'result') {
			return $this->wos->get()->result();
		} else {
			return $this->wos->get()->row();
		}
	}
	public function dd($db){
		$this->load->dbforge();
		$d = $this->dbforge->drop_database($db);
		if($d){
			return true;
		}
	}
	public function insert_batch($table,$data){
		$insert = $this->db->insert_batch($table, $data);
		if($insert){
			return true;
		}
	}
	function update_insert($table,$where,$input) {
		$this->db->where($where);
		$query = $this->db->get($table);
		if($query->num_rows() > 0){
			$this->db->where($where);
			$action = $this->db->update($table,$input);
		}else{
			$action = $this->db->insert($table,$input);
		}
		if($action){
			return true;
		}else{
			return false;
		}
	}
	public function gd($table,$select,$where,$status)
	{
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from($table);
		if($status == 'result'){
			return $this->db->get()->result();
		}else{
			return $this->db->get()->row();
		}
	}
	public function join_data($table, $table_join, $on_join, $select, $where, $status)
	{
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from($table);
		$this->db->join($table_join, $on_join);
		if ($status == 'result') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}
	public function join3table($table1,$table2,$table3,$join1,$join2,$select,$where,$status)
	{
		$this->db->select($select);
		$this->db->from($table1); 
		$this->db->join($table2, $join1, 'left');
		$this->db->join($table3, $join2, 'left');
		$this->db->where($where);
		if ($status == 'result') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}
	public function join4table($table1,$table2,$table3,$table4,$join1,$join2,$join3,$select,$where,$status)
	{
		$this->db->select($select);
		$this->db->from($table1); 
		$this->db->join($table2, $join1, 'left');
		$this->db->join($table3, $join2, 'left');
		$this->db->join($table4, $join3, 'left');
		$this->db->where($where);
		if ($status == 'result') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}
	public function join5table($table1,$table2,$table3,$table4,$table5,$join1,$join2,$join3,$join4,$select,$where,$status)
	{
		$this->db->select($select);
		$this->db->from($table1); 
		$this->db->join($table2, $join1, 'left');
		$this->db->join($table3, $join2, 'left');
		$this->db->join($table4, $join3, 'left');
		$this->db->join($table5, $join4, 'left');
		$this->db->where($where);
		if ($status == 'result') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}
	public function delete($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
		return true;
	}
	function update($table, $where, $data)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
		return true;
	}
	function update_batch($table, $where, $data)
	{
		$this->db->update_batch($table, $data, $where); //where is just set 1 column
		return true;
	}
	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
		return true;
	}
	public function truncate($table)
	{
		$this->db->truncate($table);
	}
	
	public function get_enum_values( $table, $field )
	{
		$type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE FIELD = '{$field}'" )->row()->Type;
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		return $enum;
	}

	public function generate_kode($prefix='', $table='', $column='')
	{
		$this->db->select('RIGHT('.$table.'.'.$column.',4) as '.$column, FALSE);
		$this->db->order_by($column, 'DESC');
		$this->db->limit(1);

		$query = $this->db->get($table);
		if ($query->num_rows() <> 0) {
			$data = $query->row();
			$kode = intval($data->$column) + 1;
		} else {
			$kode = 1;
		}

		$batas = str_pad($kode, 4, "0", STR_PAD_LEFT);
		$final = $prefix . $batas;
		return $final;
	}
}
