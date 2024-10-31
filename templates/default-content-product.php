<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/psbc/<template_name>-content-product.php
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>

	<a href="<?php the_permalink(); ?>">

		<?php
			do_action( 'psbc_before_shop_loop_item_title' );
		?>
        <?php
        echo PSBCFront()->template_loop_sale_flash();
        echo PSBCFront()->template_loop_product_thumbnail();
        ?>

		<h5><?php the_title(); ?></h5>

        <?php
        echo PSBCFront()->template_loop_rating();
        echo PSBCFront()->template_loop_price();
        ?>
		<?php
			do_action( 'psbc_after_shop_loop_item_title' );
		?>

	</a>

</li>