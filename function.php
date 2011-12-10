<?php function Login_Radius_Connect_button() {
$LoginRadius_apikey=get_option('LoginRadius_apikey');
$LoginRadius_secret=get_option('LoginRadius_secret');
if (!is_user_logged_in()) {
	if( $args == NULL )
		$display_label = true;
	elseif ( is_array( $args ) )
		extract( $args );
if( $display_label != false ) : ?>
			<div style="margin-bottom: 3px;"><label><?php _e( 'Please Login With', 'LoginRadius' );?>:</label></div>
		<?php endif; ?>
		<?php if( $LoginRadius_apikey!= "") : ?>
		<iframe src="https://hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $LoginRadius_apikey;?>" width="169" height="49" frameborder="0" scrolling="no" ></iframe>
		<?php endif; ?>
<?php }
if (is_user_logged_in() && !is_admin()) {
	global $user_ID; $user = get_userdata( $user_ID );
	echo "Welcome! "."".$user->user_login ;
	$redirect= get_permalink();?>
	<br />
<a href="<?php echo wp_logout_url($redirect);?>">Log Out</a><?php }
}
add_action( 'login_form','Login_Radius_Connect_button');
add_action( 'register_form', 'Login_Radius_Connect_button');
add_action( 'after_signup_form','Login_Radius_Connect_button');
add_action( 'comment_form_must_log_in_after','Login_Radius_Connect_button');?>