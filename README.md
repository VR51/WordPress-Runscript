# WordPress Runscript
WordPress plugin and theme deployment script. This is a WordPress plugin that installs a configurable default set of plugins and themes into a WordPress site.

Use this WordPress plugin to install and deploy preferred plugin and theme sets.

# Instructions
The terminology:

* When we say 'install' we mean download from some URL and install the downloaded file into wp-content/plugins or wp-content/themes
* When we say 'deploy' we mean move plugin and theme packages from WordPress-Runscript-master/wp-content/plugins or WordPress-Runscipt-master/wp-content/themes and deploy them into site/wp-content/plugins or site/wp-content/themes.

Plugins and themes 'installed' by this WordPress Runscript can be hosted anywhere on the internet. The URL of the zip file must be publicly accessible.
Plugins and themes 'deployed' by WordPress Runscript must be stored in zip format in the relevent subdirectory of the 'WordPress-Runscipt-master' directory.

To download plugins and themes from a repository:

* Add URL addresses for plugins to the 'Remote' plugin URLs array. One URL per line. The files to grab can be anywhere on the Internet e.g Github.com or wordpress.org
* Add URL addresses for themes to the 'Remote' theme URLs array. One URL per line. The files to grab can be anywhere on the Internet e.g Github.com or wordpress.org

To deploy plugins and themes with the WordPress Runscript package:

* Place a copy of the plugin zip files into WordPress-Runscipt-master/wp-content/plugins. Add package names to the 'Local' plugin package array code in the script file. One zip file per line.
* Place a copy of the theme zip files into WordPress-Runscipt-master/wp-content/themes. Add package names to the 'Local' theme package array code in the script file. One zip file per line.

WordPress Runscript will run as soon as activated. The plugin is configured to run for no longer than 10 minutes. Adjust the execution time limit in set_time_limit(600) around line 212 of this file if more time is required.

The plugin will deactivate itself after it has run. You must delete the plugin immediately it has completed its task. Do not leave active or installed under any circumstances.