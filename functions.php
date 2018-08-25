<?php


/* ---------------------------------------------------------------------------------------------
   THEME SETUP
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'rams_setup' ) ) {

	function rams_setup() {
		
		// Automatic feed
		add_theme_support( 'automatic-feed-links' );
		
		// Post thumbnails
		add_theme_support( 'post-thumbnails' ); 
		add_image_size( 'post-image', 800, 9999 );
		
		// Post formats
		add_theme_support( 'post-formats', array( 'gallery', 'quote' ) );
		
		add_theme_support('title-tag');
			
		// Jetpack infinite scroll
		add_theme_support( 'infinite-scroll', array(
			'container' => 'posts',
			'footer'    => 'wrapper',
			'type'      => 'click'
		) );
		
		// Add nav menu
		register_nav_menu( 'primary', __( 'Primary Menu', 'rams' ) );
		
		// Make the theme translation ready
		load_theme_textdomain( 'rams', get_template_directory() . '/languages' );
		
		$locale = get_locale();
		$locale_file = get_template_directory() . '/languages/$locale.php';

		if ( is_readable( $locale_file ) ) {
			require_once( $locale_file );
		}
		
	}
	add_action( 'after_setup_theme', 'rams_setup' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE SCRIPTS
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_load_javascript_files' ) ) {

	function rams_load_javascript_files() {

		if ( !is_admin() ) {
			wp_register_script( 'rams_global', get_template_directory_uri() . '/js/global.js', array( 'jquery' ), '', true );
			wp_register_script( 'rams_flexslider', get_template_directory_uri() . '/js/flexslider.min.js', array( 'jquery' ), '', true );
			
			wp_enqueue_script( 'rams_flexslider' );
			wp_enqueue_script( 'rams_global' );
			
			if ( is_singular() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
			
		}
	}
	add_action( 'wp_enqueue_scripts', 'rams_load_javascript_files' );

}


/* ---------------------------------------------------------------------------------------------
   ENQUEUE STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_load_style' ) ) {

	function rams_load_style() {
		if ( ! is_admin() ) {
			wp_register_style( 'rams_googleFonts', 'https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,700,700i|Montserrat:400,700|Noto+Serif|Palanquin:700' );
			wp_register_style( 'rams_style', get_stylesheet_uri() );
			
			wp_enqueue_style( 'rams_googleFonts' );
			wp_enqueue_style( 'rams_style' );
		}
	}
	add_action( 'wp_print_styles', 'rams_load_style' );

}


/* ---------------------------------------------------------------------------------------------
   ADD EDITOR STYLES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_add_editor_styles' ) ) {

	function rams_add_editor_styles() {
		add_editor_style( 'rams-editor-styles.css' );
		$font_url = 'https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,700,700i|Montserrat:400,700|Noto+Serif|Palanquin:700';
		add_editor_style( str_replace( ',', '%2C', $font_url ) );
	}
	add_action( 'init', 'rams_add_editor_styles' );

}


/* ---------------------------------------------------------------------------------------------
   SET CONTENT WIDTH
   --------------------------------------------------------------------------------------------- */


if ( ! isset( $content_width ) ) $content_width = 672;


/* ---------------------------------------------------------------------------------------------
   CHECK FOR JAVASCRIPT SUPPORT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_html_js_class' ) ) {

	function rams_html_js_class () {
		echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>'. "\n";
	}
	add_action( 'wp_head', 'rams_html_js_class', 1 );

}


/* ---------------------------------------------------------------------------------------------
   ADD CLASSES TO PAGINATION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_pagination_class_next' ) ) {

	function rams_pagination_class_next() {
		return 'class="archive-nav-older"';
	}
	add_filter( 'next_posts_link_attributes', 'rams_pagination_class_next' );

}

if ( ! function_exists( 'rams_pagination_class_prev' ) ) {

	function rams_pagination_class_prev() {
		return 'class="archive-nav-newer"';
	}
	add_filter( 'previous_posts_link_attributes', 'rams_pagination_class_prev' );

}


/* ---------------------------------------------------------------------------------------------
   CUSTOM MORE LINK TEXT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_custom_more_link' ) ) {

	function rams_custom_more_link( $more_link, $more_link_text ) {
		return str_replace( $more_link_text, __( 'Continue reading', 'rams' ) . ' &rarr;', $more_link );
	}
	add_filter( 'the_content_more_link', 'rams_custom_more_link', 10, 2 );

}

function remove_more_jump_link($link) { 
	$offset = strpos($link, '#more-');
	if ($offset) {
		$end = strpos($link, '"',$offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
}
add_filter('the_content_more_link', 'remove_more_jump_link');

function add_prism() {
	wp_register_style('prismCSS', get_stylesheet_directory_uri() . '/css/prism.css');
	wp_register_script('prismJS', get_stylesheet_directory_uri() . '/js/prism.js');

	global $post, $wp_query;
	$post_contents = '';
	if ( is_singular() ) {
			$post_contents = $post->post_content;
	} elseif ( is_archive() || (is_front_page() && is_home())) {
			$post_ids = wp_list_pluck( $wp_query->posts, 'ID' );
			foreach ( $post_ids as $post_id ) {
					$post_contents .= get_post_field( 'post_content', $post_id );
			}
	}
	if ( strpos( $post_contents, '<code class="language-' ) !== false ) {
			wp_enqueue_style('prismCSS');
			wp_enqueue_script('prismJS');
	}
}
add_action('wp_enqueue_scripts', 'add_prism');

function add_katex() {
	wp_register_style('katexCSS', 'https://cdn.jsdelivr.net/npm/katex@0.10.0-beta/dist/katex.min.css');
	wp_register_script('katexJS', 'https://cdn.jsdelivr.net/npm/katex@0.10.0-beta/dist/katex.min.js');
	wp_register_script('autorenderJS', 'https://cdn.jsdelivr.net/npm/katex@0.10.0-beta/dist/contrib/auto-render.min.js');
	wp_register_script('katexdelimitersJS', get_stylesheet_directory_uri() . '/js/katexdelimiters.js');

	global $post, $wp_query;
	$post_contents = '';
	if ( is_singular() ) {
			$post_contents = $post->post_content;
	} elseif ( is_archive() || (is_front_page() && is_home())) {
			$post_ids = wp_list_pluck( $wp_query->posts, 'ID' );
			foreach ( $post_ids as $post_id ) {
					$post_contents .= get_post_field( 'post_content', $post_id );
			}
	}
	if ( strpos( $post_contents, '$$$' ) !== false ) {
			wp_enqueue_style('katexCSS');
			wp_enqueue_script('katexJS');
			wp_enqueue_script('autorenderJS');
			wp_enqueue_script('katexdelimitersJS');
	}
	if ( strpos( $post_contents, '$$' ) !== false ) {
			wp_enqueue_style('katexCSS');
			wp_enqueue_script('katexJS');
			wp_enqueue_script('autorenderJS');
			wp_enqueue_script('katexdelimitersJS');
	}
}
add_action('wp_enqueue_scripts', 'add_katex');


/* ---------------------------------------------------------------------------------------------
   BODY & POST CLASSES
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_body_post_class' ) ) {

	function rams_body_post_class( $classes ) {
		
		$classes[] = has_post_thumbnail() ? 'has-featured-image' : 'no-featured-image';

		if ( is_page_template( 'full-width-gallery.php' ) ) {
			$classes[] = 'full-width-gallery';
		}

		if ( is_page_template( 'normal-gallery.php' ) ) {
			$classes[] = 'normal-gallery';
		}

		return $classes;
	}
	add_filter( 'post_class', 'rams_body_post_class' );
	add_filter( 'body_class', 'rams_body_post_class' );

}


/* ---------------------------------------------------------------------------------------------
   STYLE ADMIN AREA
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_admin_css' ) ) {

	function rams_admin_css() { 
	echo '<style type="text/css">

		#postimagediv #set-post-thumbnail img {
			max-width: 100%;
			height: auto;
		}

	</style>';
	}
	add_action( 'admin_head', 'rams_admin_css' );

}


/* ---------------------------------------------------------------------------------------------
   FLEXSLIDER OUTPUT
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_flexslider' ) ) {

	function rams_flexslider( $size ) {

		$attachment_parent = is_page() ? $post->ID : get_the_ID();

		if ( $images = get_posts( array(
			'post_parent'    => $attachment_parent,
			'post_type'      => 'attachment',
			'numberposts'    => -1, // show all
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => null,
			'post_mime_type' => 'image',
		) ) ) { ?>
		
			<div class="flexslider">
			
				<ul class="slides">
		
					<?php foreach( $images as $image ) :
						
						global $attachment_id;
						
						$default_attr = array(
							'alt'   => trim(strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ),
						);
					
						$attimg = wp_get_attachment_image( $image->ID, $size, $default_attr ); ?>
						
						<li>
							<?php echo $attimg; ?>
						</li>
						
					<?php endforeach; ?>
			
				</ul>
				
			</div><?php
			
		}
	}

}


/* ---------------------------------------------------------------------------------------------
   COMMENT FUNCTION
   --------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'rams_comment' ) ) {

	function rams_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			
				<?php __( 'Pingback:', 'rams' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'rams' ), '<span class="edit-link">', '</span>' ); ?>
				
			</li>
		<?php
				break;
			default :
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		
			<div id="comment-<?php comment_ID(); ?>" class="comment">
			
				<div class="avatar-container">
					<?php echo get_avatar( $comment, 160 ); ?>
				</div>
				
				<div class="comment-inner">
			
					<div class="comment-header">
												
						<h4><?php echo get_comment_author_link(); ?></h4>
						
						<p class="comment-date"><a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date() . '<span> &mdash; ' . get_comment_time() . '</span>'; ?></a></p>
					
					</div>
		
					<div class="comment-content post-content">
					
						<?php if ( '0' == $comment->comment_approved ) : ?>
						
							<p class="comment-awaiting-moderation"><?php __( 'Your comment is awaiting moderation.', 'rams' ); ?></p>
							
						<?php endif; ?>
					
						<?php comment_text(); ?>
						
					</div><!-- /comment-content -->
					
					<div class="comment-actions">
						
						<?php 
							comment_reply_link( array_merge( $args, 
							array( 
								'reply_text' 	=>  	__( 'Reply', 'rams' ), 
								'depth'			=> 		$depth, 
								'max_depth' 	=> 		$args['max_depth'],
								'before'		=>		'<p class="comment-reply">',
								'after'			=>		'</p>'
								) 
							) ); 
						?>
						
						<?php edit_comment_link( __( 'Edit', 'rams' ), '<p class="comment-edit">', '</p>' ); ?>
														
					</div><!-- .comment-actions -->
				
				</div><!-- .comment-inner -->
							
			</div><!-- /comment-## -->
					
		<?php
			break;
		endswitch;
	}

}


/* ---------------------------------------------------------------------------------------------
   CUSTOMIZER OPTIONS
   --------------------------------------------------------------------------------------------- */


class rams_Customize {

   public static function rams_register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'rams_options', 
         array(
            'title' => __( 'Options for Rams', 'rams' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize theme settings for Rams.', 'rams'), //Descriptive tooltip
         ) 
      );
      
      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
            'default' => '#6AA897', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
      		'sanitize_callback' => 'sanitize_hex_color'
         ) 
      );
      
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
         $wp_customize, //Pass the $wp_customize object (required)
         'rams_accent_color', //Set a unique ID for the control
         array(
            'label' => __( 'Accent Color', 'rams' ), //Admin-visible name of the control
            'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'accent_color', //Which setting to load and manipulate (serialized is okay)
            'priority' => 10, //Determines the order this control appears in for the specified section
         ) 
      ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }

   public static function rams_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           <?php self::rams_generate_css('body a', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('body a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.sidebar', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.flex-direction-nav a:hover', 'background-color', 'accent_color'); ?>
	           <?php self::rams_generate_css('a.post-quote:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content a', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content a:hover', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content a.more-link:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content input[type="submit"]:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content input[type="button"]:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-content input[type="reset"]:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('#infinite-handle span:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.page-links a:hover', 'background', 'accent_color'); ?>
	           <?php self::rams_generate_css('.post-meta-inner a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.add-comment-title a', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.add-comment-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.bypostauthor .avatar', 'border-color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.comment-actions a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.comment-header h4 a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('#cancel-comment-reply-link', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.comments-nav a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.comment-form input[type="submit"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.logged-in-as a:hover', 'color', 'accent_color'); ?>
	           <?php self::rams_generate_css('.archive-nav a:hover', 'color', 'accent_color'); ?>
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   
   public static function rams_live_preview() {
      wp_enqueue_script( 
           'rams-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

   public static function rams_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'rams_Customize' , 'rams_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'rams_Customize' , 'rams_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'rams_Customize' , 'rams_live_preview' ) );

?>