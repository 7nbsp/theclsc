<?php
/*
This file is part of codium_extend. Hack and customize by henri labarre and based on the marvelous sandbox theme

codium_extend is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

*/


// This theme allows users to set a custom background
	add_custom_background();
	
// This theme allows users to set a custom header image
	define('HEADER_TEXTCOLOR', '444');
	define('HEADER_IMAGE_WIDTH', 980); // use width and height appropriate for your theme
	define('HEADER_IMAGE_HEIGHT', 250);
	
// gets included in the site header
function codium_extend_header_style() {
    if (get_header_image() != ''){
    ?><style type="text/css">
        div#header {
            background: url(<?php header_image(); ?>); height :230px; -moz-border-radius-topleft:6px;border-top-left-radius:6px;-moz-border-radius-topright:6px;border-top-right-radius:6px;
        }
        <?php if ( 'blank' == get_header_textcolor() ) { ?>
		h1.blogtitle,.description { display: none; }
		<?php } else { ?>
		h1.blogtitle a,.description { color:#<?php header_textcolor() ?>; }
    	<?php
		} ?>
		</style><?php
		}
	}


// gets included in the admin header
function codium_extend_admin_header_style() {
    ?><style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
    </style><?php
}
	
	add_custom_image_header('codium_extend_header_style', 'codium_extend_admin_header_style');

// Set the content width based on the theme's design and stylesheet.
	if ( ! isset( $content_width ) )
	$content_width = 620;
	
// Make theme available for translation
// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'codium_extend', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

//feed
	add_theme_support('automatic-feed-links');
	

// Generates semantic classes for BODY and POST element
function codium_extend_category_id_class($classes) {
	global $post;
	if (!is_404() && isset($post))
	foreach((get_the_category($post->ID)) as $category)
		$classes[] = $category->category_nicename;
	return $classes;
}
add_filter('body_class', 'codium_extend_category_id_class');

function codium_extend_tag_id_class($classes) {
	global $post;
	if (!is_404() && isset($post))
	if ( $tags = get_the_tags() )
		foreach ( $tags as $tag )
			$classes[] = 'tag-' . $tag->slug;
	return $classes;
}
add_filter('body_class', 'codium_extend_tag_id_class');

function codium_extend_author_id_class($classes) {
	global $post;
	if (!is_404() && isset($post))
		$classes[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
	return $classes;
}
add_filter('post_class', 'codium_extend_author_id_class');

// count comment
function codium_extend_comment_count( $print = true ) {
	global $comment, $post, $codium_extend_comment_alt;

	// Counts trackbacks and comments
	if ( $comment->comment_type == 'comment' ) {
		$count[] = "$codium_extend_comment_alt";
	} else {
		$count[] = "$codium_extend_comment_alt";
	}

	$count = join( ' ', $count ); // Available filter: comment_class

	// Tada again!
	echo $count;
	//return $print ? print($count) : $count;
}


// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function codium_extend_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function codium_extend_cats_meow($glue) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset($cats[$i]);
			break;
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function codium_extend_tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;

	return trim(join( $glue, $tags ));
}

if ( ! function_exists( 'codium_extend_posted_on' ) ) :
// data before post
function codium_extend_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s.', 'codium_extend' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'codium_extend' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'codium_extend_posted_in' ) ) :
// data after post
function codium_extend_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium_extend' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium_extend' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'codium_extend' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function codium_extend_widgets_init() {

		register_sidebar(array(
		'name' => 'SidebarTop',
		'description' => 'Top sidebar',
		'id'            => 'sidebartop',
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s"><div class="widgetblock">',
		'after_widget'   =>   "\n\t\t\t</div></li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<div class="widgettitleb"><h3 class="widgettitle">',
		'after_title'    =>   "</h3></div>\n" .''
		));
		
		register_sidebar(array(
		'name' => 'SidebarBottom',
		'description' => 'Bottom sidebar',
		'id'            => 'sidebarbottom',
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s"><div class="widgetblock">',
		'after_widget'   =>   "\n\t\t\t</div></li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<div class="widgettitleb"><h3 class="widgettitle">',
		'after_title'    =>   "</h3></div>\n" .''
		));

	}



// Changes default [...] in excerpt to a real link
function codium_extend_excerpt_more($more) {
       global $post;
       $readmore = __(' read more <span class="meta-nav">&raquo;</span>', 'codium_extend' );
	return ' <a href="'. get_permalink($post->ID) . '">' . $readmore . '</a>';
}
add_filter('excerpt_more', 'codium_extend_excerpt_more');


// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'codium_extend_widgets_init' );


// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

// Remember: the codium_extend is for play.

// footer link 
add_action('wp_footer', 'footer_link');

function footer_link() {	
	$anchorthemeowner='<a href="http://www.code-2-reduction.fr/codium_extend/" title="code reduction" target="blank">code reduction</a>';
  	$textfooter = __('Proudly powered by <a href="http://www.wordpress.org">Wordpress</a> and designed by ', 'codium_extend' );
  	$content = '<div id="footerlink"><div class="alignright"><p>' .$textfooter. $anchorthemeowner.'</p></div><div class="clear"></div></div></div>';
  	//echo $content;
}

//Remove <p> in excerpt
function codium_extend_strip_para_tags ($content) {
if ( is_home() && ($paged < 2 )) {
  $content = str_replace( '<p>', '', $content );
  $content = str_replace( '</p>', '', $content );
  return $content;
}
} 

if ( ! function_exists( 'codium_extend_comment' ) ) :
//Comment function
function codium_extend_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
   <li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
      <div class="comment-author vcard">
        <?php echo get_avatar( $comment, 48 ); ?>
		<?php printf(__('<div class="fn">%s</div> '), get_comment_author_link()) ?>
      </div>
        
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is in moderation.', 'codium_extend') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta"><?php printf(__('%1$s - %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'codium_extend'),
										get_comment_date(),
										get_comment_time(),
										'#comment-' . get_comment_ID() );
										edit_comment_link(__('Edit', 'codium_extend'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
	  <div class="clear"></div>	
			
      <div class="comment-body"><?php comment_text(); ?></div>
      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'codium_extend' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'codium_extend' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;
        
//custom menu support
add_action( 'init', 'codium_extend_register_my_menu' );

function codium_extend_register_my_menu() {
	register_nav_menu( 'primary-menu', __( 'Primary Menu', 'codium_extend' ) );
	register_nav_menu( 'secondary-menu', __( 'Footer Menu', 'codium_extend' ) );
}        

//font for the Title
function codium_extend__google_font() {
?>
<link href='http://fonts.googleapis.com/css?family=PT+Sans&subset=latin' rel='stylesheet' type='text/css'>
<?php
}

add_action('wp_head', 'codium_extend__google_font');


/* adds the correct author to the post based on URL org. */

add_action("gform_after_submission", "set_post_author", 10, 2);
function set_post_author($entry, $form){

    //getting post
    $post = get_post($entry["post_id"]);

    //changing post content
    $post->post_author = $entry["20"];

    //updating post
    wp_update_post($post);
    
}
?>