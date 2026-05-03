=== WP BrowserUpdate ===
Contributors: MacSteini
Tags: Browser, Update, Notice, Outdated, Warning
Tested up to: 6.9
Compatible up to: 6.9
Requires at least: 4.6
Requires PHP: 7.4
Stable tag: 5.2.0
License: GPLv3 or later
License URI: https://gnu.org/licenses/gpl

This plugin notifies website visitors to update their outdated browser in a non-intrusive way.

== Description ==
Many users still browse with outdated browsers, often unaware of the risks. Upgrading ensures better security and reliability. This plugin displays a subtle notification prompting visitors to update their browser. Activate the plugin, and it works seamlessly.

Visit [browserupdate.org](https://browserupdate.org/) for more details.

Want to help translate this plugin? Visit the [WordPress Translation Project](https://translate.wordpress.org/projects/wp-plugins/wp-browser-update).

== How it works ==
WP BrowserUpdate connects WordPress to the Browser-Update.org service. After activation, the plugin loads the bundled Browser-Update.org notification runtime from the plugin directory and passes your configured browser-version thresholds to those scripts. The notification is shown only when Browser-Update.org detection logic matches a browser to your settings.

The settings page is available under **Settings > WP BrowserUpdate**. You can define the browser versions that should trigger a notification, choose where the message appears, enable testing mode, decide whether mobile or unsupported browsers should be notified, and add trusted custom CSS for the notification.

Browser version fields accept major versions such as `115` and positive dotted versions such as `137.0.3912.63`. Dotted versions are passed exactly to Browser-Update.org instead of being reduced to their major version; exact comparison depends on Browser-Update.org and the browser key used by that service. A value of `0` uses the default Browser-Update.org outdated-browser detection. Negative whole numbers are migrated to concrete version numbers where a latest-version source is configured. These checks are cached for 6 hours by default and can be adjusted by developers with the `wpbu_browser_version_cache_hours` filter.

Microsoft Edge and Microsoft Internet Explorer have separate settings. The plugin passes Edge as `e` and Internet Explorer as `i` to Browser-Update.org so the two browsers can use different thresholds.

This plugin bundles the Browser-Update.org notification scripts and styles to avoid runtime blocking of external script URLs on sites with strict Content Security Policies or tracker blocking. Browser-Update.org remains the upstream source of the detection logic. The unmodified upstream files are included for review, and WP BrowserUpdate uses clearly named CSP adapter files only where the upstream runtime would otherwise generate inline styles.

== Important Notice ==
**Breaking Changes in Version 5.0**
- Requires **PHP 7.4** or newer.
- Ensure your hosting is updated to PHP 7.4 before upgrading to version 5.0 or newer.
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
= 5.2.0 =
* Changed:
    * Takes the long-postponed step of making the Browser-Update.org integration CSP-compatible by shipping the complete runtime with the plugin, so sites on shared hosting or strict Content Security Policies no longer need to allow scripts from `browser-update.org`.
    * Adds several Browser-Update.org asset files intentionally: unmodified upstream files are included for attribution and review, while clearly named WP BrowserUpdate adapter files remove runtime-generated inline styles.
    * Loads bundled Browser-Update.org runtime files from the plugin directory through the WordPress script queue.
    * Includes unmodified upstream Browser-Update.org files with attribution, source URLs and hashes for review.
    * Uses WP BrowserUpdate CSP adapter files for the notification and test-mode scripts so the runtime can avoid generated inline styles.
    * Moves the frontend Browser-Update.org configuration and notification styles to local, enqueueable assets for better compatibility with stricter Content Security Policies.
    * Uses the WordPress HTTP API with a host allowlist for remote browser-version checks.
    * Uses the WordPress Settings API for the admin settings page.
    * Splits admin settings handling into smaller validation, migration and rendering steps.
    * Documents the browser-update.org service dependency and expected visitor-facing behaviour.
    * Passes dotted browser versions such as `137.0.3912.63` to browser-update.org without reducing them to major versions.
    * Adds separate Microsoft Edge and Microsoft Internet Explorer thresholds.
* Security:
    * Adds stricter settings validation before saving options.
    * Sanitizes custom CSS before saving and before frontend output.
    * Hardens external admin links with `rel="noopener noreferrer"`.

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
