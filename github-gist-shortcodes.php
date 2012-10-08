<?php
/*
	Plugin Name: GitHub Gist Shortcode and Helper
	Plugin URI: http://christophersu.org/wp-github-gist-shortcode-helper/
	Description: Adds the [gist] shortcode and also automatically replaces Gist links with Gist embeds.
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
	
// shortcode format: [gist id="ID"] and [gist id="ID" file="FILE"]
function gist_shortcode($atts) {
  return sprintf(
    '<script src="https://gist.github.com/%s.js%s"></script>', 
    $atts['id'], 
    $atts['file'] ? '?file=' . $atts['file'] : ''
  );
}
add_shortcode('gist','gist_shortcode');

// replaces: https://gist.github.com/[ID], https://gist.github.com/[ID]#[FILE], and https://gist.github.com/[ID]?file=[FILE] with gist shortcodes, which in turn, embeds the gists
function gist_link_replace($content) {
  return preg_replace('/https:\/\/gist.github.com\/([\d]+)[\.js\?]*[\#]*file[=|_]+([\w\.]+)(?![^<]*<\/a>)/i', '[gist id="${1}" file="${2}"]', $content );
}
add_filter( 'the_content', 'gist_link_replace');
?>