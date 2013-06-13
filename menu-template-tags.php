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

		if( ! $user )
			return $nav;

		$tags = self::get_tags();

		foreach( $tags as $tag_id => $tag ) {

			preg_match('#\((.*?)\)#', $tag_id, $match );

			switch ( $tag_id ) {

				case '(user_profile_link)':
					$url = parse_url( get_edit_profile_url( $user->ID ) );
					$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					break;

				case '(bbp_user_profile_link)':

					if ( function_exists( 'bbp_get_user_profile_url' ) ) {

						$url = parse_url( bbp_get_user_profile_url( $user->ID ) );
						$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					}

					break;

				case '(bbp_topics_started_link)':

					if ( function_exists( 'bbp_user_topics_created_url' ) ) {

						$url = parse_url( bbp_get_user_topics_created_url( $user->ID ) );
						$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					}

					break;

				case '(bbp_replies_link)':

					if ( function_exists( 'bbp_get_user_replies_created_url' ) ) {

						$url = parse_url( bbp_get_user_replies_created_url( $user->ID ) );
						$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					}

					break;

				case '(bbp_favorites_link)':

					if ( function_exists( 'bbp_get_favorites_permalink' ) ) {

						$url = parse_url( bbp_get_favorites_permalink( $user->ID ) );
						$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					}

					break;

				case '(bbp_subscriptions_link)':

					if ( function_exists( 'bbp_get_subscriptions_permalink' ) ) {

						$url = parse_url( bbp_get_subscriptions_permalink( $user->ID ) );
						$nav = str_replace( $tag_id, $url['host'] . $url['path'], $nav );
					}

					break;

				default:
					$nav = str_replace( $tag_id, $user->{ $match[1] }, $nav );
					break;
			}

			$nav = apply_filters( 'mtt_get_nav', $nav, $tag_id, $tag, $tags );

		}

		return $nav;

	}


	private static function get_tags() {

		$tags = array(
			'(user_login)'              => __( 'Replace with the current user\'s login name.', 'menu-template-tags' ),
			'(user_firstname)'          => __( 'Replace with the current user\'s first name.', 'menu-template-tags' ),
			'(user_lastname)'           => __( 'Replace with the current user\'s last name.', 'menu-template-tags' ),
			'(display_name)'            => __( 'Replace with the current user\'s display name.', 'menu-template-tags' ),
			'(user_profile_link)'       => __( 'Replace with the current user\'s edit profile URL.', 'menu-template-tags' ),
			'(bbp_user_profile_link)'   => __( 'Replace with the current user\'s bbPress User Profile URL.', 'menu-template-tags' ),
			'(bbp_topics_started_link)' => __( 'Replace with the current user\'s bbPress Topics Started URL.', 'menu-template-tags' ),
			'(bbp_replies_link)'        => __( 'Replace with the current user\'s bbPress Replies Created URL.', 'menu-template-tags' ),
			'(bbp_favorites_link)'      => __( 'Replace with the current user\'s bbPress Favorites URL.', 'menu-template-tags' ),
			'(bbp_subscriptions_link)'  => __( 'Replace with the current user\'s bbPress Subscriptions URL.', 'menu-template-tags' )
		);

		return apply_filters( 'mtt_get_tags', $tags );
	}

}

$menu_template_tags = new Menu_Template_Tags;