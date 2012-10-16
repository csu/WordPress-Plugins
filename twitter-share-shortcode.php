<?php
/*
	Plugin Name: Twitter Share Shortcode
	Plugin URI: http://christophersu.org/wp-twitter-share-shortcode/
	Description: Shortcodes for embedding Twitter share buttons. Shortcode: [twittershare].
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

function load_scripts() {
	wp_register_script( 'twitter_share_script', 'http://platform.twitter.com/widgets.js', false, '');
	wp_enqueue_script( 'twitter_share_script' );
}
add_action( 'wp_print_scripts', 'load_scripts' );

function twitter_share_func( $atts ) {
	//return "<a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-url=\"" + the_permalink() + \"" data-count=\"vertical\" data-via=\"" + bloginfo('name') + \"">Tweet</a>";
}
add_shortcode( 'twittershare', 'twitter_share_func' );
?>
