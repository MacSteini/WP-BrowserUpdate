// WPBU CSP adapter for Browser-Update.org update.test.js.
// Upstream: https://browser-update.org/update.test.js
// Upstream SHA-256 on 2026-05-03: ba2f98ae94db0ecadf3f7d9b748d4414707f8a2b1a33ad618616ec9a9b5f47d1
// Original license: MIT Style License <browser-update.org/LICENSE.txt>
// Changes: moves generated styles to update.show.wpbu.css and uses classes for CSP compatibility.
// This script is loaded when the bar is shown in testing mode.
// Shows debug information and a note that the browser may actually not be outdated.
"use strict";
var $buo_test_ = function () {
var op = window._buorgres;
var bb = $bu_getBrowser();

var div = document.createElement("div");
div.className = "buorg-test";


if (op.style === "bottom")
div.className += " buorg-test-bottom";
if (op.style === "corner")
div.className += " buorg-test-corner";

var h = '<div>Browser Notification Debug-Mode (v'+op.jsv+')</div>';


h += '<div class="buorg-test-sub">'
h += "<div>Browser would normally be notified: " + op.notified + "</div>";

if (op.reasons.length>0)
h += "<div><b>Reasons to show</b>: " + op.reasons.join(",") + "</div>";

if (op.hide_reasons.length>0)
h += "<div><b>Reasons to hide</b>: " + op.hide_reasons.join(",") + "</div>"

h += "<div><b>Browser info</b></div>";
h += "<span>is_latest:" + bb.is_latest + "</span>, ";
h += "<span>is_insecure:" + bb.is_insecure + "</span>, ";
h += "<span>other:" + bb.other + "</span>, ";
h += "<span>no_device_update:" + bb.no_device_update + "</span>, ";
h += "<span>cookie set:" + (document.cookie.indexOf("browserupdateorg=pause")>-1) + "</span>";

h += '</div>'

div.innerHTML = h;
div.onclick = function (e) {
e = e || window.event;
if (e.stopPropagation) e.stopPropagation();
else e.cancelBubble = true;

div.parentNode.removeChild(div);
return false;
}
op.div.appendChild(div);


}();
