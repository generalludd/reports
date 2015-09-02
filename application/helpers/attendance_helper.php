<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

function format_attendance ( $attendance )
{
	$output = array();
	if ($attendance) {
		if ($attendance->attendType) {
			$output [] = $attendance->attendType;
		}
		if ($attendance->attendSubtype) {
			$output [] = $attendance->attendSubtype;
		}
		if($attendance->attendLength){
			$output[] = sprintf("Length: %s",$attendance->attendLength);
		}
		if($attendance->attendNote){
			$output[] = sprintf( "Note: %s",$attendance->attendNote);
		}
		return implode(", ", $output);
	}
}