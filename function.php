<?php function Login_Radius_Connect_button() {
$LoginRadius_apikey=get_option('LoginRadius_apikey');
$LoginRadius_secret=get_option('LoginRadius_secret');
$title=get_option('title');
if (!is_user_logged_in()) {
	if( $args == NULL )
		$display_label = true;
	elseif ( is_array( $args ) )
		extract( $args );
if( $display_label != false ) : ?>
			<div style="margin-bottom: 3px;"><label><?php _e( $title, 'LoginRadius' );?>:</label></div>
		<?php endif; ?>
		<?php if( $LoginRadius_apikey!= "") : 
	    $loc="http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		if($_SERVER["REQUEST_URI"]=='/wp-login.php?action=register' 
		OR $_SERVER["REQUEST_URI"]=='/wp-login.php' OR $_SERVER["REQUEST_URI"]=='/wp-login.php?loggedout=true' ) {
		$loc="http://".$_SERVER["HTTP_HOST"];
		}
		if(isset($_GET['redirect_to'])) {
		$loc=$_GET['redirect_to'];
		}
		if(urldecode($_GET['redirect_to'])==admin_url()) {
		$loc="http://".$_SERVER["HTTP_HOST"];
		}?>
		<iframe src="https://hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $LoginRadius_apikey;?>&callback=<?php echo $loc;?>" width="169" height="49" frameborder="0" scrolling="no" ></iframe>
		<?php endif; ?>
<?php }
if (is_user_logged_in() && !is_admin()) {
	global $user_ID; $user = get_userdata( $user_ID );
	echo "Benvenuto! "."".$user->user_login ;
	$redirect= get_permalink();?>
	<br />
<a href="<?php echo wp_logout_url($redirect);?>">Log Out</a><?php }
}
add_action( 'login_form','Login_Radius_Connect_button');
add_action( 'register_form', 'Login_Radius_Connect_button');
add_action( 'after_signup_form','Login_Radius_Connect_button');
if ( get_option('comment_registration') && !$user_ID )
{
add_action( 'comment_form_must_log_in_after','Login_Radius_Connect_button');
}else{
add_action( 'comment_form_top','Login_Radius_Connect_button');}
function LoginRadius_redirect()
{
$LoginRadius_redirect=get_option('LoginRadius_redirect');
$LoginRadius_redirect_custom_redirect=get_option('LoginRadius_redirect_custom_redirect');
$redirect_to = site_url();
$redirect_to_safe = false;
if ( ! empty ($_GET['redirect_to']))
{
$redirect_to = $_GET['redirect_to'];
$redirect_to_safe = true;
}
else
{
 if (isset($LoginRadius_redirect))
	{
		switch (strtolower($LoginRadius_redirect))
			{
			   case 'homepage':
				$redirect_to = site_url();
				break;
				case 'dashboard':
				$redirect_to = admin_url();
				break;
				case 'custom':
				if ( isset ($LoginRadius_redirect) && strlen(trim($LoginRadius_redirect_custom_redirect)) > 0)
				{
				$redirect_to = trim($LoginRadius_redirect_custom_redirect);
				}
				break;
				default:
				case 'samepage':
				$redirect_to = $_GET['callback'];
				break;
	        }
}		}
if ($redirect_to_safe)
{
wp_redirect($redirect_to);
}
else
{
wp_safe_redirect($redirect_to);
}
}?>