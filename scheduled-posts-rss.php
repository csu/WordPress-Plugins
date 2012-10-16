<?php
/*
	Plugin Name: Scheduled Posts in RSS Feed
	Plugin URI: 
	Description: 
	Version: 1.0
	Author: Christopher Su
	Author URI: 
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

function scheduled_posts_rss_func($query) {
	if ($query->is_feed) {
	     $query->set('post_status','publish,future');
	}
	return $query;
}
add_filter('pre_get_posts','scheduled_posts_rss_func');

?>