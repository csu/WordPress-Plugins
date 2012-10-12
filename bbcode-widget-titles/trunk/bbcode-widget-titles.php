<?php
/*
	Plugin Name: BBCode Widget Titles
	Plugin URI: http://christophersu.org/wp-bbcode-widget-titles/
	Description: Allows for the use of BBCode in styling widget titles. Supports [b], [i], [u], [s], [url=], and [color=].
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

function parse_bbcode_widget_title( $title ) {
	$title = str_replace( '[b]', '<b>', $title );
	$title = str_replace( '[/b]', '</b>', $title );

	$title = str_replace( '[i]', '<i>', $title );
	$title = str_replace( '[/i]', '</i>', $title );

	$title = str_replace( '[u]', '<u>', $title );
	$title = str_replace( '[/u]', '</u>', $title );

	$title = str_replace( '[s]', '<del>', $title );
	$title = str_replace( '[/del]', '</del>', $title );

	if (strlen(strstr($title,"[url"))>0) {
		$title = str_replace( '[/url]', '</a>', $title );
		$title = str_replace( '[url=', '<a href="', $title );
	}

	if (strlen(strstr($title,"[color"))>0) {
		$title = str_replace( '[/color]', '</font>', $title );
		$title = str_replace( '[color=', '<font color="', $title );
		$title = str_replace( ']', '">', $title );
	}
	
	//replacing the end bracket in the [url] section can conflict with the first replace in the [color] section, so do this after checking for [color]
	if (strlen(strstr($title,"<a href="))>0) {
		$title = str_replace( ']', '">', $title );
	}

	return $title;
}

add_filter( 'widget_title', 'parse_bbcode_widget_title' );

?>