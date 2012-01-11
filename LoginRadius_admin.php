<?php 
/**
 * Add the LoginRadius menu to the Settings menu
 * @param boolean $force if set to true, force updates the settings.
 */
function LoginRadius_restore_config($force=false) {
if ( $force or !( get_option('LoginRadius_apikey')) ) {
		update_option('LoginRadius_apikey',false);
	}
	
if ( $force or !( get_option('LoginRadius_secret')) ) {
		update_option('LoginRadius_secret',false);
	}
if ( $force or !( get_option('dummyemail')) ) {
		update_option('dummyemail',false);
	}	
if ( $force or !( get_option('LoginRadius_redirect')) ) {
		update_option('LoginRadius_redirect',false);
	}	
if ( $force or !( get_option('title')) ) {
		update_option('title',false);
	}
	}
	/**
 * Displays the LoginRadius admin menu, first section (re)stores the settings.
 */
function LoginRadius_submenu() {
	global $LoginRadius_known_sites, $LoginRadius_date, $LoginRadiuspluginpath;
    if (isset($_REQUEST['restore']) && $_REQUEST['restore']) {
		check_admin_referer('LoginRadius-config');
		LoginRadius_restore_config(true);
		LoginRadius_message(__("Ripristina tutte le impostazioni ai valori predefiniti.", 'LoginRadius'));
	} 
	else if (isset($_REQUEST['save']) && $_REQUEST['save']) {
	
	if (isset($_POST['LoginRadius_apikey']) && $_POST['LoginRadius_apikey']!="") {
			update_option('LoginRadius_apikey',$_POST['LoginRadius_apikey']);
		} else {
			LoginRadius_message(__("Occorre l'API Key per il processo di Login.", 'LoginRadius'));
		}
		if (isset($_POST['LoginRadius_secret']) && $_POST['LoginRadius_secret']!="") {
			update_option('LoginRadius_secret',$_POST['LoginRadius_secret']);
		} else {
			LoginRadius_message(__("Occorre l'API Key Secret per il processo di Login.", 'LoginRadius'));
		}
		if (isset($_POST['title']) && $_POST['title']!="") {
			update_option('title',$_POST['title']);
		} else {
			update_option('title',$_POST['title']=='Effettua il Login con');
		}
		if (isset($_POST['dummyemail'])==true && $_POST['dummyemail']!="") {
			update_option('dummyemail',$_POST['dummyemail']==true);
		} else {
			update_option('dummyemail',$_POST['dummyemail']==false);
		}
		$LoginRadius_redirect = $_POST['LoginRadius_redirect'];
		if ($LoginRadius_redirect=='samepage' && $LoginRadius_redirect!="") {
		$samepage = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		} 
		if ($LoginRadius_redirect=='homepage' && $LoginRadius_redirect!="") {
		$homepage = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		} 
		else if($LoginRadius_redirect=='dashboard'){
		$dashboard = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		}
		else if($LoginRadius_redirect=='custom'){
		$custom = 'checked';
			update_option('LoginRadius_redirect',$LoginRadius_redirect);
		}
		else{
		update_option('LoginRadius_redirect',$LoginRadius_redirect=='homepage');
		}
		if($LoginRadius_redirect=='custom' && $custom == 'checked' && isset($_POST['LoginRadius_redirect_custom_redirect'])!="")
		{
		update_option('LoginRadius_redirect_custom_redirect',$_POST['LoginRadius_redirect_custom_redirect']);
		}
		if($LoginRadius_redirect=='custom' && $custom == 'checked' && $_POST['LoginRadius_redirect_custom_redirect']=="")
		{
			LoginRadius_message(__("Avete bisogno di un URL di reindirizzamento per il reindirizzamento Login.", 'LoginRadius'));
		}
		
		check_admin_referer('LoginRadius-config');
		LoginRadius_message(__("modifiche salvate.", 'LoginRadius'));
	}
	/**
	 * Display options.
	 */?>
<form action="<?php echo attribute_escape( $_SERVER['REQUEST_URI'] ); ?>" method="post">
<?php if ( function_exists('wp_nonce_field') )
		wp_nonce_field('LoginRadius-config');?>

<div class="wrap">
	<?php //screen_icon();?>
	<h2><?php _e("Configurazione <b style='color:#00ccff;'>Login</b><b>Radius</b>", 'LoginRadius'); ?></h2>
	<div class="LoginRadius_container_outer">
		<div class="LoginRadius_container">
			<h3>Grazie per l installazione di plugin LoginRadius!</h3><p>
E possibile selezionare impostazioni desiderate per il vostro plugin in questa pagina. Se potete scegliere Provider ID e puo ottenere <strong> LoginRadius API Key & Secret </strong>accedendo al <a href="http://www.LoginRadius.com" target="_blank">www.LoginRadius.com.</a> </p>
<p><strong>LoginRadius</strong> e una societa di tecnologia l America del Nord based che offre accesso sociale attraverso ospiti popolari come Facebook, Twitter, Google e oltre 15 di piu! Per supporto tecnico o domande, non esitate a contattarci all'indirizzo <strong>hello@loginradius.com.</strong></p><h3>Ci sono fino 24x7 per assistere i nostri clienti!</h3>
<p>
<a class="button-secondary" href="http://www.loginradius.com/" target="_blank"><strong>Crea il tuo account adesso!</strong></a>
</p>
		</div>
		<div class="LoginRadius_container_inner">
		<h3 style="color:black;">Plugin Aiuto</h3>
		<p><ul class="LoginRadius_container_links">
		<li><a href="http://www.loginradius.com/loginradius/plugins.aspx" target="_blank">documentazione</a></li>
		<li><a href="http://wordpress.org/extend/plugins/loginradius-for-wordpress/" target="_blank">Plugin pagina web</a></li>
		<li><a href="http://wordpressdemo.loginradius.com/" target="_blank">Demo sito live</a></li>
		<li><a href="http://www.loginradius.com/loginradius/" target="_blank">Chi LoginRadius</a></li>
		<li><a href="http://blog.loginradius.com/" target="_blank">LoginRadius Blog</a></li>
		<li><a href="http://www.loginradius.com/loginradius/plugins.aspx" target="_blank">Plugins LoginRadius altri</a></li>
		
		<li><a href="http://www.loginradius.com/loginradius/writetous.aspx" target="_blank">Supporto tecnico</a></li>
		<br />
		</ul>
        </p>
		</div>
	</div>
	<table class="form-table LoginRadius_table">
	<tr>
	<th class="head" colspan="2">LoginRadius API Impostazioni</small></th>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small>API Key</small></th>
	<td><?php _e("Incolla l'API Key qui sotto. Per ottenere l'API Key loggati su  
<a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a>", 'LoginRadius'); ?><br/>
<input size="60" type="text" name="LoginRadius_apikey" id="LoginRadius_apikey" value="<?php echo get_option('LoginRadius_apikey' ); ?>" /></td>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small>API Segreto</small></th>
	<td><?php _e("Incolla l'API Secret qui sotto. Per ottenere l'API Key loggati su <a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a>", 'LoginRadius'); ?><br/>
		<input size="60" type="text" name="LoginRadius_secret" id="LoginRadius_secret" value="<?php echo get_option('LoginRadius_secret' ); ?>" /></td>
	</tr>
	</table>
	<table class="form-table LoginRadius_table">
	<tr>
	<th class="head" colspan="2">LoginRadius Impostazioni di base</small></th>
	</tr>
	<tr>
	<th scope="row">titolo</th>
	<td><?php _e("Questo testo displyed sopra il pulsante di accesso sociale.", 'LoginRadius'); ?>
	<br />
<input type="text"  name="title" size="60" value="<?php if(htmlspecialchars(get_option('title'))){echo htmlspecialchars(get_option('title'));}else{echo 'Effettua il Login con';} ?>" />
</td>
	</tr>
	<tr>
	<th scope="row">Email richiesta:</th>
	<td><?php _e("alcuni provider non forniscono l'ID email. Seleziona SI se desideri un popup email dopo il login o seleziona NO se vuoi generare automaticamente l'indirizzo email.", 'LoginRadius'); ?>
	</td></tr>
	<tr class="row_white">
	<th></th>
	<td> 
Si &nbsp;&nbsp;&nbsp;<input name="dummyemail" type="radio"  value="0" <?php checked( '0', get_option( 'dummyemail' ) ); ?> checked /><br />
No &nbsp;<input name="dummyemail" type="radio" value="1" <?php checked( '1', get_option( 'dummyemail' ) ); ?>  />
</td>
	</tr>
	<tr >
	<th scope="row">Accedi impostazioni di reindirizzamento</th>
	<td>
<input type="radio" name="LoginRadius_redirect" value="samepage" <?php checked( 'samepage', get_option( 'LoginRadius_redirect' )); ?> checked /> <?php _e ('Reindirizzamento alla stessa pagina del blog'); ?> <strong>(<?php _e ('difetto') ?>)</strong><br />

<input type="radio" name="LoginRadius_redirect" value="homepage" <?php checked( 'homepage', get_option( 'LoginRadius_redirect' )); ?>  /> <?php _e ('Reindirizzamento alla home page del blog'); ?>
<br />
<input type="radio" name="LoginRadius_redirect" value="dashboard" <?php checked( 'dashboard', get_option( 'LoginRadius_redirect' )); ?> /> <?php _e ('Reindirizzare cruscotto conto'); ?>
<br />
<input type="radio" name="LoginRadius_redirect" value="custom" <?php checked( 'custom', get_option( 'LoginRadius_redirect' )); ?> /> <?php _e ('Reindirizzamento alla seguente url:'); ?>
<br />
<input type="text"  name="LoginRadius_redirect_custom_redirect" size="60" value="<?php if($LoginRadius_redirect=='custom' && $custom == 'checked'){echo htmlspecialchars(get_option('LoginRadius_redirect_custom_redirect'));}else{} ?>" />
</td>
</tr>
</table>
<table>
<tr>
<td>&nbsp;</td>
<td>
<span class="submit"><input name="save" value="<?php _e("Salva Modifiche", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
<span class="submit"><input name="restore" value="<?php _e("Ripristina", 'LoginRadius'); ?>" type="submit" class="button-primary"/></span>
</td>
</tr>
</table>
</div>
</form>
<?php }?>