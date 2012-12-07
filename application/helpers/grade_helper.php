<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Takes an array of stdObjects each containing individual assignment grades
 * ($grade->points, $grade->total_points, $grade->weight, $grade->status)
 * produces the final weighted grade from the result.
 * @param array $grades
 * @return number
*/
function calculate_final_grade($grades){
	$student_total = 0;// the total weighted points for the student
	$assignment_total = 0; //the total weighted possible points for the assignments
	if(!empty($grades)){
		foreach($grades as $grade){
			//process any grade where the $grade->status is empty (no absents, excused, etc)
			//and one of the following are also true
			//the grade->points exceed the total available points (these are make-up grades),
			//or where the assignment has total_points more than 0
			if(!$grade->status && ( ($grade->points > 0 && $grade->total_points == 0) || $grade->total_points > 0 ) ){
				$points = $grade->points;
				$student_total += $grade->points * $grade->weight;
				$assignment_total += $grade->total_points * $grade->weight;

			} //end if
		}//end foreach grade

		$total_grade = round($student_total/$assignment_total*100,1);

		return $total_grade;
	}else{
		return false;
	}
}