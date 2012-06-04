<?php defined('BASEPATH') OR exit('No direct script access allowed');

class benchmark_legend_model extends CI_Model
{

	var $kTeach;
	var $subject;
	var $term;
	var $year;
	var $gradeStart;
	var $gradeEnd;
	var $legend;
	var $title;

	function prepare_variables()
	{

		$variables = array("title","term","year","gradeStart","gradeEnd","subject","legend","kTeach");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}

		$this->recModified = mysql_timestamp();
		$this->recModifier = $this->session->userdata('userID');
	}

	/**
	 *
	 * @param array $params
	 * can accept $kTeach, $term, $year, $gradeStart, $gradeEnd, $subject
	 */

	function search($params = array())
	{
		$variables = array("term","year","gradeStart","gradeEnd","subject","kTeach");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if(array_key_exists($myVariable, $params)){
				$this->db->where($myVariable, $params[$myVariable]);
			}
		}

		$this->db->from("benchmark_legend");
		$result = $this->db->get()->result();
		return $result;


	}

	function get_one($params){
		$result = $this->search($params);
		$output = FALSE;
		if($result){
		$output = $result[0];
		}
		return $output;
	}

	function get($kLegend){
		$this->db->where("kLegend",$kLegend);
		$this->db->from("benchmark_legend");
		$result = $this->db->get()->row();
		return $result;
	}

	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('benchmark_legend',$this);
		return $this->db->insert_id();
	}

	function update($kBenchmark){
		$this->prepare_variables();
		$this->db->where('kLegend', $kBenchmark);
		$this->db->update('benchmark_legend', $this);
	}


	function delete($kBenchmark)
	{
		$delete_array['kBenchmark'] = $kBenchmark;
		$this->db->delete('benchmark', $delete_array);
	}
}



/*Type	Collation	Attributes	Null	Default	Extra	Action
 1	kLegend	int(5)			No	None	AUTO_INCREMENT	  Change	  Drop	 More
2	kTeach	int(4)			No	None		  Change	  Drop	 More
3	subject	varchar(25)	utf8_general_ci		No	None		  Change	  Drop	 More
4	term	varchar(10)	utf8_general_ci		No	None		  Change	  Drop	 More
5	year	int(4)			No	None		  Change	  Drop	 More
6	gradeStart	varchar(1)	utf8_general_ci		No	None		  Change	  Drop	 More
7	gradeEnd	varchar(1)	utf8_general_ci		No	None		  Change	  Drop	 More
8	legend	text

*/