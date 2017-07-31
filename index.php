<?php

/*
Plugin Name: SiteCreate Core
Plugin URI:https://www.sitecreate.io
Description: Core Framework Plugin for SiteCreate WordPress Themes.
Version: 1.0
Author: SiteCreate
Author URI: https://www.sitecreate.io
*/	
/**
 * Plugin definitions
 */
define( 'SITECREATE_CORE_PATH', trailingslashit(plugin_dir_path(__FILE__)) );
define( 'DISTINCTIVE_CORE_VERSION', '1.0');

/**
 * Grab all custom post type functions
 */
require_once( SITECREATE_CORE_PATH . 'core_cpts.php' );

/**
 * Everything else in the framework is conditionally loaded depending on theme options.
 * Let's include all of that now.
 */
require_once( SITECREATE_CORE_PATH . 'core_init.php' );
require_once( SITECREATE_CORE_PATH . 'kirki/kirki.php' );
?>
