<?php
function get_css_data( $file, $default_headers, $context = '' ) {
	
	// We don't need to write to the file, so just open for reading.
	$fp = fopen( $file, 'r' );

	// Pull only the first 8kiB of the file in.
	$file_data = fread( $fp, 8192 );

	// PHP will close file handle, but we are good citizens.
	fclose( $fp );

	// Make sure we catch CR-only line endings.
	$file_data = str_replace( "\r", "\n", $file_data );

	/**
	 * Filter extra file headers by context.
	 *
	 * The dynamic portion of the hook name, `$context`, refers to
	 * the context where extra headers might be loaded.
	 *
	 * @since 2.9.0
	 *
	 * @param array $extra_context_headers Empty array by default.
	 */
	
	if ( $context && $extra_headers = apply_filters( "extra_{$context}_headers", array() ) ) {
		$extra_headers = array_combine( $extra_headers, $extra_headers ); // keys equal values
		$all_headers = array_merge( $extra_headers, (array) $default_headers );
	} else {
		$all_headers = $default_headers;
	}

	if(is_array($all_headers))
	{
		foreach ( $all_headers as $field => $regex ) {
			if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
				$all_headers[ $field ] = _cleanup_header_comment( $match[1] );
			else
				$all_headers[ $field ] = '';
		}
	}

	return $all_headers;
}

/*
	check valid file template json color
*/
function my_backup_color_data()
{
		
		$backupfile = get_stylesheet_directory()."/backup-mycolor.json";

		
		if(file_exists($backupfile))
		{

				$file = file_get_contents($backupfile);
				$color_data = json_decode($file, true);

				$origin_mainColor = $color_data["mainColor"];
				$origin_childColor = $color_data["childColor"];

				if(!empty($color_data))
				{
						$data["file_url"]= $backupfile;
						$data["file_data"]= $color_data;

						return $data;
				}
				else
				{
						return $color_data;
				}

		}
		else{
			return false;
		}
}

function file_headers()
{
			$file_headers = array(
							'mainColor'        => 'Main Color',
							'childColor'        => 'Child Color'
			);

			return $file_headers;
}

function mycolor_data()
{
		/* int file headers */
			

			$file_headers=file_headers();

			$cssfile=get_stylesheet_directory()."/style.css";

			// get css content
			$string=file_get_contents($cssfile);
				
			$data=get_css_data($cssfile, $file_headers, $context = 'Name' );
		
			return $data;
}		
