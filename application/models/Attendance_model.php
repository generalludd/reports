<?php
class Attendance_model extends MY_Model {
	
	var $kStudent;
	var $term;
	var $year;
	var $absent;
	var $tardy;
	
	function prepare_variables(){
		
	}
	
	function get($kStudent,$term,$year){
		$this->db->from("attendance");
		$this->db->where("kStudent",$kStudent);
		$this->db->where("term",$term);
		$this->db->where("year",$year);
		$result = $this->db->get()->row();
		return $result;
	}
	
	function update($kStudent,$term,$year,$absent,$tardy){
		$query = sprintf("REPLACE INTO `attendance` (`kStudent`,`term`,`year`,`absent`,`tardy`) VALUES('%s','%s','%s','%s','%s');",$kStudent,$term,$year,$absent,$tardy);
		echo $query;
		$this->db->query($query);
	}
}