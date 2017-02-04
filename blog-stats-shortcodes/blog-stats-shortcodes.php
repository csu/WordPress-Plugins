<?php
/*
	Plugin Name: Blog Stats Shortcodes
	Plugin URI: http://christopher.su/wp-blog-stats-shortcodes/
	Description: Shortcodes for displaying post, comment, page, and word counts. Shortcodes: [postcount], [commentcount], [pagecount], and [totalwordcount].
	Version: 1.0
	Author: Christopher Su
	Author URI: http://christopher.su/
	License: GPL2

	Copyright 2012  Christopher J. Su  (email : christophersu9 (at) gmail.com)

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
	
function post_count_func( $atts ) {
	$total = wp_count_posts()->publish;
	return $total;
}
add_shortcode( 'postcount', 'post_count_func' );
add_shortcode( 'post-count', 'post_count_func' );

function comment_count_func ( $atts ) {
	$total = wp_count_comments()->approved;
	return $total;
}
add_shortcode( 'commentcount', 'comment_count_func' );
add_shortcode( 'comment-count', 'comment_count_func' );

function page_count_func ( $atts ) {
	$total = wp_count_posts('page')->publish;
	return $total;
}
add_shortcode( 'pagecount', 'page_count_func' );
add_shortcode( 'page-count', 'page_count_func' );

function word_count_func() {
    global $wpdb;
		$now = gmdate("Y-m-d H:i:s",time());
        $query = "SELECT post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_date < '$now'";
		$words = $wpdb->get_results($query);
	if ($words) {
    	foreach ($words as $word) {
        	$post = strip_tags($word->post_content);
        	$post = explode(' ', $post);
        	$count = count($post);
        	$total = $count + $oldcount;
        	$oldcount = $total;
    	}
	}
	 
	else {
    	$total=0;
	}
	return number_format($total);
}
add_shortcode( 'totalwordcount', 'word_count_func' );
add_shortcode( 'total-word-count', 'word_count_func' );
add_shortcode( 'blogwordcount', 'word_count_func' );
add_shortcode( 'blog-word-count', 'word_count_func' );

?>