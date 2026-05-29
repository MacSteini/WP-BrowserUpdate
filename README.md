# WP BrowserUpdate

![GitHub Release](https://img.shields.io/github/v/release/macsteini/wp-browserupdate?label=Release&color=red)
![Static Badge](https://img.shields.io/badge/PHP_>=-v7.4-red)
![Static Badge](https://img.shields.io/badge/WordPress_>=-v6.0-blue)
![Static Badge](https://img.shields.io/badge/WordPress_Tested_Up_To-v7.0-blue)
![Static Badge](https://img.shields.io/badge/WordPress_Compatible_Up_To-v7.0-blue)
[![Licence: GPL3](https://img.shields.io/badge/License-GPL3-green)](https://gnu.org/licenses/gpl)

WP BrowserUpdate is a WordPress plugin that shows a clear update notice when a visitor uses a browser older than the versions a site owner wants to support.

WP BrowserUpdate uses the browser-update.org detection logic, but bundles the visitor-facing runtime with the plugin. A site can show the notice without loading scripts, styles, or default notification links from browser-update.org at display time.

## What it does

- Detects outdated browsers through the bundled browser-update.org runtime.
- Lets site owners define supported browser versions from the WordPress admin area.
- Supports defaults, major versions, negative offsets, and exact dotted versions such as `137.0.3912.63`.
- Keeps Microsoft Edge and legacy Internet Explorer as separate browser targets.
- Lets administrators configure notice text, links, language, placement, reminder timing, mobile handling, unsupported-browser handling, and trusted custom CSS.
- Provides a test mode so administrators can force the notice during setup.
- Loads the visitor-facing runtime from the WordPress site instead of from browser-update.org.
- Documents the bundled upstream runtime files with retrieval dates and SHA-256 hashes.

## Why WP BrowserUpdate bundles the runtime

Older WP BrowserUpdate versions depended on browser-update.org at display time. Version 6 moves the required browser-update.org runtime and WP BrowserUpdate adapters into the plugin package.

This matters for sites that use stricter security or privacy controls:

- Content Security Policies do not need to allow runtime scripts from `browser-update.org`.
- Tracker blockers are less likely to block the notice runtime.
- Reviewers can inspect runtime files as part of the plugin package.
- Future upstream refreshes can use the recorded source URLs and hashes for comparison.

browser-update.org remains the credited upstream project for browser detection and notification behaviour.

## Requirements

- WordPress 6.0 or newer.
- PHP 7.4 or newer.
- A modern WordPress installation that can enqueue the bundled plugin scripts and styles.

Servers running older PHP versions should stay on WP BrowserUpdate 4.8.1 until the hosting provider upgrades the environment.

## Installation

### Normal WordPress installation

Install WP BrowserUpdate from the WordPress plugin directory:

1. Open **Plugins > Add New** in the WordPress admin area.
2. Search for **WP BrowserUpdate**.
3. Install and activate the plugin.
4. Open **Settings > WP BrowserUpdate**.
5. Adjust the browser thresholds and notice behaviour for the site.

The WordPress plugin directory remains the recommended installation and update channel for normal sites.

### Source review from GitHub

Use this repository when you want to inspect the source, compare release snapshots, review the bundled runtime assets, or report issues.

The active development package lives in `wp-browser-update/trunk/`. Released WordPress snapshots live under `wp-browser-update/tags/<version>/`.

## Repository layout

```text
wp-browser-update/trunk/        current plugin package
wp-browser-update/tags/         released WordPress snapshots
site/                           static project page
README.md                       GitHub-facing project overview
LICENSE                         project license
```

GitHub source archives are intentionally scoped through `.gitattributes` so release source downloads contain the plugin package rather than the full repository workspace.

## Runtime asset provenance

[`wp-browser-update/trunk/assets/README.md`](wp-browser-update/trunk/assets/README.md "browser-update.org runtime asset notes") documents the bundled browser-update.org files.

That file records:

- upstream source URLs
- retrieval date
- SHA-256 hashes for retrieved upstream files
- shipped filenames
- the local-only WP BrowserUpdate adapter expectations
- the update workflow for future runtime refreshes

## Development notes

The plugin package has no front-end build step. The WordPress plugin source is plain PHP, CSS, and JavaScript.

Before changing runtime or admin behaviour, check the relevant files in `wp-browser-update/trunk/` and keep the WordPress.org readme in sync when user-facing behaviour changes.

Useful local checks include:

```sh
php -l wp-browser-update/trunk/WP-BrowserUpdate.php
php -l wp-browser-update/trunk/uninstall.php
node --check wp-browser-update/trunk/assets/update.test.wpbu.js
```

The repository also carries Codex-local validation tooling outside the product package. Those local agent paths are not part of the published WordPress plugin.

## Changelog

[`wp-browser-update/trunk/readme.txt`](wp-browser-update/trunk/readme.txt "WordPress.org readme") maintains the WordPress.org changelog.

Current release: **6.0.1**.

Recent highlights:

- 6.0.1 corrects the original plugin source strings to en-US so WordPress.org translations can provide locale-specific variants.
- 6.0.0 introduces the structured settings model and bundles the visitor-facing browser-update.org runtime for CSP-friendly local loading.
- 5.0.0 requires PHP 7.4.

## Project links

- [WordPress.org plugin page](https://wordpress.org/plugins/wp-browser-update/ "WP BrowserUpdate on WordPress.org")
- [Project page source](site/index.html "WP BrowserUpdate project page source")
- [GitHub releases](https://github.com/MacSteini/wp-browserupdate/releases "WP BrowserUpdate releases")
- [browser-update.org](https://browser-update.org/ "browser-update.org")
- [WordPress Translation Project](https://translate.wordpress.org/projects/wp-plugins/wp-browser-update "WP BrowserUpdate translations")

## Contributing

Bug reports, focused fixes, documentation improvements, and translation help are welcome.

For plugin text translations, use the [WordPress Translation Project](https://translate.wordpress.org/projects/wp-plugins/wp-browser-update/ "WP BrowserUpdate translations"). For source changes, keep pull requests narrow and include the checks or manual verification that match the touched area.

## Licence

WP BrowserUpdate uses the [GNU General Public Licence v3 or later](LICENSE "GNU General Public Licence v3 or later").

The bundled browser-update.org runtime remains credited to browser-update.org and keeps its upstream notice, as documented in the runtime asset notes.
