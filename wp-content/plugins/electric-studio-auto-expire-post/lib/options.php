<?php

/* N.B esape stands for 'Electric Studio Auto Post Expire' */

add_action('admin_menu', 'create_esape_options_page');
add_action('admin_init', 'register_and_build_esape_options');


function create_esape_options_page() {
  add_options_page('Post Expiry', 'Post Expiry', 'administrator', __FILE__, 'esape_options_page');
}

function esape_options_page(){
?>
  <div id="theme-options-wrap">
    <div class="icon32" id="icon-tools"> <br /> </div>
    <h2>Electric Studio Auto Post Expire Settings</h2>
    <p><?php _e('Change the settings of this plugin here.'); ?></p>
    <form method="post" action="options.php">
      <?php settings_fields('esape_posttypes'); ?>
      <?php do_settings_sections(__FILE__); ?>
      <p class="submit">
        <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
      </p>
    </form>
    <p>Plugin Created By <a href="http://www.electricstudio.co.uk/2011/05/wordpress-auto-post-expire-plugin/">Electric Studio</a></p>
  </div>
<?php
}


function register_and_build_esape_options() {
  register_setting('esape_posttypes', 'esape_posttypes', 'validate_posttype_setting');
  add_settings_section('main_section', 'Main Settings', 'esape_section_cb', __FILE__);
  add_settings_field('esape_posttypes','Post Types:','esape_posttype',__FILE__,'main_section'); //POST TYPES
}


function validate_posttype_setting($posttypes) {
  return $posttypes;
}

function esape_section_cb(){}

function esape_posttype(){
      $selectedArray = get_option('esape_posttypes'); //get list of checkboxes that should be selected
      $postTypes = get_post_types();
      $result = "";
      $i = 0;
      foreach($postTypes as $pt){ ?>
        <?php if($pt!='revision' && $pt!='nav_menu_item'){ 
          $result .= '<input type="checkbox" name="esape_posttypes[posttype_'.$i.']" value="'.$pt.'" ';
          if(is_array($selectedArray)){
            if(in_array($pt, $selectedArray)){
              $result .= "checked=\"checked\"";
            }
          }
          $result .= '/><label for="esape_posttypes[$posttype_$i]">'.$pt.'</label><br/>';
          $i++;
         } ?>
      <?php }
      echo $result;
}

?>
