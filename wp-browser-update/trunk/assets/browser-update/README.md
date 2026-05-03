# browser-update.org runtime assets

WP BrowserUpdate bundles the browser-update.org runtime files so sites with restrictive Content Security Policies can load the notification from their own WordPress origin.

browser-update.org remains the upstream source for browser detection and notification behaviour. The unmodified upstream files are stored under `upstream/` for review and attribution. The `*.wpbu.*` files are WP BrowserUpdate CSP adapters that remove browser-update.org's generated inline `<style>` blocks and use `update.show.wpbu.css` instead.

## Upstream files

Retrieved from `https://browser-update.org/` on 2026-05-03.

File | Upstream URL | SHA-256
-|-|-
`upstream/update.min.js`|`https://browser-update.org/update.min.js`|`d0732d97fb1d79bb3caf374e0a26923eac41fc211ecfc450cb8f73899841acbd`
`upstream/update.show.min.js`|`https://browser-update.org/update.show.min.js`|`9976811f7fd2ad3a89cc3f3450f9655938c39f3869de8000e50030e10a502747`
`upstream/update.test.js`|`https://browser-update.org/update.test.js`|`ba2f98ae94db0ecadf3f7d9b748d4414707f8a2b1a33ad618616ec9a9b5f47d1`

## Licence and attribution

The upstream browser-update.org files include this notice:

`(c)2017, MIT Style License <browser-update.org/LICENSE.txt>`

WP BrowserUpdate does not claim authorship of the browser-update.org runtime. The adapter files are maintained only to make the bundled runtime compatible with stricter CSP setups where inline scripts and inline styles are blocked.

## Loaded files

The plugin loads `upstream/update.min.js` unchanged. That script is configured to load `update.show.wpbu.min.js`, and the test mode adapter loads `update.test.wpbu.js`. Notification styles are loaded from `update.show.wpbu.css` through the WordPress stylesheet queue.
