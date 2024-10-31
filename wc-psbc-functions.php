<?php
/***********************************
 * Product Showcase Misc Functions *
 ***********************************/

if (!function_exists('PSBCFront')) {
/**
 * Shortcut to Woo_PSBC_Front object
 * @return Woo_PSBC_Front
 */
function PSBCFront()
{
    return Woo_PSBC_Front::get_instance();
}
}

if (!function_exists('psbc_add_settings')) {
/**
 * Hook to include PSBC Settings in WooCommerce Settings page
 * @param $settings
 * @return array
 */
function psbc_add_settings($settings)
{
    $settings[] = include(PSBC_PATH . 'class-psbc-settings.php');

    return $settings;
}
}
