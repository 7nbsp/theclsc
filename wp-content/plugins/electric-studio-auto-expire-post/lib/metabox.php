<?php

function add_es_meta_box(){
  $esape_posttypes = get_option('esape_posttypes');
  if(is_array($esape_posttypes)){
    foreach($esape_posttypes as $espt){
      add_meta_box(
        'es_auto_expire',
        'Post Expiry Date',
        'es_meta_box',
        $espt,
        'side',
        'high'
      );
    }
  }
}

function esape_add_datepicker(){
  wp_enqueue_script(
		'jquery-ui-datepicker',
		get_bloginfo('wpurl') . '/wp-content/plugins/electric-studio-auto-expire-post/js/jquery-ui-1.8.11.custom.min.js',
		array('jquery')
	);
 
	wp_enqueue_script(
		'esape-datepicker',
		get_bloginfo('wpurl') . '/wp-content/plugins/electric-studio-auto-expire-post/js/esape-datepicker.js',
		array('jquery', 'jquery-ui-datepicker')
	);
}

function esape_add_datepicker_css() {
	wp_enqueue_style(
		'jquery-ui-datepicker-css',
		get_bloginfo('wpurl') . '/wp-content/plugins/electric-studio-auto-expire-post/css/smoothness/jquery-ui-1.8.11.custom.css'
	);
}

function es_meta_box( $post ){
  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'es_expirydate_noncename' );
  
  // The actual fields for data entry ?>
  <label for="expirydate"><?php _e('Expiry Date (YYYY-MM-DD)');?>:</label>
  <input type="text" id="expirydate" name="expirydate" value="<?php echo get_post_meta($post->ID,"_es_expirydate",true); ?>" size="11" />
<?php }

if(is_admin()){
  add_action('add_meta_boxes','add_es_meta_box');
  add_action('save_post', 'es_save_expirydate');
  add_action('admin_print_scripts', 'esape_add_datepicker');
  add_action('admin_print_styles', 'esape_add_datepicker_css');
}


function es_save_expirydate( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['es_expirydate_noncename'], plugin_basename(__FILE__) ) )
      return $post_id;

  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
      return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data

  $mydata = $_POST['expirydate'];

  update_post_meta($post_id, '_es_expirydate', $mydata);

  // Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)

   return $mydata;
}

?>
