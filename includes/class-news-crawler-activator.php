<?php

/**
 * Fired during plugin activation
 *
 * @link       https://webdinasty.ro/
 * @since      1.0.0
 *
 * @package    News_Crawler
 * @subpackage News_Crawler/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    News_Crawler
 * @subpackage News_Crawler/includes
 * @author     Webdinasty <office@webdinasty.ro>
 */
class News_Crawler_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'curl';
		wp_mkdir_p( $uploads_dir );
	}

}
