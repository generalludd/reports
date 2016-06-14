<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

function mysql_timestamp()
{
	return date ( 'Y-m-d H:i:s' );
}

/**
 * @function format_date
 * 
 * @param s $date
 *        	date string
 * @param s $format
 *        	string
 *        	description: this shouldn't be in this file, but I didn't want to create a new file with general formatting tools yet.
 *        	idea courtesy:
 *        	http://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format
 */
function format_date($date, $format = NULL)
{
	$date = str_replace ( "/", "-", $date );
	$output = $date;
	if ($date) {
		$new_date = DateTime::createFromFormat ( 'm-d-Y', $date );
		if ($new_date && $format == "mysql") {
			$output = $new_date->format ( 'Y-m-d' );
		} else { // assume standard date format
			$new_date = DateTime::createFromFormat ( 'Y-m-d', $date );
			if ($new_date) {
				$output = $new_date->format ( 'm/d/Y' );
			}
		}
	}
	return $output;
}

/**
 * Format a range of two dates, if the second is null or the same as the first, show just one date.
 *
 * @param unknown $date_one        	
 * @param string $date_two        	
 * @return unknown
 */
function format_date_range($date_one, $date_two = NULL)
{
	if ($date_one == $date_two || $date_two == NULL) {
		if ($date_one == YEAR_START) {
			$output = "Since the start of school.";
		} elseif ($date_one == MID_YEAR) {
			$output = "Since the start of the current term.";
		} else {
			$output = format_date ( $date_one );
		}
	} else {
		$output = sprintf ( "%s to %s", format_date ( $date_one ), format_date ( $date_two ) );
	}
	return $output;
}

function format_timestamp($timeStamp, $include_time = TRUE)
{
	$output = date ( "m-d-Y g:i:s a", strtotime ( $timeStamp ) );
	return $output;
}

function capitalize($key){
	$key = humanize($key,"_");
	return ucwords($key);
}

function get_value($object, $item, $default = null)
{
	$output = $default;
	
	if ($default) {
		$output = $default;
	}
	if ($object) {
		
		$var_list = get_object_vars ( $object );
		$var_keys = array_keys ( $var_list );
		if (in_array ( $item, $var_keys )) {
			$output = $object->$item;
		}
	}
	return $output;
}

function get_current_grade($baseGrade, $baseYear, $targetYear = null)
{
	if ($targetYear == null) {
		$targetYear = get_current_year ();
	}
	if ($baseGrade == "K") {
		$baseGrade = 0;
	}
	$grade = $baseGrade + ($targetYear - $baseYear);
	return $grade;
}

/**
 * @TODO consider a system preference or constant declaration for the cutoff month.
 */
function get_current_year()
{
	$year = date ( "Y" ); // get the current year
	$month = date ( "n" ); // get the current month as an integer
	if ($month < 7) {
		$year = $year - 1; // if the current month is during the spring term
	}
	return $year;
}

function format_schoolyear($year, $term = NULL)
{
	$firstHalf = $year;
	$secondHalf = strval ( $year ) + 1;
	return "$firstHalf-$secondHalf";
}

function get_year_list($initial_blank = FALSE, $next_year = FALSE)
{
	$baseYear = 2006;
	$narrYear = get_current_year ();
	if ($initial_blank) {
		$result [] = "";
	}
	if ($next_year) {
		$narrYear = $narrYear + 1;
	}
	for($i = $baseYear; $i <= $narrYear; $i ++) {
		$result [$i] = $i;
	}
	return $result;
}

/**
 * @TODO this may be something that could be modified with a system preference using the "config" table
 * for term names, durations and cutoffs.
 * for now it is hard-coded.
 *
 * @param date $targetDate        	
 */
function get_current_term($targetDate = NULL)
{
	if ($targetDate == NULL) {
		$month = date ( 'n' );
	} else {
		$month = date ( 'n', $targetDate );
	}
	if ($month > 2 and $month < 7) {
		$term = "Year-End";
	} else {
		$term = "Mid-Year";
	}
	return "$term";
}

function get_term_menu($id, $currentTerm = null, $initial_blank = FALSE, $options = array(), $is_required = FALSE)
{
	$terms = array (
			"Mid-Year",
			"Year-End" 
	);
	$required = "";
	if ($is_required) {
		$required = "required";
	}
	$select [] = sprintf ( "<select id='%s' name='%s' %s>", $id, $id, $required );
	$classes = FALSE;
	if (! empty ( $options )) {
		if (array_key_exists ( "classes", $options )) {
			$classes = sprintf ( "class='%s'", $options ["classes"] );
		}
	}
	if ($initial_blank) {
		$select [] = "<option value=''></option>";
	}
	foreach ( $terms as $term ) {
		$selection = "";
		if ($term == $currentTerm) {
			$selection = "selected";
		}
		$select [] = "<option value='$term' $classes $selection>$term</option>";
	}
	$select [] = "</select>";
	$output = join ( "\n", $select );
	return $output;
}

function get_term_start($current_term = FALSE)
{
	$current_term || $current_term = get_current_term ();
	$output = $current_term == "Mid-Year"?YEAR_START:MID_YEAR;
	return $output;
}

/*
 * @params $table varchar table name
 * @params $data array consisting of "where" string or array, and "select" comma-delimited string
 * @returns an array of key-value pairs reflecting a Database primary key and human-meaningful string
 */
function get_keyed_pairs($list, $pairs, $initialBlank = NULL, $other = NULL, $alternate = array())
{
	$output = false;
	if ($initialBlank) {
		$output [""] = "";
	}
	if (! empty ( $alternate )) {
		$output [$alternate ['name']] = $alternate ['value'];
	}
	
	foreach ( $list as $item ) {
		$key_name = $pairs [0];
		$key_value = $pairs [1];
		$output [$item->$key_name] = $item->$key_value;
	}
	if ($other) {
		$output ["other"] = "Other...";
	}
	return $output;
}

/**
 * This should probably be adjusted or renamed.
 * This is currently only used to identify the kind of teacher for a given
 * student based on the student's grade
 *
 * @param int $grade        	
 */
function get_teacher_type($grade)
{
	if ($grade == "K") {
		$grade = 0;
	}
	$teacherType = "Classroom Teacher";
	if ($grade > 4) {
		$teacherType = "Middle School Advisor";
	}
	return $teacherType;
}

function format_grade($grade)
{
	if ($grade == "0" || $grade == NULL) {
		$grade = "K";
	}
	return $grade;
}

function format_grade_range($gradeStart, $gradeEnd, $show_label = FALSE)
{
	$label = "Grade:";
	if ($gradeStart == $gradeEnd) {
		$output = format_grade ( $gradeStart );
	} else {
		switch ($gradeStart + $gradeEnd) {
			case 0 :
				$output = format_grade ( $gradeStart );
				break;
			case 13 :
				$output = "Middle School";
				break;
			case 4 :
				$output = "Lower School";
				break;
			default :
				$output = format_grade ( $gradeStart ) . "-" . format_grade ( $gradeEnd );
				$label = "Grades:";
		}
	}
	if ($show_label) {
		$output = "$label&nbsp;$output";
	}
	return $output;
}

function create_grade_checklist($start = 0, $limit = 8, $name = "grades", $grade_cookie = array())
{
	$id = $name;
	$name = $name . "[]";
	for($i = $start; $i <= $limit; $i ++) {
		$text = format_grade_text ( $i );
		$checked = "";
		if (is_array ( $grade_cookie ) && ! empty ( $grade_cookie )) {
			if (in_array ( $i, $grade_cookie )) {
				$checked = "checked";
			}
		}
		$grades [] = "<li><input type='checkbox' name='$name' id='$id' $checked value='$i'>$text</li>";
	}
	return $grades;
}

/**
 * create a copy of the student class or group.
 * Classes are for lower grades, groups are for middle school.
 *
 * @param Int $student_grade        	
 * @param string $student_group        	
 * @return string
 */
function format_classroom($student_class, $student_grade, $student_group = NULL)
{
	switch ($student_grade) {
		case (5) :
		case (6) :
			$class = "5/6";
			break;
		case (7) :
		case (8) :
			$class = "7/8";
			break;
		default :
			$class = $student_class;
	}
	if ($class == "5/6" || $class == "7/8") {
		$class = $class . $student_group;
	}
	return $class;
}

function format_grade_text($number = 0)
{
	switch ($number) {
		case 0 :
			$output = "Kindergarten";
			break;
		case 1 :
			$output = "First";
			break;
		case 2 :
			$output = "Second";
			break;
		case 3 :
			$output = "Third";
			break;
		case 4 :
			$output = "Fourth";
			break;
		case 5 :
			$output = "Fifth";
			break;
		case 6 :
			$output = "Sixth";
			break;
		case 7 :
			$output = "Seventh";
			break;
		case 8 :
			$output = "Eighth";
			break;
	}
	return $output;
}

function format_name($firstName, $lastName, $nickname = NULL, $separator = NULL)
{
	$name [] = $firstName;
	$informal = "";
	switch ($separator) {
		case "parenthesis" : // for parenthesis/parentheses
			$openSeparator = "(";
			$closeSeparator = ")";
		case "informal" : // set stage for showing nickname instead of first name
			$informal = true;
			break;
		case "highlight" :
			$openSeparator = "<span class='highlight'>(";
			$closeSeparator = ")</span>";
		default :
			$openSeparator = "\"";
			$closeSeparator = "\"";
	}
	if ($informal) {
		if ($nickname != NULL) {
			$name ['firstName'] = $nickname;
		} else {
			$name ['firstName'] = $firstName;
		}
	} else if ($nickname != NULL and $nickname != $firstName) {
		$name ['nickname'] = $openSeparator . $nickname . $closeSeparator;
	}
	$name ['lastName'] = $lastName;
	
	$output = join ( " ", $name );
	return $output;
}

/*
 * create an "ORDER" query instruction that allows proper sorting of grades
 */
function get_grade_order()
{
	$grades = array (
			"LS",
			"K",
			"1-2",
			1,
			2,
			"3-4",
			3,
			4,
			"MS",
			"5-6",
			5,
			6,
			"7-8",
			7,
			8 
	);
	for($i = 0; $i < count ( $grades ); $i ++) {
		$grade = $grades [$i];
		$output [] = "(CASE WHEN grade='$grade' THEN 1 ELSE 0 END)";
	}
	$order = "(" . join ( "+", $output ) . ") ASC";
	return $order;
}

function get_subject_order($subjects = NULL)
{
	// @TODO there should be a UI-available tool for global sorting.
	if (! $subjects) {
		$subjects = "Introduction,Academic Progress,Humanities,Reading,Writing,Science,Math,Social Studies,Social Studies/Science,Social/Emotional,Music,Physical Education,Spanish,Art";
	}
	$subjectOrder = "CASE ";
	$list = explode ( ",", $subjects );
	for($i = 0; $i < count ( $list ); $i ++) {
		$mySubject = $list [$i];
		$x = $i + 1;
		$subjectOrder .= "WHEN subject='$mySubject' THEN $x ";
	}
	$subjectOrder .= "END";
	return $subjectOrder;
}

/**
 *
 * @param string $glue        	
 * @param array $list        	
 * @param string $conjunction
 *        	creates a list in proper English list format (lists less than 3 have no comma, list with 3 or more have commas and final conjunction)
 */
function grammatical_implode($glue, $list, $conjunction = "and")
{
	$output = $list;
	if (is_array ( $list )) {
		if (count ( $list ) == 1) {
			$output = implode ( "", $list );
		} elseif (count ( $list ) == 2) {
			$output = implode ( " $conjunction ", $list );
		} else {
			for($i = 0; $i < count ( $list ); $i ++) {
				$prefix = "";
				if ($i + 1 == count ( $list )) {
					$prefix = $conjunction;
				}
				$adjusted_list [] = $prefix . " " . $list [$i];
			}
			$output = implode ( $glue, $adjusted_list );
		}
	}
	return $output;
}

/**
 *
 * @param varchar $array        	
 * @param varchar $key
 *        	return an array key value if it exists and is not empty
 */
function get_array_value($array, $key)
{
	$result = FALSE;
	if (array_key_exists ( $key, $array )) {
		if (isset ( $array [$key] )) {
			$result = $array [$key];
		}
	}
	return $result;
}

function format_email($email, $show_address = FALSE)
{
	$output = "";
	$address_text = $email;
	if ($show_address) {
		$address_text = $email;
	}
	if (! empty ( $email )) {
		$output = "<span style='font-weight:normal'><a href='mailto:$email' title='$email'>$address_text</a></span>";
	}
	return $output;
}

function get_age($dob)
{
	$birth = new DateTime ( $dob );
	$today = new DateTime ( "now" );
	$interval = date_diff ( $birth, $today );
	return $interval->format ( '%Y' );
}

function format_table($data, $header = array(), $options = array())
{
	$table = array ();
	$table_class = "";
	if (array_key_exists ( "table_class", $options )) {
		$table_class = "class='" . $options ["table_class"] . "'";
	}
	$table [] = "<table $table_class >";
	
	if (! empty ( $header )) {
		$thead_class = "";
		if (array_key_exists ( "thead_class", $options )) {
			$thead_class = "class='" . $options ["thead_class"] . "'";
		}
		$table [] = "<thead $thead_class><tr>";
		foreach ( $header as $head ) {
			$table [] = "<th>$head</th>";
		}
		$table [] = "</tr></thead>";
	}
	
	$tbody_class = "";
	if (array_key_exists ( "tbody_class", $options )) {
		$tbody_class = "class='" . $options ["tbody_class"] . "'";
	}
	$table [] = "<tbody $tbody_class>";
	foreach ( $data as $row ) {
		$table [] = "<tr>";
		foreach ( $row as $item ) {
			$table [] = "<td>" . format_timestamp ( $item ) . "</td>";
		}
		$table [] = "</tr>";
	}
	$table [] = "</tbody></table>";
	
	return implode ( "", $table );
}

function calculate_letter_grade($points, $pass_fail = FALSE)
{
	$letters = array (
			"9" => "A",
			8 => "B",
			7 => "C",
			6 => "D",
			5 => "F" 
	);
	$valence = "";
	$output = "";
	$plus = 6;
	$minus = 3;
	$letter = "";
	if (strval ( $points ) >= 99) {
		$output = "A+";
	} elseif (strval ( $points ) > 93) {
		$output = "A";
	} elseif (strval ( $points ) < 60) {
		$output = "F";
	} else {
		$split = str_split ( $points );
		$tens = $split [0];
		$hundreds = $split [1];
		if ($hundreds < $minus) {
			$valence = "-";
		} elseif ($hundreds > $plus) {
			$valence = "+";
		} else {
			$valence = "";
		}
		$letter = $letters [$tens];
		$output = $letter . $valence;
	}
	if ($pass_fail) {
		if($points > 59){
			$output = "Pass";
		}else{
			$output="Fail";
		}
	}
	return $output;
}

function bake_cookie($name, $value)
{
	set_cookie ( array (
			"name" => $name,
			"value" => $value,
			"expire" => 0 
	) );
}

function burn_cookie($name)
{
	set_cookie ( array (
			"name" => $name,
			"value" => "",
			"expire" => NULL 
	) );
}