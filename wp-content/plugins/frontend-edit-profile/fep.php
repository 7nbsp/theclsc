<?php
/*
Plugin Name: Frontend Edit Profile
Version: 1.0.2
Description: Add edit profile to your post or page
Author: Abdul Ibad
Author URI: http://www.dulabs.com
Plugin URI: http://www.localorganicfoodstore.com/wp-frontend-edit-profile
License: GPL
*/

/*Version Check*/
global $wp_version;
$exit_msg = "Dude, upgrade your stinkin Wordpress Installation.";
if(version_compare($wp_version, "2.8", "<")) { exit($exit_msg); }

define('FEP_VERSION', '1.0.2');
define("FEP_URL", WP_PLUGIN_URL . '/frontend-edit-profile/' );

if(!(function_exists('get_user_to_edit'))){
	require_once(ABSPATH.'/wp-admin/includes/user.php');
}

if(!(function_exists('_wp_get_user_contactmethods'))){
	require_once(ABSPATH.'/wp-includes/registration.php');
}

class FRONTEND_EDIT_PROFILE{
	
	var $wp_error;
		
	function __construct(){
		
		register_activation_hook(__FILE__, array($this,'default_settings'));
		add_action('admin_init', array($this,'settings_init'));
		add_shortcode('editprofile',array($this,'shortcode'));
		add_shortcode('EDITPROFILE',array($this,'shortcode'));
		
		add_action('admin_menu',array($this,'admin_menu'));	
		add_action('wp_print_styles',array($this,'form_style'));
		add_action('wp_print_scripts', array($this,'form_script'));
		add_action('init',array($this,'process_login_form'));	
		add_action('fep_loginform',array($this,'login_form'));
		
		add_filter('fep_contact_methods', array($this,'contact_methods'));
		add_filter('logout_url', array($this,'logout_url'));
		add_filter('login_url', array($this,'login_url'));
		add_filter('lostpassword_url', array($this,'lostpassword_url'));
		
	}
	
	function plugin_url(){
		$currentpath = dirname(__FILE__);
		$siteurl = get_option('siteurl').'/';
		$plugin_url = str_replace(ABSPATH,$siteurl,$currentpath);
		
		return $plugin_url;
	}
	
	
	function admin_menu(){
		$mypage = add_options_page('Frontend Edit Profile','Frontend Edit Profile','administrator','fep',array($this,'options_page'));
		
		add_action('admin_print_styles-'.$mypage,array($this,'admin_style'));
		add_action('admin_print_scripts-'.$mypage,array($this,'admin_script'));
	}
	
	function default_settings(){
		
		$siteurl = get_option('siteurl');
		
		$logout_url = $siteurl.'?action=logout&redirect_to='.$siteurl;
		$login_url = wp_login_url();
		
		$login_text = "You need <a href=\"%LOGIN_URL%\">login</a> to access this page";
		
		add_option('fep_pass_hint','off','','','yes');
		add_option('fep_custom_pass_hint','off','','yes');
		add_option('fep_text_pass_hint','','','yes');
		add_option('fep_pass_indicator','on','','yes');
		add_option('fep_biographical','off','','yes');
		add_option('fep_style','','','yes');
		add_option('fep_passmeter_style','','','yes');
		add_option('fep_notlogin',$login_text,'','yes');
		add_option('fep_contact_methods','','','yes');
		add_option('fep_loginform','off','','yes');
		add_option('fep_logouturl',$logout_url,'','yes');
		add_option('fep_loginurl','','','yes');
		add_option('fep_lostpasswordurl','','','yes');
	}
	
	function settings_init(){
		register_setting('fep_options','fep_pass_hint','');
		register_setting('fep_options','fep_custom_pass_hint','');
		register_setting('fep_options','fep_text_pass_hint','');
		register_setting('fep_options','fep_pass_indicator','');
		register_setting('fep_options','fep_biographical','');
		register_setting('fep_options','fep_style','');
		register_setting('fep_options','fep_passmeter_style','');
		register_setting('fep_options','fep_notlogin','');
		register_setting('fep_options','fep_contact_methods','');
		register_setting('fep_options','fep_loginform','');
		register_setting('fep_options','fep_logouturl','');
		register_setting('fep_options','fep_loginurl','');
		register_setting('fep_options','fep_lostpasswordurl','');
	}
	
	function login_url( $url ){
		$fep_url = get_option('fep_loginurl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function logout_url( $url ){
		
		if(is_admin()) return $url;
		
		$fep_url = get_option('fep_logouturl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function lostpassword_url( $url ){
		$fep_url = get_option('fep_lostpasswordurl');
		
		if(!empty($fep_url)){
			$url = $fep_url;
		}
		
		return $url;
	}
	
	function contact_methods(){
		
		$contact_methods = _wp_get_user_contactmethods();
		$fep_contact_methods = get_option('fep_contact_methods');
		
					if(!(is_array($fep_contact_methods))){
                                            $fep_contact_methods = array();
                                         }

		$new_contact_methods = array();
	
		foreach($contact_methods as $name => $desc){
			
			if(!in_array(strtolower($name),$fep_contact_methods)) continue;
			
			$new_contact_methods[] = $name;
		}
		
		return $new_contact_methods;
	}
	
	//
	// http://www.webcheatsheet.com/PHP/get_current_page_url.php
	//
	
	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	function options_page(){
		
		$pass_hint = (get_option('fep_pass_hint')=="on")? " checked=\"checked\"" : " ";
		
		$show_text_pass_hint = (get_option('fep_custom_pass_hint')=="on")? true : false;
		
		$custom_pass_hint = (get_option('fep_custom_pass_hint')=="on")? " checked=\"checked\"" : " ";
		
		$pass_indicator = (get_option('fep_pass_indicator')=="on")? " checked=\"checked\"" : " ";
		
		$biographical = (get_option('fep_biographical')=="on")? " checked=\"checked\"" : " ";
		
		$login_form = (get_option('fep_loginform')=="on") ? " checked=\"checked\"" : " ";
	
		$contact_methods = get_option("fep_contact_methods");
		
		if(!(is_array($contact_methods))){
			$contact_methods = array();
		}
		
		?>
		<script type="text/javascript">
			if($ == undefined){
				$ = jQuery;
			}
			
		    $(document).ready(function() {
		      $(':checkbox').iphoneStyle();
			});
		</script>
		<div class="wrap">
			<h2>Frontend Edit Profile</h2>
			<hr />
			<h3><?php _e("General Settings");?></h3>
			<form action="options.php" method="post">
			 <?php settings_fields('fep_options'); ?>
			<table class="widefat fixed">
				<tr>
					<th scope="row" style="width: 160px;"><?php _e("Override CSS file");?></th>
					<td><input type="text" name="fep_style" value="<?php echo esc_attr(get_option('fep_style'));?>" style="width: 60%;" /></td>
				</tr>
				<tr>
					<th scope="row"><?php _e("Override Passmeter CSS file");?></th>
					<td><input type="text" name="fep_passmeter_style" value="<?php echo esc_attr(get_option('fep_passmeter style'));?>" style="width: 60%;" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="fep_biographical"><?php _e("Show Biographical Info");?></label></th>
					<td><input type="checkbox" value="on" id="fep_biographical" name="fep_biographical"<?php echo $biographical;?>/></td>
				</tr>
				<tr>
					<th scope="row"><label for="pass_indicator"><?php _e("Show Password Indicator");?></label></th>
					<td><input type="checkbox" value="on" id="pass_indicator" name="fep_pass_indicator"<?php echo $pass_indicator;?>/></td>
				</tr>
				<tr>
					<th scope="row"><label for="pass_hint"><?php _e("Show Password Hint");?></label></th>
					<td><input type="checkbox" value="on" id="pass_hint" name="fep_pass_hint"<?php echo $pass_hint;?>/></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="custom_pass_hint"><?php _e("Custom Password Hint");?></label></th>
					<td valign="top">
						<input type="checkbox" value="on" id="fep_custom_pass_hint" name="fep_custom_pass_hint"<?php echo $custom_pass_hint;?>/>
					
							
						<br />	
						<textarea name="fep_text_pass_hint" id="fep_text_pass_hint" rows="5" cols="40"><?php echo get_option('fep_text_pass_hint')?></textarea>
					
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="login_form"><?php _e("Show Login Form");?></label></th>
					<td><input type="checkbox" value="on" id="login_form" name="fep_loginform"<?php echo $login_form;?>/></td>
				</tr>
				<tr>
					<th scope="row" valign="top"><label for="fep_notlogin"><?php _e("Not Logged in Text");?></label></th>
					<td valign="top"><textarea id="fep_notlogin" name="fep_notlogin" rows="5" cols="40"><?php echo get_option('fep_notlogin');?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="fep_loginurl"><?php _e("Login URL");?></label></th>
					<td><input type="text" id="fep_loginurl" name="fep_loginurl" value="<?php echo esc_attr(get_option('fep_loginurl'));?>" style="width: 60%;" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="fep_logouturl"><?php _e("Logout URL");?></label></th>
					<td><input type="text" id="fep_logouturl" name="fep_logouturl" value="<?php echo esc_attr(get_option('fep_logouturl'));?>" style="width: 60%;" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="fep_lostpasswordurl"><?php _e("Lost Password URL");?></label></th>
					<td><input type="text" id="fep_lostpasswordurl" name="fep_lostpasswordurl" value="<?php echo esc_attr(get_option('fep_lostpasswordurl'));?>" style="width: 60%;" /></td>
				</tr>
			</table>
			
			<h3><?php _e("Disable Contact Method(s)");?></h3>
			<em><?php _e("Click to disable contact method(s) in profile page");?></em>
		
			<table class="widefat fixed">
				<?php
					foreach (_wp_get_user_contactmethods() as $name => $desc) {
						
					if(in_array($name,$contact_methods)){
						$checked = " checked=\"checked\"";
					}else{
						$checked = " ";
					}
				?>
				<tr>
					<th scope="row" style="width:100px;"><input type="checkbox" name="fep_contact_methods[]" id="fep_contactmethod_<?php echo $name; ?>" value="<?php echo $name;?>" class="regular-text"<?php echo $checked;?> /></th>
					<td><label for="fep_contactmethod_<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label></td>
				</tr>
				<?php
					}	
				?>
			</table>
			  <p class="submit">
			  <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
			  </p>
			</form>	
		</div>
		<?php
	}
	
	function admin_style(){
			$plugin_url = self::plugin_url();
			$src = $plugin_url.'/iphonestyle/style.css';
			wp_enqueue_style('fep-admin-iphone-style',$src,'','1.0');
	}
	
	function admin_script(){
			$plugin_url = self::plugin_url();
			$src = $plugin_url.'/iphonestyle/iphone-style-checkboxes.js';
			wp_enqueue_script('fep-admin-iphone-script',$src,array('jquery'),'1.0');
	}
	
	function form_style() {

		$style = get_option('fep_style');
		$passmeter = get_option('fep_passmeter_style');
		
		if(!$style) {
			$src = FEP_URL .'fep.css';
			wp_register_style('fep-forms-style',$src,'',FEP_VERSION);
			wp_enqueue_style('fep-forms-style');
		} else {
			$src = $style;
			wp_register_style('fep-forms-custom-style',$src,'',FEP_VERSION);
			wp_enqueue_style('fep-forms-custom-style');
		}
		
		if(!$passmeter){
			$plugin_url = self::plugin_url();
			$passmeter = $plugin_url.'/passmeter/simplePassMeter.css';
			wp_enqueue_style('fep-forms-passmeter',$passmeter,'','0.3');
		}else{
			wp_enqueue_style('fep-forms-custom-passmeter',$passmeter,'','0.3');
		}
		
	//End Function
	}
	
	function form_script(){
		
		$plugin_url = self::plugin_url();
		
		$src = $plugin_url.'/fep.js';
		$passmeter = $plugin_url."/passmeter/jquery.simplePassMeter-0.3.min.js";
		wp_enqueue_script('fep-forms-passmeter',$passmeter, array('jquery'),'0.3');
		wp_enqueue_script('fep-forms-script',$src,array('fep-forms-passmeter'),'1.0');
	}
	
	function process_form( $atts ){
		
		global $wpdb;
		
		error_reporting(0);
		
		$errors = new WP_ERROR();
		
		$current_user = wp_get_current_user();
		
		$user_id = $current_user->ID;
		
		do_action('personal_options_update', $user_id);
		
		$user = get_userdata( $user_id );
		
		// Update the email address in signups, if present.
		if ( $user->user_login && isset( $_POST[ 'email' ] ) && is_email( $_POST[ 'email' ] ) && $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $user->user_login ) ) )
			$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s", $_POST[ 'email' ], $user_login ) );

		// WPMU must delete the user from the current blog if WP added him after editing.
		$delete_role = false;
		$blog_prefix = $wpdb->get_blog_prefix();
		if ( $user_id != $current_user->ID ) {
			$cap = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = '{$user_id}' AND meta_key = '{$blog_prefix}capabilities' AND meta_value = 'a:0:{}'" );
			if ( null == $cap && $_POST[ 'role' ] == '' ) {
				$_POST[ 'role' ] = 'contributor';
				$delete_role = true;
			}
		}
		if ( !isset( $errors ) || ( isset( $errors ) && is_object( $errors ) && false == $errors->get_error_codes() ) )
			$errors = edit_user($user_id);
		if ( $delete_role ) // stops users being added to current blog when they are edited
			delete_user_meta( $user_id, $blog_prefix . 'capabilities' );
		
		if(is_wp_error( $errors ) ) {
			$message = $errors->get_error_message();
			$style = "error";
		}else{
			$message = __("<strong>Success</strong>: Profile updated");
			$style = "success";
		}
			$output  = "<div id=\"fep-message\" class=\"fep-message-".$style."\">".$message.'</div>';
			$output .= $this->build_form();
			
			return $output; 
	}
	
	function build_form( $data="" ){
		
		$current_user = wp_get_current_user();
		
		$user_id = $current_user->ID;
		
		$profileuser = get_user_to_edit($user_id);
		
		$show_pass_hint = (get_option('fep_pass_hint')=="on")? true:false;
		
		$show_pass_indicator = (get_option('fep_pass_indicator')=="on")? true:false;
		
		$show_biographical = (get_option('fep_biographical')=="on")? true:false;
		
		ob_start();
		?>
		<div class="fep">
			<form id="your-profile" action="#fep-message" method="post"<?php do_action('user_edit_form_tag'); ?>>
			<?php wp_nonce_field('update-user_' . $user_id) ?>
			<?php if ( $wp_http_referer ) : ?>
				<input type="hidden" name="wp_http_referer" value="<?php echo esc_url($wp_http_referer); ?>" />
			<?php endif; ?>
			<p>
			<input type="hidden" name="from" value="profile" />
			<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
			</p>

			<table class="form-table">
	
<?php
			do_action('personal_options', $profileuser);
			?>
			</table>
			<?php
			do_action('profile_personal_options', $profileuser);
			?>

			<h3><?php _e('Name',FEP) ?></h3>

			<table class="form-table">
				<tr>
					<th><label for="user_login"><?php _e('Username'); ?></label></th>
					<td><input type="text" name="user_login" id="user_login" value="<?php echo esc_attr($profileuser->user_login); ?>" disabled="disabled" class="regular-text" /><br /><em><span class="description"><?php _e('Usernames cannot be changed.'); ?></span></em></td>
				</tr>
			<tr>
				<th><label for="first_name"><?php _e('First Name',FEP) ?></label></th>
				<td><input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($profileuser->first_name) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="last_name"><?php _e('Last Name',FEP) ?></label></th>
				<td><input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($profileuser->last_name) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="nickname"><?php _e('Nickname'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
				<td><input type="text" name="nickname" id="nickname" value="<?php echo esc_attr($profileuser->nickname) ?>" class="regular-text" /></td>
			</tr>

			<tr>
				<th><label for="display_name"><?php _e('Display to Public as',FEP) ?></label></th>
				<td>
					<select name="display_name" id="display_name">
					<?php
						$public_display = array();
						$public_display['display_username']  = $profileuser->user_login;
						$public_display['display_nickname']  = $profileuser->nickname;
						if ( !empty($profileuser->first_name) )
							$public_display['display_firstname'] = $profileuser->first_name;
						if ( !empty($profileuser->last_name) )
							$public_display['display_lastname'] = $profileuser->last_name;
						if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
							$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
							$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
						}
						if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
							$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;
						$public_display = array_map( 'trim', $public_display );
						$public_display = array_unique( $public_display );
						foreach ( $public_display as $id => $item ) {
					?>
						<option id="<?php echo $id; ?>" value="<?php echo esc_attr($item); ?>"<?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
					<?php
						}
					?>
					</select>
				</td>
			</tr>
			</table>

			<h3><?php _e('Contact Info',FEP) ?></h3>

			<table class="form-table">
			<tr>
				<th><label for="email"><?php _e('E-mail'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
				<td><input type="text" name="email" id="email" value="<?php echo esc_attr($profileuser->user_email) ?>" class="regular-text" />
				<?php
				$new_email = get_option( $current_user->ID . '_new_email' );
				if ( $new_email && $new_email != $current_user->user_email ) : ?>
				<div class="updated inline">
				<p><?php printf( __('There is a pending change of your e-mail to <code>%1$s</code>. <a href="%2$s">Cancel</a>',FEP), $new_email['newemail'], esc_url(get_permalink().'?dismiss=' . $current_user->ID . '_new_email'  ) ); ?></p>
				</div>
				<?php endif; ?>
				</td>
			</tr>

			<tr>
				<th><label for="url"><?php _e('Website',FEP) ?></label></th>
				<td><input type="text" name="url" id="url" value="<?php echo esc_attr($profileuser->user_url) ?>" class="regular-text code" /></td>
			</tr>

			<?php
				$contact_methods = array();
				
				$contact_methods = apply_filters("fep_contact_methods",$contact_methods);
					if(!(is_array($contact_methods))){
                                            $contact_methods = array();
                                         }
				foreach (_wp_get_user_contactmethods() as $name => $desc) {
				
						if(in_array($name,$contact_methods)) continue;
				?>
			<tr>
				<th><label for="<?php echo $name; ?>"><?php echo apply_filters('user_'.$name.'_label', $desc); ?></label></th>
				<td><input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_attr($profileuser->$name) ?>" class="regular-text" /></td>
			</tr>
			<?php
				}
			?>
			</table>
			<?php
			if( $show_biographical):
			?>
			<h3><?php _e('About Yourself'); ?></h3>
			<?php
			endif;
			?>

			<table class="form-table">
			<?php
			if( $show_biographical):
			?>
			<tr>
				<th><label for="description"><?php _e('Biographical Info'); ?></label></th>
				<td><textarea name="description" id="description" rows="5" cols="30"><?php echo esc_html($profileuser->description); ?></textarea><br />
				<span class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></span></td>
			</tr>
			<?php
			endif;
			?>
			
			<?php
			$show_password_fields = apply_filters('show_password_fields', true, $profileuser);
			if ( $show_password_fields ) :
			?>
			<tr id="password">
				<th><label for="pass1"><?php _e('New Password'); ?></label><br /><br /><em><span class="description"><?php _e("If you would like to change the password type a new one. Otherwise leave this blank."); ?></span></em></th>
				<td>
					<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" /><br /><br />
					<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" />&nbsp;<em><span class="description"><?php _e("Type your new password again."); ?></span></em>
					
					<?php if($show_pass_indicator):?>
					<div id="pass-strength-result"><?php _e('Strength indicator'); ?></div>
					<?php endif;?>
					
					<?php if($show_pass_hint):?>
					<p class="description indicator-hint">
					<?php 
					$passhint = get_option('fep_text_pass_hint');
					
					if(!empty($passhint)){ echo $passhint;}
					else{?>
							-&nbsp;<?php _e('The password should be at least seven characters long.'); ?><br />
							-&nbsp;<?php _e('To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).');?>
					<?php
				 		}
					?>
					</p>
					<?php endif;?>
				</td>
			</tr>
			<?php endif; ?>
			</table>

			<?php
				do_action( 'show_user_profile', $profileuser );
			?>

			<?php if ( count($profileuser->caps) > count($profileuser->roles) && apply_filters('additional_capabilities_display', true, $profileuser) ) { ?>
			<br class="clear" />
				<table width="99%" style="border: none;" cellspacing="2" cellpadding="3" class="editform">
					<tr>
						<th scope="row"><?php _e('Additional Capabilities') ?></th>
						<td><?php
						$output = '';
						foreach ( $profileuser->caps as $cap => $value ) {
							if ( !$wp_roles->is_role($cap) ) {
								if ( $output != '' )
									$output .= ', ';
								$output .= $value ? $cap : "Denied: {$cap}";
							}
						}
						echo $output;
						?></td>
					</tr>
				</table>
			<?php } ?>

			<p class="submit">
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($user_id); ?>" />
				<input type="submit" class="button-primary" value="<?php _e('Update Profile'); ?>" name="submit" />
			</p>
			</form>
		</div>
		
		<script type="text/javascript" charset="utf-8">
			if (window.location.hash == '#password') {
				document.getElementById('pass1').focus();
			}
		</script>
		<?php
		$form = ob_get_contents();
		ob_end_clean();
		
		return $form;
	}
	
	function process_login_form(){
		
		if(isset($_GET['action'])){
			$action = strtoupper($_GET['action']);
			switch($action){
				case "LOGOUT":
					if(is_user_logged_in()){
						wp_logout();
						$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : get_bloginfo('url').'/wp-login.php?loggedout=true';
						wp_safe_redirect( $redirect_to );
						exit();
					}else{
						$url = get_option('siteurl');
						wp_safe_redirect($url);
					}	
					
				break;
			}
		}
		
		if(!isset($_POST['fep_login'])) return;
		
		$userlogin = $_POST['log'];
		$userpass = $_POST['pwd'];
		$remember = $_POST['rememberme'];
		$creds = array();
		$creds['user_login'] = $userlogin;
		$creds['user_password'] = $userpass;
		$creds['remember'] = $remember;
		
		if(empty($userlogin)){
			$this->wp_error = new WP_ERROR("invalid_username",__('<strong>ERROR</strong>: Empty username'));
			return;
		}
		
		if(empty($userpass)){
			$this->wp_error = new WP_ERROR("incorrect_password",__('<strong>ERROR</strong>: Empty password'));
			return;
		}
		
		$user = wp_signon( $creds, false );
		
		if ( is_wp_error($user) ){
			$error_code = $user->get_error_code();
			switch(strtoupper($error_code)){
				case "INVALID_USERNAME":
				$this->wp_error = new WP_ERROR("invalid_username", __('<strong>ERROR</strong>: Invalid username'));
				break;
				case "INCORRECT_PASSWORD":
				$this->wp_error = new WP_ERROR("incorret_password", __('<strong>ERROR</strong>: Incorrect password'));
				break;
				default:
					$this->wp_error = $user;
				break;
			}
			
			return;
		}else{	
		 	$redirect = $this->curPageURL();
			wp_redirect($redirect);
			exit;
		}
	
	}
	
	function login_form( $url="" ){
		
		$wp_error = $this->wp_error;
			
		if( is_wp_error($wp_error)){
			echo "<div class=\"fep-message-error\">".$wp_error->get_error_message()."</div>";
		}
		
		?>
		<form method="post">
			<input type="hidden" name="fep_login" value="1" />
		    <p><label for="log"><?php _e('Username');?></label><br /><input type="text" name="log" id="log" value="" size="20" /> </p>

		    <p><label for="pwd"><?php _e('Password');?></label><br /><input type="password" name="pwd" id="pwd" size="20" /></p>

		    <p><input type="submit" name="submit" value="<?php _e('Logged me in');?>" class="button" /></p>

		    <p>
		       <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> <?php _e('Remember me');?></label>
		       <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
		    </p>
		</form>
		
		<?php
	}
	
	function basic_form( $atts ){
		
		$text = get_option("fep_notlogin");
		$show_loginform = (get_option('fep_loginform') == "on")? true : false;	
			
		if( !(is_user_logged_in()) ){
			
			$login_url = wp_login_url();
			$lostpassword_url = wp_lostpassword_url();
			$text = str_replace("%LOGIN_URL%",$login_url,$text);
			$text = str_replace("%LOSTPASSWORD_URL%",$lostpassword_url,$text);
			
			_e($text);
			if($show_loginform){
				echo "<br /><br />";
				do_action('fep_loginform');
			}
			return;
		}
		
		if(isset($_POST['user_id'])) {
			$output = self::process_form($atts);	
			return $output;
		} else {
			$data = array();
			$form = self::build_form( $data );
			return $form;		
		}
		

	}
	
	function shortcode( $atts ){
		$function = self::basic_form( $atts );
		return $function;
	}
	
}

$fep = new FRONTEND_EDIT_PROFILE;

?>