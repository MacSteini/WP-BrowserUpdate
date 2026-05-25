# WP BrowserUpdate
![GitHub Release](https://img.shields.io/github/v/release/macsteini/wp-browserupdate?label=Release&color=red)
![Static Badge](https://img.shields.io/badge/PHP_>=-v7.4-red)
![Static Badge](https://img.shields.io/badge/WordPress_>=-v6.0-blue)
![Static Badge](https://img.shields.io/badge/WordPress_Tested_Up_To-v7.0-blue)
![Static Badge](https://img.shields.io/badge/WordPress_Compatible_Up_To-v7.0-blue)
[![License: GPL3](https://img.shields.io/badge/License-GPL3-green)](https://gnu.org/licenses/gpl)

This plugin notifies website visitors to update their outdated browser in a non-intrusive way.

## Description
Many users still browse with outdated browsers, often unaware of the risks. Upgrading ensures better security and reliability. This plugin displays a subtle notification prompting visitors to update their browser. Activate the plugin, and it works seamlessly.

WP BrowserUpdate is based on the browser-update.org detection logic. The plugin now runs independently from browser-update.org at display time: the visitor-facing notification is served from the plugin's own bundled runtime files, so sites do not need to load scripts, styles, or default notification links from browser-update.org. browser-update.org remains the credited upstream source for the bundled detection logic, and future runtime refreshes are tracked with source URLs and hashes in the plugin assets.

Visit [browserupdate.org](https://browserupdate.org/) for more details about the upstream project.

Want to help translate this plugin? Visit the [WordPress Translation Project](https://translate.wordpress.org/projects/wp-plugins/wp-browser-update).

## How It Works
WP BrowserUpdate bundles browser-update.org detection logic for WordPress. After activation, the plugin loads the local notification runtime from the plugin directory and passes your configured browser-version thresholds to those scripts. The notification is shown only when the bundled detection logic matches a browser to your settings.

The settings page is available under **Settings > WP BrowserUpdate**. You can define browser versions for every browser key supported by the bundled browser-update.org runtime, choose where the message appears and which element should contain it, enable testing mode, decide whether mobile or unsupported browsers should be notified, customize links, language and message text, and add trusted custom CSS for the notification.

Browser version fields accept major versions such as `115` and positive dotted versions such as `137.0.3912.63`. Dotted versions are passed exactly to the bundled runtime instead of being reduced to their major version. A value of `0` uses the default bundled outdated-browser detection. Negative whole numbers are passed to the bundled runtime as relative offsets from the current version checked by the plugin.

Microsoft Edge and Microsoft Internet Explorer have separate settings. The plugin passes Edge as `e` and Internet Explorer as `i` to the bundled runtime so the two browsers can use different thresholds.

## Important Notice
> **Breaking Changes in Version 6.0.0**
- Version 6.0.0 requires **WordPress 6.0** or newer.
- Version 6.0.0 introduces a new structured settings model to support the browser-update.org customization surface used by WP BrowserUpdate.
- Version 6.0.0 also moves the visitor-facing browser-update.org runtime into the plugin package so strict Content Security Policies and tracker blockers no longer need to allow scripts from `browser-update.org`.
- Existing WP BrowserUpdate settings from version 5.x are migrated automatically.

> **Breaking Changes in Version 5.0.0**
- Requires **PHP 7.4** or newer.
- Ensure your hosting is updated to PHP 7.4 before upgrading to version 5.0.0 or newer.
- Servers running older PHP versions are no longer supported.
  - If your server is running an earlier PHP version, please download [version 4.8.1](https://downloads.wordpress.org/plugin/wp-browser-update.4.8.1.zip "Download WP BrowserUpdate from WordPress.org").

## Installation
### Installing via WordPress Plugin Search (Recommended)
This is the easiest and quickest way to install the plugin:
1. Log in to your WordPress admin dashboard.
1. Navigate to **Plugins > Add New**.
1. In the search bar, type **WP BrowserUpdate**.
1. Locate the correct plugin in the search results.
1. Click **Install Now** next to WP BrowserUpdate.
1. Once installed, click **Activate** to enable the plugin.

### Manual Installation
If you prefer to install the plugin manually via SFTP, follow these steps:
1. **Download the plugin**
   - [Download the latest version](https://downloads.wordpress.org/plugin/wp-browser-update.zip "Download WP BrowserUpdate from WordPress.org") from the WordPress Plugin Directory.
1. **Extract the plugin files**
   - Locate the downloaded ZIP file and extract it on your computer.
   - You should now have a folder named `wp-browser-update`.
1. **Upload the plugin to your website**
   - Connect to your website using an FTP client (e. g., [FileZilla](https://filezilla-project.org/ "FileZilla")) or access the File Manager in your hosting control panel.
   - Navigate to `/wp-content/plugins/` in your WordPress installation directory.
   - Upload the extracted `wp-browser-update` folder.
1. **Activate the plugin**
   - Log in to your WordPress admin dashboard.
   - Go to **Plugins > Installed Plugins**.
   - Find **WP BrowserUpdate** in the list and click **Activate**.

### Installing via the WordPress Admin Panel
If you have already downloaded the ZIP file, you can install it via the WordPress admin panel:
1. Log in to your WordPress admin dashboard.
1. Navigate to **Plugins > Add New**.
1. Click **Upload Plugin** at the top of the page.
1. Click **Choose File**, select `wp-browser-update.zip` from your computer, and click **Install Now**.
1. Once the installation is complete, click **Activate Plugin** to enable it.

## Changelog
### 6.0.0
- Breaking:
    - Requires WordPress 6.0 or newer.
    - Replaces the legacy space-separated settings storage with the structured `wp_browserupdate_options` option.
    - Migrates existing `wp_browserupdate_browsers`, `wp_browserupdate_js`, and `wp_browserupdate_css_buorg` values automatically.
    - Removes the old normal-render-path conversion of negative browser versions; values are now passed predictably to the bundled runtime.
    - Removes developer callback settings from the admin UI and saved settings model.
- Added:
    - Adds interface coverage for the browser-update.org customization options used by WP BrowserUpdate: all runtime browser keys, `reminderClosed`, `notify_esr`, `noclose`, `no_permanent_hide`, `container`, `url`, `url_permanent_hide`, `burl`, fixed language, and text overrides.
- Changed:
    - Takes the long-postponed step of making the browser-update.org integration CSP-compatible by shipping the complete runtime with the plugin, so sites on shared hosting or strict Content Security Policies no longer need to allow scripts from `browser-update.org`.
    - Adds the required browser-update.org runtime/adapter asset files intentionally, with upstream source URLs and hashes documented for attribution and review.
    - Loads bundled browser-update.org runtime files from the plugin directory through the WordPress script queue.
    - Removes browser-update.org runtime requests from the frontend by loading only same-origin plugin assets.
    - Uses WP BrowserUpdate CSP adapter files for the notification and test-mode scripts so the runtime can avoid generated inline styles.
    - Moves the frontend browser-update.org configuration and notification styles to local, enqueueable assets for better compatibility with stricter Content Security Policies.
    - Uses the WordPress HTTP API with a host allowlist for remote browser-version checks.
    - Uses the WordPress Settings API for the admin settings page.
    - Splits admin settings handling into smaller validation, migration and rendering steps.
    - Documents the bundled browser-update.org runtime, local frontend loading, and expected visitor-facing behavior.
    - Passes dotted browser versions such as `137.0.3912.63` to the bundled runtime without reducing them to major versions.
    - Adds separate Microsoft Edge and Microsoft Internet Explorer thresholds.
    - Ships only the loaded runtime/adapter files in the release package; upstream reference copies are documented by URL and hash rather than duplicated in the plugin ZIP.
- Security:
    - Adds stricter settings validation before saving options.
    - Sanitizes custom CSS before saving and before frontend output.
    - Hardens external admin links with `rel="noopener noreferrer"`.

### 5.1.1
- Added:
    - Support for free-text input of browser versions (replaces dropdown selection).
    - Browser version check and caching.
    - Cache duration filter: Added a wpbu_browser_version_cache_hours filter to allow developers to adjust the browser version cache duration (default: 6 hours).
- Changed:
    - Admin settings page follows WordPress admin standards.
    - Cleaner field structure: Improved markup and consistency of settings form fields.
    - Security: Improved sanitization and nonce handling for all settings fields.
    - Performance: Fewer unnecessary remote lookups when opening the admin settings page.
- Fixed:
    - Prevented a frontend "Uncaught Error" caused by invalid/unexpected browser version strings by hardening version parsing and normalization. Thanks to @danyloliptuha for pointing out.

### 5.0.0
- **Breaking Changes**: PHP 7.4 now required.
- Updated to follow WordPress Coding Standards.
- Improved security and sanitization.
- Integrated WordPress Settings API.
- Added customization filters.
- Optimized script and style handling.
- Updated outdated browser versions.

### 4.8.1
- Optimized code.
- Fixed bug.
- Updated outdated browser versions.

### 4.6.1
- Removed redundant colons.
- Updated outdated browser versions.

### 4.6.0
- Fixed Cross-Site Scripting (XSS) vulnerability.
- Updated outdated browser versions.

### 4.5.0
- Fixed Cross-Site Request Forgery (CSRF) vulnerability.
- Updated outdated browser versions.

### 4.4.0
- Updated source URL.
- Updated outdated browser versions.

### 4.3.0
- Fixed plugin activation issue.
- Updated outdated browser versions.

### 4.0.0
- Fixed bugs.
- Updated JavaScript.

### 3.2.0
- Corrected version typo causing plugin issues.

### 3.1.0
- Fixed bugs.
- Updated outdated browser versions.

### 3.0.3
- Fixed initialization error.
- Changed protocol to HTTPS.
- Updated outdated browser versions.

### 3.0.0
- Overhauled functions.
- Updated JavaScript.
- Updated outdated browser versions.

### 2.4.0
- Fixed functions.

### 2.3.0
- Overhauled functions.
- Updated translation files.
- Minor fixes.

### 2.2.0
- Changed license to GPLv3.
- Added text domain to header.
- Added POT file for easier translations.

### 2.1.3
- Included minified JavaScript.
- Minor fixes to notification messages.

### 2.1.0
- Added JavaScript customization options.

### 2.0.3
- Updated outdated browser versions.
- Added settings link.
- Minor fixes.

### 2.0.0
- Added admin settings panel.
- Added uninstall function.

### 1.0.0
- Initial stable version.
