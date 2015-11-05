<?php
require_once('../../../wp-load.php');

/* get Origin color backup */

$my_backup_color_data=my_backup_color_data();


$origin_mainColor = $my_backup_color_data["file_data"]["mainColor"];
$origin_childColor = $my_backup_color_data["file_data"]["childColor"];


// get current color

$cssfile=get_stylesheet_directory()."/style.css";

$string=file_get_contents($cssfile);


/* int file headers */
$file_headers = array(
		'mainColor'        => 'Main Color',
		'childColor'        => 'Child Color'
);


$style_current_data=get_css_data($cssfile, $file_headers, $context = 'Name' );


if(empty($style_current_data))
{
	$data["error"]="file headers undefined";
}
else
{

	/* current color of style.css */

	$color_main_current = $style_current_data["mainColor"];
	$color_child_current = $style_current_data["childColor"];


	$subject = $string;


	/* find and replace color */
	$rep_origin_color_=str_replace($color_main_current, $origin_mainColor, $subject);
	$rep_origin_color_end=str_replace($color_child_current, $origin_childColor, $rep_origin_color_);

	/* xuat ra file */
	file_put_contents($cssfile,$rep_origin_color_end);

	echo json_encode($origin_mainColor);
		
}