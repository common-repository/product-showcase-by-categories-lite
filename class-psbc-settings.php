<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Woo_PSBC_Settings' ) ) :

/**
 * Product Showcase Setting Class
 * Class Woo_PSBC_Settings
 */
class Woo_PSBC_Settings extends WC_Settings_Page
{
    public function __construct() {
        $this->id    = 'psbc';
        $this->label = __( 'Product Showcase', 'wc-psbc' );

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 30 );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    /**
     * Get settings array
     *
     * @return array
     */
    public function get_settings()
    {
        return array(

            // General Options
            array( 'title' => __( 'Product Showcase Options', 'wc-psbc' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

            array(
                'title' => __( 'Max. Number of Products', 'wc-psbc' ),
                'desc' 		=> __( 'Maximum number of products for each category.', 'wc-psbc' ),
                'id' 		=> 'psbc_limit',
                'css' 		=> 'width:60px;',
                'default'	=> '12',
                'desc_tip'	=>  true,
                'type' 		=> 'number',
                'custom_attributes' => array(
                    'min' 	=> 1,
                    'step' 	=> 1
                )
            ),

            array(
                'title' => __( 'Max. Number of Columns', 'wc-psbc' ),
                'desc' 		=> __( 'Number of columns when displaying products on desktop screen size.', 'wc-psbc' ),
                'id' 		=> 'psbc_product_columns',
                'css' 		=> 'width:60px;',
                'default'	=> '4',
                'desc_tip'	=>  true,
                'type' 		=> 'number',
                'custom_attributes' => array(
                    'min' 	=> 1,
                    'step' 	=> 1
                )
            ),

            array(
                'title' => __( 'Product Template', 'wc-psbc' ),
                'desc' 		=> __( 'Template for displaying each product.', 'wc-psbc' ),
                'desc_tip'	=>  true,
                'id' 		=> 'psbc_product_template',
                'default'	=> 'default',
                'type' 		=> 'select',
                'options' => array(
                    'default'  	=> __( 'Use Plugin Template', 'wc-psbc' ),
                    'theme'  	=> __( 'Use Theme Template', 'wc-psbc' ),
                ),
            ),

            array( 'type' => 'sectionend', 'id' => 'general_options'),

            // Container Style
            array( 'title' => __( 'Container Style', 'wc-psbc' ), 'type' => 'title', 'desc' => '', 'id' => 'container_options' ),

            array(
                'title' => __( 'Style', 'wc-psbc' ),
                'desc' 		=> __('Choose container style:', 'wc-psbc'),
                'id' 		=> 'psbc_container_style',
                'default'	=> 'rounded',
                'type' 		=> 'radio',
                'options'   => array(
                    'noborder' => __('No Border', 'wc-psbc'),
                    'rounded' => __('Border with rounded corner', 'wc-psbc'),
                    'square' => __('Square Border', 'wc-psbc'),
                )
            ),

            array(
                'title' => __( 'Border Color', 'wc-psbc' ),
                'desc' 		=> __('Color for container border.', 'wc-psbc'),
                'desc_tip'  => true,
                'id' 		=> 'psbc_container_border_color',
                'default'	=> '#F1F1F1',
                'type' 		=> 'color'
            ),

            array(
                'title' => __( 'Background Color', 'wc-psbc' ),
                'desc' 		=> __('Color for container background.', 'wc-psbc'),
                'desc_tip'  => true,
                'id' 		=> 'psbc_container_bg_color',
                'default'	=> '#FFFFFF',
                'type' 		=> 'color'
            ),
            array( 'type' => 'sectionend', 'id' => 'container_options'),

            // Category Buttons
            array( 'title' => __( 'Category Buttons Style', 'wc-psbc' ), 'type' => 'title', 'desc' => '', 'id' => 'cat_button_options' ),

            array(
                'title' => __( 'Style', 'wc-psbc' ),
                'desc' 		=> __('Choose button style:', 'wc-psbc'),
                'id' 		=> 'psbc_button_style',
                'default'	=> 'capsule',
                'type' 		=> 'radio',
                'options'   => array(
                                    'capsule'   => __('Capsule', 'wc-psbc'),
                                    'square'    => __('Square', 'wc-psbc'),
                                    'underline' => __('Underline', 'wc-psbc'),
                               )
            ),

            array(
                'title' => __( 'Color', 'wc-psbc' ),
                'desc' 		=> __('Color for button border or background when active.', 'wc-psbc'),
                'desc_tip'  => true,
                'id' 		=> 'psbc_button_color',
                'default'	=> '#00d2a8',
                'type' 		=> 'color'
            ),

            /*
            array(
                'title' => __( 'Active Text Color', 'wc-psbc' ),
                'desc' 		=> __('Color for button text when active.', 'wc-psbc'),
                'desc_tip'  => true,
                'id' 		=> 'psbc_button_text_color',
                'default'	=> '#FFFFFF',
                'type' 		=> 'color'
            ),
            */

            array( 'type' => 'sectionend', 'id' => 'cat_button_options'),

        ); // End PSBC settings
    }

    /**
     * Save settings
     */
    public function save()
    {
        $settings = $this->get_settings();

        WC_Admin_Settings::save_fields( $settings );
    }
}

endif;

return new Woo_PSBC_Settings();
