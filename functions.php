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
		
		// Post formats
		add_theme_support( 'post-formats', array( 'gallery', 'quote', 'status' ) );
		
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
			wp_register_style( 'rams_googleFonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,700|Noto+Serif+SC:500,900' );
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
		$font_url = 'https://fonts.googleapis.com/css?family=Montserrat:400,700|Noto+Serif+SC:500,900';
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

//替换<code>标签为<code class="language-markdown">
function replace_code_tag($content) {
	$content = str_replace('<code>', '<code class="language-markdown">', $content);
	return $content;
}
add_filter('the_content', 'replace_code_tag');

function add_prism() {
	wp_register_style('prismCSS', get_stylesheet_directory_uri() . '/css/prism.css');
	wp_register_script('prismJS', get_stylesheet_directory_uri() . '/js/prism.js');
	$content = '';
	if (is_singular()) {
		$content = get_the_content();
	} else {
		global $wp_query;
		$posts = $wp_query->posts;
		foreach ($posts as $post) {
			$content .= get_the_content(null, false, $post);
		}
	}
	if (strpos( $content, '<code class="language-') !== false) {
		wp_enqueue_style('prismCSS');
		wp_enqueue_script('prismJS');
	}
	if (strpos( $content, '<code>') !== false) {
		wp_enqueue_style('prismCSS');
		wp_enqueue_script('prismJS');
	}
}
add_action('wp_enqueue_scripts', 'add_prism');

function add_katex() {
	wp_register_style('katexCSS', get_stylesheet_directory_uri() . '/css/katex.min.css');
	wp_register_script('katexJS', get_stylesheet_directory_uri() . '/js/katex.min.js');
	wp_register_script('autorenderJS', get_stylesheet_directory_uri() . '/js/auto-render.min.js');
	wp_register_script('katexdelimitersJS', get_stylesheet_directory_uri() . '/js/katexdelimiters.js');
	$content = '';
	if (is_singular()) {
		$content = get_the_content();
	} else {
		global $wp_query;
		$posts = $wp_query->posts;
		foreach ($posts as $post) {
			$content .= get_the_content(null, false, $post);
		}
	}
	if ( strpos( $content, '$$' ) !== false ) {
		wp_enqueue_style('katexCSS');
		wp_enqueue_script('katexJS');
		wp_enqueue_script('autorenderJS');
		wp_enqueue_script('katexdelimitersJS');
	}
	if ( strpos( $content, '$' ) !== false ) {
		wp_enqueue_style('katexCSS');
		wp_enqueue_script('katexJS');
		wp_enqueue_script('autorenderJS');
		wp_enqueue_script('katexdelimitersJS');
	}
}
add_action('wp_enqueue_scripts', 'add_katex');

function remove_unnecessary_resources() {
	wp_dequeue_style( 'classic-theme-styles' );
	if( !is_page_template( 'normal-gallery.php' ) && !is_page_template( 'full-width-gallery.php' ) ){
		wp_dequeue_style('finalTilesGallery_stylesheet');
		wp_dequeue_style('fontawesome_stylesheet');
		wp_dequeue_style('everlightbox');
		wp_dequeue_script('finalTilesGallery');
		wp_dequeue_script('everlightbox');
	}
}
add_action( 'wp_enqueue_scripts', 'remove_unnecessary_resources' );

//每上传一张图片 wordpress都会在媒体库文件夹至少拉七坨不同尺寸的屎，故全面禁止拉屎
add_filter( 'intermediate_image_sizes_advanced', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );

function exclude_category_home( $query ) {
    if ( $query->is_home ) {
        $query->set( 'cat', '-2' );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'exclude_category_home' );

function remove_h2_from_post_content( $content ) {
    if ( is_home() ) {
        // 使用正则表达式移除首页所有 h2 标签
        $content = preg_replace('/<h2.*?>.*?<\/h2>/is', '', $content);
    }
    return $content;
}
add_filter( 'the_content', 'remove_h2_from_post_content' );

function article_index($content) {
	$matches = array();
	$ul_li = '';
	$r = '/<h([2-6]).*?\>(.*?)<\/h[2-6]>/is';
	if(is_single() && preg_match_all($r, $content, $matches)) {
	foreach($matches[1] as $key => $value) {
	$title = trim(strip_tags($matches[2][$key]));
	$content = str_replace($matches[0][$key], '<h' . $value . ' id="title-' . $key . '">'.$title.'</h2>', $content);
	$ul_li .= '<li><a href="#title-'.$key.'" title="'.$title.'">'.$title."</a></li>\n";
	}
	$content = "\n<div id=\"article-index\">
	<strong>目录</strong>
	<ul id=\"index-ul\">\n" . $ul_li . "</ul>
	</div>\n" . $content;
	}
	return $content;
	}
	add_filter( 'the_content', 'article_index' );

function wpd_change_date_structure(){
    global $wp_rewrite;
    $wp_rewrite->date_structure = 'archives/%year%/%monthnum%/%day%';
}
add_action( 'init', 'wpd_change_date_structure' );


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