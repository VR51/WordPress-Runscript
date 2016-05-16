<?php
/*
Plugin Name: WordPress Runscript
Plugin URI: https://github.com/VR51/WordPress-Runscript
Description: WordPress plugin and theme package deployment. This plugin will run as soon as activated. Install WordPress Runscript as you do any other plugin. Use to install basic plugins, eCommerce plugins, admin tools, feature extras and themes. Deploy specific packages.
Author: Lee Hodson
Author URI: https://vr51.com
Version: 1.1.0
License: GPL
*/

/**
*
* Use this WordPress plugin to install and deploy preferred plugin and theme sets.
*
* Enable/Disable plugin packs by editing configs between START CONFIGURATION HERE and STOP CONFIGURATION HERE (Between lines 55 and 92)
*
* The terminology:
*
*	When we say 'install' we mean download from some URL and install the file into wp-content/plugins or wp-content/themes
*	When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/wp-content/plugins or WordPress-Runscipt-master/wp-content/themes and deploy them into wp-content/plugins or wp-content/themes.
*
* Plugins and themes installed by this plugin can be hosted anywhere on the internet.
* Plugins and themes deployed by this plugin must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.
* 
*  Add URL addresses for plugins to be download from a remote repository (such as from wordpress.org or github) into the most appropriate plugin list at wp-content/plugins/wordpress-runscript/lists
*  Add URL addresses for themes to be download from a remote repository (such as from wordpress.org or github) into the theme list at wp-content/plugins/wordpress-runscript/lists/themes.txt
*  URLs must be placed one per line.
*
*  Add local packages for plugins to be deployed by WordPress Runscript into WordPress-Runscipt/plugins/. Packages must be in zip format.
*  Add local packages for themes to be deployed by WordPress Runscript into WordPress-Runscipt/themes/. Packages must be in zip format.
*
* The plugin will run as soon as activated. The plugin is configured to run for no longer than 10 minutes. Adjust the execution time limit in set_time_limit(600) around line 212 of this file if more time is required.
* The plugin will deactivate itself after it has run. You must delete the plugin immediately it has completed its task. Do not leave active under any circumstances.
*
* To always get the most recent stable version of a plugin hosted on wordpress.org, change the version number in the download URL to '.latest-stable.zip', for example
*
*	From: https://downloads.wordpress.org/plugin/wp-admin-ui-customize.1.5.9.zip
*	To:  https://downloads.wordpress.org/plugin/wp-admin-ui-customize.latest-stable.zip
**/

/**
*
* Security First!
*
**/

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}


/**
*
* START CONFIGURATION HERE
* Empty arrays are ignored
*
**/


/**
*
*	Remote Plugin and Theme Files
*		to fetch from a URL
*
**/

$basePlugins = '1'; // Install the base plugin set. 1 = Enabled, 0 = Disabled.
$wooPlugins = '1'; // Install the WooCommerce plugin set. 1 = Enabled, 0 = Disabled.
$adminTools = '1'; // Install the developer tools plugin set. 1 = Enabled, 0 = Disabled.
$extraPlugins = '1'; // Install the extras plugin set. 1 = Enabled, 0 = Disabled.
$themes = '1'; // Install the base themes. 1 = Enabled, 0 = Disabled.


/**
*
*	Local Plugin and Theme Files
*		to deploy from WordPress-Runscript/plugins and WordPress-Runscript/themes
*
**/

$deployPlugins = '1'; // Deploy the plugins stored in WordPress-Runscipt-master/plugins/. 1 = Enabled, 0 = Disabled.
$deployThemes = '1'; // Deploy the themes stored in WordPress-Runscipt-master/themes/. 1 = Enabled, 0 = Disabled.


/**
*
* STOP CONFIGURATION HERE
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

$wppluginpath = get_home_path().'wp-content/plugins/';
$wpthemepath = get_home_path().'wp-content/themes/';

$payloadpluginpath = plugin_dir_path(__FILE__).'plugins';
$payloadthemepath = plugin_dir_path(__FILE__).'themes';


/**
*
*	Download and install plugins and themes from remote sources
*
*	$file = Name of file to be read
*	$destdir = The dirctory where downloaded files will be unzipped
*
**/

function vr_grab_remote_files($file, $destdir) {

	if ( $file_handle = fopen(plugin_dir_path(__FILE__).'lists'."/$file", "r") ) {

		while (!feof($file_handle)) {
			$line = trim(fgets($file_handle));

			copy("$line", "$destdir/file.zip");
			$zip = new ZipArchive;
			$res = $zip->open("$destdir/file.zip");
			if ($res === TRUE) {
				$zip->extractTo($destdir);
				$zip->close();
				unlink("$destdir/file.zip");
			}

		}
		fclose($file_handle);

	}

}

if ( $basePlugins=='1' ) {
	vr_grab_remote_files ('basePlugins.txt',"$wppluginpath");
}

if ( $wooPlugins=='1' ) {
	vr_grab_remote_files ('wooPlugins.txt',"$wppluginpath");
}

if ( $adminTools=='1' ) {
	vr_grab_remote_files ('adminTools.txt',"$wppluginpath");
}

if ( $extraPlugins=='1' ) {
	vr_grab_remote_files ('extraPlugins.txt',"$wppluginpath");
}

if ( $themes=='1' ) {
	vr_grab_remote_files ('themes.txt',"$wpthemepath");
}


/**
*
*	Move plugins and themes from WordPress Runscript's plugins and themes directories
*
**/

function vr_deploy_payload( $sourcedir, $destdir) {
	// Establish the directory to scan
	$dircontents = scandir("$sourcedir");
	// Read files in the directory
	foreach ($dircontents as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'zip') {
			// Unzip to correct WP directory
			copy("$sourcedir/$file", "$destdir/$file");
			$zip = new ZipArchive;
			$res = $zip->open("$destdir/$file");
			if ($res === TRUE) {
				$zip->extractTo($destdir);
				$zip->close();
				unlink("$destdir/$file");
			}

		}
	}

}

if ( $deployPlugins=='1' ) {
	vr_deploy_payload ("$payloadpluginpath","$wppluginpath");
}

if ( $deployThemes=='1' ) {
	vr_deploy_payload ("$payloadthemepath","$wpthemepath");
}


/**
*
*	Self deactivate the Runscript
*
**/

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

add_action( 'admin_notices', 'vr_run_script_admin_notice' );
add_action( 'admin_init', 'vr_run_script_deactivate' );