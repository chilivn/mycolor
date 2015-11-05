<?php
require_once('../../../wp-load.php');

/* int file headers */
$file_headers = array(
		'mainColor'        => 'Main Color',
		'childColor'        => 'Child Color'
);

$errors         = array();      // array to hold validation errors
$data           = array();      // array to pass back data

$postdata = file_get_contents("php://input");

$cssfile=get_stylesheet_directory()."/style.css";

// get css content
$string=file_get_contents($cssfile);
	
$color_current=get_css_data($cssfile, $file_headers, $context = 'Name' );
	
$data['color_main_current']=$color_current["mainColor"];
$data['color_child_current']=$color_current["childColor"];



	

// return all our data to an AJAX call

echo json_encode($data);
