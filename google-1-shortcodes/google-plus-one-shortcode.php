<?php
/*
	Plugin Name: Google +1 Shortcodes
	Plugin URI: http://christophersu.org/wp-google-plus-one-shortcodes/
	Description: Shortcodes for embedding Google +1 buttons. Shortcodes: [gplusone], [plusone], [google+1], [g+1], [+1].
	Version: 1.0
	Author: Christopher Su
	Author URI: http://christophersu.org/
	License: GPL2

	Copyright 2012  Christopher J. Su  (email : christophersu9 (at) gmail (dot) com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function load_plus_one_script() {
	wp_register_script( 'google_plus_one_script', 'https://apis.google.com/js/plusone.js', false, '');
	wp_enqueue_script( 'google_plus_one_script' );
}
add_action( 'wp_print_scripts', 'load_plus_one_script' ); 
	
function google_plus_one_func( $atts ) {
	return "<g:plusone></g:plusone>";
}
add_shortcode( 'gplusone', 'google_plus_one_func' );
add_shortcode( 'plusone', 'google_plus_one_func' );
add_shortcode( 'google+1', 'google_plus_one_func' );
add_shortcode( 'g+1', 'google_plus_one_func' );
add_shortcode( '+1', 'google_plus_one_func' );

?>