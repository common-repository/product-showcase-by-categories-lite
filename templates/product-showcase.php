<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="psbc-product-showcase <?= $container_class ?> <?= ($product_template=='theme'? 'woocommerce': '') ?>">
    <?php if ($title !== ''): ?>
    <h2 class="psbc-title"><?= $title ?></h2>
    <?php endif; ?>

    <div class="<?= $cats_wrapper_class ?>">
        <ul class="psbc-categories psbc-cat-text-nav">
        <?php
        $i=0;
        foreach ( $categories as $cat ):
            echo '<li class="category cat-text ' .($i++==0? 'active':'').'">';
            echo '<a href="#'. PSBCFront()->ajax_params($cat) . '">';
            echo esc_html($cat->name);

            echo '</a>';
            echo '</li>';
        endforeach;
        ?>
        </ul>
    </div>

    <div class="<?= $products_wrapper_class ?>">
        <?= PSBCFront()->show_products( $categories[0]->term_id ) ?>
        <div class="loading"><?= __('Loading...', 'wc-psbc') ?></div>
    </div>
</div>
