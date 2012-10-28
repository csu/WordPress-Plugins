<?php
/*
	Plugin Name: Remove Login Shake
	Plugin URI: http://christophersu.org/wordpress/remove-login-shake/
	Description: Removes the shaking animation on the login screen.
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

	function my_login_head() {
		remove_action('login_head', 'wp_shake_js', 12);
	}
	add_action('login_head', 'my_login_head');
	
?>