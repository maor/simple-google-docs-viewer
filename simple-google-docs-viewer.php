<?php
/*
 Plugin Name: Simple Google Docs Viewer
 Plugin URI: http://www.illuminea.com/plugins
 Description: Enables you to easily embed documents with Google Docs Viewer - that are supported by Google Docs (PDF/DOC/DOCX/PPTX/etc).
 Author: illuminea
 Author URI: http://www.illuminea.com
 Version: 1.0
 License: GPL2+
 */

if ( ! class_exists( 'Simple_Google_Docs_Viewer' ) ) :

/**
 * Shortcode handler wrapper
 * 
 * @author Maor Chasen <info@illuminea.com>
 */
class Simple_Google_Docs_Viewer {

	/**
	 * Get things moving
	 *
	 * @uses add_shortcode() for initializing the shortcode
	 */
	function __construct() {
		add_shortcode( 'gviewer', array( $this, 'the_shortcode' ) );
	}

	/**
	 * The actual shortcode.
	 *
	 * @since 1.0
	 * @param  array $atts Shortcode attributes
	 * @param  string $content Not used at this moment
	 * @return mixed The embed HTML on success, null on failure
	 */
	function the_shortcode( $atts, $content = '' ) {
		extract( apply_filters( 'simple_gviewer_atts', shortcode_atts( array(
			'file' => '',
			'width' => 600,
			'height' => 700,
			'language' => 'en'
		), $atts ) ) );

		if ( '' != ( $file = apply_filters( 'simple_gviewer_file_url', $file ) ) ) {
			$embed_format = '<iframe src="http://docs.google.com/viewer?url=%1$s&embedded=true&hl=%2$s" width="%3$d" height="%4$d" style="border: none;"></iframe>';
			
			return sprintf( $embed_format, 
				urlencode( esc_url( $file, array( 'http', 'https' ) ) ),
				esc_attr( $language ),
				absint( $width ),
				absint( $height )
			);
		}
		// No file specified, bail.
		return;
	}
}

/**
 * Template tag for using the Google Docs Viewer shortcode.
 *
 * @since 1.0
 * @param  string $file The absolute URL to the document you wish to embed
 * @param  array $args Optional, associative array with shortcode attributes
 * @return string The iframe URL to print in your template files
 */
function simple_gviewer_embed( $file, $args = array() ) {
	global $simple_google_docs_viewer;

	// If file is empty, we have really nothing to show
	if ( '' == $file || ! $simple_google_docs_viewer )
		return;
	
	return $simple_google_docs_viewer->the_shortcode(
		array_merge( $args, array( 'file' => $file ) ) 
	);
}

/**
 * @global simple_google_docs_viewer
 */
$GLOBALS['simple_google_docs_viewer'] = new Simple_Google_Docs_Viewer;

endif;