<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function mysql_timestamp()
{
	return date('Y-m-d H:i:s');

}

/*
 * @function format_date
* @params $date date string
* @params $format string
*description:  this shouldn't be in this file, but I didn't want to create a new file with general formatting tools yet.
*/
function format_date($date, $format = NULL){
	//$format=mysql//yyyy-mm-dd
	//$format=standard//mm-dd-yyyy
	$clean_date = str_replace("-","/",$date);
	switch($format){
		case "mysql":
			$parts = explode("/", $clean_date);
			$month = $parts[0];
			$day = $parts[1];
			$year = $parts[2];
			$output = "$year-$month-$day";
			break;
		case "standard":
			$parts = explode("/", $clean_date);
			$year = $parts[0];
			$month = $parts[1];
			$day = $parts[2];
			$output = "$month/$day/$year";
			break;
		default:
			$output = $clean_date;
	}
	return $output;
}


function format_time($time, $showSeconds = false){
	$pm = substr_count($time, "PM");
	$am = substr_count($time, "AM");
	if($pm || $am){
		$outputFormat = 24;
	}else{
		$outputFormat = 12;
	}
	$time = str_ireplace("PM", "", $time);
	$time = str_ireplace("AM", "", $time);
	$parts = explode(":", trim($time));
	$hour = $parts[0];
	$minute = $parts[1];
	$seconds = $parts[2];

	if($outputFormat == 12 ){
		if( $hour > 12 ){
			$hour = $hour - 12;
			$meridian = "PM";
		}else{
			$meridian = "AM";
		}
		$output = "$hour:$minute";
		if($showSeconds){
			$output .= ":$seconds";
		}
		$output .= " $meridian";

	}elseif($outputFormat == 24){
		if($pm){
			$hour += 12;
		}
		$output = "$hour:$minute";
		if($showSeconds){
			$output .= ":$seconds";
		}

	}

	return $output;
}



function format_timestamp($timeStamp,$include_time = TRUE){
	$output = $timeStamp;
	$pattern = $pattern = '/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\ [0-9]{2}\:[0-9]{2}\:[0-9]{2}$/';
	if(preg_match($pattern,$timeStamp)){
		$items = explode(" ", $timeStamp);
		$date = format_date($items[0], 'standard');
		$time = "";
		if($include_time){
			$time = $items[1];

			if(count($items) > 2 ){
				$time .= " " . $items[2];
			}
			$time = ", " . format_time($time);

		}
		$output = "$date$time";
	}
	return $output;
}



function get_value($object, $item, $default = null){
	$output = $default;

	if($default){
		$output = $default;
	}
	if($object){

		$var_list = get_object_vars($object);
		$var_keys = array_keys($var_list);
		if (in_array($item, $var_keys) ){
			$output = $object->$item;
		}
	}
	return $output;
}


function get_current_grade($baseGrade, $baseYear, $targetYear = null)
{
	if($targetYear == null){
		$targetYear = get_current_year();
	}
	if($baseGrade == "K") {
		$baseGrade = 0;
	}
	$grade = $baseGrade + ($targetYear - $baseYear);
	return $grade;
}



/**
 * @TODO consider a system preference or constant declaration for the cutoff month.
 */
function get_current_year(){
	$year = date("Y");//get the current year
	$month = date("n"); //get the current month as an integer
	if($month < 7){
		$year = $year-1; //if the current month is during the spring term
	}
	return $year;
}

function format_schoolyear($year, $term = NULL){
	$firstHalf = $year;
	$secondHalf = strval($year)+1;
	return "$firstHalf-$secondHalf";
}

function get_year_list($initial_blank = FALSE){
	$baseYear = 2006;
	$narrYear = get_current_year();
	if($initial_blank){
		$result[] = "";
	}
	for($i=$baseYear;$i <= $narrYear;$i++){
		$result[$i]=$i;
	}
	return $result;
}




/**
 * @TODO this may be something that could be modified with a system preference using the "config" table
 * for term names, durations and cutoffs.
 * for now it is hard-coded.
 * @param date $targetDate
 */
function get_current_term($targetDate = NULL)
{
	if($targetDate == NULL){
		$month = date('n');
	}else{
		$month = date('n', $targetDate);
	}
	if($month > 2 and $month < 7){
		$term = "Year-End";
	}else{
		$term = "Mid-Year";
	}
	return "$term";
}

function get_term_menu($id, $currentTerm=null, $initial_blank = FALSE){
	$terms = array("Mid-Year", "Year-End");
	$select[]="<select id='$id' name='$id'>";
	if($initial_blank){
		$select[] = "<option value=''></option>";
	}
	foreach($terms as $term){
		$selection = "";
		if($term == $currentTerm){
			$selection = "selected";
		}
		$select[]="<option value='$term' $selection>$term</option>";

	}
	$select[]="</select>";
	$output=join("\n",$select);
	return $output;
}

/*
 * @params $table varchar table name
* @params $data array consisting of "where" string or array, and "select" comma-delimited string
* @returns an array of key-value pairs reflecting a Database primary key and human-meaningful string
*/
function get_keyed_pairs($list,$pairs,$initialBlank = NULL,$other = NULL,$alternate = array()){
	$output=false;
	if($initialBlank){
		$output[] = "";
	}
	if(!empty($alternate)){
		$output[$alternate['name']] = $alternate['value'];
	}

	foreach($list as $item){
		$key_name = $pairs[0];
		$key_value = $pairs[1];
		$output[$item->$key_name] = $item->$key_value;
	}
	if($other){
		$output["other"] = "Other...";
	}
	return $output;

}

/**
 * This should probably be adjusted or renamed.
 * This is currently only used to identify the kind of teacher for a given
 * student based on the student's grade
 * @param int $grade
 */
function get_teacher_type($grade)
{
	if($grade == "K"){
		$grade = 0;
	}
	$teacherType = "Classroom Teacher";
	if($grade > 4){
		$teacherType = "Middle School Advisor";
	}
	return $teacherType;
}


function format_grade($grade)
{
	if($grade == "0" || $grade == NULL){
		$grade = "K";
	}
	return $grade;
}

function format_grade_range($gradeStart, $gradeEnd, $show_label = FALSE)
{

	$label = "Grade:";
	if($gradeStart == $gradeEnd){
		$output = format_grade($gradeStart);
	}else{
		switch($gradeStart + $gradeEnd){
			case 0:
				$output = format_grade($gradeStart);
				break;
			case 13:
				$output = "Middle School";
				break;
			case 4:
				$output = "Lower School";
				break;
			default:
				$output = format_grade($gradeStart) . "-" . format_grade($gradeEnd);
				$label = "Grades:";
		}
	}
	if($show_label){
		$output = "$label&nbsp;$output";
	}
	return $output;
}

function create_grade_checklist($start = 0, $limit = 8, $name = "grades", $grade_cookie = array()){
	$id = $name;
	$name = $name . "[]";
	for($i = $start; $i <= $limit; $i++){
		$text = format_grade_text($i);
		$checked = "";
		if($grade_cookie){
			if(in_array($i, $grade_cookie)){
				$checked = "checked";
			}
		}
		$grades[] = "<li><input type='checkbox' name='$name' id='$id' $checked value='$i'>$text</li>";
	}
	return $grades;
}



function format_grade_text($number = 0){
	switch($number){
		case 0:
			$output = "Kindergarten";
			break;
		case 1:
			$output = "First";
			break;
		case 2:
			$output = "Second";
			break;
		case 3:
			$output = "Third";
			break;
		case 4:
			$output = "Fourth";
			break;
		case 5:
			$output = "Fifth";
			break;
		case 6:
			$output = "Sixth";
			break;
		case 7:
			$output = "Seventh";
			break;
		case 8:
			$output = "Eighth";
			break;
	}
	return $output;
}


function format_name($firstName, $lastName, $nickname=NULL, $separator=NULL){
	$name[]=$firstName;
	$informal = "";
	switch($separator){
		case "parenthesis": // for parenthesis/parentheses
			$openSeparator="(";
			$closeSeparator=")";
		case "informal": // set stage for showing nickname instead of first name
			$informal = true;
			break;
		case "highlight":
			$openSeparator = "<span class='highlight'>(";
			$closeSeparator = ")</span>";
		default:
			$openSeparator="\"";
			$closeSeparator="\"";
	}
	if($informal){
		if($nickname != NULL){
			$name['firstName'] = $nickname;
		}else{
			$name['firstName'] = $firstName;
		}
	}else if($nickname != NULL and $nickname != $firstName){
		$name['nickname']=$openSeparator.$nickname.$closeSeparator;
	}
	$name['lastName']=$lastName;

	$output=join(" ", $name);
	return $output;
}

/*
 * create an "ORDER" query instruction that allows proper sorting of grades
*/
function get_grade_order(){
	$grades=array("LS","K","1-2",1,2,"3-4",3,4,"MS","5-6",5,6,"7-8",7,8);
	for($i=0;$i<count($grades);$i++){
		$grade=$grades[$i];
		$output[]="(CASE WHEN grade='$grade' THEN 1 ELSE 0 END)";
	}
	$order="(". join("+",$output). ") ASC";
	return $order;

}

function get_subject_order($subjects = NULL)
{
	if(!$subjects){
		$subjects = "Introduction,Academic Progress,Humanities,Reading,Writing,Math,Science,Social Studies,Social Studies/Science,Social/Emotional,Music,Physical Education,Spanish,Art";
	}
	$subjectOrder = "CASE ";
	$list = explode(",", $subjects);
	for($i=0;$i<count($list);$i++){
		$mySubject = $list[$i];
		$x=$i+1;
		$subjectOrder .= "WHEN narrSubject='$mySubject' THEN $x ";
	}
	$subjectOrder .= "END";
	return $subjectOrder;
}

/**
 *
 * @param string $glue
 * @param array $list
 * @param string $conjunction
 *
 * creates a list in proper English list format (lists less than 3 have no comma, list with 3 or more have commas and final conjunction)
 */

function grammatical_implode($glue, $list, $conjunction = "and"){
	$output = $list;
	if(is_array($list)){
		if(count($list) == 1){
			$output = implode("",$list);
		}elseif(count($list) == 2){
			$output = implode(" $conjunction ", $list);
		}else{
			for($i=0; $i < count($list); $i++){
				$prefix = "";
				if($i + 1 == count($list)){
					$prefix = $conjunction;
				}
				$adjusted_list[] = $prefix . " " . $list[$i];
			}
			$output = implode($glue, $adjusted_list);
		}
	}
	return $output;
}

/**
 *
 * @param varchar $array
 * @param varchar $key
 * return an array key value if it exists and is not empty
 */

function get_array_value($array,$key){
	$result = FALSE;
	if(array_key_exists($key,$array)){
		if(isset($array[$key])){
			$result = $array[$key];
		}
	}
	return $result;
}

function format_email($email, $show_address = FALSE){
	$output = "";
	$address_text = "email";
	if($show_address){
		$address_text = $email;
	}
	if(!empty($email)){
		$output = "<a href='mailto:$email' title='$email'>$address_text</a>";
	}
	return $output;
}

function get_age($dob){
	$birth = new DateTime($dob);
	$today = new DateTime("now");
	$interval = date_diff($birth, $today);
	return $interval->format('%Y');
}


function format_table($data,$header = array(),$options = array()){
	$table = array();
	$table_class = "";
	if(array_key_exists("table_class", $options)){
		$table_class = "class='" . $options["table_class"] . "'";
	}
	$table[] = "<table $table_class >";


	if(!empty($header)){
		$thead_class = "";
		if(array_key_exists("thead_class", $options)){
			$thead_class = "class='" . $options["thead_class"] . "'";
		}
		$table[] = "<thead $thead_class><tr>";
		foreach($header as $head){
			$table[] = "<th>$head</th>";
		}
		$table[] = "</tr></thead>";
	}

	$tbody_class = "";
	if(array_key_exists("tbody_class", $options)){
		$tbody_class = "class='" . $options["tbody_class"] . "'";
	}
	$table[] = "<tbody $tbody_class>";
	foreach($data as $row){
		$table[] = "<tr>";
		foreach($row as $item){
			$table[] = "<td>" . format_timestamp($item) . "</td>";
		}
		$table[] = "</tr>";
	}
	$table[] ="</tbody></table>";

	return implode("",$table);
}


function calculate_letter_grade($points)
{
	$letters = array("9"=>"A",8=>"B",7=>"C",6=>"D",5=>"F");
	$valence = "";
	$output = "";
	$plus = 7;
	$minus = 3;
	if(strval($points) >= 98){
		$output = "A+";
	}elseif(strval($points) < 60){
		$output == "F";
	}else{
		$split = str_split($points);
		$tens = $split[0];
		$hundreds = $split[1];
		if($hundreds < $minus){
			$valence = "-";
		}elseif($hundreds > $plus){
			$valence = "+";
		}else{
			$valuence = "";
	
		}
		$letter = $letters[$tens];
		$output = $letter . $valence;
	}
	return $output;

}