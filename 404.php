<?php get_header(); ?>

<div class="content">

	<div class="post single">
	
		<div class="post-inner section-inner thin">
		                
		<div class="post-header">
		
			<h2 class="post-title"><?php _e( 'Error 404', 'rams' ); ?></h2>
													
		</div><!-- .post-header -->
	                                                	            
        <div class="post-content">
        	            
            <p><?php _e( "你似乎是在尝试打开一个并不存在的网页，这个网页可能已被删除或者从未存在过，但也可能被移动到了其他地方。试着搜索一下？", 'rams' ); ?></p>
            
            <?php get_search_form(); ?>
            
        </div><!-- .post-content -->
        	            	                        	
	</div><!-- .post -->
	
</div><!-- .content -->

<?php get_footer(); ?>
