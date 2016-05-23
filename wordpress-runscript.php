<?php
/**
*
* Security First!
*
**/

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there! I'm just a plugin, not much I can do when called directly.";
	exit;
}


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
*	This Downloads and installs plugins and themes from remote sources
*
*	$file = Name of file to be read
*	$destdir = The dirctory where downloaded files will be unzipped
*
**/

function vr_grab_remote_files($file, $type, $destdir) {

	switch ($type) {
		case 'plugins':
			$path = 'lists/plugins';
			break;
		case 'themes':
			$path = 'lists/themes';
			break;
	}

	if ( $file_handle = fopen(plugin_dir_path(__FILE__).$path."/$file", "r") ) {

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


/**
*
*	This Checks for Locally Stored Files & Deploys Them If Asked
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


/**
*
*	Test available compilation packs against options set in the database. Install when they match.
*		The database options table for wp_runscript_settings stores the file name of selected packs
*
**/

$options = get_option( 'wp_runscript_settings' );

/* Remote Plugins */

foreach (scandir(plugin_dir_path(__FILE__).'lists/plugins') as $file) {
	$extension = pathinfo($file, PATHINFO_EXTENSION);
	if ($extension == 'txt') {

		$pack = $file;
		$file = str_replace('.txt', '', "$file");
		$fieldTitle = str_replace(' ', '_', "$file");

		if ( isset( $options["wp_runscript_checkbox_$fieldTitle"] ) ) {
			vr_grab_remote_files("$pack", 'plugins', "$wppluginpath");
		}

	}
}
		
/* Remote Themes */

foreach (scandir(plugin_dir_path(__FILE__).'lists/themes') as $file) {
	$extension = pathinfo($file, PATHINFO_EXTENSION);
	if ($extension == 'txt') {

		$pack = $file;
		$file = str_replace('.txt', '', "$file");
		$fieldTitle = str_replace(' ', '_', "$file");

		if ( isset( $options["wp_runscript_checkbox_$fieldTitle"] ) ) {
			vr_grab_remote_files("$pack", 'themes', "$wpthemepath");
		}

	}
}


/**
*
*	Move plugins and themes from WordPress Runscript's plugins and themes directories
*
**/

if ( isset( $options["wp_runscript_checkbox_Local_Plugins"] ) ) {
	vr_deploy_payload("$payloadpluginpath","$wppluginpath");
}

if ( isset( $options["wp_runscript_checkbox_Local_Themes"] ) ) {
	vr_deploy_payload("$payloadthemepath","$wpthemepath");
}

/*
wp_nonce_field( plugin_basename( __FILE__ ), 'wordpress_runscript_nonce',true,false);
wp_create_nonce( 'wordpress_runscript_nonce' );
*/

/**
*
*	Self deactivate the Runscript
*
**/

/*
function vr_run_script_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

add_action( 'admin_init', 'vr_run_script_deactivate' );
*/
