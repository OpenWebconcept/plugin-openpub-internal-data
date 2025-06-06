<?php

declare(strict_types=1);

/**
 * Plugin Name:       OpenPub Internal Data
 * Plugin URI:        https://www.openwebconcept.nl/
 * Description:       Adds internal data to authenticated requests
 * Version:           2.1.4
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       openpub-internal-data
 * Domain Path:       /languages
 */

use OWC\OpenPub\InternalData\Autoloader;
use OWC\OpenPub\InternalData\Foundation\Plugin;

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new Autoloader();

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
	add_action('after_setup_theme', function () {
    	$plugin = (new Plugin(__DIR__));
		$plugin->boot();
		do_action('owc/openpub-internal-data/plugin', $plugin);
	});
}, 11);
