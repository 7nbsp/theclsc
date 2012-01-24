<div id="primary" class="sidebar">
	<ul class="xoxo">
	
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebartop') ) : // begin primary sidebar widgets ?>	
	
<!-- about -->
	<li id="description" class="widget widget_text">
	<div class="widgetblock">		
	<div class="widgettitleb"><h3 class="widgettitle"><?php printf(__('About', 'codium_extend')); ?></h3></div> 
	<p><?php printf(__('codium_extend is a minimalist theme wich can be customize quite easily. Have fun with it!', 'codium_extend')); ?></p>
	</div>	
	</li>
	
<!-- search -->
	<li id="search" class="widget widget_search">
	<div class="widgetblock">		
	<div class="widgettitleb"><h3 class="widgettitle"><?php printf(__('Search', 'codium_extend')); ?></h3></div> 
	<form id="searchform" method="get" action="<?php echo home_url( '/' ); ?>">
				<input type="text" name="s" id="s" style="width:190px;border:1px solid #DDD;background:#fff;font-size:1em" value="" onfocus="this.value='';" />
				<input type="submit" style="border:1px solid #DDD;background:#F5F5F5;width:40px;font-size:1em" value="<?php printf(__('Ok', 'codium_extend')); ?>" />
				
	</form>
	</div>
	</li>
	
<!-- Archive -->
	<li id="archives" class="widget widget_archive"> 
	<div class="widgetblock">		
	<div class="widgettitleb"><h3 class="widgettitle"><?php printf(__('Archives', 'codium_extend')); ?></h3></div> 
	<ul> 
	<?php wp_get_archives(); ?> 
	</ul> 
 	</div>
 	</li>

<!-- Meta -->
 	<li id="meta" class="widget widget_meta">
	<div class="widgetblock">
	<div class="widgettitleb"><h3 class="widgettitle"><?php printf(__('Meta', 'codium_extend')); ?></h3></div> 
	<ul>
	<?php wp_register(); ?>
	<li><?php wp_loginout(); ?></li>
	<?php wp_meta(); ?>
	</ul>
	</div>
	</li>
 	

	<?php endif; // end primary sidebar widgets  ?>
	<?php /* <li id="text-6" class="widget widget_text"><div class="widgetblock">
		<div class="widgettitleb"><h3 class="widgettitle">Your Account</h3></div>
			<div class="textwidget">
				<?php if ( is_user_logged_in() ) { ?>
					<a href="http://thecn.org/login/?action=profile&subs=0">profile</a><br />
					<a href="http://thecn.org/wp-admin/users.php?page=s2_users">subscriptions</a>
					<!--<a href="http://thecn.org/login/?action=profile&subs=1">subscriptions</a>--><br />
					<a href="http://thecn.org/login/?action=logout">logout</a><br />
				<?php }else{ ?> 
					<a href="<?php echo wp_login_url( get_permalink() ); ?>" title="Login">login</a>
				<?php } ?>
			</div>
		</div>
	</li>
	*/ ?>
	</ul>
</div><!-- #primary .sidebar -->

<div id="secondary" class="sidebar">
	<ul class="xoxo">
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebarbottom') ) : // begin  secondary sidebar widgets ?>
	
	
	<?php endif; // end secondary sidebar widgets  ?>
	</ul>
</div><!-- #secondary .sidebar -->