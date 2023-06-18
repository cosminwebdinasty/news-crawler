<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://webdinasty.ro/
 * @since      1.0.0
 *
 * @package    News_Crawler
 * @subpackage News_Crawler/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    News_Crawler
 * @subpackage News_Crawler/includes
 * @author     Webdinasty <office@webdinasty.ro>
 */
class News_Crawler_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'curl';
		rmdir( $uploads_dir );
	}

}
