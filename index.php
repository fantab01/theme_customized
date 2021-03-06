<?php get_header(); ?>

<div class="content">
																	                    
	<?php if ( have_posts() ) : ?>
	
		<div class="posts" id="posts">
	
			<?php
			$paged = get_query_var( 'paged' ) ?: 1;
			$total_post_count = wp_count_posts();
			$published_post_count = $total_post_count->publish;
			$total_pages = ceil( $published_post_count / $posts_per_page );
			
			if ( 1 < $paged ) : ?>
			
				<div class="page-title">
				
					<h4><?php printf( __( 'Page %s of %s', 'rams' ), $paged, $wp_query->max_num_pages ); ?></h4>
					
				</div><!-- .page-title -->
				
				<div class="clear"></div>
			
				<?php 

			endif; 
			
			while ( have_posts() ) : the_post();
			
				get_template_part( 'content', get_post_format() );
												
			endwhile;
			?>
		
		</div><!-- .posts -->

	<?php endif; 
	
	if ( $wp_query->max_num_pages > 0 ) : ?>
	
		<div class="archive-nav">
				
			<?php echo get_next_posts_link( __( 'Older posts', 'rams' ) . ' &rarr;' ); ?>
				
			<?php echo get_previous_posts_link( '&larr; ' . __( 'Newer posts', 'rams' ) ); ?>

			<?php if ( (is_home() || is_front_page()) && !is_paged() ) { ?>
			
				<a class="copyright-link" href="https://beian.miit.gov.cn/" target="_blank">豫ICP备19034455号-1</a>
		
			<?php } ?>
			
			<div class="clear"></div>
						
		</div><!-- .archive-nav -->
						
	<?php endif; ?>
		
</div><!-- .content section-inner -->
	              	        
<?php get_footer(); ?>