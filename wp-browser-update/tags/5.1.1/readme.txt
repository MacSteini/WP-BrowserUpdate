=== WP BrowserUpdate ===
Contributors: MacSteini
Tags: Browser, Update, Notice, Outdated, Warning
Tested up to: 6.9
Compatible up to: 6.9
Requires at least: 4.6
Requires PHP: 7.4
Stable tag: 5.1.1
License: GPLv3 or later
License URI: https://gnu.org/licenses/gpl

This plugin notifies website visitors to update their outdated browser in a non-intrusive way.

== Description ==
Many users still browse with outdated browsers, often unaware of the risks. Upgrading ensures better security and reliability. This plugin displays a subtle notification prompting visitors to update their browser. Activate the plugin, and it works seamlessly.

Visit [browserupdate.org](https://browserupdate.org/) for more details.

Want to help translate this plugin? Visit the [WordPress Translation Project](https://translate.wordpress.org/projects/wp-plugins/wp-browser-update).

== Important Notice ==
**Breaking Changes in Version 5.0.0**
- Requires **PHP 7.4** or newer.
- Ensure your hosting is updated to PHP 7.4 before upgrading to version 5.0.0 or newer.
- Servers running older PHP versions are no longer supported.
  - If your server is running an earlier PHP version, please download [version 4.8.1](https://downloads.wordpress.org/plugin/wp-browser-update.4.8.1.zip "Download WP BrowserUpdate from WordPress.org").

== Installation ==

= Installing via WordPress Plugin Search (Recommended) =
This is the easiest and quickest way to install the plugin:
1. Log in to your WordPress admin dashboard.
2. Navigate to **Plugins > Add New**.
3. In the search bar, type **WP BrowserUpdate**.
4. Locate the correct plugin in the search results.
5. Click **Install Now** next to WP BrowserUpdate.
6. Once installed, click **Activate** to enable the plugin.

= Manual Installation =
If you prefer to install the plugin manually via SFTP, follow these steps:
1. **Download the plugin**
   - [Download the latest version](https://downloads.wordpress.org/plugin/wp-browser-update.zip "Download WP BrowserUpdate from WordPress.org") from the WordPress Plugin Directory.
2. **Extract the plugin files**
   - Locate the downloaded ZIP file and extract it on your computer.
   - You should now have a folder named `wp-browser-update`.
3. **Upload the plugin to your website**
   - Connect to your website using an FTP client (e. g., [FileZilla](https://filezilla-project.org/ "FileZilla")) or access the File Manager in your hosting control panel.
   - Navigate to `/wp-content/plugins/` in your WordPress installation directory.
   - Upload the extracted `wp-browser-update` folder.
4. **Activate the plugin**
   - Log in to your WordPress admin dashboard.
   - Go to **Plugins > Installed Plugins**.
   - Find **WP BrowserUpdate** in the list and click **Activate**.

= Installing via the WordPress Admin Panel =
If you have already downloaded the ZIP file, you can install it via the WordPress admin panel:
1. Log in to your WordPress admin dashboard.
2. Navigate to **Plugins > Add New**.
3. Click **Upload Plugin** at the top of the page.
4. Click **Choose File**, select `wp-browser-update.zip` from your computer, and click **Install Now**.
5. Once the installation is complete, click **Activate Plugin** to enable it.

== Changelog ==
= 5.1.1 =
* Added:
    * Support for free-text input of browser versions (replaces dropdown selection)
    * Browser version check and caching
    * Cache duration filter: Added a wpbu_browser_version_cache_hours filter to allow developers to adjust the browser version cache duration (default: 6 hours).
* Changed:
    * Admin settings page follows WordPress admin standards
    * Cleaner field structure: Improved markup and consistency of settings form fields
    * Security: Improved sanitization and nonce handling for all settings fields
    * Performance: Fewer unnecessary remote lookups when opening the admin settings page
* Fixed:
    * Prevented a frontend "Uncaught Error" caused by invalid/unexpected browser version strings by hardening version parsing and normalization. Thanks to @danyloliptuha for pointing out.

= 5.0.0 =
* **Breaking Changes**: PHP 7.4 now required.
* Updated to follow WordPress Coding Standards.
* Improved security and sanitization.
* Integrated WordPress Settings API.
* Added customization filters.
* Optimized script and style handling.
* Updated outdated browser versions.

= 4.8.1 =
* Optimized code.
* Fixed bug.
* Updated outdated browser versions.

= 4.6.1 =
* Removed redundant colons.
* Updated outdated browser versions.

= 4.6.0 =
* Fixed Cross-Site Scripting (XSS) vulnerability.
* Updated outdated browser versions.

= 4.5.0 =
* Fixed Cross-Site Request Forgery (CSRF) vulnerability.
* Updated outdated browser versions.

= 4.4.0 =
* Updated source URL.
* Updated outdated browser versions.

= 4.3.0 =
* Fixed plugin activation issue (thanks @Naveen).
* Updated outdated browser versions.

= 4.0.0 =
* Fixed bugs (thanks to forum commenters).
* Updated JavaScript.

= 3.2.0 =
* Corrected version typo causing plugin issues.

= 3.1.0 =
* Fixed bugs (thanks @tristanmason).
* Updated outdated browser versions.

= 3.0.3 =
* Fixed initialization error.
* Changed protocol to HTTPS.
* Updated outdated browser versions.

= 3.0.0 =
* Overhauled functions.
* Updated JavaScript.
* Updated outdated browser versions.

= 2.4.0 =
* Fixed functions.

= 2.3.0 =
* Overhauled functions.
* Updated translation files.
* Minor fixes.

= 2.2.0 =
* Changed license to GPLv3.
* Added text domain to header.
* Added POT file for easier translations.

= 2.1.3 =
* Included minified JavaScript.
* Minor fixes to notification messages.

= 2.1.0 =
* Added JavaScript customization options.

= 2.0.3 =
* Updated outdated browser versions.
* Added settings link.
* Minor fixes.

= 2.0.0 =
* Added admin settings panel.
* Added uninstall function.

= 1.0.0 =
* Initial stable version.
