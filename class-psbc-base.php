<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Woo_PSBC_Base' ) ) :

/**
 * Product Showcase By Categories Base Class
 * Class Woo_PSBC_Base
 */
class Woo_PSBC_Base
{
    protected $options = array();

    public function __construct()
    {
        $this->get_options();

        add_action('init', array($this, 'init'));
    }

    /**
     * Set default parameters from options
     */
    protected function get_options()
    {
        $this->options = array(
            'title'         => '',              // title above product showcase
            'categories'    => '',              // categories selection
            'show'          => 'featured',      // what products to be displayed

            'columns'           => (int) get_option('psbc_product_columns', 4),         // max number of product columns
            'limit'             => (int) get_option('psbc_limit', 12),                  // maximum products displayed, 0 = show all
            'product_template'  => get_option('psbc_product_template', 'default'),      // template for displaying product: default=use plugin template; theme=use theme template

            'container_style'        => get_option('psbc_container_style', 'rounded'),          // container style
            'container_border_color' => get_option('psbc_container_border_color', '#f1f1f1'),
            'container_bg_color'     => get_option('psbc_container_bg_color', '#ffffff'),

            'button_style'           => get_option('psbc_button_style', 'capsule'),             // category button style
            'button_color'           => get_option('psbc_button_color', '#00d2a8'),
            'button_text_color'      => get_option('psbc_button_text_color', '#ffffff'),

            'container_class'        => apply_filters('psbc_container_class', 'psbc-container'),                // container class
            'cats_wrapper_class'     => apply_filters('psbc_cats_wrapper_class', 'psbc-cats-wrapper'),          // categories wrapper class
            'products_wrapper_class' => apply_filters('psbc_products_wrapper_class', 'psbc-products-wrapper'),  // products wrapper class
        );
    }

    /**
     * Do everything needed on initialization
     */
    public function init()
    {
        load_plugin_textdomain('wc-psbp', false, basename(PSBC_PATH) . '/languages/');
    }

    /**
     * Return taxonomy query for product category
     * @param $cat_id
     * @param array $tax_query
     * @return array
     */
    protected function product_cat_query($cat_id, $tax_query = array())
    {
        $tax_query[] = array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $cat_id,
                        );

        return $tax_query;
    }

    /**
     * Retrieve featured products
     *
     * @param $cat_id
     * @param int $limit
     * @return WP_Query
     */
    public function featured_products($cat_id, $limit=0 )
    {
        $meta_query = WC()->query->get_meta_query();
        $meta_query[] = array(
                            'key' 		=> '_featured',
                            'value' 	=> 'yes'
                        );

        $args = array(
            'post_type'				=> 'product',
            'post_status' 			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'meta_query'			=> $meta_query,
            'tax_query'             => $this->product_cat_query($cat_id),
            'posts_per_page'        => $limit,
            'suppress_filters'      => true,
        );

        $products = new WP_Query( $args );

        return $products;
    }
}

endif; // if class_exists