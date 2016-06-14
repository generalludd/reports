<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
    @function parseTemplate
    @params $template text
    @params $name string Student first name or nickname
    @params $gender string(1)
    @depends parsePronouns in template_control.php, parseName() in template_control.php
    @abstract accepts three strings, the template text, a student name, and the gender of the student (F/M) and converts the generic template into an acceptable format.
*/
function parse_template($template,$name,$gender){
    $text = parse_pronouns($template,$gender);
    $text = parse_name($text,$name);
    return $text;
}


function parse_pronouns($text,$gender){
    $searchArray = array("Himself","himself","His","his","Him","him","He","he");
    $replaceArray = array("Herself","herself","Her","her","Her","her","She","she");
    if($gender == "F" or $gender == "Female"){
        for($i = 0;$i < count($replaceArray); $i++){
            $term = $searchArray[$i];
            $search = "/\b$term\b/";
            $replace = $replaceArray[$i];
            $text = preg_replace($search, $replace, $text);
        }
    }
    return $text;
}

/**
 * replace the generic code for name (STUDENT) with the given $name within the submitted $text
 * @param $text
 * @param $name
 */
function parse_name($text,$name){
    $search = "/\bSTUDENT/";
    $text = preg_replace($search, ucfirst($name), $text);
    return $text;
}