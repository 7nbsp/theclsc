<?php $themeroot = get_template_directory_uri(); ?>
<?php 
//echo $_SERVER['HTTP_HOST']; 
$host = $_SERVER['HTTP_HOST'];
$sub = explode('.',$host);
$sublen = count($sub);
if($sublen > 2){
	//echo $sub[0];
	$username = $sub[0];
	$query = "SELECT id FROM $wpdb->users WHERE user_login='".$username."'";
	$author_row = $wpdb->get_row($wpdb->prepare($query), ARRAY_A);
	global $author_id;
	$author_id = $author_row['id'];
	if($author_id != 0){
		$_GET['author_id'] = $author_id;
	}else{
		echo 'There is no coalesce page by that name.';
		$_GET['author_id'] = 1;
	}
	//echo $author_id;
}else{
	$_GET['author_id'] = 1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php bloginfo('name'); wp_title();?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/type/Helsinki-fontfacekit/stylesheet.css" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head() ?>
<script src="<?=$themeroot?>/js/jquery-1.6.4.min.js"></script>
<script src="<?=$themeroot?>/js/jqfn.js"></script>
</head>

<body <?php body_class(); ?>> 
<div id="wrapperpub">
	<div id="header">
	<span onclick="window.location.href='/'" style="cursor: pointer;"><img style="margin-left: -10px;" src="<?=$themeroot?>/images/banner.png" /></span>
		<!--
		<div class="dp100">	
			<h1 id="blog-title" class="blogtitle"><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a></h1>
			
		</div>
		-->
		<div class="tagline">Fuse. Blend. Unite as one.</div>
		<div class="abouttagline"><?php bloginfo('description'); ?></div>
		<!-- dp100 -->	
		
	</div><!--  #header -->	
</div><!--  #wrapperpub -->			
<div class="clear"></div>
<div id="wrapper">	
		<div id="access">
			<?php wp_nav_menu(array( 'container_class' => 'menu-header', 'theme_location' => 'primary-menu',)); ?>
				<!--search-->
				<div class="search_form">
					<form role="search" method="get" id="searchform" action="http://thecn.org/" >
						<div><label class="screen-reader-text" for="s">Search for:</label>
						<input type="text" value="" name="s" id="s" />
						<input type="submit" id="searchsubmit" value="Search" />
						</div>
					</form>
				</div>
				<!--/search-->
		</div><!--  #access -->	
<div class="clear"></div>		
	