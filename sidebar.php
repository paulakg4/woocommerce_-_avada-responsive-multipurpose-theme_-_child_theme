<?php
/**
 * Sidebar
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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
</div>
