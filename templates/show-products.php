<?php
if ( $products->have_posts() ) :
?>
    <ul class="<?= ($product_template=='theme'? 'products':'psbc-products') ?> psbc-carousel">
    <?php
    while ( $products->have_posts() ) :
        $products->the_post();

        wc_get_template_part( 'content', 'product' );
    endwhile;
    ?>
    </ul>

<?php
endif;

