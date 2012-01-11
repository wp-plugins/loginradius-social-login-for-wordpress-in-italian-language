<?php
/*Plugin Name:Social Login for wordpress in italian language
Plugin URI: http://www.LoginRadius.com
Description: LoginRadius plugin enables social login on a wordpress website letting users log in through their existing IDs such as Facebook, Twitter, Google, Yahoo and over 15 more! This eliminates long registration process i.e. filling up a long registration form, verifying email ID, remembering another username and password so your users are just one click away from logging in to your website. Other than social login, LoginRadius plugin also include User Profile Data and Social Analytics.
Version: 2.1
Author: LoginRadius Team
Author URI: http://www.LoginRadius.com
License: GPL2+
*/
include('function.php');
include('header.php');
include('LoginRadius_admin.php');
include('LoginRadiusSDK.php');
@ini_set('display_errors',0);
$LoginRadiuspluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';
class Login_Radius_Connect {
public static function init() {
			add_action( 'parse_request', array(get_class(), 'connect') );
		    add_action( 'wp_enqueue_scripts', array(get_class(), 'LoginRadius_front_css_custom_page' ) );
			add_filter( 'LR_logout_url' , array(get_class(), 'log_out_url'), 20, 2);
					}
public static function LoginRadius_front_css_custom_page() {
    wp_register_style('LoginRadius-plugin-frontpage-css', plugins_url('style.css', __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_style('LoginRadius-plugin-frontpage-css');
 }			
public static function log_out_url() {
				$redirect= get_permalink();
			    $link = '<a href="' . wp_logout_url($redirect) . '" title="'.e__('Logout').'">'.e__('Logout').'</a>';
			    echo apply_filters('Login_Radius_log_out_url',$link);
		                 }
public static function connect() {
                            $LoginRadius_secret=get_option('LoginRadius_secret');
							$dummyemail=get_option('dummyemail');
							$obj = new LoginRadius();
                            $userprofile = $obj->construct($LoginRadius_secret);
if($obj->IsAuthenticated == true && !is_user_logged_in() && !is_admin()) 
      {
	   $id=$userprofile->ID;
		if(!empty($userprofile->Email[0]->Value) || $dummyemail==true)
			{
					 $Email=$userprofile->Email[0]->Value;
					 $FullName=$userprofile->FullName;
					 $ProfileName=$userprofile->ProfileName;
					 $Fname=$userprofile->FirstName; 
					 $Lname=$userprofile->LastName;
					 $id=$userprofile->ID;
					 $Provider=$userprofile->Provider;
					 $user_pass=wp_generate_password();
					 self::add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass);
			}
if(empty($userprofile->Email[0]->Value) && $dummyemail==false )
{ global $wpdb;
$FullName=$userprofile->FullName;
$ProfileName=$userprofile->ProfileName;
$Fname=$userprofile->FirstName; 
$Lname=$userprofile->LastName;
$id=$userprofile->ID;
$Provider=$userprofile->Provider;
$msg="<p>Inserisci il tuo indirizzo email per procedere.</p>";
      // look for users with the id match
$wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='id' AND meta_value = %s",$id));
	if ( !empty($wp_user_id) ){
	            // set cookies manually since 
					self::set_cookies($wp_user_id);
				    $redirect=LoginRadius_redirect();
				   wp_redirect($redirect);
				   }
else{ self::popup($FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$msg); } 
   } //check email ends
}//autantication ends
if(isset($_POST['LoginRadiusRedSliderClick']))
{
   $user_email=urldecode($_POST['email']);
   if (! is_email($user_email) OR email_exists ($user_email))
    {
		 $msg="<p style='color:red;'>Questa e-mail e gia registrato o non valido, si prega di scegliere un altro.</p>";
		 $id=$_POST['Id'];  
		 $Fname=$_POST['fname'];
		 $Lname=$_POST['lname'];
		 $ProfileName=$_POST['profileName'];
		 $FullName=$_POST['fullName'];
		 $Provider=$_POST['provider'];
		self::popup($FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$msg);
   }
else{
	$id=$_POST['Id'];  
    $Email=urldecode($_POST['email']);
	$Fname=$_POST['fname'];
	$Lname=$_POST['lname'];
	$ProfileName=$_POST['profileName'];
	$FullName=$_POST['fullName'];
	$Provider=$_POST['provider'];
	$user_pass=wp_generate_password();
    self::add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass);
    }
}
}//connect ends
private static function add_user($Email,$FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$user_pass)
{
//if anything not found correctly
$dummyemail=get_option('dummyemail'); 
$Email_id=substr($id,7);
$Email_id2=str_replace("/","_",$Email_id);
switch( $Provider ){
		case 'facebook':
					 $username=$Fname.$Lname;
					 $fname=$Fname;
					 $lname=$Lname;
					 $email=$Email;
                     break;
        case 'twitter':
				$username=$ProfileName;
				$fname=$ProfileName;
				$lname=$ProfileName;
					if ($dummyemail==false){
					$email=$Email;}
					else{
					$email=$id.'@'.$Provider.'.com';}
					break;
        case 'google':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=$Email;
					break;
        case 'yahoo':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=$Email;
					break;
        case 'linkedin':
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					if ($dummyemail==false){
					$email=$Email;}
					else{
					$email=$id.'@'.$Provider.'.com';}
					break;
		case 'aol':
					$user_name=explode('@',$Email);
					$username=$user_name[0];
					$Name=explode('@',$username);
					$fname=str_replace("_"," ",$Name[0]);
					$lname=str_replace("_"," ",$Name[0]);
					$email=$Email;
		            break;
		case 'hyves':
					$username=$FullName;
					$fname=$FullName;
					$lname=$FullName;
					$email=$Email;
					break;
		default:
				if($Fname=='' && $Lname=='' && $FullName!='')
				{ $Fname=$FullName;}
				if($Fname=='' && $Lname=='' && $FullName=='' && $ProfileName!='')
				   {$Fname=$ProfileName;}
				$Email_id=substr($id,7);
				$Email_id2=str_replace("/","_",$Email_id);
				if($Fname=='' && $Lname=='' && $Email=='' && $id!='')
				{
				$username=$id;
				$fname=$id;
				$lname=$id;
				$email=str_replace(".","_",$Email_id2).'@'.$Provider.'.com';
				}
					else if($Fname!='' && $Lname!='' && $Email=='' && $id!=''){
					$username=$Fname.$Lname;
					$fname=$Fname;
					$lname=$Lname;
					$email=str_replace(" ","_",$username).'@'.$Provider.'.com';
					}
					else if($Fname=='' && $Lname=='' && $Email!=''){
							$user_name=explode('@',$Email);
							$username=$user_name[0];
							$Name=explode('@',$username);
							$fname=str_replace("_"," ",$Name[0]);
							$lname=str_replace("_"," ",$Name[0]);
							$email=$Email;
							}
							else if($Lname=='' && $Fname!='' && $Email!=''){
							$username=$Fname;
							$fname=$Fname;
							$lname=$Fname;
							$email=$Email;
							}
								else {
								$username=$Fname.$Lname;
								$fname=$Fname;
								$lname=$Lname;
								$email=$Email;
								}
               break;
              }
global $wpdb;
 $dummyemail=get_option('dummyemail');
 //look for user with username match	
 						  $nameexists = true;
						  $index = 0;
						  $userName = $username;
						  $first_name=$fname;
						  while ($nameexists == true) {
							if (username_exists($userName) != 0) {
							  $index++;
							  $userName = $username.$index;
							  $first_name=$fname.$index;
							} else {
							  $nameexists = false;
							}
						  }
						  $username=$userName;
						 $userdata = array( 
                               'user_login' => $username,
							   'user_nicename' => $fname,
							   'user_email' => $email, 
							   'display_name' => $fname,
                               'nickname' => $fname,
                               'first_name' => $fname,
							   'user_url' => home_url(),
							   'role' => 'Subscriber'
							   );
// look for users with the id match
$wp_user_id = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='id' AND meta_value = %s",$id));
				if (empty($wp_user_id)) {
					// Look for a user with the same email
	             $wp_user_obj = get_user_by('email', $email);
                   // get the userid from the  email if the query failed
					$wp_user_id = $wp_user_obj->ID;
					}
				    if ( !empty($wp_user_id) ) {
					// set cookies manually since wp_signon requires the username/password combo.
					  self::set_cookies($wp_user_id);
					  $redirect=LoginRadius_redirect();
					  wp_redirect($redirect);
					}
					else {  
					if (!empty($email)) {
					  $user_id = wp_create_user( $username,$user_pass, $email );
					  wp_new_user_notification($username,$user_pass);
					  }
                      if (! is_wp_error($user_id) ) 
					  {
					  if (!empty($email)) {
					   $user = wp_signon(
										array(
												'user_login' =>$username,
												'user_password' =>$user_pass,
												'remember' => true
											), false );
                        do_action( 'LR_registration',$user,$username,$email,$user_pass,$userdata);
					}
if( is_wp_error( $user )){}else{}
if (!empty($email)) {
update_user_meta($user_id,'email',$email);
}
if (!empty($id)) {
update_user_meta($user_id,'id',$id );
}
						  wp_clear_auth_cookie();
						  wp_set_auth_cookie($user_id);
			              wp_set_current_user($user_id);
						  $redirect=LoginRadius_redirect();
					      wp_redirect($redirect);
						  } 
else {
	wp_redirect($redirect);
}}
}
private static function popup($FullName,$ProfileName,$Fname,$Lname,$id,$Provider,$msg)
{?>
<div id="fade" class="LoginRadius_overlay" class="LoginRadius_content_IE">
<div id="popupouter">
  <div id="popupinner">
    <div id="textmatter"><?php if($msg){echo "<b>".$msg."</b>";}?></div> 
		<form id="wp_login_form"  method="post"  action="">
					<div><input type="text" name="email" id="email" class="inputtxt" /></div><div>
<input type="submit" id="LoginRadiusRedSliderClick" name="LoginRadiusRedSliderClick" value="Submit" class="inputbutton">
<input type="submit" value="cancel" class="inputbutton" onClick="history.back()" />
					<input type="hidden" name="provider" id="provider" value="<?php echo $Provider;?>" />
					<input type="hidden" name="fname" id="fname" value="<?php echo $Fname;?>" />
					<input type="hidden" name="lname" id="lname" value="<?php echo $Lname;?>" />
					<input type="hidden" name="profileName" id="profileName" value="<?php echo $ProfileName;?>" />
					<input type="hidden" name="fullName" id="fullName" value="<?php echo $FullName;?>" />
					<input type="hidden" name="Id" id="Id" value="<?php echo $id;?>" /></div>
					</form>
					<div id="textdiv">Poweredby <span class="span">Login</span><span class="span1">Radius</span></div>
					</div></div></div>
<?php }
private static function set_cookies( $user_id = 0, $remember = true ) 
			{   
			   if ( !function_exists( 'wp_set_auth_cookie' ) )
				return false;
			   if (!$user_id)
				return false;
			   if ( !$user = get_userdata( $user_id ) )
				return false;
				wp_clear_auth_cookie();
				wp_set_auth_cookie( $user_id, $remember );
				wp_set_current_user( $user_id );
				return true;
			}
}//class end
add_action( 'init', array( 'Login_Radius_Connect', 'init' ));
/**
 * Set the Admin settings on activation on the plugin.
 */
if (! function_exists('esc_attr')) {
function esc_attr( $text ) {
return attribute_escape( $text );
}
}
/**
 * Set the default settings on activation on the plugin.
 */
function LoginRadius_activation_hook() {
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = 'LoginRadiusoff'");
	return LoginRadius_restore_config(false);
     }
register_activation_hook(__FILE__, 'LoginRadius_activation_hook');


/**
 * Add the LoginRadius menu to the Settings menu
 */
function LoginRadius_admin_menu() {
$page=add_options_page('LoginRadius','<b style="color:#0ccdfe;">Login</b><b style="color:#000;">Radius</b>', 8,'LoginRadius', 'LoginRadius_submenu');
add_action ('admin_print_styles-'.$page, 'LoginRadius_admin_css_custom_page');
}
add_action('admin_menu', 'LoginRadius_admin_menu');
/**
 * Add Settings CSS
 **/
function LoginRadius_admin_css_custom_page() {
    wp_register_style('LoginRadius-plugin-page-css', plugins_url('style.css', __FILE__), array(), '1.0.0', 'all');
    wp_enqueue_style('LoginRadius-plugin-page-css');
 }
/**
 * Update message, used in the admin panel to show messages to users.
 */
function LoginRadius_message($message) {
	echo "<div id=\"message\" class=\"updated fade\"><p>$message</p></div>\n";
}
/**
 * Add a settings link to the Plugins page, so people can go straight from the plugin page to the
 * settings page.
*/
function LoginRadius_filter_plugin_actions( $links, $file ){
	static $this_plugin;
	if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page=LoginRadius">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'LoginRadius_filter_plugin_actions', 10, 2 );?>