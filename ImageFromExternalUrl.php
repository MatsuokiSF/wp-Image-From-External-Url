<?php
/**
 * Plugin Name: Image From External Url
 * Description: Avoid uploading images to your server and reuse them externally to improve the performance of your WordPress
 * Description: 
 * Version: 0.9
 * Author: Matsuoki
 * 
 * Add external images to the media library without physically uploading them to your WordPress site, 
 * thus avoiding increased server resource usage and optimizing overall performance. By not importing the files directly, 
 * loading times and bandwidth consumption are significantly reduced, improving the user experience and the speed of your page.
 */

if ( ! defined('ABSPATH') ) {
    exit; // Seguridad
}

define( 'IMAGEFROMEXTERNALURL_PLUGIN_DIR', plugin_dir_path(__FILE__) );
define( 'IMAGEFROMEXTERNALURL_PLUGIN_URL', plugin_dir_url(__FILE__) );

require_once IMAGEFROMEXTERNALURL_PLUGIN_DIR . 'includes/class-imagefromexternalurl-plugin.php';

add_action( 'plugins_loaded', function() {
    $plugin = new ImageFromExternalUrl\ImageFromExternalUrl_Plugin();
    $plugin->init();
} );
