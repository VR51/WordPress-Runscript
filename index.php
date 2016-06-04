<?php
/*
Plugin Name: WordPress Runscript
Plugin URI: https://github.com/VR51/WordPress-Runscript
Description: WordPress plugin and theme package deployment. This plugin will run as soon as activated. Install WordPress Runscript as you do any other plugin. Use to install basic plugins, eCommerce plugins, admin tools, feature extras and themes. Deploy specific packages.
Author: Lee Hodson
Author URI: https://vr51.com
Version: 2.0.0
License: GPL
*/

/**
*
* Install and deploy plugin and theme compilation sets. Create custom plugin and theme lists. Include custom plugin zip files and theme zip files for deployment.
*
* The terminology:
*
*	When we say 'install' we mean download from some URL and install the file into wp-content/plugins or wp-content/themes
*	When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/wp-content/plugins or WordPress-Runscipt-master/wp-content/themes and deploy them into wp-content/plugins or wp-content/themes.
*
* Plugins and themes installed by this plugin can be hosted anywhere on the internet.
* Plugins and themes deployed by this plugin must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.
* 
* 1) Download the plugin and unpack it.
* 2) Add URL addresses for plugins to be download from a remote repository (such as from wordpress.org or github) into a plugin list at wordpress-runscript/lists/plugins/
* 3) Add URL addresses for themes to be download from a remote repository (such as from wordpress.org or github) into a theme list at wordpress-runscript/lists/themes/
* 4) Add plugin zip files for plugins to be deployed by WordPress Runscript into WordPress-Runscipt/plugins/
* 5) Add theme zip files for themes to be deployed by WordPress Runscript into WordPress-Runscipt/themes/
* 6) Now zip up the WordPress Runscript directory that contains the edited plugin list URLs and zip packages
* 7) Install WordPress Runscript into a WordPress site as you would any other WordPress plugin by going to Dashboard > Plugins > Upload
* 8) Activate WordPress Ruscript and click 'Settings' under the plugin name or go to Dashboard > Settings > WordPress Runscript.
* 9) Select packages to install. Click 'Confirm Selection'. Click 'Install Selection'.
*
* WP Runscript automatically detects plugin lists, theme lists, plugin zip files and theme zip files.
*
* The plugin is configured to run for no longer than 10 minutes at a time. Adjust the execution time limit in set_time_limit(600) around line 212 of this file if more time is required.
* Delete the plugin immediately you have finished using it. Do not leave WordPress Runscript active when not in use. Do not leave installed when not in use.
*
* Pro Tips:
*
* To always get the most recent stable version of a plugin hosted on wordpress.org, change the version number in the download URL to '.latest-stable.zip', for example
*
*	From: https://downloads.wordpress.org/plugin/wp-admin-ui-customize.1.5.9.zip
*	To:  https://downloads.wordpress.org/plugin/wp-admin-ui-customize.latest-stable.zip
**/

/* Ideas

Package Removal

	Detect directory name from download slug

Clean Install All Installed Plugins/Themes

	Read plugins directory for

		all directory names

	Ping

		https://downloads.wordpress.org/plugin/ + directory name + .latest-stable.zip

	If file exists

		Download zip

		Delete existing directory

		Unpack zip

	Echo file downloaded / file not found
	
File Mod

	Append/Overwrite

	php.ini (deliver to blog root and wp-admin)
	wp-config.php (deliver into)
	.htaccess (prepend or append)
	
	Add directory for Files
	Autodetect file name (wp-config/php.ini/phprc.ini/.htaccess/.htaccessrc)
	Add notice that some servers ignore pph.ini directives
	Warn that misconfigurations can crash sites/servers
	
Import Compilation Packs from Github Gists

	Import screen
		Download URL
		Select Type (Plugin List, Theme List, File Mod)
		
SECURITY

	Check lists contain URLs
	
*/

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
*	Create Action Links for Plugin List Page
*
**/

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wordpress_runscript_add_action_links' );

function wordpress_runscript_add_action_links( $links ) {

	// Add link to settings page
	$mylinks = array(
		'<a href="' . admin_url( 'options-general.php?page=wordpress_runscript' ) . '">Settings</a>',
	);
	return array_merge( $links, $mylinks );
	
}


/**
*
* Add Settings Page
*
**/

add_action( 'admin_menu', 'wp_runscript_add_admin_menu' );
add_action( 'admin_init', 'wp_runscript_settings_init' );


function wp_runscript_add_admin_menu() { 

	add_options_page( 'WordPress Runscript', 'WordPress Runscript', 'manage_options', 'wordpress_runscript', 'wp_runscript_options_page' );

}

function wp_runscript_settings_init() { 

	register_setting( 'pluginPage', 'wp_runscript_settings' );

	add_settings_section(
		'wp_runscript_pluginPage_section', 
		__( 'Select the plugin and theme packs to install. Click <strong>Confirm Selection</strong> then click <strong>Install Packages</strong>. The page will refresh when installation is complete.<hr>', 'wp_runscript' ),
		'wp_runscript_settings_plugin_section_callback', 
		'pluginPage'
	);
	
	wp_runscript_register_fields( 'plugins' );

	wp_runscript_register_fields( 'themes' );
	
	wp_runscript_register_fields( 'deployplugins' );
		
	wp_runscript_register_fields( 'deploythemes' );
	
}


/**
*
* Register the Database Table Options and Build the Admin Page Fields
*	$type = 'plugins' or 'themes' and determines the directory paths read to autobuild the fields
*
**/

function wp_runscript_register_fields( $type ) {

	switch ($type) {
		case 'plugins':
			$path = 'lists/plugins';
			break;
		case 'themes':
			$path = 'lists/themes';
			break;
	}

	if ( isset($path) ) {
	
		foreach (scandir(plugin_dir_path(__FILE__)."$path") as $file) {
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			if ($extension == 'txt') {

				$file = str_replace('.txt', '', "$file");
				$fieldTitle = str_replace(' ', '_', "$file");
				$fieldName = 'wp_runscript_checkbox_field_'.str_replace(' ', '_', "$file");
				
				// call_user_func('wp_runscript_checkbox_field_' . str_replace('.txt', '', "$file"))
				add_settings_field( 
					"$fieldName",
					__( "$file", 'wp_runscript' ), 
					'wp_runscript_checkbox_field_render_plugins',
					'pluginPage', 
					'wp_runscript_pluginPage_section',
					array (
						'0' => $fieldTitle
					)
				);

			}
		}
		
	}
	
	if ( empty($path) ) {

		switch ($type) {
			case 'deployplugins':
			
				add_settings_field( 
					"Local_Plugins",
					__( 'Local Plugins', 'wp_runscript' ), 
					'wp_runscript_checkbox_field_render_plugins',
					'pluginPage', 
					'wp_runscript_pluginPage_section',
					array (
						'0' => 'Local_Plugins'
					)
				);
				break;
			case 'deploythemes':
				add_settings_field( 
					"Local_Themes",
					__( 'Local Themes', 'wp_runscript' ), 
					'wp_runscript_checkbox_field_render_plugins',
					'pluginPage', 
					'wp_runscript_pluginPage_section',
					array (
						'0' => 'Local_Themes'
					)
				);
				break;
		}
	
	}
	
}

function wp_runscript_checkbox_field_render_plugins( $fieldTitle ) {

	$options = get_option( 'wp_runscript_settings' );
	?>
		<input type='checkbox' name='wp_runscript_settings[wp_runscript_checkbox_<?php echo $fieldTitle[0] ?>]' <?php if ( !empty($options["wp_runscript_checkbox_$fieldTitle[0]"]) ) { checked( $options["wp_runscript_checkbox_$fieldTitle[0]"], 1 ); } ?> value='1'>
	<?php

}

function wp_runscript_settings_plugin_section_callback() { 

	echo __( '<h1>Packages</h1>', 'wp_runscript' );

}


/**
*
*	Success Message (sort of...)
*
**/
/*
function wp_runscript_admin_notice() {
	
			echo '<div class="notice notice-success is-dismissible"><h1>Run Completed</h1>';
			echo '<p>Selected packages were installed. Confirm installation by visting the <a href="'. admin_url( 'plugins.php' ) .'">plugins admin page</a>.</p>';
			echo '</div>';

}
add_action( 'admin_notices', 'wp_runscript_admin_notice' );
*/


/**
*
*	Do the Forms for the Settings Page
*
**/

add_action('admin_post_run_install_packages','admin_post_run_install_packages_function');

function admin_post_run_install_packages_function(){
	include_once( plugin_dir_path( __FILE__ ).'wordpress-runscript.php' );
    wp_redirect( admin_url( 'options-general.php?page=wordpress_runscript' ) );
    die( __FUNCTION__ );
}

function wp_runscript_options_page() { 

	?>
	<form action='<?php echo admin_url( 'options.php' ); ?>' method='post'>

		<h2>WordPress Runscript</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button( __('Confirm Selection', 'wp_runscript') );
		/* DEBUG TEST
		echo "<br><br>TEST<br><br>";
		$options = get_option( 'wp_runscript_settings' );
		print_r($options);
		*/
		?>

	</form>

	<p>Click 'Confirm Selection' before clicking 'Install Packages'.</p>
	
	<form action='<?php echo admin_url( 'admin-post.php' ); ?>'>

		<input name='action' type="hidden" value='run_install_packages'>
		<?php submit_button( __('Install Packages', 'wp_runscript') ); ?>

	</form>
	
	<?php

}
