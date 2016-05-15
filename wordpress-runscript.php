<?php
/*
Plugin Name: WordPress Runscript
Plugin URI: https://github.com/VR51/WordPress-Runscript
Description: WordPress plugin and theme package deployment script. This will run as soon as activated. Install basic plugins, eCommerce plugins, admin tools, feature extras and themes. Deploy specific packages.
Author: Lee Hodson
Author URI: https://vr51.com
Version: 1.0.1
License: GPL
*/

/**
*
* Use this WordPress plugin to install and deploy preferred plugin and theme sets.
*
* The terminology:
*
*	When we say 'install' we mean download from some URL and install the file into wp-content/plugins or wp-content/themes
*	When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/wp-content/plugins or WordPress-Runscipt-master/wp-content/themes and deploy them into wp-content/plugins or wp-content/themes.
*
* Plugins and themes installed by this plugin can be hosted anywhere on the internet.
* Plugins and themes deployed by this plugin must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.
*
* 
* Add URL addresses for plugins to the 'Remote' plugin URLs array. One URL per line. The files to grab can be anywhere on the Internet e.g Github.com or wordpress.org
* Add URL addresses for themes to the 'Remote' theme URLs array. One URL per line. The files to grab can be anywhere on the Internet e.g Github.com or wordpress.org
*
* Add package names for plugins stored in WordPress-Runscipt-master/plugins/ to the 'Local' plugin package array. One zip file per line.
* Add package names for themes stored in WordPress-Runscipt-master/themes/ to the 'Local' theme package array. One zip file per line.
*
*
* The plugin will run as soon as activated. The plugin is configured to run for no longer than 10 minutes. Adjust the execution time limit in set_time_limit(600) around line 212 of this file if more time is required.
* The plugin will deactivate itself after it has run. You must delete the plugin immediately it has completed its task. Do not leave active under any circumstances.
*
**/


/**
*
* Security First!
*
**/

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}


/**
*
* START EDITING PACKAGES HERE
* Empty arrays are ignored
*
**/

/* Enable/Disable Packages. 1 = Enable, 0 = Disable */


$base = '1'; // Install the base plugin set
$woo = '1'; // Install the WooCommerce plugin set
$tools = '1'; // Install the developer tools plugin set
$extras = '1'; // Install the extras plugin set
$pdeploy = '0'; // Deploy the plugin set stored in WordPress-Runscipt-master/plugins/. Add files and edit the $pdeploy array before enabling this.
$themes = '1'; // Install the base themes
$tdeploy = '0'; // Deploy the themes stored in WordPress-Runscipt-master/themes/. Add files and edit the $pdeploy array before enabling this.


/* Customize plugin and theme packages */


/**
*
*	Remote Plugin URLs
*
**/

if ( $base=='1' ) {

	$plugins = array(
		'https://downloads.wordpress.org/plugin/jetpack.4.0.2.zip',
		'https://downloads.wordpress.org/plugin/wordfence.6.1.7.zip',
		'https://downloads.wordpress.org/plugin/digiproveblog.2.23.zip',
		'https://downloads.wordpress.org/plugin/heartbeat-control.zip',
		'https://downloads.wordpress.org/plugin/wp-optimize.1.8.9.10.zip',
		'https://downloads.wordpress.org/plugin/wp-notification-center.1.0.1.zip',
		'https://downloads.wordpress.org/plugin/wordpress-seo.3.2.5.zip',
		'https://downloads.wordpress.org/plugin/google-analytics-dashboard-for-wp.4.9.3.1.zip',
		'https://downloads.wordpress.org/plugin/tinymce-advanced.4.3.10.1.zip',
		'https://downloads.wordpress.org/plugin/lazy-load.0.6.zip',
		'https://downloads.wordpress.org/plugin/updraftplus.1.12.6.zip',
		'https://downloads.wordpress.org/plugin/mainwp-child.3.1.4.zip' // Last item needs no final comma
	);

} else { $plugins = array(); }


if ( $woo=='1' ) {

	$pluginswoo = array(
		'https://downloads.wordpress.org/plugin/woocommerce.2.5.5.zip',
		'https://downloads.wordpress.org/plugin/woocommerce-jetpack.2.4.8.zip',
		'https://downloads.wordpress.org/plugin/wc-fields-factory.1.3.4.zip',
		'https://downloads.wordpress.org/plugin/genesis-connect-woocommerce.0.9.8.zip' // Last item needs no final comma
	);

} else { $pluginswoo = array(); }


if ( $tools=='1' ) {

	$pluginstools = array(
		'https://downloads.wordpress.org/plugin/wp-sweep.zip',
		'https://downloads.wordpress.org/plugin/wp-admin-ui-customize.1.5.9.zip',
		'https://downloads.wordpress.org/plugin/adminimize.1.10.3.zip',
		'https://downloads.wordpress.org/plugin/advanced-custom-fields.4.4.7.zip',
		'https://downloads.wordpress.org/plugin/easy.zip',
		'https://downloads.wordpress.org/plugin/child-theme-configurator.2.0.2.zip',
		'https://downloads.wordpress.org/plugin/regenerate-thumbnails.zip',
		'https://downloads.wordpress.org/plugin/baw-force-plugin-updates.zip',
		'https://downloads.wordpress.org/plugin/look-see-security-scanner.zip' // Last item needs no final comma
	);

} else { $pluginstools = array(); }


if ( $extras=='1' ) {

	$pluginsextras = array(
		'https://downloads.wordpress.org/plugin/live-composer-page-builder.1.0.8.zip',
		'https://downloads.wordpress.org/plugin/image-watermark.1.5.6.zip',
		'https://downloads.wordpress.org/plugin/ajax-search-lite.4.6.2.zip',
		'https://downloads.wordpress.org/plugin/wpdiscuz.3.2.8.zip',
		'https://downloads.wordpress.org/plugin/paid-memberships-pro.1.8.9.1.zip',
		'https://github.com/Obox/layers-updater/archive/master.zip' // Last item needs no final comma
	);

} else { $pluginsextras = array(); }



$plugins = array_merge( $plugins, $pluginswoo, $pluginstools, $pluginsextras );


/**
*
* Local plugin packages
* These, if any, must be stored in WordPress-Runscipt-master/plugins/
*
**/

if ( $pdeploy=='1' ) {

	$payloadplugins = array(
		'example-plugin-name-one.zip',
		'example-plugin-name-two.zip' // Last item needs no final comma
	);

} else { $payloadplugins = array(); }


/**
*
*	Remote Theme URLs
*
**/

if ( $themes=='1' ) {

	$themes = array(
		'https://github.com/presscustomizr/customizr/archive/master.zip',
		'https://github.com/Obox/layerswp/archive/master.zip' // Last item needs no final comma
	);

} else { $themes = array(); }


/**
*
* Local theme packages
* These, if any, must be stored in WordPress-Runscipt-master/themes/
*
**/

if ( $tdeploy=='1' ) {

	$payloadthemes = array(
		'example-theme-name-one.zip',
		'example-theme-name-two.zip' // Last item needs no final comma
	);

} else { $payloadthemes = array(); }


/**
*
* STOP EDITING
*
**/




/**
*
* The Work Begins Here....
*
**/




/* Set 10 minute execution time */

set_time_limit(600);

/**
*
*	Server paths
*
**/

require_once(ABSPATH . 'wp-admin/includes/file.php');

$contentpath = get_home_path().'wp-content/';
 
$pluginpath = get_home_path().'wp-content/plugins/';
$themepath = get_home_path().'wp-content/themes/';

$payloadpluginpath = plugin_dir_path(__FILE__).'plugins';
$payloadthemepath = plugin_dir_path(__FILE__).'themes';



/**
*
*	Download and install plugins and themes
*
**/

foreach ($plugins as $plugin) {
	copy("$plugin", "$pluginpath/file.zip");
	$zip = new ZipArchive;
	$res = $zip->open("$pluginpath/file.zip");
	if ($res === TRUE) {
		$zip->extractTo($pluginpath);
		$zip->close();
	}
}

foreach ($themes as $theme) {
	copy("$theme", "$themepath/file.zip");
	$zip = new ZipArchive;
	$res = $zip->open("$themepath/file.zip");
	if ($res === TRUE) {
		$zip->extractTo($themepath);
		$zip->close();
	}
}


/**
*
*	Move plugins and themes from WordPress Runscript's plugins and themes directories
*
**/

foreach ($payloadplugins as $plugin) {
	copy("$payloadpluginpath/$plugin", "$pluginpath/file.zip");
	$zip = new ZipArchive;
	$res = $zip->open("$pluginpath/file.zip");
	if ($res === TRUE) {
		$zip->extractTo($pluginpath);
		$zip->close();
	}
}

foreach ($payloadthemes as $theme) {
	copy("$payloadthemepath/$theme", "$themepath/file.zip");
	$zip = new ZipArchive;
	$res = $zip->open("$themepath/file.zip");
	if ($res === TRUE) {
		$zip->extractTo($themepath);
		$zip->close();
	}
}


/**
*
*	Remove residual download zips
*
**/

unlink("$themepath/file.zip");
unlink("$pluginpath/file.zip");

/**
*
*	Self deactivate the Runscript
*
**/

add_action( 'admin_init', 'vr_run_script_deactivate' );
add_action( 'admin_notices', 'vr_run_script_admin_notice' );

function vr_run_script_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

function vr_run_script_admin_notice() {
	if ( current_user_can( 'install_plugins' ) ) {
		echo '<div class="notice notice-success is-dismissible"><h1>Run Completed</h1><p><strong>WordPress Runscript</strong> has been deactivated. Deployed plugins and themes are installed ready for activation. You must now delete the plugin <strong>WordPress Runscript</strong>.</p></div>';
		if ( isset( $_GET['activate'] ) )
			unset( $_GET['activate'] );
	}
}