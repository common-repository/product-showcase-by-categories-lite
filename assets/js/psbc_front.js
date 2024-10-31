jQuery(function ($) {

    function activate_slider(container)
    {
        var parColumns = parseInt(psbc_vars.columns);
        var desktopCols = parColumns>4? 4 : parColumns;
        var tabletCols = parColumns>3? 3 : parColumns;
        var tabletSmallCols = parColumns>2? 2 : parColumns;

        container.find(".psbc-products-wrapper .psbc-carousel").owlCarousel({
            items : parColumns,
            itemsCustom : false,
            itemsDesktop : [979, desktopCols],
            itemsDesktopSmall : false,
            itemsTablet: [800, tabletCols],
            itemsTabletSmall: [599, tabletSmallCols],
            itemsMobile : [480,1],
            singleItem : false,
            itemsScaleUp : false,
            navigation: true,
            scrollPerPage: true,
            pagination:false
        });
    }

    activate_slider($(".psbc-product-showcase"));

    function ajax_change_cat(el, cat, show, limit, template)
    {
        var container = el.parents(".psbc-container");

        // prepare ajax
        var data = {
            'action': 'psbc_show_products',
            'product_cat': cat,
            'show' : show,
            'limit': limit,
            'template': template
        };

        // cosmetic updates
        container.find(".psbc-categories .category").removeClass("active");
        el.parent('li').addClass("active");

        var wrapper = container.find(".psbc-products-wrapper");
        var loading = wrapper.find(".loading");
        loading.show();
        wrapper.css("height", wrapper.height());

        // load products
        var products = wrapper.find(".psbc-products");
        products.fadeTo(500,0.2);
        $.post(psbc_vars.ajax_url, data, function(result) {
            products.fadeTo(500,0);
            loading.hide();

            var content = $(result).fadeTo(0,0);
            products.replaceWith(content);

            wrapper.css("height", "auto");

            wrapper.find(".psbc-products").fadeTo(700,1);

        }).done(function() {
            activate_slider(container);
        });
    }

    $(".psbc-categories .category a").click(function() {
        var params = $(this).attr("href").substr(1).split(',');

        ajax_change_cat($(this), params[0], params[1], params[2], params[3]);

        return false;
    });

    $(".psbc-container").on("change", ".tinynav", function() {
        var params = $(this).val().substr(1).split(',');

        ajax_change_cat($(this), params[0], params[1], params[2], params[3]);
    })
});


