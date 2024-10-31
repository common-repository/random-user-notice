<?php
/* 
Plugin Name: Random User Notice
Plugin URI: http://sumonhasan.com/plugins/random-user-notice/
Description: This plugin in the WordPress admin dashboard to show a random or a single message. Every admin user to be able to see this message. Go here for the Message & Settings<a href="options-general.php?page=random-user-notice"> Options </a>
Version: 1.0
Author: Sumon Hasan
Author URI: http://www.sumonhasan.com
*/
// Some Set-up
define('RANDOM_USER_NOTICE', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function random_user_notice_latest_jquery()
{
	wp_enqueue_script('jquery');
}
add_action('init', 'random_user_notice_latest_jquery');


// Random User Notice options
function random_user_notice_options_panel()  
{  
	add_options_page('Random User Notice', 'Random User Notice', 'manage_options', 'random-user-notice','random_user_notice_options_framwrork');  
}  
add_action('admin_menu', 'random_user_notice_options_panel');

// Random User Notice wp color picke
add_action( 'admin_enqueue_scripts', 'random_user_notice_color_pickr_function' );
function random_user_notice_color_pickr_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Default options values
$random_user_notice_default_options = array(
	'random_user_notice_default_value' => 
   "This Random Notice Title One.
	This Random Notice Title Two.
	This Random Notice Title Three.",
	'runcolor' => 'red',

);

if ( is_admin() ) : // Load only if we are viewing an admin page

function random_user_notic_settings_register() {
	// Register settings and call sanitation functions
	register_setting( 'random_user_notic_p_options', 'random_user_notice_default_options', 'random_user_notic_validate_options' );
}

add_action( 'admin_init', 'random_user_notic_settings_register' );

// Function to generate options page
function random_user_notice_options_framwrork() {
	global $random_user_notice_default_options;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; ?>

	<div class="wrap">

	<h2><?php _e('Random User Notice', 'run-text-domain'); ?></h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
	<?php $settings = get_option( 'random_user_notice_default_options', $random_user_notice_default_options ); ?>
	<?php settings_fields( 'random_user_notic_p_options' ); ?>
		<table class="form-table">

	
		
	
		<tr>
			<th scope="row"><?php _e('Random User Notice', 'run-text-domain'); ?></th>
			<td><fieldset>
			<p><label for="random_user_notice_default_value"><?php _e('Insert your notice or any massage here. Note: One line brake are count a single notice.</br>  Example : </br> This Random Notice Title One. </br> This Random Notice Title Two. </br> This Random Notice Title Three.', 'run-text-domain'); ?></label></p>
			<p>
			<textarea name="random_user_notice_default_options[random_user_notice_default_value]" rows="10" cols="50" id="random_user_notice_default_value" class="large-text code"><?php echo stripslashes($settings['random_user_notice_default_value']); ?></textarea>
			</p>
			</fieldset></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="runcolor"><?php _e('Notice Color', 'run-text-domain'); ?></label></th>
			<td>
				<input id="runcolor" type="text" name="random_user_notice_default_options[runcolor]" value="<?php echo $settings['runcolor']; ?>" class="my-color-field" />
			</td>
		</tr>
	</table>
	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>

	</form>
	</div>

	<?php
}

function random_user_notic_validate_options( $input ) {
	global $random_user_notice_default_options;

	$settings = get_option( 'random_user_notice_default_options', $random_user_notice_default_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['random_user_notice_default_value'] = wp_filter_post_kses( $input['random_user_notice_default_value'] );
	$input['runcolor'] = wp_filter_post_kses( $input['runcolor'] );

	return $input;
}

endif;  // EndIf is_admin()

class Random_User_Notic_Print {
	public function __construct()
	{
		add_action( 'admin_notices', array( $this, 'random_user_notic' ) );
	}

	function random_user_notic() {
		global $random_user_notice_default_options, $runcolor; $random_user_notice_ebtt_settings = get_option( 'random_user_notice_default_options', $random_user_notice_default_options ); 
		
		echo ( '<p class="random-un"><em><h3  style="color:'.$random_user_notice_ebtt_settings['runcolor'].'">' .
		$this->get_a_quote() .
		'</h3></em></p>' );
	}

	function get_a_quote() {
		global $random_user_notice_default_options,$runcolor, $random_user_notice_default_value; $random_user_notice_ebtt_settings = get_option( 'random_user_notice_default_options', $random_user_notice_default_options ); 
		
		$random_user_notice_ver= $random_user_notice_ebtt_settings['random_user_notice_default_value'];
	$random_user_notice_ver = explode( "\n", $random_user_notice_ver );
	$random_user_notice_ver_main  = $random_user_notice_ver[ mt_rand( 0, count ( $random_user_notice_ver ) - 1 ) ];
	return ( wptexturize( $random_user_notice_ver_main ) );
	}
}

$Random_User_Notic_Print = new Random_User_Notic_Print();

