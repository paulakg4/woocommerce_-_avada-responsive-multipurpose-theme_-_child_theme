<?php
// Template Name: Product Portfolio Three Column
get_header(); ?>
	<?php
	$content_css = 'width:100%';
	$sidebar_css = 'display:none';
	if(get_post_meta($post->ID, 'pyre_portfolio_full_width', true) == 'yes') {
		$content_css = 'width:100%';
		$sidebar_css = 'display:none';
	}
	elseif(get_post_meta($post->ID, 'pyre_portfolio_sidebar_position', true) == 'left') {
		$content_css = 'float:right;';
		$sidebar_css = 'float:left;';
		$content_class = 'portfolio-three-sidebar';
	} elseif(get_post_meta($post->ID, 'pyre_portfolio_sidebar_position', true) == 'right') {
		$content_css = 'float:left;';
		$sidebar_css = 'float:right;';
		$content_class = 'portfolio-three-sidebar';
	} elseif(get_post_meta($post->ID, 'pyre_sidebar_position', true) == 'default') {
		$content_class = 'portfolio-three-sidebar';
		if($data['default_sidebar_pos'] == 'Left') {
			$content_css = 'float:right;';
			$sidebar_css = 'float:left;';
		} elseif($data['default_sidebar_pos'] == 'Right') {
			$content_css = 'float:left;';
			$sidebar_css = 'float:right;';
		}
	}
	?>
	<div id="content" class="portfolio portfolio-three <?php echo $content_class; ?>" style="<?php echo $content_css; ?>">
		<?php while(have_posts()): the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
		</div>
		<?php $current_page_id = $post->ID; ?>
		<?php endwhile; ?>
		<?php
		$portfolio_category = get_terms('product_cat');
		if($portfolio_category && get_post_meta($post->ID, 'pyre_portfolio_filters', true) != 'no'):
		?>
		<ul class="portfolio-tabs clearfix">
			<li class="active"><a data-filter="*" href="#"><?php echo __('All', 'Avada'); ?></a></li>
			<?php foreach($portfolio_category as $portfolio_cat): ?>
			<?php if(get_post_meta(get_the_ID(), 'pyre_portfolio_category', true)  && !in_array('0', get_post_meta(get_the_ID(), 'pyre_portfolio_category', true))): ?>
			<?php if(in_array($portfolio_cat->term_id, get_post_meta(get_the_ID(), 'pyre_portfolio_category', true))): ?>
			<li><a data-filter=".<?php echo $portfolio_cat->slug; ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php endif; ?>
			<?php else: ?>
			<li><a data-filter=".<?php echo $portfolio_cat->slug; ?>" href="#"><?php echo $portfolio_cat->name; ?></a></li>
			<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<div class="portfolio-wrapper">
			<?php
			if(is_front_page()) {
				$paged = (get_query_var('page')) ? get_query_var('page') : 1;
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			$args = array(
				'post_type' => 'product',
				'paged' => $paged,
				'posts_per_page' => $data['portfolio_items'],
			);
			$pcats = get_post_meta(get_the_ID(), 'pyre_portfolio_category', true);
			if($pcats && $pcats[0] == 0) {
				unset($pcats[0]);
			}
			if($pcats){
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'ID',
					'terms' => $pcats
				);
			}
			$gallery = new WP_Query($args);
			while($gallery->have_posts()): $gallery->the_post();
				if($pcats) {
					$permalink = tf_addUrlParameter(get_permalink(), 'portfolioID', $current_page_id);
				} else {
					$permalink = get_permalink();
				}
				if(has_post_thumbnail() || get_post_meta($post->ID, 'pyre_video', true)):
			?>
			<?php
			$item_classes = '';
			$item_cats = get_the_terms($post->ID, 'product_cat');
			if($item_cats):
			foreach($item_cats as $item_cat) {
				$item_classes .= $item_cat->slug . ' ';
			}
			endif;
			?>
			<div class="portfolio-item <?php echo $item_classes; ?>">
				<?php if(has_post_thumbnail()): ?>
				<div class="image">
					<?php if($data['image_rollover']): ?>
					<?php the_post_thumbnail('portfolio-three'); ?>
					<?php else: ?>
					<a href="<?php echo $permalink; ?>"><?php the_post_thumbnail('portfolio-three'); ?></a>
					<?php endif; ?>
					<?php
					if(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'link') {
						$link_icon_css = '';
						$zoom_icon_css = 'display:none;';
					} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'zoom') {
						$link_icon_css = 'display:none;';
						$zoom_icon_css = '';
					} elseif(get_post_meta($post->ID, 'pyre_image_rollover_icons', true) == 'no') {
						$link_icon_css = 'display:none;';
						$zoom_icon_css = 'display:none;';
					} else {
						$link_icon_css = '';
						$zoom_icon_css = '';
					}

					$icon_url_check = get_post_meta(get_the_ID(), 'pyre_link_icon_url', true); if(!empty($icon_url_check)) {
						$icon_permalink = get_post_meta($post->ID, 'pyre_link_icon_url', true);
					} else {
						$icon_permalink = $permalink;
					}
					?>
					<div class="image-extras">
						<div class="image-extras-content">
							<?php $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
							<a style="<?php echo $link_icon_css; ?>" class="icon link-icon" href="<?php echo $icon_permalink; ?>">Permalink</a>
							<?php
							if(get_post_meta($post->ID, 'pyre_video_url', true)) {
								$full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
							}
							?>
							<a style="<?php echo $zoom_icon_css; ?>" class="icon gallery-icon" href="<?php echo $full_image[0]; ?>" rel="prettyPhoto[gallery]" title="<?php echo get_post_field('post_content', get_post_thumbnail_id($post->ID)); ?>"><img style="display:none;" alt="<?php echo get_post_field('post_excerpt', get_post_thumbnail_id($post->ID)); ?>" />Gallery</a>
							<h3><?php the_title(); ?></h3>
							<h4><?php echo get_the_term_list($post->ID, 'product_cat', '', ', ', ''); ?></h4>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; endwhile; ?>
		</div>
		<?php themefusion_pagination($gallery->max_num_pages, $range = 2); ?>
	</div>
<div id="sidebar" style="<?php echo $sidebar_css; ?>">
		<ul class="side-nav">
			<?php wp_reset_query(); ?>
			<?php
			//var_dump($post->ID);
			$post_ancestors = get_ancestors($post->ID, 'page');
			//var_dump($post_ancestors);
			$post_parent = end($post_ancestors);
			?>
            <li><a href="http://jindienails.bitnamiapp.com"><img src="http://jindienails.bitnamiapp.com/wp-content/uploads/logo1.png" alt="Jindie Nails" class="image"></a></li>
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
	</div><?php get_footer(); ?>