<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>
    <?php
    $content_css = 'float:right;'; // <- this doesnt work, float it right in your style.css or something
    $sidebar_css = 'float:left;';
	?>
	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */

		do_action('woocommerce_before_main_content');
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action('woocommerce_after_main_content');
	?>
    <div id="sidebar" style="<?php echo $sidebar_css; ?>">
        <ul class="side-nav">
            <?php wp_reset_query(); ?>
            <?php
            //var_dump($post->ID);
            $post_ancestors = get_ancestors($post->ID, 'page');
            //var_dump($post_ancestors);
            $post_parent = end($post_ancestors);
            ?>
            <li><a href="http://jindienails.bitnamiapp.com/test"><img src="http://jindienails.bitnamiapp.com/wp-content/uploads/logo1.png" alt="Jindie Nails" class="image"></a></li>
            <?php if(is_page($post_parent)): ?><?php endif; ?>
            <li <?php if(is_page($post_parent)): ?>class="current_page_item"<?php endif; ?>><a href="<?php echo get_permalink($post_parent); ?>" title="Back to Parent Page"><?php echo get_the_title($post_parent); ?></a></li>
            <?php
            if($post_parent) {
                $children = wp_list_pages("title_li=&child_of=".$post_parent."&echo=0");
            }
            else {
                $children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
            }
            if ($children) {
            ?>
            <?php echo $children; ?>
            <?php } ?>
        </ul>
        <?php
        $selected_sidebar_replacement = get_post_meta($post->ID, 'sbg_selected_sidebar_replacement', true);
        if(!$selected_sidebar_replacement[0] == 0) {
            generated_dynamic_sidebar();
        }
        ?>
    </div>
<?php get_footer('shop'); ?>