<?php
/*
Template Name: Posts
*/
 ?>
<?php get_header(); ?>
	<div id="container">
		<div id="content"> 
		<?php 
			//echo $author_id;
		 ?>
			
			<ul>
			<?php
			$the_query = new WP_Query('author='.$author_id);
			//var_dump($myposts);
			while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	

		
			<!-- Begin post -->
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Link to %s', 'codium_extend'), esc_html(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				<div class="entry-date"><?php unset($previousday); printf(__('%1$s &#8211; %2$s', 'codium_extend'), the_date('', '', '', false), get_the_time()) ?></div> 

						<div class="entry-content">
						
						<?php 
							$needDate = get_post_meta($post->ID, 'needDate', true);
							if($needDate!=''){
								echo '<strong>When: </strong>'.$needDate.'<br />';
							} 
							echo '<strong>'.get_post_meta($post->ID, 'needName', true).' needs your help: </strong>';
						?>
						<?php the_content(''.__('read more <span class="meta-nav">&raquo;</span>', 'codium_extend').''); ?>
						<?php 
							$needAddress = get_post_meta($post->ID, 'needAddress', true);
							if($needAddress!=''){
								
								/* variables */
								$needCity = get_post_meta($post->ID, 'needCity', true);
								if($needCity!=''){
									$needCity = $needCity.', ';
								}
								$needState = get_post_meta($post->ID, 'needState', true);
								if($needState!=''){
									$needState = $needState.' ';
								}
								$needZip = get_post_meta($post->ID, 'needZip', true);
								$csz = $needCity.$needState.$needZip;
								$addrtosearch = $needAddress.' '.$csz;
								
								/* output */
								echo '<strong>Where: </strong>'.$needAddress.'&nbsp;';
								echo $csz.'&nbsp;<a href="http://maps.google.com/?q='.$addrtosearch.'" target="_blank">Map</a>';
							
							} 
						?>
					
					<?php 
					/* Contact Info */
						echo '<br /><strong>Contact: </strong>&nbsp;';
						echo get_post_meta($post->ID, 'contactName', true).'&nbsp;'; 
						echo ' <strong>phone</strong> '.get_post_meta($post->ID, 'contactPhone', true).'&nbsp';
						echo ' <strong>email:</strong> <a href="mailto'.get_post_meta($post->ID, 'contactEmail', true).'">'.get_post_meta($post->ID, 'contactEmail', true).'</a><br />';
						echo '<br />'; ?>
						<a href="<?php the_permalink() ?>">I can help with this!</a>
					<?php wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'codium_extend'), "</div>\n", 'number'); ?>
						</div>
						<div class="clear"></div>
						<div class="entry-meta">
							<?php the_tags(__('<span class="tag-links">Tags ', 'codium_extend'), ", ", "</span>\n\t\t\t\t\t<span class=\"meta-sep\">|</span>\n") ?>
							<?php //comments_template(); ?> 
						</div>
						
			</div>
			<!-- End post -->

<div class="linebreak clear"></div>
<?php 
endwhile; 
wp_reset_postdata();
?>
		</ul>
		
		</div><!-- #content -->
	</div><!-- #container -->
	
<?php get_sidebar() ?>
<?php get_footer() ?>