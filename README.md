# WordPress Runscript
Simple WordPress plugin and theme deployment. This is a WordPress plugin that installs a configurable default set of plugins and themes into a WordPress site.

Use this WordPress plugin to install and deploy preferred plugin and theme sets.

# Use Case
* Quickly install plugin and theme packages
* Quickly reset plugin and theme packages

# Instructions
WordPress Runscript downloads and installs plugins and themes from any publicly accessible plugin or theme repository such as wordpress.org or github.com. It also deploys plugins and themes that are stored locally within the WordPress Runscript plugin directory. The plugins and themes are not automatically activated - we download or deploy files only.

The default plugin sets included with WordPress Runscript install a base plugin pack plus plugins for eCommerce, backend admin and fronend features. The default theme set installs 2 themes.

The terminology:

* When we say 'install' we mean download from some URL and install the downloaded file into wp-content/plugins or wp-content/themes
* When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/wp-content/plugins or WordPress-Runscipt-master/wp-content/themes and deploy them into site/wp-content/plugins or site/wp-content/themes.

Plugins and themes 'installed' by WordPress Runscript can be hosted anywhere on the internet. The URL of the zip file must be publicly accessible.
Plugins and themes 'deployed' by WordPress Runscript must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.

To download plugins and themes from a repository:

* Open the directory wp-content/plugins/wordpress-runscript-master/lists
* Add URL addresses for plugins to be download from a remote repository (such as from wordpress.org or github) into the appropriate plugin list at wp-content/plugins/wordpress-runscript-master/lists
* Add URL addresses for themes to be download from a remote repository (such as from wordpress.org or github) into the theme list at wp-content/plugins/wordpress-runscript-master/lists/themes.txt
* URLs must be placed one per line.
* The plugin and theme files can be grabbed from be anywhere on the Internet e.g Github.com or wordpress.org

To deploy plugins and themes with the WordPress Runscript package:

* Add local plugin packages to be deployed by WordPress Runscript into WordPress-Runscipt-master/plugins/. Packages must be in zip format.
* Add local theme packages to be deployed by WordPress Runscript into WordPress-Runscipt-master/themes/. Packages must be in zip format.

To see plugin lists provided with WordPress Runscript: https://github.com/VR51/WordPress-Runscript/tree/master/lists

# Configuration
No configuration is needed unless you want to disable use of specific plugin lists. Edit the file wordpress-runscript.php and look around line 54 where it reads "START CONFIGURATION HERE" to enable or disable specific lists.

# Take Note
WordPress Runscript will run as soon as activated. The plugin is configured to run for no longer than 10 minutes. Adjust the execution time limit in set_time_limit(600) around line 212 of this file if more time is required.

WordPress Runscript will deactivate itself after it has run. You must delete the plugin immediately it has completed its task. Do not leave active or installed under any circumstances.

# Tip
To always get the most recent stable version of a plugin hosted on wordpress.org, change the version number in the download URL to '.latest-stable.zip', for example

* From: https://downloads.wordpress.org/plugin/wp-admin-ui-customize.1.5.9.zip
* To:  https://downloads.wordpress.org/plugin/wp-admin-ui-customize.latest-stable.zip

# What WordPress Runscript Does Not Do
* Plugins and themes are not activated after installation
* Any existing plugin or theme files in wp-content/plugins or wp-content/themes are not removed.
* Where plugin or themes have matching directory names (likely because they are the same plugin or theme as being installed), the new version is written into the existing directory and sname named files are overwritten.
* WordPress Runscipt will happily refresh an existing plugin install but because it does not remove existing files (only overwrites same named files) WP Runscript is not suited to malware removal application unless existing files are removed manually.

# Donations Welcome
Send donations to https://paypal.me/vr51

# Changelog

## 1.1.0
- Major change to the way repository plugin sets are created. Change made to simplify plugin configuration.
- Plugin sets are now added to category themed lists under wp-content/plugins/wordpress-runscript-master/lists
- Plugin packages stored in wp-content/plugins/wordpress-runscript-master/plugins are now read automatically. This means there is no need to edit any arrays.
- Theme packages stored in wp-content/plugins/wordpress-runscript-master/themes are now read automatically. This means there is no need to edit any arrays.

## 1.0.1
- Minor bug fix.

## 1.0.0
- First public release