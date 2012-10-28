<?php
/*
	Plugin Name: Conceal WordPress
	Plugin URI: http://christophersu.org/wordpress/conceal-wordpress/
	Description: Cleans up the output of wp_head and adds root relative URLs.
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

	function roots_head_cleanup() {
	  remove_action('wp_head', 'feed_links', 2);
	  remove_action('wp_head', 'feed_links_extra', 3);
	  remove_action('wp_head', 'rsd_link');
	  remove_action('wp_head', 'wlwmanifest_link');
	  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	  remove_action('wp_head', 'wp_generator');
	  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

	  global $wp_widget_factory;
	  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

	  add_filter('use_default_gallery_style', '__return_null');
	}
	add_action('init', 'roots_head_cleanup');
	
	/**
	 * Cleaner walker for wp_nav_menu()
	 *
	 * Walker_Nav_Menu (WordPress default) example output:
	 *   <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
	 *   <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
	 *
	 * Roots_Nav_Walker example output:
	 *   <li class="menu-home"><a href="/">Home</a></li>
	 *   <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
	 */
	class Roots_Nav_Walker extends Walker_Nav_Menu {
	  function check_current($classes) {
	    return preg_match('/(current[-_])|active|dropdown/', $classes);
	  }

	  function start_lvl(&$output, $depth = 0, $args = array()) {
	    $output .= "\n<ul class=\"dropdown-menu\">\n";
	  }

	  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    $item_html = '';
	    parent::start_el($item_html, $item, $depth, $args);

	    if ($item->is_dropdown && ($depth === 0)) {
	      $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
	    }

	    $output .= $item_html;
	  }

	  function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
	    $element->is_dropdown = !empty($children_elements[$element->ID]);

	    if ($element->is_dropdown) {
	      if ($depth === 0) {
	        $element->classes[] = 'dropdown';
	      } elseif ($depth === 1) {
	        $element->classes[] = 'dropdown-submenu';
	      }
	    }

	    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	  }
	}

	/**
	 * Remove the id="" on nav menu items
	 * Return 'menu-slug' for nav menu classes
	 */
	function roots_nav_menu_css_class($classes, $item) {
	  $slug = sanitize_title($item->title);
	  $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
	  $classes = preg_replace('/((menu|page)[-_\w+]+)+/', '', $classes);

	  $classes[] = 'menu-' . $slug;

	  $classes = array_unique($classes);

	  return array_filter($classes, 'is_element_empty');
	}

	add_filter('nav_menu_css_class', 'roots_nav_menu_css_class', 10, 2);
	add_filter('nav_menu_item_id', '__return_null');
	
	/**
	 * Root relative URLs
	 *
	 * WordPress likes to use absolute URLs on everything - let's clean that up.
	 * Inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
	 *
	 * You can enable/disable this feature in config.php:
	 * current_theme_supports('root-relative-urls');
	 *
	 * @author Scott Walkinshaw <scott.walkinshaw@gmail.com>
	 */
	function roots_root_relative_url($input) {
	  $output = preg_replace_callback(
	    '!(https?://[^/|"]+)([^"]+)?!',
	    create_function(
	      '$matches',
	      // If full URL is home_url("/"), return a slash for relative root
	      'if (isset($matches[0]) && $matches[0] === home_url("/")) { return "/";' .
	      // If domain is equal to home_url("/"), then make URL relative
	      '} elseif (isset($matches[0]) && strpos($matches[0], home_url("/")) !== false) { return $matches[2];' .
	      // If domain is not equal to home_url("/"), do not make external link relative
	      '} else { return $matches[0]; };'
	    ),
	    $input
	  );

	  return $output;
	}

	/**
	 * Terrible workaround to remove the duplicate subfolder in the src of <script> and <link> tags
	 * Example: /subfolder/subfolder/css/style.css
	 */
	function roots_fix_duplicate_subfolder_urls($input) {
	  $output = roots_root_relative_url($input);
	  preg_match_all('!([^/]+)/([^/]+)!', $output, $matches);

	  if (isset($matches[1]) && isset($matches[2])) {
	    if ($matches[1][0] === $matches[2][0]) {
	      $output = substr($output, strlen($matches[1][0]) + 1);
	    }
	  }

	  return $output;
	}

	if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
	  add_filter('bloginfo_url', 'roots_root_relative_url');
	  add_filter('theme_root_uri', 'roots_root_relative_url');
	  add_filter('stylesheet_directory_uri', 'roots_root_relative_url');
	  add_filter('template_directory_uri', 'roots_root_relative_url');
	  add_filter('script_loader_src', 'roots_fix_duplicate_subfolder_urls');
	  add_filter('style_loader_src', 'roots_fix_duplicate_subfolder_urls');
	  add_filter('plugins_url', 'roots_root_relative_url');
	  add_filter('the_permalink', 'roots_root_relative_url');
	  add_filter('wp_list_pages', 'roots_root_relative_url');
	  add_filter('wp_list_categories', 'roots_root_relative_url');
	  add_filter('wp_nav_menu', 'roots_root_relative_url');
	  add_filter('the_content_more_link', 'roots_root_relative_url');
	  add_filter('the_tags', 'roots_root_relative_url');
	  add_filter('get_pagenum_link', 'roots_root_relative_url');
	  add_filter('get_comment_link', 'roots_root_relative_url');
	  add_filter('month_link', 'roots_root_relative_url');
	  add_filter('day_link', 'roots_root_relative_url');
	  add_filter('year_link', 'roots_root_relative_url');
	  add_filter('tag_link', 'roots_root_relative_url');
	  add_filter('the_author_posts_link', 'roots_root_relative_url');
	}

?>