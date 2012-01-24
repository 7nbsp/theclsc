<div id="footer">
<?php wp_nav_menu(array( 'container_class' => 'menu-header', 'theme_location' => 'secondary-menu',)); ?>
</div>
<div style="float: left; padding-top: 30px; color: #00137F;">
&copy; 2003 - 2011 The Coalesce
</div>
<div id="accessmobile" class="mobileon">
	<?php wp_nav_menu(array('link_before' => '<img src="/wp-content/themes/codium-extend/images/arrow.png">', 'container_class' => 'menu-header', 'theme_location' => 'primary-menu',)); ?>			
</div><!--  #accessmobile -->	
<div class="clear"></div>
<br />
<br />
<?php wp_footer() ?>
</body>
</html>