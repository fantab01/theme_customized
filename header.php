<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>
		
		<meta http-equiv="Content-type" content="text/html;charset=<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >
		 
		<?php wp_head(); ?>
	
	</head>
	
	<body <?php body_class(); ?>>
	
		<div class="sidebar bg-mint">
		
			<div class="sidebar-inner">

				<ul class="mobile-menu bg-dark hidden">
					
					<?php if ( has_nav_menu( 'primary' ) ) {

						$nav_menu_args = array( 
							'container' 		=> '', 
							'items_wrap' 		=> '%3$s',
							'theme_location' 	=> 'primary'
						);
						wp_nav_menu( $nav_menu_args );

					} else {

						$list_pages_args = array(
							'container' 	=> '',
							'title_li' 		=> ''
						);
						wp_list_pages( $list_pages_args );
						
					} ?>
					
				</ul>
						
				<?php if ( get_theme_mod( 'rams_logo' ) ) : ?>
				
			        <a class="blog-logo" href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'title' ) ); ?> &mdash; <?php echo esc_attr( get_bloginfo( 'description' ) ); ?>' rel='home'>
			        	<img src='<?php echo esc_url( get_theme_mod( 'rams_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>'>
			        </a>
			
				<?php elseif ( get_bloginfo( 'description' ) || get_bloginfo( 'title' ) ) : ?>
			
					<h1 class="blog-title">
						<a href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?> &mdash; <?php echo esc_attr( get_bloginfo( 'description' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'title' ) ); ?></a>
					</h1>
					
				<?php endif; ?>
				
				<a class="nav-toggle hidden" title="<?php _e( 'Click to view the navigation', 'rams' ); ?>" href="#">
				
					<div class="bars">
					
						<div class="bar"></div>
						<div class="bar"></div>
						<div class="bar"></div>
						
						<div class="clear"></div>
					
					</div>
					
					<p>
						<span class="menu"><?php _e( 'Menu', 'rams' ); ?></span>
						<span class="close"><?php _e( 'Close', 'rams' ); ?></span>
					</p>
				
				</a>
				
				<ul class="main-menu">
					
					<?php if ( has_nav_menu( 'primary' ) ) {

						$nav_menu_args = array( 
							'container' 		=> '', 
							'items_wrap' 		=> '%3$s',
							'theme_location' 	=> 'primary'
						);
						wp_nav_menu( $nav_menu_args );

					} else {

						$list_pages_args = array(
							'container' 	=> '',
							'title_li' 		=> ''
						);
						wp_list_pages( $list_pages_args );
						
					} ?>
					
				 </ul>
				 
				 
				
				 <div class="clear"></div>
			
			</div><!-- .sidebar-inner -->
							
		</div><!-- .sidebar -->
		

	
		<div class="wrapper" id="wrapper">
		
			<div class="section-inner wrapper-inner">