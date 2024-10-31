<?php
/*
Plugin Name: WooCommerce Product Showcase By Categories (Lite version)
Plugin URI: http://indowebkreasi.com/psbc/woocommerce-product-showcase-by-categories/
Description: This plugin allows you to display product showcase with category filters
Author: IndoWebKreasi
Version: 1.1.2
Author URI: http://indowebkreasi.com
License: Free (GPL)
*/

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return; // Check if WooCommerce is active

define('PSBC_PATH', plugin_dir_path(__FILE__));

require PSBC_PATH . 'wc-psbc-functions.php';
require PSBC_PATH . 'class-psbc-base.php';
require PSBC_PATH . 'class-psbc-front.php';

add_filter('woocommerce_get_settings_pages', 'psbc_add_settings');

PSBCFront();