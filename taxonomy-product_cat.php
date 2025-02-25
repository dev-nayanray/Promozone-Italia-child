<?php
/**
 * Custom WooCommerce Top Rated Product Category Template with Booking Location Support
 */

get_header(); ?>


<div class="ast-col-md-12">
            <?php
            if (is_product_category()) {
                $category = get_queried_object();
                echo '<h1 class="product-category-title">' . esc_html($category->name) . '</h1>';
                echo '<div class="product-category-description">' . term_description() . '</div>';
            }
            ?>
        </div>
</div>
<div class="ast-conta-ca" style="width: 100%; max-width: 1280px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 10px 15px; border: 1px solid #ddd; border-radious: 10px; ">
    
    <!-- Sorting Options -->
    <div class="sorting-options" style="display: flex; align-items: center; font-size: 14px; color: #333;">
        <label for="sort" style="font-weight: bold; margin-right: 10px;">Ordina per</label>
        <span style="margin: 0 10px; color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none;">Prezzo <span>&#9650;</span></a>
        <span style="margin: 0 10px; color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none;">Data <span>&#9650;</span></a>
        <span style="margin: 0 10px; color: #ccc;">|</span>
        <a href="#" style="color: #666; text-decoration: none;">Rating <span>&#9650;</span></a>
    </div>

    <!-- View Toggle Buttons -->
    <div class="view-toggle" style="display: flex; gap: 5px;">
        <button onclick="toggleView('grid')" style="padding: 8px 12px; border: none; background: #ff8000; color: white; cursor: pointer; border-radius: 3px; font-size: 14px; transition: 0.3s; box-shadow: inset 0 0 5px rgba(0,0,0,0.1);">
            &#9638;
        </button>
        <button onclick="toggleView('list')" style="padding: 8px 12px; border: none; background: #ccc; color: white; cursor: pointer; border-radius: 3px; font-size: 14px; transition: 0.3s; box-shadow: inset 0 0 5px rgba(0,0,0,0.1);">
            &#9776;
        </button>
    </div>

</div>



<div class="ast-container">
    
    <div class="ast-row">
        
    </div>

    <div id="product-container" class="product-grid">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_key' => '_wc_average_rating',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $category->term_id,
                ),
            ),
        );

        $top_rated_products = new WP_Query($args);

        if ($top_rated_products->have_posts()) {
            while ($top_rated_products->have_posts()) {
                $top_rated_products->the_post();
                global $product;
                $product_title = get_the_title();
                $product_image = get_the_post_thumbnail($product->get_id(), 'full');
                $sale_price = $product->get_sale_price();
                $regular_price = $product->get_regular_price();
                
                // Fetch booking location using the WooCommerce Booking Elementor Support plugin
                $product_location = get_post_meta($product->get_id(), '_booking_location', true);
                ?>
                <div class="product-item">
                    <div class="product-image">
                        <?php echo $product_image; ?>
                        <?php if ($product->is_on_sale()) : ?>
                            <div class="ribbon">Sale</div>
                        <?php endif; ?>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title"> <?php echo esc_html($product_title); ?> </h3>
                        <hr>
                        <p class="product-price">From <?php echo wc_price($sale_price ? $sale_price : $regular_price); ?></p>
                        <hr>
                        <p class="product-location">  <?php echo esc_html($product_location ? $product_location : 'N/A'); ?></p>
                        <hr>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" 
   class="button" 
   style="display: inline-block; padding: 10px 15px; background: #ff6f00; color: white; text-decoration: none; font-size: 14px; font-weight: bold; border-radius: 5px; transition: 0.3s ease;">
   Find Out More
</a>

                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p class="no-products">' . esc_html__('No top-rated products found in this category.', 'astra-child') . '</p>';
        }
        wp_reset_postdata();
        ?>
    </div>
</div>

<script>
function toggleView(view) {
    let container = document.getElementById("product-container");
    if (view === 'grid') {
        container.classList.add("product-grid");
        container.classList.remove("product-list");
    } else {
        container.classList.add("product-list");
        container.classList.remove("product-grid");
    }
}
</script>

<?php get_footer(); ?>
