# WordPress Runscript
Simple WordPress plugin and theme deployment. WordPress Runscript installs plugins and themes from configurable compilation lists.

Use this WordPress plugin to install and deploy preferred plugin and theme sets.

# Use Case
* Quickly install plugin and theme packages
* Quickly reset plugin and theme packages

# Background
WordPress Runscript downloads and installs plugins and themes from any publicly accessible plugin or theme repository such as wordpress.org or github.com. It also deploys plugins and themes that are stored locally within the WordPress Runscript plugin directory. The plugins and themes are not automatically activated - we download or deploy files only.

Collections that ship with WP Runscript:

- Admin Tools
- Base Plugins
- Blog Organization
- Copy Protection
- Developer Tools
- Experimental
- Feature Plugins
- Genesis Theme Plugins
- Interesting Oddities
- Media Plugins
- Optimization
- Security and Backup
- Useful Widgets
- WooCommerce Plugins
- Themes

All lists are configurable. You can select which lists to use. You can add your own lists and WP Runscript will automatically detect them and add them to the settings panel.

Additionally, there are two locations for .zip files to be stored ready for deployment. The otions panel shows them as:

- Local Plugins
- Local Themes

The terminology:

* When we say 'install' we mean download from some URL and install the downloaded file into wp-content/plugins or wp-content/themes
* When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/plugins or WordPress-Runscipt-master/themes and deploy them into site/wp-content/plugins or site/wp-content/themes.

Plugins and themes 'installed' by WordPress Runscript can be hosted anywhere on the internet. The URL of the zip file must be publicly accessible.
Plugins and themes 'deployed' by WordPress Runscript must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.

# Instructions
To use WP Runscript as is:

- Download WP Runscript.
- Install WordPress Runscript into a WordPress site as you would any other WordPress plugin by going to Dashboard > Plugins > Upload
- Activate WordPress Ruscript and click 'Settings' under the plugin name or go to Dashboard > Settings > WordPress Runscript.
- Select packages to install. Click 'Confirm Selection'. Click 'Install Selection'.

To configure the lists of plugins and themes to be downloaded and installed:

- Download WP Runscript
- Unpack the package
- Open the directory wp-content/plugins/wordpress-runscript-master/lists
- Add URL addresses for plugins to be download from a remote repository (such as from wordpress.org or github) into a plugin list at wordpress-runscript/lists/plugins/
- Add URL addresses for themes to be download from a remote repository (such as from wordpress.org or github) into a theme list at wordpress-runscript/lists/themes/
- Now zip up the WordPress Runscript directory that contains the edited plugin list URLs and zip packages
- Install WordPress Runscript into a WordPress site as you would any other WordPress plugin by going to Dashboard > Plugins > Upload
- Activate WordPress Ruscript and click 'Settings' under the plugin name or go to Dashboard > Settings > WordPress Runscript.
- Select packages to install. Click 'Confirm Selection'. Click 'Install Selection'.

To deploy plugins and themes with the WordPress Runscript package:

* Add local plugin packages to be deployed by WordPress Runscript into WordPress-Runscipt-master/plugins/. Packages must be in zip format.
* Add local theme packages to be deployed by WordPress Runscript into WordPress-Runscipt-master/themes/. Packages must be in zip format.

To see plugin lists provided with WordPress Runscript: https://github.com/VR51/WordPress-Runscript/tree/master/lists

# Settings Configuration
The plugin settings are located at WordPress Dashboard > Settings > WordPress Runscript.

# Take Note
You must delete the plugin immediately it has completed its task. Do not leave active or installed under any circumstances.

# Tip
To always get the most recent stable version of a plugin hosted on wordpress.org, change the version number in the download URL to '.latest-stable.zip', for example

* From: https://downloads.wordpress.org/plugin/wp-admin-ui-customize.1.5.9.zip
* To:  https://downloads.wordpress.org/plugin/wp-admin-ui-customize.latest-stable.zip

# What WordPress Runscript Does Not Do
* Plugins and themes are not activated after installation
* Any existing plugin or theme files in wp-content/plugins or wp-content/themes are not removed.
* Where plugin or themes have matching directory names (likely because they are the same plugin or theme as being installed), the new version is written into the existing directory and same named files are overwritten.
* WordPress Runscipt will happily refresh an existing plugin install but because it does not remove existing files (only overwrites same named files) WP Runscript is not suited to malware removal application unless existing files are removed manually.

# Donations Welcome
If you found this plugin useful, please consider sending a donation to pay toward development time of WordPress Runscript. Most donations are between $5 and $50. We appreciate them all :)

Send donations to https://paypal.me/vr51

# Changelog
## 2.0.1
- Added check to confirm URLs in plugin and theme lists are properly formatted URLs and to confirm they contain the text '.zip'. This is a security precaution.
- Added remote URL protocol check. URLs listed in plugin and theme checks must use either HTTP, HTTPS, FTP or an FTPS protocol.
- A few more items added to the plugin lists.

## 2.0.0
- Complete overhaul of code, configuration and processing.
- Removed need to edit variables in the main plugin file.
- Moved options to an admin settings page. Install the plugin, go to Settings > WordPress Runscript, select the plugin and theme compilation packages to install, click 'Confirm Selection' then click 'Install Selection'.
- Add as many plugin and theme lists to wordpress-runsript/lists/ as you want. The settings panel will build itself to match the number of available lists.
- More to come...

## 1.1.0
- Major change to the way repository plugin sets are created. Change made to simplify plugin configuration.
- Plugin sets are now added to category themed lists under wp-content/plugins/wordpress-runscript-master/lists
- Plugin packages stored in wp-content/plugins/wordpress-runscript-master/plugins are now read automatically. This means there is no need to edit any arrays.
- Theme packages stored in wp-content/plugins/wordpress-runscript-master/themes are now read automatically. This means there is no need to edit any arrays.

## 1.0.1
- Minor bug fix.

## 1.0.0
- First public release
