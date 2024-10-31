<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'Woo_PSBC_Front' ) ) :

/**
 * Product Showcase By Categories Front-end Class
 * Class Woo_PSBC_Front
 */
class Woo_PSBC_Front extends Woo_PSBC_Base
{
    private static $instance = null;
    protected $saved_filters;
    protected $params = array();

    /**
     * Singleton
     */
    public static function get_instance()
    {
        if (!self::$instance)
            self::$instance = new Woo_PSBC_Front();

        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();

        add_action('wp_enqueue_scripts', array($this, 'enqueue_style'));
        add_action('wp_enqueue_scripts', array($this, 'slider_script'));
        add_action('wp_enqueue_scripts', array($this, 'cat_navigation'));

        if (is_admin()) {
            add_action( 'wp_ajax_psbc_show_products', array($this, 'ajax_show_products') );
            add_action( 'wp_ajax_nopriv_psbc_show_products', array($this, 'ajax_show_products') );
        }

        add_filter( 'post_class', array($this, 'product_post_class'), 30, 3 );

        if ($this->options['product_template'] != 'theme')
            add_filter('wc_get_template_part', array($this, 'get_template_part'), 10, 3);

        add_shortcode('psbc_product_showcase', array($this, 'product_showcase'));
    }

    /**
     * Enqueue site & inline css
     */
    public function enqueue_style()
    {
        wp_register_style( 'psbc_front', plugins_url('assets/css/psbc_front.css', __FILE__) );

        $css = '';

        $container_css = '';
        if ($this->options['container_style'] != 'noborder') {
            $container_css = 'border: 5px solid '. $this->options['container_border_color'] .'; ';
            if ($this->options['container_style'] == 'rounded')
                $container_css .= 'border-radius: 16px; -webkit-border-radius: 16px;';
        }

        $container_css .= "background: {$this->options['container_bg_color']}; ";

        if ($container_css != '')
           $css .=  ".psbc-container { $container_css }\n";

        $cat_button_css = '';
        if ($this->options['button_style'] != 'underline') {
            $cat_button_css = 'border:3px solid '. $this->options['button_color'] .';';

            if ($this->options['button_style'] == 'capsule')
                $cat_button_css .= 'border-radius: 20px; -webkit-border-radius: 20px;';
        } else {
            $cat_button_css = 'border-width: 0 0 3px 0; border-style: solid; border-color: '. $this->options['button_color'] .';';
        }

        if ($cat_button_css != '')
            $css .= ".psbc-categories .category.cat-text a { $cat_button_css }\n";

        $cat_active_css = "background: {$this->options['button_color']}; ";
        $cat_active_css .= "color: {$this->options['button_text_color']}; ";

        $css .= ".psbc-categories .category.cat-text.active a { $cat_active_css }\n";

        $css .= ".owl-next, .owl-prev { background-color:{$this->options['button_color']} !important; opacity:1 !important; }\n";

        $css .= ".tinynav { border-color: {$this->options['button_color']}; }";

        wp_add_inline_style('psbc_front', $css);
    }

    /**
     * Enqueue slider script
     */
    public function slider_script()
    {
        wp_register_style( 'owl-theme', plugins_url('assets/owl-carousel/owl-carousel/owl.theme.css', __FILE__) );
        wp_register_style( 'owl-carousel', plugins_url('assets/owl-carousel/owl-carousel/owl.carousel.css', __FILE__), array('owl-theme') );
        wp_register_script( 'owl-carousel', plugins_url( 'assets/owl-carousel/owl-carousel/owl.carousel.min.js', __FILE__ ), array('jquery') );
        wp_register_script( 'psbc_front', plugins_url( 'assets/js/psbc_front.js', __FILE__ ), array('owl-carousel') );

        wp_localize_script( 'psbc_front', 'psbc_vars', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'columns'  => $this->options['columns'],
                'cat_columns' => $this->options['cat_columns'],
            )
        );
    }

    /**
     * Enqueue category navigation assets
     */
    public function cat_navigation()
    {
        wp_register_style( 'tiny-nav', plugins_url('assets/css/tinynav.css', __FILE__) );
        wp_register_script( 'tiny-nav', plugins_url('assets/js/tinynav.min.js', __FILE__), array('jquery') );
        wp_register_script( 'cat_text_nav', plugins_url('assets/js/cat_text_nav.js', __FILE__), array('tiny-nav') );
    }

    /**
     * Set product post class
     * @param $classes
     * @param string $class
     * @param string $post_id
     * @return array
     */
    public function product_post_class( $classes, $class = '', $post_id = '' )
    {
        global $woocommerce_loop;

        if ( ! $post_id || get_post_type( $post_id ) !== 'product' ||
             !isset($woocommerce_loop['name']) || $woocommerce_loop['name'] != 'psbc' )
            return $classes;

        $classes = array('product');

        return $classes;
    }

    /**
     * Method to get template part from plugin template path
     * @param $template
     * @param $slug
     * @param $name
     * @return string
     */
    public function get_template_part($template, $slug, $name)
    {
        global $woocommerce_loop;

        $template_name = $slug . '-' . $name .'.php';

        if (isset($woocommerce_loop['name']) && $woocommerce_loop['name'] == 'psbc' &&
            $template_name == 'content-product.php') {

            $template_name = $this->options['product_template'] . '-' . $template_name;
            return wc_locate_template( $template_name, 'psbc/', plugin_dir_path(__FILE__).'templates/');
        }

        return $template;
    }

    /**
     * method to show sale flash in template loop
     */
    public function template_loop_sale_flash()
    {
        wc_get_template( 'loop/sale-flash.php', array(), 'psbc/', plugin_dir_path(__FILE__).'templates/' );
    }

    /**
     * method to show product thumbnail in template loop
     * @return string
     */
    public function template_loop_product_thumbnail()
    {
        return woocommerce_get_product_thumbnail();
    }

    /**
     * method to show product rating in template loop
     */
    public function template_loop_rating()
    {
        wc_get_template( 'loop/rating.php', array(), 'psbc/', plugin_dir_path(__FILE__).'templates/' );
    }

    /**
     * method to show product price in template loop
     */
    public function template_loop_price()
    {
        wc_get_template( 'loop/price.php', array(), 'psbc/', plugin_dir_path(__FILE__).'templates/' );
    }

    /**
     * Method to handle showing products via ajax
     */
    public function ajax_show_products()
    {
        $product_cat = $_POST['product_cat'];
        $cat = get_term_by('slug', $product_cat, 'product_cat');
        $show = $_POST['show'];
        $limit = $_POST['limit'];

        echo $this->show_products($cat->term_id, $show, $limit);

        die();
    }

    /**
     * Get parameter from ajax call
     * @param $cat
     * @return string
     */
    public function ajax_params($cat)
    {
        $params = array(
            $cat->slug,
            $this->params['show'],
            $this->params['limit'],
        );

        return implode(',', $params);
    }

    /**
     * Return products section html in showcase
     * @param $cat_id
     * @param string $show
     * @param int $limit
     * @return string
     */
    public function show_products($cat_id, $show = '', $limit=0)
    {
        global $woocommerce_loop;

        if ($show == '')
            $show = $this->params['show'];
        if ($limit == 0)
            $limit = $this->params['limit'];

        $products = $this->featured_products($cat_id, $limit);

        if (empty($products))
            return '';

        $old_loop_name = (isset($woocommerce_loop['name']))? $woocommerce_loop['name'] : '';

        $woocommerce_loop['name'] = 'psbc'; // important to identify which product loop being displayed

        ob_start();
        wc_get_template('show-products.php',
            array(
                'products' => $products,
                'product_template' => $this->options['product_template']
            ),
            'psbc/',
            PSBC_PATH . 'templates/'
        );

        $woocommerce_loop['name'] = $old_loop_name;

        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Set parameters based on options overridden by passing args
     * @param $args
     * @return array
     */
    public function set_params($args)
    {
        $this->params = $this->options;
        foreach(array_keys($this->params) as $key) {
            if ( isset($args[$key]) && ((is_string($args[$key]) && $args[$key]!=='') || !is_null($args[$key])) )
                $this->params[$key] = $args[$key];
        }
    }

    /**
     * Return product showcase html
     * @param null $args
     * @return string
     */
    public function product_showcase($args = array())
    {
        if ($args['categories'] === '')
            return '';

        $this->set_params( $args );

        $this->params['categories'] = explode(',', $this->params['categories']);

        $categories = array();
        foreach($this->params['categories'] as $cat) {
            $category = get_term_by('slug', $cat, 'product_cat');

            if ($category !== false) {
                $products = $this->featured_products($category->term_id, 1);

                if ($products !== false && $products->have_posts())
                    $categories[] = $category;
            }
        }

        if (empty($categories))
            return '';

        wp_enqueue_style('owl-carousel');
        wp_enqueue_style('psbc_front');
        wp_enqueue_script('psbc_front');

        wp_enqueue_style('tiny-nav');
        wp_enqueue_script('cat_text_nav');

        ob_start();
        wc_get_template('product-showcase.php', array(
                'categories' => $categories,
                'title' => $this->params['title'],
                'container_class' => $this->params['container_class'],
                'cats_wrapper_class' => $this->params['cats_wrapper_class'],
                'products_wrapper_class' => $this->params['products_wrapper_class'],
                'columns' => $this->params['columns'],
                'product_template' => $this->params['product_template'],
            ),
            'psbc/',
            PSBC_PATH . 'templates/'
        );

        return ob_get_clean();
    }

}

endif;