<?php
/**
 * Plugin Name: Menu Template Tags
 * Plugin URI: http://pippinsplugins.com/menu-template-tags
 * Description: Adds template tag support to Nav Menu Items that allow you to create dynamic menu items
 * Author: Pippin Williamson
 * Author URI: http://pippinsplugins.com
 * Version: 1.0
 * Text Domain: menu-template-tags
 * Domain Path: languages
 *
 */


class Menu_Template_Tags {


	function __construct() {

		add_filter( 'wp_nav_menu_items', array( $this, 'filter_tags' ), 10, 2 );

	}


	public function filter_tags( $nav, $args ) {
	
		$user = get_userdata( get_current_user_id() );

		$tags = $this->get_tags();

		foreach( $tags as $tag_id => $tag ) {
			
			preg_match('#\((.*?)\)#', $tag_id, $match );
			
			if( '(user_profile_link)' === $tag_id ) {
				$url = parse_url( get_edit_profile_url( $user->ID ) );
				$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
			} else {
				$nav = str_replace( $tag_id, $user->{$match[1]}, $nav );
			}
		}

		return $nav;

	}


	public function get_tags() {
		$tags = array(
			'(user_login)'        => __( 'Replace with the current user\'s login name', 'menu-template-tags' ),
			'(user_firstname)'    => __( 'Replace with the current user\'s first name', 'menu-template-tags' ),
			'(user_lastname)'     => __( 'Replace with the current user\'s last name', 'menu-template-tags' ),
			'(display_name)'      => __( 'Replace with the current user\'s display name', 'menu-template-tags' ),
			'(user_profile_link)' => __( 'Replace with the current user\'s edit profile URL', 'menu-template-tags' ),
		);

		return apply_filters( 'mtt_get_tags', $tags );
	}

}
$menu_template_tags = new Menu_Template_Tags;