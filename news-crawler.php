<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/cosminwebdinasty
 * @since             1.0.0
 * @package           News_Crawler
 *
 * @wordpress-plugin
 * Plugin Name:       News Crawler
 * Plugin URI:        https://github.com/cosminwebdinasty
 * Description:       This plugin is a news crawler project.
 * Version:           1.0.0
 * Author:            Cosmin
 * Author URI:        https://github.com/cosminwebdinasty
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       news-crawler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEWS_CRAWLER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-news-crawler-activator.php
 */
function activate_news_crawler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-news-crawler-activator.php';
	News_Crawler_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-news-crawler-deactivator.php
 */
function deactivate_news_crawler() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-news-crawler-deactivator.php';
	News_Crawler_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_news_crawler' );
register_deactivation_hook( __FILE__, 'deactivate_news_crawler' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-news-crawler.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_news_crawler() {

	$plugin = new News_Crawler();
	$plugin->run();

}
run_news_crawler();
