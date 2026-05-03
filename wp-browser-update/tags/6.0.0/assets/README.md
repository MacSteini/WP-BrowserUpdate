# browser-update.org runtime assets

WP BrowserUpdate bundles the browser-update.org runtime logic so sites with restrictive Content Security Policies can load the notification from their own WordPress origin.

browser-update.org remains the upstream source for browser detection and notification behaviour. The shipped files are local WP BrowserUpdate runtime/adapters with provenance documented here. Reference copies of upstream files are not packaged; use the URLs and hashes below when comparing or refreshing the bundled runtime.

## Upstream files

Retrieved from `https://browser-update.org/` on 2026-05-03.

Upstream file|Upstream URL|SHA-256 of retrieved upstream file|Shipped file
-|-|-|-
`update.min.js`|`https://browser-update.org/update.min.js`|`d0732d97fb1d79bb3caf374e0a26923eac41fc211ecfc450cb8f73899841acbd`|`update.min.js`
`update.show.min.js`|`https://browser-update.org/update.show.min.js`|`9976811f7fd2ad3a89cc3f3450f9655938c39f3869de8000e50030e10a502747`|`update.show.wpbu.min.js`
`update.test.js`|`https://browser-update.org/update.test.js`|`ba2f98ae94db0ecadf3f7d9b748d4414707f8a2b1a33ad618616ec9a9b5f47d1`|`update.test.wpbu.js`

## Licence and attribution

The upstream browser-update.org files include this notice:

`(c)2017, MIT Style License <browser-update.org/LICENSE.txt>`

WP BrowserUpdate does not claim authorship of the browser-update.org runtime. The shipped runtime files are maintained only to make the bundled runtime compatible with stricter CSP setups and to avoid runtime requests to browser-update.org assets.

## Loaded files

The plugin loads `update.min.js`. That script is configured to require an explicit local `jsshowurl`, which points to `update.show.wpbu.min.js`; the test mode adapter loads `update.test.wpbu.js` from the local runtime `domain`. Notification styles are loaded from `update.show.wpbu.css` through the WordPress stylesheet queue.

## Update workflow

1. Download the three upstream files from the URLs in the table.
2. Record the retrieval date and SHA-256 hashes in this file.
3. Compare upstream changes against the shipped files.
4. Reapply the WP BrowserUpdate changes deliberately:
    - keep the runtime loader local-only; no fallback to `browser-update.org` scripts
    - keep notification/test styles in `update.show.wpbu.css`, not generated `<style>` blocks
    - keep test mode loading `update.test.wpbu.js`
    - keep the default update link independent from `browser-update.org`
5. Run PHP syntax checks and `node --check` for all shipped JavaScript files.
