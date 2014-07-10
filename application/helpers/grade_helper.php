<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Takes an array of stdObjects each containing individual assignment grades
 * ($grade->points, $grade->total_points, $grade->weight, $grade->status)
 * produces the final weighted grade from the result.
 *
 * @param array $grades
 * @return number
 *
 */

function calculate_final_grade($grades) {

	$output = FALSE;
	if (! empty ( $grades )) {
		$student_total = 0;
		$assignment_total = 0;
		$weight_sums = 0;
		$categories = array();

		foreach ( $grades as $grade ) {
			if (($grade->points > 0 && $grade->total_points == 0) || ($grade->total_points > 0)) {
				if ($grade->footnote) {
					$footnotes [$grade->footnote] = $grade->label;
				}

				// if the student does not have an assignment listed as absent,excused, incomplete, redo, then calculate the grade otherwise ignore
				if (empty ( $grade->status )) {
					$points = $grade->points;
					$student_total += $grade->points * $grade->weight;
					if (! array_key_exists ( $grade->category, $categories )) {
						$categories [$grade->category] ["category"] = $grade->category;
						$categories [$grade->category] ["weight"] = $grade->weight;
						$categories [$grade->category] ["total_points"] = $grade->total_points;
						$categories [$grade->category] ["points"] = $points;
					} else {
						$categories [$grade->category] ["total_points"] += $grade->total_points;
						$categories [$grade->category] ["points"] += $points;
					}
				}
			} // end if
		} // end foreach grade

		foreach ( $categories as $category ) {
			$category_grade = round($category["points"]/$category["total_points"]*100,2);
			$assignment_total += $category_grade * $category ["weight"];
			$weight_sums += $category ["weight"];
		}

		$grade_total = 0;
		$category_count = 0;
		if($weight_sums > 0){
		$output = round ( $assignment_total / $weight_sums, 1 );
		}
	}
	return $output;

}


