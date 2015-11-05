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
$request = json_decode($postdata);
$mainColor_new = $request->mainColor;
$childColor_new = $request->childColor;

if(empty($mainColor_new)||empty($childColor_new))
{
	$data['error']="Error";

	echo json_encode($data);
}
else
{
	
	

	
	// get mycolor.css content

	$cssfile=get_stylesheet_directory()."/style.css";

	$string=file_get_contents($cssfile);
	
	$style_current_data=get_css_data($cssfile, $file_headers, $context = 'Name' );
	
	/* current color of style.css */
	
	$color_main_current = $style_current_data["mainColor"];
	$color_child_current = $style_current_data["childColor"];
	


	/* show log */
  //$data['color_new']=$color_new; 
	
	
	$subject = $string;
	
	
	/* Find mainColor */
	
	/* find and replace color */
	$new_color=str_replace($color_main_current, $mainColor_new, $subject);
	$new_color=str_replace($color_child_current, $childColor_new, $new_color);
	
	$data["Current main color"]=$mainColor_new;
	$data["Current child color"]=$childColor_new;
	
	/* xuat ra file */
	file_put_contents($cssfile,$new_color);
	
	/* show console.log */
	$color_before_change=get_css_data($cssfile, $file_headers, $context = 'Name' );
	$data['color_main_before_change']=$color_before_change["mainColor"];
	$data['color_child_before_change']=$color_before_change["childColor"];


	// return all our data to an AJAX call

	echo json_encode($data);
	
}



